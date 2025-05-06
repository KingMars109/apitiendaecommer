<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarterasElectronicasTable extends Migration
{
    public function up()
    {
        Schema::create('carteras_electronicas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('saldo', 10, 2)->default(0.00);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->enum('estado', ['activa', 'suspendida'])->default('activa');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('carteras_electronicas');
    }
}
