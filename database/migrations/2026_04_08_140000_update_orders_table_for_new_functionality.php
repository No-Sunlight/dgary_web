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
        Schema::table('orders', function (Blueprint $table) {
            // Agregar delivery_fee si no existe
            if (!Schema::hasColumn('orders', 'delivery_fee')) {
                $table->decimal('delivery_fee', 10, 2)->default(0)->after('subtotal');
            }
            
            // Agregar tax si no existe
            if (!Schema::hasColumn('orders', 'tax')) {
                $table->decimal('tax', 10, 2)->default(0)->after('delivery_fee');
            }
            
            // Agregar notes si no existe
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('discount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumnIfExists(['delivery_fee', 'tax', 'notes']);
        });
    }
};
