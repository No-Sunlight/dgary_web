<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->decimal('destination_lat', 10, 7)->nullable()->after('address');
            $table->decimal('destination_lng', 10, 7)->nullable()->after('destination_lat');
            $table->decimal('driver_lat', 10, 7)->nullable()->after('status');
            $table->decimal('driver_lng', 10, 7)->nullable()->after('driver_lat');
            $table->timestamp('driver_location_updated_at')->nullable()->after('driver_lng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn([
                'destination_lat',
                'destination_lng',
                'driver_lat',
                'driver_lng',
                'driver_location_updated_at',
            ]);
        });
    }
};
