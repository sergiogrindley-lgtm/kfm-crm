<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Policy;
use App\Models\Sede;
use App\Models\GestoriaTramite;
use App\Models\Extorno;
use App\Models\SinMatricula;
use App\Models\PolizaEmbarque;
use App\Models\Siniestro;
use App\Models\PagoFraccionado;
use Illuminate\Http\Request;

class OperationsController extends Controller
{
    /**
     * =========================================================================
     * 1. GESTORÍA DGT & TRANSFERENCIAS DE VEHÍCULOS
     * =========================================================================
     */
    public function indexGestoria(Request $request)
    {
        $query = GestoriaTramite::with(['client', 'policy', 'sede'])->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('matricula', 'like', "%{$search}%")
                  ->orWhere('vin', 'like', "%{$search}%")
                  ->orWhere('buyer_nombre', 'like', "%{$search}%")
                  ->orWhere('buyer_dni', 'like', "%{$search}%")
                  ->orWhere('seller_nombre', 'like', "%{$search}%")
                  ->orWhere('seller_dni', 'like', "%{$search}%")
                  ->orWhere('marca', 'like', "%{$search}%")
                  ->orWhere('modelo', 'like', "%{$search}%");
            });
        }

        if ($estado = $request->input('estado')) {
            if ($estado === 'PENDIENTE_DOC') {
                $query->whereIn('estado', ['PENDIENTE_DOC', 'DOCUMENTOS']);
            } elseif ($estado === 'ENVIADO_GESTORIA') {
                $query->whereIn('estado', ['ENVIADO_GESTORIA', 'OUT_GESTORIA']);
            } elseif (in_array($estado, ['PERMISO_RECIBIDO', 'TERMINADAS', 'TERMINADA'])) {
                $query->whereIn('estado', ['PERMISO_RECIBIDO', 'BACK_AVISADO', 'LIQUIDADO', 'RECOGIDO']);
            } else {
                $query->where('estado', $estado);
            }
        }

        if ($tipo = $request->input('tipo')) {
            if ($tipo === 'Transferencia' || $tipo === 'Transferencias') {
                $query->where('tipo', 'like', '%Transferencia%');
            } else {
                $query->where('tipo', $tipo);
            }
        }

        if ($sedeId = $request->input('sede_id')) {
            $query->where('sede_id', $sedeId);
        }

        if ($agente = $request->input('agente')) {
            $query->where('agente', $agente);
        }

        $tramites = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => GestoriaTramite::count(),
            'preparado' => GestoriaTramite::where('estado', 'PREPARADO')->count(),
            'pendiente_doc' => GestoriaTramite::whereIn('estado', ['PENDIENTE_DOC', 'DOCUMENTOS'])->count(),
            'enviado_gestoria' => GestoriaTramite::whereIn('estado', ['ENVIADO_GESTORIA', 'OUT_GESTORIA'])->count(),
            'permiso_recibido' => GestoriaTramite::whereIn('estado', ['PERMISO_RECIBIDO', 'BACK_AVISADO', 'LIQUIDADO', 'RECOGIDO'])->count(),
            'cancelado' => GestoriaTramite::where('estado', 'CANCELADO')->count(),
            'cesion_poliza' => GestoriaTramite::where('estado', 'CESION_POLIZA')->count(),
            'transferencias' => GestoriaTramite::where('tipo', 'like', '%Transferencia%')->count(),
        ];

        $sedes = Sede::all();

        return view('operations.gestoria.index', compact('tramites', 'stats', 'sedes'));
    }

    public function storeGestoria(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:100',
            'agente' => 'nullable|string|max:50',
            'sede_id' => 'nullable|exists:sedes,id',
            'matricula' => 'required|string|max:50',
            'vin' => 'nullable|string|max:50',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'fecha_tramite' => 'nullable|string|max:50',
            'precio_venta' => 'nullable|string|max:50',
            'buyer_nombre' => 'required|string|max:150',
            'buyer_dni' => 'nullable|string|max:50',
            'buyer_fa' => 'nullable|string|max:50',
            'buyer_dob' => 'nullable|string|max:50',
            'buyer_email' => 'nullable|string|max:100',
            'buyer_telefono' => 'nullable|string|max:50',
            'buyer_direccion' => 'nullable|string|max:255',
            'buyer_cp' => 'nullable|string|max:20',
            'seller_nombre' => 'required|string|max:150',
            'seller_dni' => 'nullable|string|max:50',
            'seller_fa' => 'nullable|string|max:50',
            'seller_direccion' => 'nullable|string|max:255',
            'seller_cp' => 'nullable|string|max:20',
            'seller_contacto' => 'nullable|string|max:100',
            'precio_tramite' => 'nullable|string|max:50',
            'itp_cuota' => 'nullable|string|max:50',
            'recibo_numero' => 'nullable|string|max:50',
            'estado' => 'nullable|string|max:50',
            'tiene_cesion' => 'nullable|boolean',
            'numero_poliza' => 'nullable|string|max:100',
            'ssn' => 'nullable|string|max:50',
            'box_ship' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string',
        ]);

        $validated['matricula'] = strtoupper(trim($validated['matricula']));
        $validated['vin'] = !empty($validated['vin']) ? strtoupper(trim($validated['vin'])) : null;
        $validated['buyer_nombre'] = strtoupper(trim($validated['buyer_nombre']));
        $validated['buyer_dni'] = !empty($validated['buyer_dni']) ? strtoupper(trim($validated['buyer_dni'])) : null;
        $validated['seller_nombre'] = strtoupper(trim($validated['seller_nombre']));
        $validated['seller_dni'] = !empty($validated['seller_dni']) ? strtoupper(trim($validated['seller_dni'])) : null;
        $validated['fecha_tramite'] = !empty($validated['fecha_tramite']) ? trim($validated['fecha_tramite']) : date('d/m/Y');
        $validated['estado'] = $validated['estado'] ?? 'PREPARADO';
        $validated['tiene_cesion'] = $request->has('tiene_cesion') || $request->input('tipo') === 'Cesión de póliza';
        $validated['numero_poliza'] = !empty($validated['numero_poliza']) ? strtoupper(trim($validated['numero_poliza'])) : null;

        // Documentos checklist por defecto
        $validated['documentos_checklist'] = [
            'doc_comprador' => true,
            'doc_vendedor' => true,
            'permiso_circulacion' => true,
            'ficha_itv' => true,
            'contrato_firmado' => true,
            'mandato_comprador' => true,
            'mandato_vendedor' => true,
            'tasas_dgt' => true,
        ];

        $tramite = GestoriaTramite::create($validated);

        return redirect()->route('operations.gestoria.index')
            ->with('success', "Expediente para {$tramite->matricula} ({$tramite->buyer_nombre}) registrado correctamente.");
    }

    public function updateGestoria(Request $request, GestoriaTramite $tramite)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:100',
            'agente' => 'nullable|string|max:50',
            'sede_id' => 'nullable|exists:sedes,id',
            'matricula' => 'required|string|max:50',
            'vin' => 'nullable|string|max:50',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'fecha_tramite' => 'nullable|string|max:50',
            'precio_venta' => 'nullable|string|max:50',
            'buyer_nombre' => 'required|string|max:150',
            'buyer_dni' => 'nullable|string|max:50',
            'buyer_fa' => 'nullable|string|max:50',
            'buyer_dob' => 'nullable|string|max:50',
            'buyer_email' => 'nullable|string|max:100',
            'buyer_telefono' => 'nullable|string|max:50',
            'buyer_direccion' => 'nullable|string|max:255',
            'buyer_cp' => 'nullable|string|max:20',
            'seller_nombre' => 'required|string|max:150',
            'seller_dni' => 'nullable|string|max:50',
            'seller_fa' => 'nullable|string|max:50',
            'seller_direccion' => 'nullable|string|max:255',
            'seller_cp' => 'nullable|string|max:20',
            'seller_contacto' => 'nullable|string|max:100',
            'precio_tramite' => 'nullable|string|max:50',
            'itp_cuota' => 'nullable|string|max:50',
            'recibo_numero' => 'nullable|string|max:50',
            'estado' => 'required|string|max:50',
            'tiene_cesion' => 'nullable|boolean',
            'numero_poliza' => 'nullable|string|max:100',
            'ssn' => 'nullable|string|max:50',
            'box_ship' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string',
        ]);

        $validated['matricula'] = strtoupper(trim($validated['matricula']));
        $validated['vin'] = !empty($validated['vin']) ? strtoupper(trim($validated['vin'])) : null;
        $validated['buyer_nombre'] = strtoupper(trim($validated['buyer_nombre']));
        $validated['buyer_dni'] = !empty($validated['buyer_dni']) ? strtoupper(trim($validated['buyer_dni'])) : null;
        $validated['seller_nombre'] = strtoupper(trim($validated['seller_nombre']));
        $validated['seller_dni'] = !empty($validated['seller_dni']) ? strtoupper(trim($validated['seller_dni'])) : null;
        $validated['tiene_cesion'] = $request->has('tiene_cesion') || $request->input('tipo') === 'Cesión de póliza';
        $validated['numero_poliza'] = !empty($validated['numero_poliza']) ? strtoupper(trim($validated['numero_poliza'])) : null;

        $tramite->update($validated);

        return redirect()->route('operations.gestoria.index')
            ->with('success', "Expediente {$tramite->matricula} actualizado correctamente.");
    }

    public function updateGestoriaStatus(Request $request, GestoriaTramite $tramite)
    {
        $estado = $request->input('estado');
        $hoy = date('d/m/Y');

        $data = ['estado' => $estado];

        if ($estado === 'ENVIADO_GESTORIA' && !$tramite->fecha_out_gestoria) {
            $data['fecha_out_gestoria'] = $hoy;
        } elseif ($estado === 'PERMISO_RECIBIDO' && !$tramite->fecha_recogido) {
            $data['fecha_recogido'] = $hoy;
        }

        $tramite->update($data);

        return redirect()->back()
            ->with('success', "Expediente {$tramite->matricula} actualizado a estado: {$tramite->status_label}.");
    }

    /**
     * Generación de Documentos Oficiales de Gestoría
     */
    public function documentoPortada(GestoriaTramite $tramite)
    {
        return view('operations.gestoria.docs.portada', compact('tramite'));
    }

    public function documentoMandatoComprador(GestoriaTramite $tramite)
    {
        return view('operations.gestoria.docs.mandato-comprador', compact('tramite'));
    }

    public function documentoMandatoVendedor(GestoriaTramite $tramite)
    {
        return view('operations.gestoria.docs.mandato-vendedor', compact('tramite'));
    }

    public function documentoContratoCompraventa(GestoriaTramite $tramite)
    {
        $fecha = $tramite->fecha_tramite ?: ($tramite->created_at ? $tramite->created_at->format('d/m/Y') : date('d/m/Y'));
        $dia = date('d');
        $mesNombre = 'OCTUBRE';
        $ano = date('Y');

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($fecha), $m)) {
            $dia = (int)$m[1];
            $meses = [
                1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
                5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
                9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
            ];
            $mesNombre = $meses[(int)$m[2]] ?? 'OCTUBRE';
            $ano = $m[3];
        }

        $precioNum = 1000;
        if (!empty($tramite->precio_venta)) {
            $cleaned = str_replace(['€', ' ', '.'], '', $tramite->precio_venta);
            $cleaned = str_replace(',', '.', $cleaned);
            $parsed = (float)$cleaned;
            if ($parsed > 0) {
                $precioNum = $parsed;
            }
        }
        $precioLetras = $this->numeroALetras($precioNum);
        $precioFormatted = number_format($precioNum, 2, ',', '.') . ' €';

        return view('operations.gestoria.docs.contrato-compraventa', compact('tramite', 'dia', 'mesNombre', 'ano', 'precioLetras', 'precioFormatted'));
    }

    public function documentoExtravioVendedor(GestoriaTramite $tramite)
    {
        $fecha = $tramite->fecha_tramite ?: ($tramite->created_at ? $tramite->created_at->format('d/m/Y') : date('d/m/Y'));
        $dia = date('d');
        $mesNombre = 'OCTUBRE';
        $ano = date('Y');

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($fecha), $m)) {
            $dia = (int)$m[1];
            $meses = [
                1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
                5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
                9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
            ];
            $mesNombre = $meses[(int)$m[2]] ?? 'OCTUBRE';
            $ano = $m[3];
        }

        return view('operations.gestoria.docs.extravio-vendedor', compact('tramite', 'dia', 'mesNombre', 'ano'));
    }

    public function documentoCesion(GestoriaTramite $tramite)
    {
        $fecha = $tramite->fecha_tramite ?: ($tramite->created_at ? $tramite->created_at->format('d/m/Y') : date('d/m/Y'));
        $dia = date('d');
        $mesNombre = 'OCTUBRE';
        $ano = date('Y');

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($fecha), $m)) {
            $dia = (int)$m[1];
            $meses = [
                1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
                5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
                9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
            ];
            $mesNombre = $meses[(int)$m[2]] ?? 'OCTUBRE';
            $ano = $m[3];
        }

        return view('operations.gestoria.docs.cesion', compact('tramite', 'dia', 'mesNombre', 'ano'));
    }

    public function documentoEmbargo(GestoriaTramite $tramite)
    {
        $fecha = $tramite->fecha_tramite ?: ($tramite->created_at ? $tramite->created_at->format('d/m/Y') : date('d/m/Y'));
        $dia = date('d');
        $mesNombre = 'OCTUBRE';
        $ano = date('Y');

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($fecha), $m)) {
            $dia = (int)$m[1];
            $meses = [
                1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
                5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
                9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
            ];
            $mesNombre = $meses[(int)$m[2]] ?? 'OCTUBRE';
            $ano = $m[3];
        }

        return view('operations.gestoria.docs.embargo', compact('tramite', 'dia', 'mesNombre', 'ano'));
    }

    private function numeroALetras($number): string
    {
        $unidades = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $especiales = [11 => 'ONCE', 12 => 'DOCE', 13 => 'TRECE', 14 => 'CATORCE', 15 => 'QUINCE', 16 => 'DIECISÉIS', 17 => 'DIECISIETE', 18 => 'DIECIOCHO', 19 => 'DIECINUEVE'];
        $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

        $number = (int)$number;
        if ($number === 0) return 'CERO EUROS';
        if ($number === 100) return 'CIEN EUROS';

        $convertir3 = function($n) use ($unidades, $especiales, $decenas, $centenas) {
            $res = '';
            $c = (int)($n / 100);
            $restoCentena = $n % 100;
            $d = (int)($restoCentena / 10);
            $u = $restoCentena % 10;

            if ($n === 100) return 'CIEN';
            if ($c > 0) $res .= $centenas[$c] . ' ';

            if ($restoCentena >= 11 && $restoCentena <= 19) {
                $res .= $especiales[$restoCentena];
            } elseif ($restoCentena === 10) {
                $res .= 'DIEZ';
            } elseif ($restoCentena === 20) {
                $res .= 'VEINTE';
            } elseif ($restoCentena > 20 && $restoCentena < 30) {
                $res .= 'VEINTI' . $unidades[$u];
            } else {
                if ($d > 0) {
                    $res .= $decenas[$d];
                    if ($u > 0) $res .= ' Y ' . $unidades[$u];
                } elseif ($u > 0) {
                    $res .= $unidades[$u];
                }
            }
            return trim($res);
        };

        $miles = (int)($number / 1000);
        $resto = $number % 1000;
        $texto = '';

        if ($miles === 1) {
            $texto .= 'MIL ';
        } elseif ($miles > 1) {
            $texto .= $convertir3($miles) . ' MIL ';
        }

        if ($resto > 0) {
            $texto .= $convertir3($resto);
        }

        return trim($texto) . ' EUROS';
    }

    /**
     * =========================================================================
     * 2. EXTORNOS & TRASLADOS MILITARES (PCS)
     * =========================================================================
     */
    public function indexExtornos(Request $request)
    {
        $query = Extorno::with(['client', 'policy'])->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_poliza', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('projected_departure', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $extornos = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Extorno::count(),
            'pendientes' => Extorno::where('status', 'Pendiente')->count(),
            'tramitados' => Extorno::where('status', 'Tramitado')->count(),
            'reembolsados' => Extorno::where('status', 'Reembolsado')->count(),
        ];

        return view('operations.extornos.index', compact('extornos', 'stats'));
    }

    public function storeExtorno(Request $request)
    {
        $validated = $request->validate([
            'office' => 'required|string|max:50',
            'cartera' => 'required|string|max:50',
            'numero_poliza' => 'required|string|max:50',
            'client_name' => 'required|string|max:150',
            'email_sent_patria_date' => 'nullable|string|max:50',
            'projected_departure' => 'nullable|string|max:150',
            'patria_amount' => 'nullable|string|max:50',
            'amount_reimbursed' => 'nullable|string|max:50',
            'status' => 'required|string|max:50',
            'stateside_geico_form' => 'nullable|boolean',
            'observaciones' => 'nullable|string',
        ]);

        $validated['stateside_geico_form'] = $request->has('stateside_geico_form');

        Extorno::create($validated);

        return redirect()->route('operations.extornos.index')
            ->with('success', "Extorno para la póliza #{$validated['numero_poliza']} registrado correctamente.");
    }

    public function updateExtorno(Request $request, Extorno $extorno)
    {
        $validated = $request->validate([
            'status' => 'required|string|max:50',
            'amount_reimbursed' => 'nullable|string|max:50',
            'recibo_numero' => 'nullable|string|max:50',
            'stateside_geico_form' => 'nullable|boolean',
            'observaciones' => 'nullable|string',
        ]);

        $validated['stateside_geico_form'] = $request->has('stateside_geico_form');
        if ($validated['status'] === 'Reembolsado' && empty($extorno->recibo_fecha)) {
            $validated['recibo_fecha'] = date('d/m/Y');
        }

        $extorno->update($validated);

        return redirect()->route('operations.extornos.index')
            ->with('success', "Extorno #{$extorno->numero_poliza} actualizado.");
    }

    /**
     * =========================================================================
     * 3. SIN MATRÍCULA / PLACAS PROVISIONALES
     * =========================================================================
     */
    public function indexSinMatricula(Request $request)
    {
        $query = SinMatricula::with(['client', 'policy'])->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_poliza', 'like', "%{$search}%")
                  ->orWhere('matricula_provisional', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        $items = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => SinMatricula::count(),
            'pendientes' => SinMatricula::where('estado', 'Pendiente')->count(),
            'en_tramite' => SinMatricula::where('estado', 'En Trámite DGT')->count(),
            'regularizados' => SinMatricula::where('estado', 'Regularizado')->count(),
        ];

        return view('operations.sin-matricula.index', compact('items', 'stats'));
    }

    public function storeSinMatricula(Request $request)
    {
        $validated = $request->validate([
            'numero_poliza' => 'required|string|max:50',
            'aseguradora' => 'required|string|max:50',
            'matricula_provisional' => 'required|string|max:100',
            'client_name' => 'required|string|max:150',
            'date_notification' => 'nullable|string|max:50',
            'estado' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        SinMatricula::create($validated);

        return redirect()->route('operations.sin-matricula.index')
            ->with('success', 'Caso de placa provisional añadido para seguimiento.');
    }

    public function updateSinMatricula(Request $request, SinMatricula $item)
    {
        $validated = $request->validate([
            'estado' => 'required|string|max:50',
            'matricula_definitiva' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $item->update($validated);

        return redirect()->route('operations.sin-matricula.index')
            ->with('success', "Estado actualizado para {$item->client_name}.");
    }

    /**
     * =========================================================================
     * 4. PÓLIZAS DE EMBARQUE / SHIPPING POV (US ⇄ ROTA)
     * =========================================================================
     */
    public function indexEmbarque(Request $request)
    {
        $query = PolizaEmbarque::with(['client', 'policy'])->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_poliza', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        $items = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => PolizaEmbarque::count(),
            'en_transito' => PolizaEmbarque::where('estado', 'En Tránsito')->count(),
            'en_vpc' => PolizaEmbarque::where('estado', 'Llegado a VPC')->count(),
            'peritados' => PolizaEmbarque::where('estado', 'Peritado')->count(),
            'liquidados' => PolizaEmbarque::where('estado', 'Liquidado')->count(),
        ];

        return view('operations.embarque.index', compact('items', 'stats'));
    }

    public function storeEmbarque(Request $request)
    {
        $validated = $request->validate([
            'numero_poliza' => 'required|string|max:50',
            'client_name' => 'required|string|max:150',
            'prima' => 'required|string|max:50',
            'fecha_emision' => 'nullable|string|max:50',
            'fecha_desactivacion' => 'nullable|string|max:50',
            'fecha_reactivacion' => 'nullable|string|max:50',
            'fecha_peritacion' => 'nullable|string|max:50',
            'importe_renovacion' => 'nullable|string|max:50',
            'importe_prima_no_consumida' => 'nullable|string|max:50',
            'importe_a_pagar' => 'nullable|string|max:50',
            'prima_aplicada_renovacion' => 'nullable|boolean',
            'estado' => 'required|string|max:50',
            'comments' => 'nullable|string',
        ]);

        $validated['prima_aplicada_renovacion'] = $request->has('prima_aplicada_renovacion');

        PolizaEmbarque::create($validated);

        return redirect()->route('operations.embarque.index')
            ->with('success', "Póliza de embarque #{$validated['numero_poliza']} registrada.");
    }

    public function updateEmbarque(Request $request, PolizaEmbarque $embarque)
    {
        $validated = $request->validate([
            'estado' => 'required|string|max:50',
            'fecha_reactivacion' => 'nullable|string|max:50',
            'fecha_peritacion' => 'nullable|string|max:50',
            'importe_prima_no_consumida' => 'nullable|string|max:50',
            'importe_a_pagar' => 'nullable|string|max:50',
            'prima_aplicada_renovacion' => 'nullable|boolean',
            'comments' => 'nullable|string',
        ]);

        $validated['prima_aplicada_renovacion'] = $request->has('prima_aplicada_renovacion');

        $embarque->update($validated);

        return redirect()->route('operations.embarque.index')
            ->with('success', "Expediente de embarque #{$embarque->numero_poliza} actualizado.");
    }

    /**
     * =========================================================================
     * 5. SINIESTROS & MULTAS ARAG
     * =========================================================================
     */
    public function indexSiniestros(Request $request)
    {
        $tab = $request->input('tab', 'siniestros'); // siniestros or multas
        $query = Siniestro::with(['client', 'policy'])->where('tipo', $tab === 'multas' ? 'Multa' : 'Siniestro')->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_poliza', 'like', "%{$search}%")
                  ->orWhere('matricula', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('referencia_siniestro', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(20)->withQueryString();

        $stats = [
            'siniestros_abiertos' => Siniestro::where('tipo', 'Siniestro')->where('estado_siniestro', '!=', 'Cerrado')->count(),
            'siniestros_total' => Siniestro::where('tipo', 'Siniestro')->count(),
            'multas_activas' => Siniestro::where('tipo', 'Multa')->where('estado_multa', 'En Trámite ARAG')->count(),
            'multas_total' => Siniestro::where('tipo', 'Multa')->count(),
        ];

        return view('operations.siniestros.index', compact('items', 'stats', 'tab'));
    }

    public function storeSiniestro(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:Siniestro,Multa',
            'office' => 'required|string|max:50',
            'cartera' => 'required|string|max:50',
            'numero_poliza' => 'required|string|max:50',
            'matricula' => 'required|string|max:50',
            'client_name' => 'required|string|max:150',
            'fecha_email_diego' => 'nullable|string|max:50',
            'referencia_siniestro' => 'nullable|string|max:100',
            'tramitadora' => 'nullable|string|max:100',
            'estado_siniestro' => 'nullable|string|max:50',
            'ticket_date' => 'nullable|string|max:50',
            'date_client_notified' => 'nullable|string|max:50',
            'date_sent_arag' => 'nullable|string|max:50',
            'estado_multa' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        Siniestro::create($validated);

        return redirect()->route('operations.siniestros.index', ['tab' => strtolower($validated['tipo']) === 'multa' ? 'multas' : 'siniestros'])
            ->with('success', "{$validated['tipo']} registrado correctamente.");
    }

    public function updateSiniestro(Request $request, Siniestro $siniestro)
    {
        $validated = $request->validate([
            'estado_siniestro' => 'nullable|string|max:50',
            'estado_multa' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $siniestro->update($validated);

        return redirect()->back()
            ->with('success', 'Registro actualizado con éxito.');
    }

    /**
     * =========================================================================
     * 6. PAGOS FRACCIONADOS (FINANCIACIÓN EN 2 PLAZOS)
     * =========================================================================
     */
    public function indexPagos(Request $request)
    {
        $query = PagoFraccionado::with(['client', 'policy'])->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_poliza', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        $pagos = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => PagoFraccionado::count(),
            'pendientes' => PagoFraccionado::where('estado', 'Pendiente 2º Pago')->count(),
            'cobrados' => PagoFraccionado::where('estado', 'Cobrado')->count(),
            'vencidos' => PagoFraccionado::where('estado', 'Vencido')->count(),
        ];

        return view('operations.pagos.index', compact('pagos', 'stats'));
    }

    public function updatePago(Request $request, PagoFraccionado $pago)
    {
        $validated = $request->validate([
            'estado' => 'required|string|max:50',
            'segundo_pago_recibo' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $pago->update($validated);

        return redirect()->route('operations.pagos.index')
            ->with('success', "Estado de pago actualizado para #{$pago->numero_poliza}.");
    }
}
