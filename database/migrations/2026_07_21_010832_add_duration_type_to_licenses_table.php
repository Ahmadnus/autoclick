<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds the new duration options (1 day / 6 months / 1 year / manual) on
     * top of the existing duration_months column, which is kept as-is for
     * backward compatibility with already-issued codes. duration_type is
     * nullable so existing rows (created before this migration) keep
     * resolving their expiry from duration_months alone, exactly as before
     * (see App\Services\LicenseService::calculateExpiry).
     */
    public function up(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->string('duration_type')->nullable()->after('duration_months');
            $table->unsignedSmallInteger('manual_days')->nullable()->after('duration_type');
        });
    }

    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn(['duration_type', 'manual_days']);
        });
    }
};
