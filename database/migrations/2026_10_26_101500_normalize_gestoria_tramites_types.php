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
        DB::table('gestoria_tramites')
            ->where('tipo', 'Cesión de póliza')
            ->orWhereNull('tipo')
            ->orWhere('tipo', '')
            ->update([
                'tipo' => 'Transferencia ordinaria',
                'tiene_cesion' => true,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
