<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingresso_carrinho', function (Blueprint $table) {

            $table->unsignedBigInteger('idCarrinho');
            $table->unsignedBigInteger('idLote');

            $table->integer('quantidade');
            $table->decimal('valorUnitario', 10, 2);

            $table->timestamps();

            $table->primary(['idCarrinho', 'idLote']);

            $table->foreign('idCarrinho')
                  ->references('idCarrinho')
                  ->on('carrinho')
                  ->onDelete('cascade');

            $table->foreign('idLote')
                  ->references('idLote')
                  ->on('lote')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingresso_carrinho');
    }
};