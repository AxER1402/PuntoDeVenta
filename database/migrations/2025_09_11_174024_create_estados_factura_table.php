<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estados_factura', function (Blueprint $table) {
        $table->id('estado_id');
        $table->string('nombre_estado');
    });

    DB::table('estados_factura')->insert([
        ['estado_id' => 1, 'nombre_estado' => 'Cotización'],
        ['estado_id' => 2, 'nombre_estado' => 'Anulado'],
        ['estado_id' => 3, 'nombre_estado' => 'Vendido pero no facturado electrónicamente'],
        ['estado_id' => 4, 'nombre_estado' => 'Vendido y Facturado Electrónicamente'],
    ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados_factura');
    }
};
