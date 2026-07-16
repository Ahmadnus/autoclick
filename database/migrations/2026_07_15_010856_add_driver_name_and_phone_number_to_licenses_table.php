<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Captured from the activation form (Flutter's ActivationView) at the
     * moment a code is first bound to a device — never required when an
     * admin generates a blank code, hence nullable.
     */
    public function up(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->string('driver_name')->nullable()->after('device_id');
            $table->string('phone_number')->nullable()->after('driver_name');
        });
    }

    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn(['driver_name', 'phone_number']);
        });
    }
};
