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
    Schema::create('organizadora', function (Blueprint $table) {
        $table->id('idOrg');

        $table->unsignedBigInteger('idCidade');

        $table->string('nomeOrg');
        $table->string('cnpjOrg')->unique();
        $table->string('enderecoOrg');
        $table->string('cepOrg');
        $table->string('telefoneOrg');
        $table->string('emailOrg')->unique();
        $table->string('senhaOrg');

        $table->timestamps();

        $table->foreign('idCidade')
              ->references('idCidade')
              ->on('cidade');
    });
}


    public function down(): void
    {
        Schema::dropIfExists('organizadora');
    }
};
