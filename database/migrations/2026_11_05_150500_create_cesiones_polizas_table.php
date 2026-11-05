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
        Schema::create('cesiones_polizas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('policy_id')->nullable()->constrained('policies')->nullOnDelete();
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->string('agente')->nullable();
            
            // Póliza y Cartera
            $table->string('numero_poliza');
            $table->string('cartera')->default('81'); // 81 = Patria 81, 87 = GEICO 87

            // Cedente (Titular actual / Tomador original)
            $table->string('cedente_nombre');
            $table->string('cedente_dni')->nullable();
            $table->string('cedente_telefono')->nullable();

            // Nuevo Tomador / Cesionario (Receptor con derechos y obligaciones)
            $table->string('cesionario_nombre');
            $table->string('cesionario_ssn')->nullable();
            $table->string('cesionario_dob')->nullable();
            $table->string('cesionario_direccion')->nullable();
            $table->string('cesionario_telefono')->nullable();
            $table->string('cesionario_email')->nullable();

            // Vehículo
            $table->string('matricula');
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('vin')->nullable();

            // Pago de Suplemento (en caso de que hubiera suplemento)
            $table->boolean('tiene_suplemento')->default(false);
            $table->string('suplemento_recibo')->nullable();
            $table->string('suplemento_cantidad')->nullable();

            // Tramitación
            $table->string('fecha_cesion')->nullable();
            $table->string('estado')->default('Activa'); // Activa, Pendiente de firma, Finalizada, Anulada
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cesiones_polizas');
    }
};
