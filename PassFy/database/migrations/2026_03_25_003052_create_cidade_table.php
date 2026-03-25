<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cidade', function (Blueprint $table) {
            $table->id('idCidade');
            $table->string('nomeCidade');
            $table->string('ufCidade', 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cidade');
    }
};