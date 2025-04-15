<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('proveedores')) {
            Schema::create('proveedores', function (Blueprint $table) {
                $table->id('id_proveedor'); 
                $table->string('nombre', 100);
                $table->string('telefono', 20)->nullable();
                $table->string('email', 100)->unique();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};