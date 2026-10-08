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
            'nombre' => $request->query('buyer_nombre', 'EMILY SARAH'),
            'apellido' => $request->query('buyer_apellido', 'JOHNSON'),
            'doc_identidad' => $request->query('buyer_doc', 'Z4192048E'),
            'dob' => $request->query('buyer_dob', '19/11/1995'),
            'direccion' => $request->query('buyer_direccion', 'LU BASE NAVAL DE ROTA 999, 11520 ROTA'),
        ];

        return view('clients.cesion', compact('client', 'policy', 'buyer'));
    }

    /**
     * Simulador / Clon Interactivo de la Gestoría Sánchez Nieva
     */
    public function gestoriaDemo(Request $request)
    {
        $clientId = $request->query('client_id');
        $policyId = $request->query('policy_id');

        $client = $clientId ? Client::with('policies')->find($clientId) : null;
        $policy = $policyId ? Policy::find($policyId) : ($client?->policies?->first());

        if (!$client) {
            $client = Client::where('doc_identidad', 'Y8492015B')->first() ?? Client::first();
            $policy = $client?->policies?->first();
        }

        $buyer = [
            'nombre' => 'EMILY SARAH',
            'apellido' => 'JOHNSON',
            'doc_identidad' => 'Z4192048E',
            'sexo' => 'Mujer',
            'dob' => '19/11/1995',
            'tipo_via' => 'LU - LUGAR',
            'nombre_via' => 'BASE NAVAL DE ROTA',
            'numero' => '999',
            'provincia' => 'Cádiz (CA)',
            'municipio' => 'ROTA',
            'cp' => '11520',
            'precio' => '1.000,00 €',
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
                    'observaciones' => 'Caso demostración: Transferencia POV / Cesión de póliza en Gestoría Sánchez Nieva (Oficina NEX - Chari).',
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
            // Ignorar para evitar bloqueo si las tablas aún no existen
        }
    }
}


