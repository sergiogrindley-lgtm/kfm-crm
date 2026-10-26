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
        Schema::table('gestoria_tramites', function (Blueprint $table) {
            $table->string('numero_poliza')->nullable()->after('tiene_cesion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gestoria_tramites', function (Blueprint $table) {
            $table->dropColumn('numero_poliza');
        });
    }
};
