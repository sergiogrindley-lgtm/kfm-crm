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
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'ssn')) {
                $table->string('ssn', 50)->nullable()->after('doc_identidad');
            }
        });

        Schema::table('policies', function (Blueprint $table) {
            if (!Schema::hasColumn('policies', 'fecha_liquidacion')) {
                $table->string('fecha_liquidacion', 50)->nullable()->after('liquidacion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'ssn')) {
                $table->dropColumn('ssn');
            }
        });

        Schema::table('policies', function (Blueprint $table) {
            if (Schema::hasColumn('policies', 'fecha_liquidacion')) {
                $table->dropColumn('fecha_liquidacion');
            }
        });
    }
};
