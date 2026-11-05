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
        Schema::table('pagos_fraccionados', function (Blueprint $table) {
            $table->string('cvv', 10)->nullable()->after('tarjeta_exp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos_fraccionados', function (Blueprint $table) {
            $table->dropColumn('cvv');
        });
    }
};
