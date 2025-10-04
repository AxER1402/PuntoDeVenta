<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Tabla asociada
    protected $table = 'productos';

    // Clave primaria
    protected $primaryKey = 'producto_id';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen',
    ];

    /**
     * Si deseas que la clave primaria no sea auto-increment
     * o sea de tipo string, puedes configurar lo siguiente:
     */
    // public $incrementing = true;
    // protected $keyType = 'int';

    /**
     * Opcional: puedes agregar un accesorio para obtener la URL completa de la imagen
     */
    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return asset('storage/' . $this->imagen);
        }
        return 'https://via.placeholder.com/200';
    }
}
