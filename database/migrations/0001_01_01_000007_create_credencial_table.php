<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('credencial', function (Blueprint $table) {
            $table->id('idCredencial');
            $table->unsignedBigInteger('Persona_idPersona');
            $table->string('carnet', 20);
            $table->string('tipo_institucion', 20);
            $table->string('cargo', 100)->nullable();
            $table->timestamp('fecha_emision')->useCurrent();
            $table->foreign('Persona_idPersona')->references('idPersona')->on('persona')->onDelete('cascade');
            
        });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('credencial');
    }
};
