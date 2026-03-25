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
            $table->string('nomeUsuario');
            $table->string('senhaUsuario');
            $table->timestamps();
        });
    }

 
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
