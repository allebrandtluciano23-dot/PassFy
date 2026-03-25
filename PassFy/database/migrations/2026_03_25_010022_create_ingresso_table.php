<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
{
    Schema::create('ingresso', function (Blueprint $table) {
        $table->id('idIngresso');

        $table->unsignedBigInteger('idLote');

        $table->integer('codigoUnico')->unique();
        $table->char('status', 1)->default('A');

        $table->timestamps();

        $table->foreign('idLote')
              ->references('idLote')
              ->on('lote');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('ingresso');
    }
};
