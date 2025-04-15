<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalles_Empleado extends Model
{
    use HasFactory;

    
    protected $table = 'detalles_empleados';

    
    protected $fillable = [
        'empleado_id',
        'direccion',
        'telefono',
    ];

    
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
