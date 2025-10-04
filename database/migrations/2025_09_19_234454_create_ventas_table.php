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
        Schema::table('ventas', function (Blueprint $table) {
            // Solo agregamos columna nit_cf opcional
            if (!Schema::hasColumn('ventas', 'nit_cf')) {
                $table->string('nit_cf')->nullable()->after('total');
            }

            // Si quieres mantener estado_id como entero simple (sin FK)
            if (!Schema::hasColumn('ventas', 'estado_id')) {
                $table->unsignedBigInteger('estado_id')->default(3)->after('nit_cf');
            }

            // Eliminamos cliente_id, no lo necesitamos
            if (Schema::hasColumn('ventas', 'cliente_id')) {
                $table->dropColumn('cliente_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            if (Schema::hasColumn('ventas', 'nit_cf')) {
                $table->dropColumn('nit_cf');
            }
            if (Schema::hasColumn('ventas', 'estado_id')) {
                $table->dropColumn('estado_id');
            }
        });
    }
};
