<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LicenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LicenseApiController extends Controller
{
    public function __construct(private readonly LicenseService $licenseService)
    {
    }

    /**
     * POST /api/activate
     * Body: { "code": "...", "device_id": "..." }
     *
     * The subscriber's name and phone number are entered by the admin when
     * the code is generated (see Admin\LicenseController::store) — the app
     * never sends or sets them.
     *
     * Success: { "status": "success", "expires_at": "2026-08-15T00:00:00.000000Z" }
     * Failure: { "status": "error", "reason": "invalid|blocked|device_mismatch|expired",
     *            "message": "<Arabic message ready to show in the UI>" }
     */
    public function activate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:64'],
            'device_id' => ['required', 'string', 'max:255'],
        ]);

        $result = $this->licenseService->activate($validated['code'], $validated['device_id']);

        if (! $result['ok']) {
            return response()->json([
                'status' => 'error',
                'reason' => $result['reason'],
                'message' => $this->messageFor($result['reason']),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'expires_at' => $result['license']->expires_at?->toISOString(),
        ]);
    }

    /**
     * POST /api/check-status
     * Body: { "device_id": "..." }
     *
     * Remote kill-switch: called on app launch and on every app resume so an
     * admin disabling a code (or it simply expiring) takes effect immediately
     * instead of waiting for the locally-cached expiry date to run out.
     *
     * { "allowed": true } | { "allowed": false, "reason": "banned_or_expired" }
     */
    public function checkStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_id' => ['required', 'string', 'max:255'],
        ]);

        if ($this->licenseService->isDeviceAllowed($validated['device_id'])) {
            return response()->json(['allowed' => true]);
        }

        return response()->json(['allowed' => false, 'reason' => 'banned_or_expired']);
    }

    private function messageFor(string $reason): string
    {
        return match ($reason) {
            'invalid' => 'كود التفعيل غير صحيح.',
            'blocked' => 'تم إيقاف هذا الكود من قبل المسؤول.',
            'device_mismatch' => 'هذا الكود مفعّل بالفعل على جهاز آخر.',
            'expired' => 'انتهت صلاحية الاشتراك، يرجى الحصول على كود جديد.',
            default => 'تعذر تفعيل الكود.',
        };
    }
}
