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

        $table->unsignedBigInteger('idOrg')->nullable();
        $table->unsignedBigInteger('idCliente')->nullable();
        $table->unsignedBigInteger('idCidade');

        $table->string('nomeEvento');
        $table->string('localEvento');
        $table->date('dataEvento');
        $table->time('horaEvento')->nullable();
        $table->string('tipoEvento')->nullable();
        $table->text('descricaoEvento');
        $table->char('statusEvento', 1);
        $table->string('imagemEvento', 255)->nullable();
        
        $table->timestamps();

        $table->foreign('idOrg')
              ->references('idOrg')
              ->on('organizadora');

        $table->foreign('idCliente')
              ->references('idCliente')
              ->on('cliente');

        $table->foreign('idCidade')
              ->references('idCidade')
              ->on('cidade');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('evento');
    }
};
