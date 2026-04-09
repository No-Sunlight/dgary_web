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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('customer_id');
            
            // Stripe IDs
            $table->string('stripe_payment_intent_id')->unique();
            $table->string('stripe_charge_id')->nullable()->unique();
            
            // Payment details
            $table->decimal('amount', 10, 2);
            $table->enum('currency', ['USD', 'MXN', 'COP', 'ARS'])->default('MXN');
            $table->enum('status', ['pending', 'processing', 'succeeded', 'canceled', 'failed'])->default('pending');
            $table->enum('payment_method', ['card', 'apple_pay', 'google_pay', 'link'])->nullable();
            
            // Card details (solo últimos 4 dígitos)
            $table->string('card_last_four', 4)->nullable();
            $table->string('card_brand')->nullable(); // visa, mastercard, amex, etc.
            
            // Error tracking
            $table->text('error_message')->nullable();
            
            // Full Stripe response
            $table->json('stripe_response')->nullable();
            
            // Timeline
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
