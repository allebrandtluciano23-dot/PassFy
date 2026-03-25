<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('carteira_digital', function (Blueprint $table) {
            $table->id('idCarteira');

            $table->unsignedBigInteger('idCliente');

            $table->decimal('saldo', 10, 2);
            $table->timestamps();

            $table->foreign('idCliente')
              ->references('idCliente')
              ->on('cliente');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carteira_digital');
    }
};
