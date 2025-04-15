<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        Producto::create([
            'nombre' => 'Producto Ejemplo 1',
            'precio' => 19.99,
            'stock' => 100
        ]);

        Producto::create([
            'nombre' => 'Producto Ejemplo 2', 
            'precio' => 29.99,
            'stock' => 50
        ]);

        Producto::create([
            'nombre' => 'Producto Ejemplo 3',
            'precio' => 39.99,
            'stock' => 25,
            'imagen' => 'producto3.jpg'
        ]);
    }
}
