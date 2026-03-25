<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('venda', function (Blueprint $table) {
        $table->id('idVenda');

        $table->unsignedBigInteger('idCliente');
        $table->unsignedBigInteger('idIngresso');

        $table->integer('quantidadeVenda');
        $table->date('dataCompra');
        $table->string('formaPagamento');
        $table->decimal('valorTotal', 10, 2);

        $table->timestamps();

        $table->foreign('idCliente')
              ->references('idCliente')
              ->on('cliente');

        $table->foreign('idIngresso')
              ->references('idIngresso')
              ->on('ingresso');
    });
}


    public function down(): void
    {
        Schema::dropIfExists('_venda');
    }
};
