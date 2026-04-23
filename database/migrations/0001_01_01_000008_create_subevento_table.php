<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subevento', function (Blueprint $table) {
            $table->id('idSubevento');
            $table->string('nombreSE', 100)->nullable();
            $table->string('tipoEvento', 100)->nullable();
            $table->date('fechaSE')->nullable();
            $table->time('horaInicio')->nullable();
            $table->time('horaFin')->nullable();
            $table->string('estadoSE', 30)->nullable();
            $table->unsignedBigInteger('Evento_idEvento');

            $table->foreign('Evento_idEvento')
                ->references('idEvento')
                ->on('evento')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subevento');
    }
};
