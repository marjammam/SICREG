<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credencial', function (Blueprint $table) {
            $table->integer('idCredencial', true);
            $table->string('tipoCredencial', 45)->nullable();
            $table->string('codigoQR', 120)->nullable();
            $table->timestamp('fechaEmision')->nullable();
            $table->integer('Persona_idPersona');
            $table->integer('Evento_idEvento')->nullable();

            $table->unique('codigoQR', 'codigoQR_UNIQUE');

            $table->index('Persona_idPersona', 'fk_Credencial_Persona1_idx');
            $table->index('Evento_idEvento', 'fk_Credencial_Evento1_idx');

            $table->foreign('Persona_idPersona', 'fk_Credencial_Persona1')
                  ->references('idPersona')
                  ->on('persona');

            $table->foreign('Evento_idEvento', 'fk_Credencial_Evento1')
                  ->references('idEvento')
                  ->on('evento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credencial');
    }
};
