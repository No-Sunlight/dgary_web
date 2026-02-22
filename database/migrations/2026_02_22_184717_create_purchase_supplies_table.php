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
        Schema::create('purchase_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplies_id')->references('id')->on('supplies');
            $table->decimal("quantity");
            $table->decimal("subtotal");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_supplies');
    }
};
