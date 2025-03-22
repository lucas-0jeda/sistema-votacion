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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_voted_id'); // Columna entera sin signo
            $table->foreign('candidate_voted_id') // Define la clave foránea
            ->references('id') // Columna referenciada
            ->on('voters') // Tabla referenciada
            ->onDelete('cascade')
            ->onUpdate('cascade'); // Opcional
            $table->bigInteger('candidate_id')->unique();
            $table->dateTime('date', precision: 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
