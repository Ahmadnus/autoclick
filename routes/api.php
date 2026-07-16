<?php

use App\Http\Controllers\Api\LicenseApiController;
use App\Http\Controllers\Api\SettingApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Called by the Flutter app's ActivationController when the user submits a
| WhatsApp-provided activation code, and by AuthController on every launch
| and app resume for the remote kill-switch check. Protected by the api.key
| middleware (a shared secret sent as the X-API-KEY header) — see
| config/license.php.
|
*/

Route::middleware('api.key')->post('/activate', [LicenseApiController::class, 'activate']);
Route::middleware('api.key')->post('/check-status', [LicenseApiController::class, 'checkStatus']);

// Public — read before a device has activated, so it can't sit behind api.key.
Route::get('/whatsapp-config', [SettingApiController::class, 'whatsappConfig']);
