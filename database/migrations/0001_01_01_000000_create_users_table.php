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
        // Apesar do nome historico do arquivo, esta migration ficou responsavel
        // apenas por sessoes. A tabela users atual e criada em migration propria,
        // com os campos de perfil exigidos pelo trabalho.
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            // user_id permite associar a sessao ao usuario logado; pode ficar nulo em visitas publicas.
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            // payload armazena os dados serializados da sessao.
            $table->longText('payload');
            // last_activity ajuda o Laravel a expirar sessoes antigas.
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
