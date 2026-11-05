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
        Schema::table('siniestros', function (Blueprint $table) {
            if (!Schema::hasColumn('siniestros', 'fecha_cliente_avisado')) {
                $table->string('fecha_cliente_avisado')->nullable()->after('fecha_reclamacion_recibida');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siniestros', function (Blueprint $table) {
            if (Schema::hasColumn('siniestros', 'fecha_cliente_avisado')) {
                $table->dropColumn('fecha_cliente_avisado');
            }
        });
    }
};
