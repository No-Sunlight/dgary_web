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
            $table->decimal('produced_quantity', 8, 2)->default(1);//Cuanto Produje
            $table->text('notes')->nullable();//Si algo saliera mal. Creo que esta columna si es util.
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
