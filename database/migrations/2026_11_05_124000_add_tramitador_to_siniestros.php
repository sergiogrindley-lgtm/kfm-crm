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
            if (!Schema::hasColumn('siniestros', 'tramitador')) {
                $table->string('tramitador')->nullable()->after('referencia_arag');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siniestros', function (Blueprint $table) {
            if (Schema::hasColumn('siniestros', 'tramitador')) {
                $table->dropColumn('tramitador');
            }
        });
    }
};
