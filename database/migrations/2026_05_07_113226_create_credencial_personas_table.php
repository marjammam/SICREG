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
        Schema::create('credencial_personas', function (Blueprint $table) {
            $table->id('idCredencialPersona');
            $table->text('codigoQR');
            $table->timestamp('fechaEmision')->useCurrent();
            $table->unsignedBigInteger('Persona_idPersona');
            
            $table->foreign('Persona_idPersona')->references('idPersona')->on('persona')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credencial_personas');
    }
};
