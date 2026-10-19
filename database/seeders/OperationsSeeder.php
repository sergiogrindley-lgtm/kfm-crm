<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Policy;
use App\Models\Sede;
use App\Models\GestoriaTramite;
use App\Models\Extorno;
use App\Models\SinMatricula;
use App\Models\PolizaEmbarque;
use App\Models\Siniestro;
use App\Models\PagoFraccionado;

class OperationsSeeder extends Seeder
{
    public function run(): void
    {
        $nex = Sede::where('slug', 'nex')->first() ?? Sede::first();
        $central = Sede::where('slug', 'central')->first() ?? Sede::first();
        $miller = Client::where('apellido', 'MILLER')->first();
        $millerPolicy = $miller ? $miller->policies()->first() : null;

        // 1. Trámites de Gestoría (DGT Traspasos y Ventas)
        GestoriaTramite::updateOrCreate(
            ['matricula' => '5931LKP', 'vin' => '1FADP5CU3DL298491'],
            [
                'tipo' => 'Transferencia',
                'client_id' => $miller?->id,
                'policy_id' => $millerPolicy?->id,
                'sede_id' => $nex?->id,
                'agente' => 'Chari',
                'marca' => 'FORD',
                'modelo' => 'FOCUS TITANIUM 1.5 ECOBOOST 5P',
                'precio_venta' => '1.000,00 €',
                'buyer_nombre' => 'EMILY SARAH',
                'buyer_dni' => 'Z4192048E',
                'buyer_fa' => 'FA-99120',
                'buyer_dob' => '19/11/1995',
                'buyer_email' => 'emily.johnson@eu.navy.mil',
                'buyer_telefono' => '671-998877',
                'buyer_direccion' => 'LU BASE NAVAL DE ROTA 999',
                'buyer_cp' => '11520',
                'seller_nombre' => 'DAVID ALEXANDER MILLER',
                'seller_dni' => 'Z1928374P',
                'seller_fa' => 'FA-84912',
                'seller_direccion' => 'C/ SAN JUAN DE PUERTO RICO 12',
                'seller_cp' => '11520',
                'seller_contacto' => 'david.miller@eu.navy.mil / 671-234567',
                'precio_tramite' => '120,00 €',
                'fecha_tramite' => '19/10/2026',
                'pago_estado' => 'Pagado',
                'recibo_numero' => 'REC-2026-084',
                'itp_cuota' => '84,00 €',
                'estado' => 'OUT_GESTORIA',
                'fecha_out_gestoria' => '19/10/2026',
                'tiene_cesion' => true,
                'ssn' => '***-**-4912',
                'box_ship' => 'PSC 819 BOX 4120',
                'observaciones' => 'Expediente telemático enviado a Gestoría Bahía & Naval. Cesión de póliza adjunta.',
                'documentos_checklist' => [
                    'doc_comprador' => true,
                    'doc_vendedor' => true,
                    'permiso_circulacion' => true,
                    'ficha_itv' => true,
                    'contrato_firmado' => true,
                    'mandato_comprador' => true,
                    'mandato_vendedor' => true,
                    'tasas_dgt' => true,
                ]
            ]
        );

        GestoriaTramite::updateOrCreate(
            ['matricula' => '8412KBC'],
            [
                'tipo' => 'Transferencia',
                'sede_id' => $central?->id,
                'agente' => 'Coral',
                'marca' => 'NISSAN',
                'modelo' => 'QASHQAI 1.3 DIG-T',
                'vin' => 'SJNFAA11U67849102',
                'precio_venta' => '12.500,00 €',
                'buyer_nombre' => 'MANUEL SÁNCHEZ GÓMEZ',
                'buyer_dni' => '31849102K',
                'buyer_dob' => '12/04/1982',
                'buyer_email' => 'manuel.sanchez@gmail.com',
                'buyer_telefono' => '654-112233',
                'buyer_direccion' => 'AVDA. SEVILLA 44',
                'buyer_cp' => '11520',
                'seller_nombre' => 'BRIAN T. WILLIAMS',
                'seller_dni' => 'Y9182374L',
                'seller_direccion' => 'LG BASE NAVAL EDIF. 21',
                'seller_cp' => '11520',
                'seller_contacto' => 'brian.williams@navy.mil',
                'precio_tramite' => '145,00 €',
                'fecha_tramite' => '18/10/2026',
                'pago_estado' => 'Pagado',
                'recibo_numero' => 'REC-2026-081',
                'itp_cuota' => '240,00 €',
                'estado' => 'BACK_AVISADO',
                'fecha_out_gestoria' => '18/10/2026',
                'fecha_back_avisado' => '19/10/2026',
                'tiene_cesion' => false,
                'observaciones' => 'Permiso temporal recibido de Gestoría. Cliente avisado por WhatsApp para pasar a recoger.',
                'documentos_checklist' => [
                    'doc_comprador' => true,
                    'doc_vendedor' => true,
                    'permiso_circulacion' => true,
                    'ficha_itv' => true,
                    'contrato_firmado' => true,
                    'mandato_comprador' => true,
                    'mandato_vendedor' => true,
                    'tasas_dgt' => true,
                ]
            ]
        );

        GestoriaTramite::updateOrCreate(
            ['matricula' => '3910MDR'],
            [
                'tipo' => 'Extravío Documentación',
                'sede_id' => $nex?->id,
                'agente' => 'Kerry',
                'marca' => 'JEEP',
                'modelo' => 'WRANGLER UNLIMITED 2.0',
                'vin' => '1C4HJXDG9MW629184',
                'seller_nombre' => 'JOSHUA R. MARTINEZ',
                'seller_dni' => 'Z8374910Q',
                'seller_fa' => 'FA-73918',
                'seller_direccion' => 'C/ HIGUERA 8, ROTA',
                'seller_cp' => '11520',
                'seller_contacto' => '671-889900',
                'precio_tramite' => '65,00 €',
                'fecha_tramite' => '17/10/2026',
                'pago_estado' => 'Pagado',
                'recibo_numero' => 'REC-2026-079',
                'estado' => 'RECOGIDO',
                'fecha_out_gestoria' => '17/10/2026',
                'fecha_back_avisado' => '18/10/2026',
                'fecha_recogido' => '19/10/2026',
                'observaciones' => 'Duplicado de permiso de circulación por extravío entregado al cliente.',
            ]
        );

        // 2. Extornos (PCS Cancellations)
        Extorno::updateOrCreate(
            ['numero_poliza' => '1809858'],
            [
                'office' => 'NEX',
                'cartera' => 'Patria Hispana',
                'client_name' => 'LT. CHRISTOPHER EVANS',
                'client_id' => null,
                'email_sent_patria_date' => '12/10/2026',
                'projected_departure' => '28/10/2026 (PCS to NAVSTA Norfolk)',
                'patria_amount' => '715,00 €',
                'recibo_numero' => 'REC-EXT-2026-19',
                'recibo_fecha' => '14/10/2026',
                'amount_reimbursed' => '328,50 €',
                'status' => 'Reembolsado',
                'stateside_geico_form' => true,
                'observaciones' => 'Vehículo entregado en VPC Rota. Devolución abonada y formulario GEICO Estados Unidos cumplimentado.',
            ]
        );

        Extorno::updateOrCreate(
            ['numero_poliza' => '1839102'],
            [
                'office' => 'NEX',
                'cartera' => 'Patria Hispana',
                'client_name' => 'PO2 BRANDON SCOTT',
                'email_sent_patria_date' => '18/10/2026',
                'projected_departure' => '05/11/2026 (PCS to San Diego)',
                'patria_amount' => '590,00 €',
                'recibo_numero' => 'PENDIENTE',
                'recibo_fecha' => null,
                'amount_reimbursed' => '245,80 € (Calculado)',
                'status' => 'Pendiente',
                'stateside_geico_form' => false,
                'observaciones' => 'Pendiente de recibir confirmación de extorno de la central de Patria Hispana.',
            ]
        );

        // 3. Sin Matrícula (Temporary Plates)
        SinMatricula::updateOrCreate(
            ['numero_poliza' => '1849102'],
            [
                'aseguradora' => 'Patria',
                'matricula_provisional' => 'T-8491-BBC / VIN: 2T3C1RFV9NW019283',
                'client_name' => 'CPT. JESSICA PARKER',
                'date_notification' => '10/10/2026',
                'estado' => 'En Trámite DGT',
                'matricula_definitiva' => null,
                'observaciones' => 'Toyota RAV4 importado de EE.UU. Placa verde caduca el 15/11. En espera de ITV VEIASA.',
            ]
        );

        SinMatricula::updateOrCreate(
            ['numero_poliza' => '1841029'],
            [
                'aseguradora' => 'GEICO',
                'matricula_provisional' => 'VIN: 1GC4YNEY8NF192841',
                'client_name' => 'SGT. TYLER BENNETT',
                'date_notification' => '16/10/2026',
                'estado' => 'Pendiente',
                'matricula_definitiva' => null,
                'observaciones' => 'Chevrolet Silverado llegado al puerto. Requerida presentación de DUA y matrícula española.',
            ]
        );

        // 4. Pólizas de Embarque (Shipping POV)
        PolizaEmbarque::updateOrCreate(
            ['numero_poliza' => 'EMB-2026-012'],
            [
                'client_name' => 'CHIEF DANIEL MORGAN',
                'prima' => '420,00 €',
                'fecha_emision' => '01/09/2026',
                'fecha_liquidacion' => '01/09/2026',
                'fecha_desactivacion' => '05/09/2026',
                'fecha_reactivacion' => '16/10/2026',
                'fecha_peritacion' => '17/10/2026',
                'importe_renovacion' => '650,00 €',
                'importe_prima_no_consumida' => '185,00 €',
                'importe_a_pagar' => '465,00 €',
                'prima_aplicada_renovacion' => true,
                'estado' => 'Liquidado',
                'comments' => 'Coche recogido en VPC Rota sin daños en peritación. Prima no consumida de tránsito aplicada a renovación anual.',
            ]
        );

        PolizaEmbarque::updateOrCreate(
            ['numero_poliza' => 'EMB-2026-015'],
            [
                'client_name' => 'LT. CDR. RACHEL ADAMS',
                'prima' => '450,00 €',
                'fecha_emision' => '25/09/2026',
                'fecha_desactivacion' => '30/09/2026',
                'fecha_reactivacion' => null,
                'estado' => 'En Tránsito',
                'comments' => 'Vehículo en buque transporte USNS. Llegada estimada a Rota el 24/10/2026.',
            ]
        );

        // 5. Siniestros & Multas ARAG
        Siniestro::updateOrCreate(
            ['numero_poliza' => '1839101', 'tipo' => 'Siniestro'],
            [
                'tipo' => 'Siniestro',
                'office' => 'NEX',
                'cartera' => 'Patria Hispana',
                'matricula' => '7192LMB',
                'client_name' => 'PO1 ROBERT DAVIS',
                'fecha_email_diego' => '14/10/2026',
                'referencia_siniestro' => 'SIN-PAT-84192/26',
                'tramitadora' => 'Diego Fernández / Tramitadora Sur',
                'estado_siniestro' => 'En Peritación',
                'observaciones' => 'Alcance trasero en rotonda de la Base. Taller Rota Motor concertado. Vehículo de sustitución solicitado.',
            ]
        );

        Siniestro::updateOrCreate(
            ['numero_poliza' => '1820491', 'tipo' => 'Multa'],
            [
                'tipo' => 'Multa',
                'office' => 'Central',
                'cartera' => 'Patria Hispana',
                'matricula' => '4102KZX',
                'client_name' => 'ALBERTO RIVERA MONTES',
                'ticket_date' => '02/10/2026',
                'date_client_notified' => '15/10/2026',
                'date_sent_arag' => '16/10/2026',
                'estado_multa' => 'En Trámite ARAG',
                'observaciones' => 'Radar A-491 km 14. Enviada copia de boletín y alegaciones por error en fotograma a ARAG.',
            ]
        );

        // 6. Pagos Fraccionados (Financiación 2 plazos)
        PagoFraccionado::updateOrCreate(
            ['numero_poliza' => '1849201'],
            [
                'client_name' => 'MICHAEL S. CLARKE',
                'total_seguro' => '680,00 €',
                'primer_pago_importe' => '340,00 €',
                'primer_pago_recibo' => 'REC-PAY-101',
                'primer_pago_fecha' => '15/09/2026',
                'segundo_pago_vencimiento' => '15/03/2027',
                'segundo_pago_importe' => '340,00 €',
                'segundo_pago_recibo' => 'REC-PAY-102 (Pendiente)',
                'tarjeta_info' => 'MASTERCARD terminada en 8104',
                'tarjeta_exp' => '08/28',
                'estado' => 'Pendiente 2º Pago',
                'observaciones' => 'Fraccionamiento semestral. Cobro automático programado en tarjeta para marzo 2027.',
            ]
        );
    }
}
