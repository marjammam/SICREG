<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subevento', function (Blueprint $table) {
            $table->integer('idSubevento', true);
            $table->string('nombreSE', 100)->nullable();
            $table->string('tipoEvento', 100)->nullable();
            $table->date('fechaSE')->nullable();
            $table->time('horaInicio')->nullable();
            $table->time('horaFin')->nullable();
            $table->string('estadoSE', 30)->nullable();
            $table->integer('Evento_idEvento');

            $table->index('Evento_idEvento', 'fk_Subevento_Evento_idx');

            $table->foreign('Evento_idEvento', 'fk_Subevento_Evento')
                  ->references('idEvento')
                  ->on('evento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subevento');
    }
};
