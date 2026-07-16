<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replaces the old admin-registers-a-device-by-hand schema with the
     * self-service activation-code model: an admin pre-generates a code for
     * a duration, hands it to the user over WhatsApp, and the app binds it
     * to a device on first use (see App\Services\LicenseService::activate).
     */
    public function up(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropUnique(['device_id']);
            $table->dropUnique(['phone_number']);
            $table->dropColumn(['driver_name', 'phone_number', 'status']);

            $table->string('code')->unique()->after('id');
            $table->unsignedTinyInteger('duration_months')->after('code');
            $table->boolean('is_active')->default(true)->after('expires_at');

            $table->string('device_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn(['code', 'duration_months', 'is_active']);

            $table->string('driver_name')->after('device_id');
            $table->string('phone_number')->unique()->after('driver_name');
            $table->enum('status', ['active', 'blocked'])->default('active')->after('phone_number');

            $table->string('device_id')->nullable(false)->unique()->change();
        });
    }
};
