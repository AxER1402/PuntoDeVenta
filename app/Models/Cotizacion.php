<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones';
    protected $primaryKey = 'cotizacion_id';

    protected $fillable = [
        'cliente_id',
        'fecha',
        'total',
        'estado_id',
        'usuario_crea',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function lineas()
    {
        return $this->hasMany(CotizacionLinea::class, 'cotizacion_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoFactura::class, 'estado_id');
    }

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }
}
