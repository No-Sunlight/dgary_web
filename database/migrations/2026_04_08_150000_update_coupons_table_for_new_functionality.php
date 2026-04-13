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
        Schema::table('coupons', function (Blueprint $table) {
            // Agregar código único si no existe
            if (!Schema::hasColumn('coupons', 'code')) {
                $table->string('code')->unique()->after('name');
            }
            
            // Cambiar nombre de columna discount a discount_percent si existe
            // if (Schema::hasColumn('coupons', 'discount') && !Schema::hasColumn('coupons', 'discount_percent')) {
            //     $table->renameColumn('discount', 'discount_percent');
            // }
            
            // Agregar is_active si no existe
            if (!Schema::hasColumn('coupons', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('discount_percent');
            }
            
            // Agregar expires_at si no existe
            if (!Schema::hasColumn('coupons', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('is_active');
            }
            
            // Agregar minimum_purchase si no existe
            if (!Schema::hasColumn('coupons', 'minimum_purchase')) {
                $table->decimal('minimum_purchase', 10, 2)->nullable()->after('expires_at');
            }
            
            // Agregar uses_count si no existe
            if (!Schema::hasColumn('coupons', 'uses_count')) {
                $table->integer('uses_count')->nullable()->after('minimum_purchase');
            }
            
            // Agregar used_count si no existe
            if (!Schema::hasColumn('coupons', 'used_count')) {
                $table->integer('used_count')->default(0)->after('uses_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumnIfExists(['code', 'is_active', 'expires_at', 'minimum_purchase', 'uses_count', 'used_count']);
            
            // // Revertir renombramiento si ocurrió
            // if (Schema::hasColumn('coupons', 'discount_percent') && !Schema::hasColumn('coupons', 'discount')) {
            //     $table->renameColumn('discount_percent', 'discount');
            // }
        });
    }
};
