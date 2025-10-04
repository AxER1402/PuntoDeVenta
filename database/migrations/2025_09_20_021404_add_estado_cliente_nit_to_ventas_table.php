<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar columnas primero
        Schema::table('ventas', function (Blueprint $table) {
            if (!Schema::hasColumn('ventas', 'estado_id')) {
                $table->unsignedBigInteger('estado_id')->default(3)->after('total');
            }
            if (!Schema::hasColumn('ventas', 'cliente_id')) {
                $table->unsignedBigInteger('cliente_id')->nullable()->after('estado_id');
            }
            if (!Schema::hasColumn('ventas', 'nit_cf')) {
                $table->string('nit_cf')->nullable()->after('cliente_id');
            }
        });

        // Luego agregar las foreign keys
        Schema::table('ventas', function (Blueprint $table) {
            $table->foreign('estado_id')->references('id')->on('estados_factura');
            $table->foreign('cliente_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            if (Schema::hasColumn('ventas', 'estado_id')) {
                $table->dropForeign(['estado_id']);
                $table->dropColumn('estado_id');
            }
            if (Schema::hasColumn('ventas', 'cliente_id')) {
                $table->dropForeign(['cliente_id']);
                $table->dropColumn('cliente_id');
            }
            if (Schema::hasColumn('ventas', 'nit_cf')) {
                $table->dropColumn('nit_cf');
            }
        });
    }
};
