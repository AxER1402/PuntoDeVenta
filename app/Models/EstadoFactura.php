<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <- CORRECTO

class EstadoFactura extends Model
{
   use HasFactory;

    protected $table = 'estados_factura'; 
    protected $primaryKey = 'estado_id';  
    public $timestamps = false;           

    protected $fillable = ['nombre_estado'];
}
