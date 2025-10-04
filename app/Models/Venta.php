<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'estado_id',
        'cliente_id',
        'nit_cf', // Para cuando agregues NIT o C/F
    ];

    // Relación con cliente
    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    // Relación con estado
    public function estado()
    {
        return $this->belongsTo(EstadoFactura::class, 'estado_id');
    }

    // Relación con detalles de venta
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
