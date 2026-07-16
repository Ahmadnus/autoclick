<?php

return [
    /*
    |--------------------------------------------------------------------------
    | License API Key
    |--------------------------------------------------------------------------
    |
    | Shared secret the Flutter app must send as the `X-API-KEY` header when
    | calling POST /api/verify-license. Change this in .env before deploying
    | anywhere real, and configure the same value in the Flutter app's
    | LicenseService once it talks to this backend.
    |
    */
    'api_key' => env('LICENSE_API_KEY', 'change-this-secret-key'),
];
