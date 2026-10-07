@extends('layouts.app')

@section('title', 'Buscador de Clientes — KFM CRM')

@section('content')
<div class="space-y-6">

    <!-- KPI / Top Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-lg bg-kfm-light text-kfm-primary flex items-center justify-center font-bold text-xl">
                👥
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Clientes Cargados</p>
                <div class="flex items-baseline space-x-2">
                    <span class="text-2xl font-bold font-display text-slate-900">{{ $totalClients }}</span>
                    <span class="text-xs text-emerald-600 font-medium">Muestra 20</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-lg bg-sky-50 text-kfm-cerulean flex items-center justify-center font-bold text-xl">
                🛡️
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pólizas Registradas</p>
                <div class="flex items-baseline space-x-2">
                    <span class="text-2xl font-bold font-display text-slate-900">{{ $totalPolicies }}</span>
                    <span class="text-xs text-slate-500 font-medium">Auto, Hogar, RC</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                ✅
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pólizas Activas</p>
                <div class="flex items-baseline space-x-2">
                    <span class="text-2xl font-bold font-display text-slate-900">{{ $activePolicies }}</span>
                    <span class="text-xs text-emerald-600 font-medium">Vigentes</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl">
                🏢
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Oficinas</p>
                <div class="flex items-baseline space-x-2">
                    <span class="text-2xl font-bold font-display text-slate-900">2</span>
                    <span class="text-xs text-slate-500 font-medium">Central & NEX</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Buscador Principal -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-xl font-bold font-display text-slate-900 flex items-center space-x-2">
                    <span>Buscador Rápido de Clientes y Vehículos</span>
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Busca al instante por Nombre, Apellido, Familiar, DNI/SSN, Matrícula, Bastidor (VIN), Póliza, Agente o Teléfono.
                </p>
            </div>

            <a href="{{ route('clients.create') }}" 
               class="inline-flex items-center justify-center px-4 py-2.5 bg-kfm-primary hover:bg-kfm-cerulean text-white text-sm font-semibold rounded-xl shadow-sm transition-all flex-shrink-0">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Introducir Nuevo Cliente
            </a>
        </div>

        <!-- Formulario de Búsqueda -->
        <form method="GET" action="{{ route('home') }}" class="space-y-3">
            @if(request('sede_id'))
                <input type="hidden" name="sede_id" value="{{ request('sede_id') }}">
            @endif
            @if(request('agente'))
                <input type="hidden" name="agente" value="{{ request('agente') }}">
            @endif

            <div class="relative flex items-center">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5 text-kfm-cerulean" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Escribe una matrícula (ej: 0680 JCL), apellido (ej: Bolton), familiar (ej: Esposa), o DNI..." 
                       class="w-full pl-11 pr-28 py-3.5 bg-slate-50/80 border border-slate-300 rounded-xl text-base text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary focus:border-kfm-primary transition-all shadow-inner">
                
                <div class="absolute inset-y-0 right-0 pr-2 flex items-center space-x-2">
                    @if(request('q') || request('agente') || request('sede_id'))
                        <a href="{{ route('home') }}" 
                           class="p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition" 
                           title="Limpiar búsqueda y filtros">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                    <button type="submit" 
                            class="px-4 py-2 bg-kfm-primary hover:bg-kfm-cerulean text-white font-semibold text-sm rounded-lg shadow transition">
                        Buscar
                    </button>
                </div>
            </div>

            <!-- Sugerencias rápidas de búsqueda -->
            <div class="flex items-center space-x-2 text-xs text-slate-500 overflow-x-auto pt-1">
                <span class="font-medium text-slate-600">Pruebas rápidas:</span>
                <a href="{{ route('home', ['q' => 'BOLTON']) }}" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md">Bolton (3 pólizas)</a>
                <a href="{{ route('home', ['q' => '0680 JCL']) }}" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md">Matrícula 0680 JCL</a>
                <a href="{{ route('home', ['q' => 'Kerry']) }}" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md">Tramitadas por Kerry</a>
                <a href="{{ route('home', ['q' => 'Esposa']) }}" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md">Con familiar / esposa</a>
            </div>
        </form>
    </div>

    <!-- Listado de Resultados -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/80 flex items-center justify-between bg-slate-50/50">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-700">
                Clientes Encontrados ({{ $clients->total() }})
            </h2>
            @if(request('q') || request('sede_id') || request('agente'))
                <a href="{{ route('home') }}" class="text-xs text-kfm-primary hover:underline font-semibold">
                    ← Restablecer todos los filtros
                </a>
            @endif
        </div>

        @if($clients->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">Cliente / Documento</th>
                            <th scope="col" class="px-6 py-3.5">Oficina & Agente</th>
                            <th scope="col" class="px-6 py-3.5">Contacto</th>
                            <th scope="col" class="px-6 py-3.5">Contrataciones / Pólizas</th>
                            <th scope="col" class="px-6 py-3.5 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($clients as $client)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <!-- Columna Cliente -->
                                <td class="px-6 py-4 align-top">
                                    <div class="font-bold text-slate-900 group-hover:text-kfm-primary transition-colors text-base font-display">
                                        <a href="{{ route('clients.show', $client) }}">
                                            {{ $client->apellido }}, {{ $client->nombre }}
                                        </a>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $client->doc_identidad ?: 'SIN DOC' }}
                                        </span>
                                        @if($client->dob)
                                            <span class="text-xs text-slate-400">DOB: {{ $client->dob }}</span>
                                        @endif
                                    </div>

                                    @if($client->nombre_familiar)
                                        <div class="text-xs text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200 inline-flex items-center space-x-1 mt-1.5 font-medium">
                                            <span>👥 Familiar:</span>
                                            <strong>{{ $client->nombre_familiar }}</strong>
                                        </div>
                                    @endif

                                    @if($client->direccion_base)
                                        <div class="text-xs text-slate-500 mt-1 flex items-center space-x-1">
                                            <span class="text-sky-600 font-medium">⚓</span>
                                            <span class="truncate max-w-xs">{{ $client->direccion_base }}</span>
                                        </div>
                                    @elseif($client->direccion_local)
                                        <div class="text-xs text-slate-500 mt-1 flex items-center space-x-1">
                                            <span class="text-amber-600 font-medium">📍</span>
                                            <span class="truncate max-w-xs">{{ $client->direccion_local }}</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Columna Oficina & Agente -->
                                <td class="px-6 py-4 align-top space-y-1.5">
                                    @if($client->sede)
                                        @if($client->sede->slug === 'nex')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-100 text-sky-800 border border-sky-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500 mr-1.5"></span>
                                                Oficina NEX
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                                Central (Pueblo)
                                            </span>
                                        @endif
                                    @endif

                                    @if($client->agente)
                                        <div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                👤 Agente: {{ $client->agente }}
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Columna Contacto -->
                                <td class="px-6 py-4 align-top text-xs space-y-1">
                                    @if($client->telefono_movil)
                                        <div class="font-medium text-slate-800 flex items-center space-x-1.5">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            <span>{{ $client->telefono_movil }}</span>
                                        </div>
                                    @endif
                                    @if($client->email_trabajo)
                                        <div class="text-sky-700 font-mono text-[11px] truncate max-w-[180px]" title="{{ $client->email_trabajo }}">
                                            {{ $client->email_trabajo }}
                                        </div>
                                    @endif
                                    @if($client->email_personal)
                                        <div class="text-slate-500 text-[11px] truncate max-w-[180px]" title="{{ $client->email_personal }}">
                                            {{ $client->email_personal }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Columna Pólizas -->
                                <td class="px-6 py-4 align-top">
                                    <div class="space-y-2">
                                        @forelse($client->policies as $policy)
                                            <div class="bg-slate-50 border border-slate-200/90 rounded-lg p-2.5 text-xs flex items-center justify-between space-x-2">
                                                <div class="flex items-center space-x-2">
                                                    @if(strtolower($policy->ramo) === 'vehiculo' || strtolower($policy->ramo) === 'auto')
                                                        <span class="text-base">🚗</span>
                                                    @elseif(strtolower($policy->ramo) === 'hogar')
                                                        <span class="text-base">🏠</span>
                                                    @elseif(strtolower($policy->ramo) === 'rc')
                                                        <span class="text-base">⚖️</span>
                                                    @else
                                                        <span class="text-base">📄</span>
                                                    @endif
                                                    <div>
                                                        <div class="font-bold text-slate-800">
                                                            @if($policy->matricula)
                                                                <span class="font-mono bg-white px-1.5 py-0.5 rounded border border-slate-300 text-slate-900 mr-1">{{ $policy->matricula }}</span>
                                                            @endif
                                                            <span>{{ $policy->marca }} {{ $policy->modelo }}</span>
                                                            @if($policy->tipo_vivienda)
                                                                <span class="text-slate-600 font-normal">{{ $policy->tipo_vivienda }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-[11px] text-slate-500 flex flex-wrap items-center gap-1.5 mt-0.5">
                                                            <span>Póliza: <strong class="font-mono">{{ $policy->numero_poliza }}</strong></span>
                                                            <span>•</span>
                                                            <span class="text-slate-600 font-semibold">Cía: {{ $policy->codigo_aseguradora }} ({{ $policy->aseguradora }})</span>
                                                            @if($policy->prima)
                                                                <span>•</span>
                                                                <span class="text-emerald-700 font-bold">{{ $policy->prima }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="text-right flex flex-col items-end space-y-1">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $policy->estado === 'Activo' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                        {{ $policy->estado }}
                                                    </span>
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold uppercase {{ $policy->balance === 'Paid Full' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' }}">
                                                        {{ $policy->balance }}
                                                    </span>
                                                </div>
                                            </div>
                                        @empty
                                            <span class="text-xs text-slate-400 italic">Sin pólizas registradas</span>
                                        @endforelse
                                    </div>
                                </td>

                                <!-- Columna Acción -->
                                <td class="px-6 py-4 align-top text-right">
                                    <a href="{{ route('clients.show', $client) }}" 
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-kfm-primary bg-white hover:bg-kfm-light hover:border-kfm-cerulean transition-colors shadow-sm">
                                        Ver Ficha →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($clients->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $clients->links() }}
                </div>
            @endif
        @else
            <!-- Sin resultados -->
            <div class="py-16 text-center space-y-3">
                <div class="w-16 h-16 mx-auto bg-slate-100 rounded-full flex items-center justify-center text-2xl text-slate-400">
                    🔍
                </div>
                <h3 class="text-base font-bold text-slate-800">No se encontraron clientes</h3>
                <p class="text-sm text-slate-500 max-w-sm mx-auto">
                    No hay ningún cliente o vehículo que coincida con tus criterios de búsqueda.
                </p>
                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 transition">
                        Ver todos los 20 clientes
                    </a>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
