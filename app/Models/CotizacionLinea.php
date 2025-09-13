<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use App\Models\Producto;   
use App\Models\Cotizacion; 

class CotizacionLinea extends Model
{
    use HasFactory;

    protected $table = 'cotizacion_lineas';

    protected $fillable = [
        'cotizacion_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'producto_id');
    }

    // Relación con la cotización
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id', 'cotizacion_id');
    }
}
