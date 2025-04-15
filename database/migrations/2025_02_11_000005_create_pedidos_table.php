<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente');
            $table->timestamps();

            $table->foreign('id_cliente')->references('id_cliente')->on('clientes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
