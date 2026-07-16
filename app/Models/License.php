<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'duration_months',
        'device_id',
        'driver_name',
        'phone_number',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

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
