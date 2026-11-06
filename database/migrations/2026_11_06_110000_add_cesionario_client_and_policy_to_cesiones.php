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
        Schema::table('cesiones_polizas', function (Blueprint $table) {
            if (!Schema::hasColumn('cesiones_polizas', 'cesionario_client_id')) {
                $table->unsignedBigInteger('cesionario_client_id')->nullable()->after('client_id');
            }
            if (!Schema::hasColumn('cesiones_polizas', 'cesionario_policy_id')) {
                $table->unsignedBigInteger('cesionario_policy_id')->nullable()->after('policy_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cesiones_polizas', function (Blueprint $table) {
            if (Schema::hasColumn('cesiones_polizas', 'cesionario_client_id')) {
                $table->dropColumn('cesionario_client_id');
            }
            if (Schema::hasColumn('cesiones_polizas', 'cesionario_policy_id')) {
                $table->dropColumn('cesionario_policy_id');
            }
        });
    }
};
