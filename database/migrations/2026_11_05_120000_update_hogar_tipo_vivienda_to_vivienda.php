<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('policies')
            ->where('ramo', 'Hogar')
            ->where(function ($q) {
                $q->where('tipo_vivienda', 'PISO MEDIO')
                  ->orWhereNull('tipo_vivienda');
            })
            ->update(['tipo_vivienda' => 'VIVIENDA']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No revert needed
    }
};
