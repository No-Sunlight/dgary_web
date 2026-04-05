<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('deliveries_user_id_foreign');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('address');
            $table->enum('status', ['pending', 'ready', 'completed', 'canceled','in_transit','refund'])->default('pending');
            $table->text('notes')->nullable();
            $table->decimal('total', 10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
