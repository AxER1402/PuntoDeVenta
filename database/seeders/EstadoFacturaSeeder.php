<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoFactura;

class EstadoFacturaSeeder extends Seeder
{
    public function run()
    {
        EstadoFactura::insert([
            ['estado_id' => 1, 'nombre_estado' => 'Cotización'],
            ['estado_id' => 2, 'nombre_estado' => 'Anulado'],
            ['estado_id' => 3, 'nombre_estado' => 'Vendido no facturado'],
            ['estado_id' => 4, 'nombre_estado' => 'Vendido facturado'],
        ]);
    }
}
