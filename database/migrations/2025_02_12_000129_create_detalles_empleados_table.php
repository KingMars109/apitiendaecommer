<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_empleados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->string('direccion');
            $table->string('telefono');
            $table->timestamps();

            $table->foreign('empleado_id')->references('id_empleado')->on('empleados')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_empleados');
    }
};