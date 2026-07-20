<?php

namespace Database\Seeders;

use App\Models\License;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LicenseSeeder extends Seeder
{
    /**
     * Sample activation codes covering every state the licenses list needs
     * to render correctly: unused, bound & active, bound & expired, and
     * disabled by an admin.
     */
    public function run(): void
    {
        $codes = [
            [
                'code' => 'DEMO-0001',
                'duration_months' => 1,
                'duration_type' => License::DURATION_MONTH_1,
                'device_id' => null,
                'expires_at' => null,
                'is_active' => true,
            ],
            [
                'code' => 'DEMO-0002',
                'duration_months' => 3,
                'duration_type' => License::DURATION_MONTH_3,
                'device_id' => (string) Str::uuid(),
                'expires_at' => now()->addDays(60),
                'is_active' => true,
            ],
            [
                'code' => 'DEMO-0003',
                'duration_months' => 1,
                'duration_type' => License::DURATION_MONTH_1,
                'device_id' => (string) Str::uuid(),
                'expires_at' => now()->subDays(5),
                'is_active' => true,
            ],
            [
                'code' => 'DEMO-0004',
                'duration_months' => 1,
                'duration_type' => License::DURATION_MONTH_1,
                'device_id' => (string) Str::uuid(),
                'expires_at' => now()->addDays(20),
                'is_active' => false,
            ],
        ];

        foreach ($codes as $code) {
            License::query()->firstOrCreate(['code' => $code['code']], $code);
        }
    }
}
