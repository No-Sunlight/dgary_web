<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Agrega campos adicionales para la app de drivers/repartidores
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('vehicle')->nullable()->comment('Vehículo del repartidor');
            $table->string('license_plate')->nullable()->comment('Placa del vehículo');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['vehicle', 'license_plate']);
        });
    }
};
