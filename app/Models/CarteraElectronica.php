<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarteraElectronica extends Model
{
    use HasFactory;

    protected $table = 'carteras_electronicas';
    protected $fillable = ['user_id', 'saldo', 'fecha_creacion', 'estado'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'cartera_id');
    }

    public function metodosPago()
    {
        return $this->hasMany(MetodoPago::class, 'cartera_id');
    }
}
