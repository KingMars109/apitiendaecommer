<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'id_producto'; 
    protected $fillable = ['nombre', 'precio', 'stock', 'imagen', 'categoria_id'];
    protected $appends = ['imagen_url'];

    // Relación con Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

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
}