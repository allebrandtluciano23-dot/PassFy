<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
{
    Schema::create('carrinho', function (Blueprint $table) {
        $table->id('idCarrinho');

        $table->unsignedBigInteger('idCliente');

        $table->timestamps();

        $table->foreign('idCliente')
              ->references('idCliente')
              ->on('cliente');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('carrinho');
    }
};
