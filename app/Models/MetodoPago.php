<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    use HasFactory;

    protected $table = 'metodos_pago';
    protected $fillable = ['cartera_id', 'tipo_pago', 'informacion_pago', 'fecha_expiracion'];
    public $timestamps = false;

    public function carteraElectronica()
    {
        return $this->belongsTo(CarteraElectronica::class, 'cartera_id');
    }
}
