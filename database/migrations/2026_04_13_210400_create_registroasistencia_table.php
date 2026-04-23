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
        Schema::create('registroasistencia', function (Blueprint $table) {
            $table->id('idregistroAsistencia');
            $table->text('codigoQRleido')->nullable();
            $table->timestamp('fechahoraIngreso')->nullable();
            $table->timestamp('fechahoraSalida')->nullable();
            $table->string('estadoR')->nullable();

            // Solo creamos las columnas, sin forzar la relación física
            $table->unsignedBigInteger('Subevento_idSubevento');
            $table->unsignedBigInteger('Persona_idPersona');
            $table->unsignedBigInteger('Usuario_idUsuario')->nullable();

            $table->foreign('Subevento_idSubevento')
                ->references('idSubevento')
                ->on('subevento')
                ->cascadeOnDelete();
            $table->foreign('Persona_idPersona')
                ->references('idPersona')
                ->on('persona')
                ->cascadeOnDelete();
            $table->foreign('Usuario_idUsuario')
                ->references('idUsuario')
                ->on('usuario')
                ->onDelete('set null');

            $table->timestamps();
        });

    }
    public function down(): void
    {
        Schema::dropIfExists('registroasistencia');
    }
};
