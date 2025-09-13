<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Auditoria extends Model
{
     use HasFactory;

    protected $table = 'auditorias'; // nombre de la tabla
    protected $primaryKey = 'id';    // ajusta si tu PK es otra

    // Campos permitidos para "mass assignment"
    protected $fillable = [
        'tabla',
        'accion',
        'registro_id',
        'usuario',
        'detalle',
    ];
}
