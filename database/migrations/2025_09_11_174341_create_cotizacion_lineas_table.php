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
        Schema::create('cotizacion_lineas', function (Blueprint $table) {
        $table->id('linea_id');
        $table->unsignedBigInteger('cotizacion_id');
        $table->unsignedBigInteger('producto_id');
        $table->integer('cantidad');
        $table->decimal('precio_unitario', 12, 2);
        $table->decimal('subtotal', 12, 2);
        $table->timestamps();

        $table->foreign('cotizacion_id')->references('cotizacion_id')->on('cotizaciones')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizacion_lineas');
    }
};
