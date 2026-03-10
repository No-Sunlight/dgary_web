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
        Schema::create('preparation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();//La receta a la que esta ligada
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();//Quien fue quien la preparo o aprobo
            $table->decimal('quantity_produced', 8, 2)->default(1);//Cuanto Produje
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preparation_logs');
    }
};
