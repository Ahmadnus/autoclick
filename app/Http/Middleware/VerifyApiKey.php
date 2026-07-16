<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards the public license-verification endpoint with a shared secret
 * (config('license.api_key')), sent by the client as an `X-API-KEY` header.
 * This is intentionally simple (a single shared key, not per-device auth)
 * since the endpoint itself only ever reveals a device's own active/expired
 * status — the key just keeps it from being scraped by anyone who stumbles
 * on the URL.
 */
class VerifyApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $providedKey = (string) $request->header('X-API-KEY', '');
        $expectedKey = (string) config('license.api_key');

        if ($providedKey === '' || $expectedKey === '' || ! hash_equals($expectedKey, $providedKey)) {
            return response()->json(['message' => 'غير مصرح به.'], 401);
        }

        return $next($request);
    }
}
