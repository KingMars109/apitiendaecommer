<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    use HasFactory;

    protected $table = 'transacciones';
    protected $fillable = ['cartera_id', 'monto', 'tipo_transaccion', 'fecha_transaccion', 'descripcion', 'estado'];
    public $timestamps = false;

    public function carteraElectronica()
    {
        return $this->belongsTo(CarteraElectronica::class, 'cartera_id');
    }
}
