<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // Añade esta línea

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'id_producto'; 
    protected $fillable = ['nombre', 'precio', 'stock', 'imagen'];

    // Método para guardar la imagen en storage/app/public/imagenes
    public static function guardarImagen($imagen)
    {
        $ruta = $imagen->store('imagenes', 'public'); // Guarda en storage/app/public/imagenes
        return basename($ruta); // Devuelve solo el nombre del archivo (ej: "producto.jpg")
    }

    // Método para obtener la URL pública de la imagen
    public function getImagenUrlAttribute()
    {
        return Storage::disk('public')->url('imagenes/' . $this->imagen);
    }

    // Añade el campo "imagen_url" a las respuestas JSON
    protected $appends = ['imagen_url'];
}