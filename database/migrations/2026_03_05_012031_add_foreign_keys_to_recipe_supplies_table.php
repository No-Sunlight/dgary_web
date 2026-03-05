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
        Schema::table('recipe_supplies', function (Blueprint $table) {
            $table->foreign(['recipe_id'], 'recipe_id_foreign')->references(['id'])->on('recipes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['supply_id'], 'supplies_id_foreign')->references(['id'])->on('supplies')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipe_supplies', function (Blueprint $table) {
            $table->dropForeign('recipe_id_foreign');
            $table->dropForeign('supplies_id_foreign');
        });
    }
};
