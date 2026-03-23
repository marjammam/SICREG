<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registroasistencia', function (Blueprint $table) {
            $table->integer('idregistroAsistencia', true);
            $table->string('codigoQRleido', 120)->nullable();
            $table->dateTime('fechahoraIngreso')->nullable();
            $table->dateTime('fechahoraSalida')->nullable();
            $table->string('estadoR', 30)->nullable();

            $table->integer('Subevento_idSubevento');
            $table->integer('Persona_idPersona');
            $table->integer('Usuario_idUsuario');

            $table->index('Subevento_idSubevento', 'fk_registroAsistencia_Subevento1_idx');
            $table->index('Persona_idPersona', 'fk_registroAsistencia_Persona1_idx');
            $table->index('Usuario_idUsuario', 'fk_registroAsistencia_Usuario1_idx');

            $table->foreign('Subevento_idSubevento', 'fk_registroAsistencia_Subevento1')
                  ->references('idSubevento')
                  ->on('subevento');

            $table->foreign('Persona_idPersona', 'fk_registroAsistencia_Persona1')
                  ->references('idPersona')
                  ->on('persona');

            $table->foreign('Usuario_idUsuario', 'fk_registroAsistencia_Usuario1')
                  ->references('idUsuario')
                  ->on('usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registroasistencia');
    }
};
