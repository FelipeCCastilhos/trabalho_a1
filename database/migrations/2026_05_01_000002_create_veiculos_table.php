<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela da frota disponivel para locacao.
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->string('marca');
            $table->string('modelo');
            $table->unsignedSmallInteger('ano');
            $table->string('placa', 7)->unique();
            $table->string('categoria', 30);
            $table->decimal('valor_diaria', 10, 2);
            $table->string('status', 20)->default('disponivel');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('veiculos');
    }
};
