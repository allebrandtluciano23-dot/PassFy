<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
{
    Schema::create('cliente', function (Blueprint $table) {
        $table->id('idCliente');

        $table->unsignedBigInteger('idCidade');

        $table->string('nomeCliente');
        $table->string('enderecoCliente');
        $table->string('cepCliente');
        $table->string('telefoneCliente');
        $table->string('cpfCliente');
        $table->string('emailCliente')->unique();
        $table->string('senhaCliente');

        $table->timestamps();
        
        $table->foreign('idCidade')->references('idCidade')->on('cidade');
    });

    }

    public function down(): void
    {
        Schema::dropIfExists('cliente');
    }
};
