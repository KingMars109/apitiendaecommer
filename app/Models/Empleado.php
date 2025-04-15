<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';
    protected $fillable = [
        'nombre',
        'puesto',
    ];

    public function detalles()
    {
        return $this->hasOne(DetallesEmpleado::class, 'empleado_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }
}
