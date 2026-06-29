<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento', function (Blueprint $table) {
            $table->id('idEvento');
            $table->string('nombreE', 100)->nullable();
            $table->string('tipoEvento', 255)->nullable();
            $table->date('fechaInicioE')->nullable();
            $table->date('fechaFinE')->nullable();
            $table->string('estadoE', 30)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento');
    }
};
