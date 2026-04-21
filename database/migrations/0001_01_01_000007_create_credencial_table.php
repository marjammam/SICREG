<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credencial', function (Blueprint $table) {
            $table->id('idCredencial');
            $table->string('tipoCredencial', 45)->nullable();
            $table->string('codigoQR', 120)->nullable();
            $table->timestamp('fechaEmision')->nullable();
            $table->unsignedBigInteger('Persona_idPersona');
            $table->unsignedBigInteger('Evento_idEvento')->nullable();

            $table->foreign('Persona_idPersona')
                ->references('idPersona')
                ->on('persona');
            $table->foreign('Evento_idEvento')
                ->references('idEvento')
                ->on('evento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credencial');
    }
};
