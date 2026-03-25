<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
   public function up(): void
{
    Schema::create('ingresso_venda', function (Blueprint $table) {

        $table->unsignedBigInteger('idIngresso');
        $table->unsignedBigInteger('idVenda');

        $table->integer('quantidade');
        $table->decimal('valorUnitario', 10, 2);

        $table->timestamps();

        $table->primary(['idIngresso', 'idVenda']);

        $table->foreign('idIngresso')
              ->references('idIngresso')
              ->on('ingresso');

        $table->foreign('idVenda')
              ->references('idVenda')
              ->on('venda');
    });
}


    public function down(): void
    {
        Schema::dropIfExists('ingresso_venda');
    }
};
