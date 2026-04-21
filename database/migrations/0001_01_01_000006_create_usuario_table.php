<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('idUsuario');
            $table->string('nombreApellido', 60)->nullable();
            $table->string('email', 60)->nullable();
            $table->string('usuario', 45)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('rol', 30)->nullable();
            $table->enum('estado', ['ACTIVO', 'INACTIVO'])->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
