<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    public const DURATION_DAY = 'day';
    public const DURATION_MONTH_1 = 'month_1';
    public const DURATION_MONTH_3 = 'month_3';
    public const DURATION_MONTH_6 = 'month_6';
    public const DURATION_YEAR_1 = 'year_1';
    public const DURATION_MANUAL = 'manual';

    /**
     * Source of truth for the duration dropdown in the admin panel (see
     * resources/views/admin/licenses/create.blade.php) and for validation
     * in Admin\LicenseController::store(). Keys are stored in duration_type;
     * labels are Arabic, matching the rest of the admin UI.
     */
    public const DURATION_OPTIONS = [
        self::DURATION_DAY => 'يوم واحد',
        self::DURATION_MONTH_1 => 'شهر واحد',
        self::DURATION_MONTH_3 => '3 أشهر',
        self::DURATION_MONTH_6 => '6 أشهر',
        self::DURATION_YEAR_1 => 'سنة واحدة',
        self::DURATION_MANUAL => 'مدة يدوية',
    ];

    protected $fillable = [
        'code',
        'duration_months',
        'duration_type',
        'manual_days',
        'device_id',
        'driver_name',
        'phone_number',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'duration_months' => 'integer',
        'manual_days' => 'integer',
    ];

    /**
     * Human-readable duration label for the admin list view. Falls back to
     * the legacy duration_months-based phrasing for rows created before
     * duration_type existed.
     */
    public function durationLabel(): string
    {
        if ($this->duration_type !== null && isset(self::DURATION_OPTIONS[$this->duration_type])) {
            if ($this->duration_type === self::DURATION_MANUAL) {
                return $this->manual_days . ' يوم';
            }

            return self::DURATION_OPTIONS[$this->duration_type];
        }

        return $this->duration_months . ' ' . ($this->duration_months === 1 ? 'شهر' : 'أشهر');
    }

    /**
     * Whether this code currently unlocks the app: not deactivated by an
     * admin, and — once bound to a device — not past its expiry. An
     * unbound code (device_id still null) is always "active" in this sense;
     * expiry only starts counting once App\Services\LicenseService::activate
     * binds it to a device and sets expires_at.
     */
    public function isActive(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        return $this->expires_at === null || $this->expires_at->isFuture();
    }

    public function isBound(): bool
    {
        return $this->device_id !== null;
    }
}
