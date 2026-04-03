<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_counts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supply_id')->nullable()->constrained();
            $table->foreignId('product_id')->nullable()->constrained();

            $table->decimal('stock_system', 10, 2);
            $table->decimal('stock_real', 10, 2);

            $table->decimal('difference', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_counts');
    }
};
