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
        Schema::table('customer_coupons', function (Blueprint $table) {
            $table->foreign(['coupon_id'], 'user_coupons_id_coupon_foreign')->references(['id'])->on('coupons')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['customer_id'], 'user_coupons_id_customer_foreign')->references(['id'])->on('customers')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_coupons', function (Blueprint $table) {
            $table->dropForeign('user_coupons_id_coupon_foreign');
            $table->dropForeign('user_coupons_id_customer_foreign');
        });
    }
};
