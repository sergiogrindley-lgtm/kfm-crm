<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Policy;
use App\Models\Sede;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected array $agentesDisponibles = ['Kerry', 'Coral', 'Conchi', 'Chari'];

    public function index(Request $request)
    {
        $this->ensureDemoMillerExists();

        $query = Client::with(['sede', 'policies']);

        // Filtro por búsqueda global
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('nombre_familiar', 'like', "%{$search}%")
                  ->orWhere('doc_identidad', 'like', "%{$search}%")
                  ->orWhere('telefono_movil', 'like', "%{$search}%")
                  ->orWhere('telefono_fijo', 'like', "%{$search}%")
                  ->orWhere('email_personal', 'like', "%{$search}%")
                  ->orWhere('email_trabajo', 'like', "%{$search}%")
                  ->orWhere('direccion_base', 'like', "%{$search}%")
                  ->orWhere('direccion_local', 'like', "%{$search}%")
                  ->orWhere('agente', 'like', "%{$search}%")
                  ->orWhereHas('policies', function ($pq) use ($search) {
                      $pq->where('matricula', 'like', "%{$search}%")
                         ->orWhere('vin', 'like', "%{$search}%")
                         ->orWhere('numero_poliza', 'like', "%{$search}%")
                         ->orWhere('codigo_aseguradora', 'like', "%{$search}%")
                         ->orWhere('aseguradora', 'like', "%{$search}%")
                         ->orWhere('marca', 'like', "%{$search}%")
                         ->orWhere('modelo', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por Sede (Central o NEX)
        if ($sedeId = $request->input('sede_id')) {
            $query->where('sede_id', $sedeId);
        }

        // Filtro por Agente
        if ($agente = $request->input('agente')) {
            $query->where('agente', $agente);
        }

        $clients = $query->latest()->paginate(25)->withQueryString();

        $sedes = Sede::withCount('clients')->get();
        $totalClients = Client::count();
        $totalPolicies = Policy::count();
        $activePolicies = Policy::where('estado', 'Activo')->count();
        $agentes = $this->agentesDisponibles;

        return view('clients.index', compact(
            'clients',
            'sedes',
            'totalClients',
            'totalPolicies',
            'activePolicies',
            'agentes'
        ));
    }

    public function create()
    {
        $sedes = Sede::all();
        $agentes = $this->agentesDisponibles;
        return view('clients.create', compact('sedes', 'agentes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sede_id' => 'required|exists:sedes,id',
            'agente' => 'nullable|string|max:50',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'nombre_familiar' => 'nullable|string|max:150',
            'doc_identidad' => 'nullable|string|max:50',
            'dob' => 'nullable|string|max:50',
            'telefono_movil' => 'nullable|string|max:50',
            'telefono_fijo' => 'nullable|string|max:50',
            'email_personal' => 'nullable|email|max:100',
            'email_trabajo' => 'nullable|string|max:100',
            'direccion_local' => 'nullable|string|max:255',
            'direccion_base' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',

            // Póliza inicial (opcional)
            'crear_poliza' => 'nullable',
            'ramo' => 'nullable|string|max:50',
            'codigo_aseguradora' => 'nullable|string|max:20',
            'aseguradora' => 'nullable|string|max:100',
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'matricula' => 'nullable|string|max:50',
            'vin' => 'nullable|string|max:50',
            'tipo_vivienda' => 'nullable|string|max:100',
            'detalles_cobertura' => 'nullable|string',
            'numero_poliza' => 'nullable|string|max:50',
            'prima' => 'nullable|string|max:50',
            'tipo_facturacion' => 'nullable|string|max:50',
            'balance' => 'nullable|string|max:50',
            'liquidacion' => 'nullable|string|max:50',
            'fecha_pago' => 'nullable|string|max:50',
            'fecha_vencimiento' => 'nullable|string|max:50',
        ]);

        $client = Client::create([
            'sede_id' => $validated['sede_id'],
            'agente' => $validated['agente'] ?? 'Kerry',
            'nombre' => strtoupper(trim($validated['nombre'])),
            'apellido' => strtoupper(trim($validated['apellido'])),
            'nombre_familiar' => $validated['nombre_familiar'] ? trim($validated['nombre_familiar']) : null,
            'doc_identidad' => $validated['doc_identidad'] ? strtoupper(trim($validated['doc_identidad'])) : null,
            'dob' => $validated['dob'],
            'telefono_movil' => $validated['telefono_movil'],
            'telefono_fijo' => $validated['telefono_fijo'],
            'email_personal' => $validated['email_personal'],
            'email_trabajo' => $validated['email_trabajo'],
            'direccion_local' => $validated['direccion_local'],
            'direccion_base' => $validated['direccion_base'],
            'observaciones' => $validated['observaciones'],
        ]);

        // Si se indicó una póliza inicial
        if (!empty($request->input('crear_poliza')) || !empty($validated['matricula']) || !empty($validated['numero_poliza']) || !empty($validated['tipo_vivienda'])) {
            Policy::create([
                'client_id' => $client->id,
                'sede_id' => $client->sede_id,
                'agente' => $client->agente,
                'numero_poliza' => $validated['numero_poliza'] ?? 'PENDIENTE-' . time(),
                'codigo_aseguradora' => $validated['codigo_aseguradora'] ?? '81',
                'aseguradora' => $validated['aseguradora'] ?? 'Patria Hispana',
                'ramo' => $validated['ramo'] ?? 'Vehiculo',
                'marca' => $validated['marca'] ? strtoupper(trim($validated['marca'])) : null,
                'modelo' => $validated['modelo'] ? strtoupper(trim($validated['modelo'])) : null,
                'matricula' => $validated['matricula'] ? strtoupper(trim($validated['matricula'])) : null,
                'vin' => $validated['vin'] ? strtoupper(trim($validated['vin'])) : null,
                'tipo_vivienda' => $validated['tipo_vivienda'] ?? null,
                'detalles_cobertura' => $validated['detalles_cobertura'] ?? null,
                'prima' => $validated['prima'],
                'tipo_facturacion' => $validated['tipo_facturacion'] ?? 'Annual',
                'balance' => $validated['balance'] ?? 'Paid Full',
                'liquidacion' => $validated['liquidacion'] ?? 'Liquidado',
                'fecha_pago' => $validated['fecha_pago'],
                'fecha_vencimiento' => $validated['fecha_vencimiento'],
                'estado' => 'Activo',
            ]);
        }

        return redirect()->route('clients.show', $client)
            ->with('success', "Cliente {$client->full_name} dado de alta correctamente por {$client->agente}.");
    }

    public function show(Client $client)
    {
        $client->load(['sede', 'policies']);
        $sedes = Sede::all();
        $agentes = $this->agentesDisponibles;
        return view('clients.show', compact('client', 'sedes', 'agentes'));
    }

    public function storePolicy(Request $request, Client $client)
    {
        $validated = $request->validate([
            'ramo' => 'required|string|max:50',
            'agente' => 'nullable|string|max:50',
            'codigo_aseguradora' => 'nullable|string|max:20',
            'aseguradora' => 'nullable|string|max:100',
            'numero_poliza' => 'nullable|string|max:50',
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'matricula' => 'nullable|string|max:50',
            'vin' => 'nullable|string|max:50',
            'tipo_vivienda' => 'nullable|string|max:100',
            'direccion_riesgo' => 'nullable|string|max:255',
            'detalles_cobertura' => 'nullable|string',
            'prima' => 'nullable|string|max:50',
            'tipo_facturacion' => 'nullable|string|max:50',
            'balance' => 'nullable|string|max:50',
            'liquidacion' => 'nullable|string|max:50',
            'fecha_pago' => 'nullable|string|max:50',
            'fecha_vencimiento' => 'nullable|string|max:50',
            'estado' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $validated['client_id'] = $client->id;
        $validated['sede_id'] = $client->sede_id;
        $validated['agente'] = $validated['agente'] ?? $client->agente ?? 'Kerry';
        $validated['codigo_aseguradora'] = $validated['codigo_aseguradora'] ?? '81';
        $validated['aseguradora'] = $validated['aseguradora'] ?? 'Patria Hispana';
        $validated['marca'] = $validated['marca'] ? strtoupper(trim($validated['marca'])) : null;
        $validated['modelo'] = $validated['modelo'] ? strtoupper(trim($validated['modelo'])) : null;
        $validated['matricula'] = $validated['matricula'] ? strtoupper(trim($validated['matricula'])) : null;
        $validated['vin'] = $validated['vin'] ? strtoupper(trim($validated['vin'])) : null;

        Policy::create($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Nueva póliza añadida al cliente.');
    }

    public function destroy(Client $client)
    {
        $fullName = $client->full_name;
        $totalPolicies = $client->policies()->count();

        // Eliminar pólizas vinculadas y cliente
        $client->policies()->delete();
        $client->delete();

        return redirect()->route('home')
            ->with('success', "El cliente {$fullName} (y sus {$totalPolicies} pólizas asociadas) ha sido eliminado permanentemente.");
    }

    /**
     * Vista de Impresión Oficial y Elegante para Cesión de Póliza KFM
     */
    public function cesionPoliza(Client $client, Policy $policy, Request $request)
    {
        $buyer = [
            'nombre' => strtoupper(trim($request->input('buyer_nombre') ?: 'EMILY SARAH')),
            'apellido' => strtoupper(trim($request->input('buyer_apellido') ?: 'JOHNSON')),
            'doc_identidad' => strtoupper(trim($request->input('buyer_doc') ?: 'Z4192048E')),
            'dob' => $request->input('buyer_dob') ?: '19/11/1995',
            'direccion' => $request->input('buyer_direccion') ?: 'LU BASE NAVAL DE ROTA 999, 11520 ROTA',
            'telefono' => $request->input('buyer_telefono') ?: '671-998877',
        ];

        return view('clients.cesion', compact('client', 'policy', 'buyer'));
    }

    /**
     * Simulador Interactivo de Gestoría Bahía & Naval
     */
    public function gestoriaDemo(Request $request)
    {
        $clientId = $request->input('client_id');
        $policyId = $request->input('policy_id');

        $client = $clientId ? Client::with('policies')->find($clientId) : null;
        $policy = $policyId ? Policy::find($policyId) : ($client?->policies?->first());

        if (!$client) {
            $client = Client::where('apellido', 'MILLER')->first() ?? Client::first();
            $policy = $client?->policies?->first();
        }

        $buyer = [
            'nombre' => strtoupper(trim($request->input('buyer_nombre') ?: 'EMILY SARAH')),
            'apellido' => strtoupper(trim($request->input('buyer_apellido') ?: 'JOHNSON')),
            'doc_identidad' => strtoupper(trim($request->input('buyer_doc') ?: 'Z4192048E')),
            'sexo' => $request->input('buyer_sexo') ?: 'Mujer',
            'dob' => $request->input('buyer_dob') ?: '19/11/1995',
            'tipo_via' => 'LU - LUGAR',
            'nombre_via' => $request->input('buyer_direccion') ?: 'BASE NAVAL DE ROTA',
            'numero' => '999',
            'provincia' => 'Cádiz (CA)',
            'municipio' => 'ROTA',
            'cp' => '11520',
            'precio' => $request->input('buyer_precio') ?: '1.000,00 €',
            'telefono' => $request->input('buyer_telefono') ?: '671-998877',
        ];

        return view('gestoria.demo', compact('client', 'policy', 'buyer'));
    }

    /**
     * Asegura de manera autónoma que el caso demo David Alexander Miller exista siempre
     */
    private function ensureDemoMillerExists(): void
    {
        try {
            if (!Client::where('apellido', 'MILLER')->exists()) {
                $nex = Sede::where('slug', 'nex')->first() ?? Sede::first();
                $sedeId = $nex ? $nex->id : null;

                $client = Client::create([
                    'doc_identidad' => '549218471',
                    'nombre' => 'DAVID ALEXANDER',
                    'apellido' => 'MILLER',
                    'nombre_familiar' => 'Emily Sarah Johnson (Cesionaria)',
                    'agente' => 'Chari',
                    'dob' => '14/06/1988',
                    'telefono_movil' => '671-234567',
                    'telefono_fijo' => '',
                    'email_trabajo' => 'david.miller@eu.navy.mil',
                    'email_personal' => 'david.miller.usn@gmail.com',
                    'direccion_local' => 'C/ SAN JUAN DE PUERTO RICO 12 – 11520 ROTA',
                    'direccion_base' => 'LG PSC 819 BOX 4120 - 11530 ROTA NAVAL',
                    'sede_id' => $sedeId,
                    'observaciones' => 'Caso demostración: Transferencia POV / Cesión de póliza en Gestoría Bahía & Naval (Oficina NEX - Chari).',
                ]);

                Policy::create([
                    'client_id' => $client->id,
                    'sede_id' => $sedeId,
                    'agente' => 'Chari',
                    'numero_poliza' => '1842910',
                    'codigo_aseguradora' => '04',
                    'aseguradora' => 'Patria Hispana',
                    'ramo' => 'Vehiculo',
                    'marca' => 'FORD',
                    'modelo' => 'FOCUS TITANIUM 1.5 ECOBOOST 5P',
                    'matricula' => '5931LKP',
                    'vin' => '1FADP5CU3DL298491',
                    'prima' => '642,50 €',
                    'tipo_facturacion' => 'Annual',
                    'balance' => 'Paid Full',
                    'liquidacion' => 'Liquidado',
                    'fecha_pago' => '15/11/2025',
                    'fecha_vencimiento' => '15/11/2026',
                    'estado' => 'Activo',
                    'observaciones' => 'Vehículo transferido a Emily Sarah Johnson.',
                ]);
            }
        } catch (\Throwable $e) {
            // Silencioso
        }
    }

    /**
     * Decodificador Oficial de Bastidores (NHTSA VPIC API)
     */
    public function decodeVin(Request $request)
    {
        $vin = strtoupper(trim($request->query('vin', '')));
        if (strlen($vin) < 5) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor introduce un número de bastidor (VIN) válido (mínimo 5 caracteres).'
            ], 422);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(6)
                ->get("https://vpic.nhtsa.dot.gov/api/vehicles/decodevinvalues/{$vin}?format=json");

            if ($response->successful()) {
                $data = $response->json();
                $result = $data['Results'][0] ?? null;

                if ($result && !empty($result['Make'])) {
                    $fuel = $result['FuelTypePrimary'] ?? '';
                    if (!empty($result['FuelTypeSecondary'])) {
                        $fuel .= ' / ' . $result['FuelTypeSecondary'];
                    }

                    return response()->json([
                        'success' => true,
                        'vin' => $vin,
                        'make' => $result['Make'] ?: 'DESCONOCIDO',
                        'model' => $result['Model'] ?: '',
                        'year' => $result['ModelYear'] ?: '',
                        'trim' => $result['Trim'] ?: ($result['Series'] ?: ''),
                        'body' => $result['BodyClass'] ?: 'Turismo',
                        'doors' => $result['Doors'] ?: '',
                        'engine' => ($result['DisplacementL'] ? $result['DisplacementL'] . 'L' : '') . ($result['EngineCylinders'] ? ' (' . $result['EngineCylinders'] . ' cil)' : ''),
                        'hp' => $result['EngineHP'] ? $result['EngineHP'] . ' HP' : '',
                        'fuel' => $fuel ?: 'Gasolina',
                        'origin' => trim(($result['PlantCity'] ?? '') . ', ' . ($result['PlantCountry'] ?? '')),
                        'vehicle_type' => $result['VehicleType'] ?: 'Turismo',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Error en conexión externa
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontraron especificaciones automáticas para este bastidor o el servidor no respondió.'
        ], 404);
    }

    /**
     * Calculadora Fiscal Oficial ITP Junta de Andalucía & Tasas DGT (Modelo 620 / BOE)
     */
    public function calculateItp(Request $request)
    {
        $year = (int) $request->input('year', date('Y') - 5);
        $currentYear = (int) date('Y');
        $age = max(0, $currentYear - $year);

        // Tabla oficial de depreciación de Hacienda (Orden HFP vigente)
        $depreciationTable = [
            0 => 1.00,
            1 => 0.84,
            2 => 0.67,
            3 => 0.56,
            4 => 0.47,
            5 => 0.39,
            6 => 0.34,
            7 => 0.28,
            8 => 0.24,
            9 => 0.19,
            10 => 0.17,
            11 => 0.13,
        ];
        $pct = $age >= 12 ? 0.10 : ($depreciationTable[$age] ?? 0.10);

        // Valor de compraventa o base estimada de vehículo nuevo
        $baseValue = (float) $request->input('base_value', 18000);
        if ($baseValue <= 0) $baseValue = 18000;

        $valorFiscal = round($baseValue * $pct, 2);
        $itpRate = 0.04; // 4% Andalucía general turismos
        $cuotaItp = round($valorFiscal * $itpRate, 2);
        $tasaDgt = 55.70; // Tasa DGT 4.1 cambio de titularidad
        $honorariosGestoria = 65.00; // Honorarios orientativos gestoría
        $total = round($cuotaItp + $tasaDgt + $honorariosGestoria, 2);

        return response()->json([
            'success' => true,
            'year' => $year,
            'age' => $age,
            'depreciation_pct' => ($pct * 100),
            'base_value' => $baseValue,
            'valor_fiscal' => $valorFiscal,
            'itp_rate' => ($itpRate * 100),
            'cuota_itp' => $cuotaItp,
            'tasa_dgt' => $tasaDgt,
            'honorarios_gestoria' => $honorariosGestoria,
            'total_tramite' => $total,
        ]);
    }

    /**
     * Traductor y Asistente Militar Rota EN ⇄ ES
     */
    public function translateText(Request $request)
    {
        $text = trim($request->input('text', ''));
        $langPair = $request->input('pair', 'en|es');

        if (empty($text)) {
            return response()->json(['success' => false, 'message' => 'Por favor introduce texto a traducir.'], 422);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(6)
                ->get('https://api.mymemory.translated.net/get', [
                    'q' => $text,
                    'langpair' => $langPair,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $translated = $data['responseData']['translatedText'] ?? null;
                if ($translated) {
                    return response()->json([
                        'success' => true,
                        'original' => $text,
                        'translated' => html_entity_decode($translated),
                        'pair' => $langPair,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Silencioso
        }

        return response()->json([
            'success' => false,
            'message' => 'No se pudo conectar con el servicio de traducción rápida.'
        ], 500);
    }
}


