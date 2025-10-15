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
        Schema::create('nodos', function (Blueprint $table) {

            $table->id(); // Crea una columna 'id' autoincremental
            $table->bigInteger('parent')->nullable(); // Crea una columna 'nombre' de tipo string
            $table->text('title')->nullable(); // Crea una columna 'descripcion' de tipo text, que puede ser nula
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nodos');
    }
};
