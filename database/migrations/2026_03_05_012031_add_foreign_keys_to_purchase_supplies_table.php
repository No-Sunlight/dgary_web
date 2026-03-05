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
        Schema::table('purchase_supplies', function (Blueprint $table) {
            $table->foreign(['purchase_id'], 'purchase_supplies_id_foreign_key_purchase')->references(['id'])->on('purchases')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['supplies_id'])->references(['id'])->on('supplies')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_supplies', function (Blueprint $table) {
            $table->dropForeign('purchase_supplies_id_foreign_key_purchase');
            $table->dropForeign('purchase_supplies_supplies_id_foreign');
        });
    }
};
