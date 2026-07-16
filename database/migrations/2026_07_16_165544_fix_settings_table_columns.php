<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The live server already had a `settings` table (not created by any
     * migration in this repo, and pre-dating create_settings_table) whose
     * columns didn't match what that migration assumed — it was recorded as
     * already run without ever creating `key`/`value`, so GET
     * /api/whatsapp-config failed with "Unknown column 'value'". This repairs
     * the existing table in place instead of dropping/recreating it, since
     * it may hold rows we don't want to lose.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'key')) {
                $table->string('key')->unique()->after('id');
            }

            if (! Schema::hasColumn('settings', 'value')) {
                $table->text('value')->nullable()->after('key');
            }
        });

        if (! DB::table('settings')->where('key', 'whatsapp_number')->exists()) {
            DB::table('settings')->insert([
                'key' => 'whatsapp_number',
                'value' => '1234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Repair-only migration; nothing to revert.
    }
};
