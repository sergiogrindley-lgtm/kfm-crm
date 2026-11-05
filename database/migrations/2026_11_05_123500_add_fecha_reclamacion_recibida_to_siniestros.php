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
            if (!Schema::hasColumn('siniestros', 'fecha_reclamacion_recibida')) {
                $table->string('fecha_reclamacion_recibida')->nullable()->after('fecha_email_diego');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siniestros', function (Blueprint $table) {
            $table->dropColumn('fecha_reclamacion_recibida');
        });
    }
};
