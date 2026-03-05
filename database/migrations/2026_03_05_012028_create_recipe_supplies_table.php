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
        Schema::create('recipe_supplies', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('recipe_id')->index('recipe_id_foreign');
            $table->unsignedBigInteger('supply_id')->index('supplies_id_foreign');
            $table->decimal('amount', 8, 3)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_supplies');
    }
};
