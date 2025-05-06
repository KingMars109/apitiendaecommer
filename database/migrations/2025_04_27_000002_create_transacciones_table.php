<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaccionesTable extends Migration
{
    public function up()
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cartera_id');
            $table->decimal('monto', 10, 2);
            $table->enum('tipo_transaccion', ['carga', 'retiro', 'pago']);
            $table->timestamp('fecha_transaccion')->useCurrent();
            $table->string('descripcion', 255)->nullable();
            $table->enum('estado', ['pendiente', 'completada', 'fallida'])->default('pendiente');

            $table->foreign('cartera_id')->references('id')->on('carteras_electronicas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transacciones');
    }
}
