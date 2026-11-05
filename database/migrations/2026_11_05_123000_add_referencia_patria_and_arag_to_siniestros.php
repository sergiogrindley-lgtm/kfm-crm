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
            if (!Schema::hasColumn('siniestros', 'referencia_patria')) {
                $table->string('referencia_patria')->nullable()->index()->after('referencia_siniestro');
            }
            if (!Schema::hasColumn('siniestros', 'referencia_arag')) {
                $table->string('referencia_arag')->nullable()->index()->after('referencia_patria');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siniestros', function (Blueprint $table) {
            $table->dropColumn(['referencia_patria', 'referencia_arag']);
        });
    }
};
