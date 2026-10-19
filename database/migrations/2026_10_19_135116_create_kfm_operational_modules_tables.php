<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Trámites de Gestoría & Transferencias DGT (Sheet GESTORIA + PORTADA + MANDATOS)
        Schema::create('gestoria_tramites', function (Blueprint $table) {
            $table->id();
            $table->string('tipo')->default('Transferencia'); // Transferencia, Baja, Matriculación, Extravío
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('policy_id')->nullable()->constrained('policies')->nullOnDelete();
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->string('agente')->nullable()->default('Chari');

            // Datos del Vehículo
            $table->string('matricula')->nullable()->index();
            $table->string('vin')->nullable()->index();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('precio_venta')->nullable()->default('1.000,00 €');

            // Datos del Comprador (Buyer)
            $table->string('buyer_nombre')->nullable();
            $table->string('buyer_dni')->nullable()->index(); // DNI/NIE
            $table->string('buyer_fa')->nullable(); // FA # (Foreign Affiliate)
            $table->string('buyer_dob')->nullable();
            $table->string('buyer_email')->nullable();
            $table->string('buyer_telefono')->nullable();
            $table->string('buyer_direccion')->nullable();
            $table->string('buyer_cp')->nullable()->default('11520');

            // Datos del Vendedor (Seller)
            $table->string('seller_nombre')->nullable();
            $table->string('seller_dni')->nullable()->index();
            $table->string('seller_fa')->nullable();
            $table->string('seller_direccion')->nullable();
            $table->string('seller_cp')->nullable()->default('11520');
            $table->string('seller_contacto')->nullable();

            // Liquidación & Pagos
            $table->string('precio_tramite')->nullable()->default('120,00 €');
            $table->string('fecha_tramite')->nullable();
            $table->string('pago_estado')->default('Pagado'); // Pagado, Pendiente
            $table->string('recibo_numero')->nullable();
            $table->string('itp_cuota')->nullable(); // 4% Modelo 620

            // Pipeline de Estados (Kanban)
            $table->string('estado')->default('DOCUMENTOS')->index(); // DOCUMENTOS, OUT_GESTORIA, BACK_AVISADO, LIQUIDADO, RECOGIDO
            $table->string('fecha_out_gestoria')->nullable();
            $table->string('fecha_back_avisado')->nullable();
            $table->string('fecha_liquidado')->nullable();
            $table->string('fecha_recogido')->nullable();
            $table->boolean('tiene_cesion')->default(false);
            $table->string('ssn')->nullable();
            $table->string('box_ship')->nullable();
            $table->text('observaciones')->nullable();
            $table->json('documentos_checklist')->nullable(); // Checklist de documentos recibidos/entregados
            $table->timestamps();
        });

        // 2. Extornos & PCS Departures (Sheet EXTORNOS)
        Schema::create('extornos', function (Blueprint $table) {
            $table->id();
            $table->string('office')->default('NEX'); // Central, NEX
            $table->string('cartera')->default('Patria Hispana'); // Patria Hispana, GEICO
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('policy_id')->nullable()->constrained('policies')->nullOnDelete();
            $table->string('numero_poliza')->nullable()->index();
            $table->string('client_name')->nullable();
            $table->string('email_sent_patria_date')->nullable();
            $table->string('projected_departure')->nullable(); // Fecha de salida PCS
            $table->string('patria_amount')->nullable(); // Prima original
            $table->string('recibo_numero')->nullable();
            $table->string('recibo_fecha')->nullable();
            $table->string('amount_reimbursed')->nullable(); // Devolución al militar
            $table->string('status')->default('Pendiente')->index(); // Pendiente, Tramitado, Reembolsado, Cancelado
            $table->boolean('stateside_geico_form')->default(false); // Formulario GEICO US completado
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // 3. Vehículos Sin Matrícula / Placas Provisionales (Sheet SIN MATRICULA)
        Schema::create('sin_matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('policy_id')->nullable()->constrained('policies')->nullOnDelete();
            $table->string('numero_poliza')->nullable()->index();
            $table->string('aseguradora')->default('Patria'); // Patria (P), GEICO (G)
            $table->string('matricula_provisional')->nullable()->index();
            $table->string('client_name')->nullable();
            $table->string('date_notification')->nullable();
            $table->string('estado')->default('Pendiente')->index(); // Pendiente, En Trámite DGT, Regularizado
            $table->string('matricula_definitiva')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // 4. Pólizas de Embarque / Shipping POV (Sheet POLIZAS EMBARQUE)
        Schema::create('polizas_embarque', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('policy_id')->nullable()->constrained('policies')->nullOnDelete();
            $table->string('numero_poliza')->nullable()->index();
            $table->string('client_name')->nullable();
            $table->string('prima')->nullable();
            $table->string('fecha_emision')->nullable();
            $table->string('fecha_liquidacion')->nullable();
            $table->string('fecha_desactivacion')->nullable(); // En barco
            $table->string('fecha_reactivacion')->nullable(); // Llegada a Rota
            $table->string('fecha_peritacion')->nullable(); // Peritación de daños
            $table->string('importe_renovacion')->nullable();
            $table->string('importe_prima_no_consumida')->nullable();
            $table->string('importe_a_pagar')->nullable();
            $table->boolean('prima_aplicada_renovacion')->default(false);
            $table->string('estado')->default('En Tránsito')->index(); // En Tránsito, Llegado a VPC, Peritado, Reactivado, Liquidado
            $table->text('comments')->nullable();
            $table->timestamps();
        });

        // 5. Siniestros & Multas ARAG (Sheet SINIESTROS)
        Schema::create('siniestros', function (Blueprint $table) {
            $table->id();
            $table->string('tipo')->default('Siniestro'); // Siniestro, Multa
            $table->string('office')->default('Central'); // Central, NEX
            $table->string('cartera')->default('Patria Hispana');
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('policy_id')->nullable()->constrained('policies')->nullOnDelete();
            $table->string('numero_poliza')->nullable()->index();
            $table->string('matricula')->nullable()->index();
            $table->string('client_name')->nullable();
            
            // Campos específicos Siniestro
            $table->string('fecha_email_diego')->nullable();
            $table->string('referencia_siniestro')->nullable()->index();
            $table->string('tramitadora')->nullable();
            $table->string('estado_siniestro')->default('Abierto')->index(); // Abierto, En Peritación, Indemnizado, Cerrado

            // Campos específicos Multa
            $table->string('ticket_date')->nullable();
            $table->string('date_client_notified')->nullable();
            $table->string('date_sent_arag')->nullable();
            $table->string('estado_multa')->nullable()->default('En Trámite ARAG'); // En Trámite ARAG, Estimada, Pagada, Archivada

            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // 6. Pagos Fraccionados / Financiación (Sheet PAYMENTS)
        Schema::create('pagos_fraccionados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('policy_id')->nullable()->constrained('policies')->nullOnDelete();
            $table->string('numero_poliza')->nullable()->index();
            $table->string('client_name')->nullable();
            $table->string('total_seguro')->nullable();
            $table->string('primer_pago_importe')->nullable();
            $table->string('primer_pago_recibo')->nullable();
            $table->string('primer_pago_fecha')->nullable();
            $table->string('segundo_pago_vencimiento')->nullable()->index();
            $table->string('segundo_pago_importe')->nullable();
            $table->string('segundo_pago_recibo')->nullable();
            $table->string('tarjeta_info')->nullable(); // Ej: VISA terminada en 4912
            $table->string('tarjeta_exp')->nullable();
            $table->string('estado')->default('Pendiente 2º Pago')->index(); // Pendiente 2º Pago, Cobrado, Vencido, Cancelado
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_fraccionados');
        Schema::dropIfExists('siniestros');
        Schema::dropIfExists('polizas_embarque');
        Schema::dropIfExists('sin_matriculas');
        Schema::dropIfExists('extornos');
        Schema::dropIfExists('gestoria_tramites');
    }
};
