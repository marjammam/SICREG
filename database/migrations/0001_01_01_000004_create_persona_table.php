<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->id('idPersona');
            $table->string('nombre', 60)->nullable();
            $table->string('apellidos', 60)->nullable();
            $table->string('ci', 20)->nullable();
            $table->string('tipoInstitucion', 100)->nullable();
            $table->string('distrito', 60)->nullable();
            $table->string('foto', 255)->nullable();
            $table->enum('estadoP', ['ACTIVO', 'INACTIVO'])->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};
