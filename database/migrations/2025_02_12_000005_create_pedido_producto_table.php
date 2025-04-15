<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pedido_producto')) {
            Schema::create('pedido_producto', function (Blueprint $table) {
                $table->unsignedBigInteger('id_pedido');
                $table->unsignedBigInteger('id_producto');
                $table->integer('cantidad');
                $table->timestamps();

                $table->primary(['id_pedido', 'id_producto']);
                $table->foreign('id_pedido')->references('id')->on('pedidos')->onDelete('cascade');
                $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_producto');
    }
};