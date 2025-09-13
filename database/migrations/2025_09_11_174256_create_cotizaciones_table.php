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
        Schema::create('cotizaciones', function (Blueprint $table) {
        $table->id('cotizacion_id');
        $table->unsignedBigInteger('cliente_id');
        $table->dateTime('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->decimal('total', 12, 2)->default(0);
        $table->unsignedBigInteger('estado_id')->default(1);
        $table->string('usuario_crea')->nullable();
        $table->timestamps();

        $table->foreign('estado_id')->references('estado_id')->on('estados_factura');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
