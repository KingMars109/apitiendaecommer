<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetodosPagoTable extends Migration
{
    public function up()
    {
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cartera_id');
            $table->enum('tipo_pago', ['tarjeta_credito', 'tarjeta_debito', 'transferencia', 'otro']);
            $table->string('informacion_pago', 255);
            $table->date('fecha_expiracion')->nullable();

            $table->foreign('cartera_id')->references('id')->on('carteras_electronicas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('metodos_pago');
    }
}
