<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('lote', function (Blueprint $table) {
            $table->id('idLote');

            $table->unsignedBigInteger('idEvento');

            $table->string('nomeLote');
            $table->integer('quantidadeTotal');
            $table->decimal('valoIngresso', 10, 2);
            
            $table->timestamps();

             $table->foreign('idEvento')
              ->references('idEvento')
              ->on('evento');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lote');
    }
};
