<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Policy;
use App\Models\Sede;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected array $agentesDisponibles = ['Kerry', 'Coral', 'Conchi'];

    public function index(Request $request)
    {
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
}

