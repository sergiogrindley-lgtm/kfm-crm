<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sedes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // central, nex
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('color')->default('#145a78');
            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->string('agente')->nullable()->index(); // Kerry, Coral, Conchi
            $table->string('doc_identidad')->nullable()->index();
            $table->string('nombre')->nullable()->index();
            $table->string('apellido')->nullable()->index();
            $table->string('nombre_familiar')->nullable(); // Cónyuge, Esposa, etc.
            $table->string('dob')->nullable(); // Fecha de Nacimiento
            $table->string('telefono_movil')->nullable()->index();
            $table->string('telefono_fijo')->nullable();
            $table->string('email_personal')->nullable()->index();
            $table->string('email_trabajo')->nullable()->index();
            $table->text('direccion_local')->nullable();
            $table->text('direccion_base')->nullable(); // PSC Box / USS Ship
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->string('agente')->nullable(); // Tramitador
            $table->string('numero_poliza')->nullable()->index();
            $table->string('codigo_aseguradora')->nullable()->default('81'); // 81 (Patria), 87, etc.
            $table->string('aseguradora')->nullable()->default('Patria Hispana');
            $table->string('ramo')->default('Vehiculo')->index(); // Vehiculo, Hogar, RC, Empresa, Otros
            
            // Campos específicos de Vehículo
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('matricula')->nullable()->index();
            $table->string('vin')->nullable()->index(); // Bastidor

            // Campos específicos de Hogar
            $table->string('tipo_vivienda')->nullable();
            $table->text('direccion_riesgo')->nullable();

            // Campo genérico para RC, Empresa, Otros
            $table->text('detalles_cobertura')->nullable();

            // Facturación y Cobro
            $table->string('capital')->nullable();
            $table->string('prima')->nullable();
            $table->string('tipo_facturacion')->default('Annual'); // Annual, Semi-annual, Suplemento, Prima aplicada, Cedido
            $table->string('balance')->default('Paid Full'); // Paid Full, Partial
            $table->string('liquidacion')->default('Liquidado'); // Liquidado, Pendiente
            $table->string('fecha_pago')->nullable();
            $table->string('fecha_vencimiento')->nullable()->index();
            $table->string('estado')->default('Activo')->index(); // Activo, Cancelado
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('policies');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('sedes');
    }
};
