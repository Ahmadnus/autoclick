<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingApiController extends Controller
{
    /**
     * Public, unauthenticated — the Flutter app reads this before a device
     * has activated a license, so it can't be gated behind api.key.
     */
    public function whatsappConfig(): JsonResponse
    {
        return response()->json([
            'whatsapp_number' => Setting::get('whatsapp_number', '1234567890'),
        ]);
    }
}
