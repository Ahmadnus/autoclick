<?php

namespace App\Services;

use App\Models\License;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

/**
 * Encapsulates all license business logic: generating activation codes,
 * binding a code to a device on first use, status changes, subscription
 * extension, dashboard stats, and the activation check used by the public
 * API.
 *
 * Controllers (Admin\LicenseController, Admin\DashboardController,
 * Api\LicenseApiController) only handle HTTP request/response concerns and
 * delegate every actual decision and database query to this class, per the
 * project's Single Responsibility requirement.
 */
class LicenseService
{
    private const EXTENSION_MONTHS_DEFAULT = 1;

    public function paginateLicenses(int $perPage = 15): LengthAwarePaginator
    {
        return License::query()->latest()->paginate($perPage);
    }

    /**
     * @return array{total_drivers: int, active_subscriptions: int, expired_or_blocked: int}
     */
    public function getDashboardStats(): array
    {
        $total = License::query()->count();

        $activeSubscriptions = License::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->count();

        return [
            'total_drivers' => $total,
            'active_subscriptions' => $activeSubscriptions,
            'expired_or_blocked' => $total - $activeSubscriptions,
        ];
    }

    /**
     * Generates a fresh, unused activation code for the given duration and
     * customer. Not bound to any device until App::activate() is called
     * with it — device_id stays null until the Flutter app activates it.
     */
    public function createLicense(int $durationMonths, string $driverName, string $phoneNumber): License
    {
        return License::create([
            'code' => $this->generateUniqueCode(),
            'duration_months' => $durationMonths,
            'device_id' => null,
            'driver_name' => $driverName,
            'phone_number' => $phoneNumber,
            'expires_at' => null,
            'is_active' => true,
        ]);
    }

    public function activateAdmin(License $license): License
    {
        $license->update(['is_active' => true]);

        return $license;
    }

    public function block(License $license): License
    {
        $license->update(['is_active' => false]);

        return $license;
    }

    /**
     * Extends the subscription by $months from whichever is later: the
     * current expiry date, or now. This means extending an already-expired
     * license starts the new period from today, rather than from a date
     * that's already in the past.
     */
    public function extend(License $license, int $months = self::EXTENSION_MONTHS_DEFAULT): License
    {
        $base = $license->expires_at !== null && $license->expires_at->isFuture()
            ? $license->expires_at
            : now();

        $license->update(['expires_at' => $base->copy()->addMonths($months)]);

        return $license;
    }

    public function delete(License $license): void
    {
        $license->delete();
    }

    /**
     * The core self-service activation flow, called from POST /api/activate.
     *
     * - Unknown code                       -> ['ok' => false, 'reason' => 'invalid']
     * - Code deactivated by an admin        -> ['ok' => false, 'reason' => 'blocked']
     * - Unbound code (first use)            -> binds it to $deviceId, starts the
     *                                          expiry clock, returns success.
     * - Bound to a *different* device       -> ['ok' => false, 'reason' => 'device_mismatch']
     * - Bound to this device, still valid   -> success (idempotent re-activation,
     *                                          e.g. app reinstall on the same phone).
     * - Bound to this device, lapsed        -> ['ok' => false, 'reason' => 'expired']
     *
     * @return array{ok: bool, reason?: string, license?: License}
     */
    public function activate(string $code, string $deviceId): array
    {
        $license = License::query()->where('code', $code)->first();

        if ($license === null) {
            return ['ok' => false, 'reason' => 'invalid'];
        }

        if (! $license->is_active) {
            return ['ok' => false, 'reason' => 'blocked'];
        }

        if (! $license->isBound()) {
            $license->update([
                'device_id' => $deviceId,
                'expires_at' => now()->addMonths($license->duration_months),
            ]);

            return ['ok' => true, 'license' => $license];
        }

        if ($license->device_id !== $deviceId) {
            return ['ok' => false, 'reason' => 'device_mismatch'];
        }

        if ($license->expires_at !== null && $license->expires_at->isPast()) {
            return ['ok' => false, 'reason' => 'expired'];
        }

        return ['ok' => true, 'license' => $license];
    }

    /**
     * The remote kill-switch check, called from POST /api/check-status on
     * every app launch and resume. Deliberately stricter than activate()'s
     * device-mismatch handling — an unknown device_id, a device with no
     * license at all, an admin-disabled code, and a lapsed subscription are
     * all just "not allowed", with no distinction the app needs to react to
     * differently.
     */
    public function isDeviceAllowed(string $deviceId): bool
    {
        return License::query()
            ->where('device_id', $deviceId)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->exists();
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = Str::upper(Str::random(4)) . '-' . Str::upper(Str::random(4));
        } while (License::query()->where('code', $code)->exists());

        return $code;
    }
}
