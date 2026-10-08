@extends('layouts.app')

@section('title', $client->full_name . ' — KFM CRM')

@section('content')
<div class="space-y-6">

    <!-- Top Navigation / Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-xs text-kfm-primary hover:underline font-semibold flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Volver al Buscador de Clientes</span>
        </a>

        <div class="flex items-center space-x-3">
            <span class="text-xs text-slate-400">ID Cliente: #{{ $client->id }}</span>
            <button type="button" 
                    onclick="openDeleteClientModal()"
                    class="px-2.5 py-1 text-xs font-semibold text-rose-600 hover:text-white bg-rose-50 hover:bg-rose-600 border border-rose-200 hover:border-rose-600 rounded-lg transition-all shadow-sm flex items-center gap-1.5 cursor-pointer"
                    title="Eliminar este cliente">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Borrar Cliente</span>
            </button>
        </div>
    </div>

    <!-- Client Header Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-start space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-kfm-navy to-kfm-cerulean text-white font-display font-bold text-2xl flex items-center justify-center shadow-md flex-shrink-0">
                {{ substr($client->nombre, 0, 1) }}{{ substr($client->apellido, 0, 1) }}
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-2xl font-bold font-display text-slate-900">{{ $client->apellido }}, {{ $client->nombre }}</h1>
                    @if($client->sede)
                        @if($client->sede->slug === 'nex')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-800 border border-sky-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500 mr-1.5"></span>
                                Oficina NEX (Base Naval)
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                Central (Pueblo)
                            </span>
                        @endif
                    @endif
                    @if($client->agente)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                            👤 Agente: {{ $client->agente }}
                        </span>
                    @endif
                </div>

                @if($client->nombre_familiar)
                    <div class="text-xs text-purple-800 bg-purple-50 px-2.5 py-1 rounded-lg border border-purple-200 inline-flex items-center space-x-1.5 mt-2 font-medium">
                        <span>👥 Familiar / Cónyuge registrado:</span>
                        <strong class="font-bold">{{ $client->nombre_familiar }}</strong>
                    </div>
                @endif

                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 mt-2">
                    <span class="font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-700 font-semibold border border-slate-200">
                        DOC: {{ $client->doc_identidad ?: 'NO ESPECIFICADO' }}
                    </span>
                    @if($client->dob)
                        <span>Fecha Nacimiento: <strong class="text-slate-700">{{ $client->dob }}</strong></span>
                    @endif
                    <span>Alta en CRM: <strong class="text-slate-700">{{ $client->created_at->format('d/m/Y') }}</strong></span>
                </div>
            </div>
        </div>

        <!-- Quick Contact Actions -->
        <div class="flex flex-wrap items-center gap-2.5">
            @if($client->telefono_movil)
                <a href="tel:{{ $client->telefono_movil }}" 
                   class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold transition flex items-center space-x-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span>Llamar {{ $client->telefono_movil }}</span>
                </a>
            @endif
            @if($client->email_personal)
                <a href="mailto:{{ $client->email_personal }}" 
                   class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold transition flex items-center space-x-1.5">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Email Personal</span>
                </a>
            @endif
            @if($client->email_trabajo)
                <a href="mailto:{{ $client->email_trabajo }}" 
                   class="px-3.5 py-2 rounded-xl bg-sky-50 hover:bg-sky-100 text-sky-900 border border-sky-200 text-xs font-semibold transition flex items-center space-x-1.5" title="{{ $client->email_trabajo }}">
                    <span class="text-xs">⚓</span>
                    <span class="truncate max-w-[140px]">{{ $client->email_trabajo }}</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Columna Izquierda: Datos del Cliente & Domicilios -->
        <div class="space-y-6">

            <!-- Ficha de Contacto -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-3 flex items-center space-x-2">
                    <span>📋 Contacto & Teléfonos</span>
                </h2>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="block text-slate-400 font-medium">Número 1 (Móvil):</span>
                        <span class="text-sm font-bold text-slate-800">{{ $client->telefono_movil ?: '—' }}</span>
                    </div>

                    @if($client->telefono_fijo)
                        <div>
                            <span class="block text-slate-400 font-medium">Número 2 (Fijo / Base):</span>
                            <span class="text-sm font-semibold text-slate-800">{{ $client->telefono_fijo }}</span>
                        </div>
                    @endif

                    <div>
                        <span class="block text-slate-400 font-medium">Email Personal:</span>
                        <span class="text-sm font-mono text-slate-800">{{ $client->email_personal ?: '—' }}</span>
                    </div>

                    @if($client->email_trabajo)
                        <div>
                            <span class="block text-slate-400 font-medium">Email Trabajo (Militar):</span>
                            <span class="text-sm font-mono text-sky-700 font-semibold">{{ $client->email_trabajo }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Direcciones -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-3 flex items-center space-x-2">
                    <span>📍 Direcciones</span>
                </h2>

                <div class="space-y-3 text-xs">
                    @if($client->direccion_base)
                        <div class="p-3 bg-sky-50 rounded-xl border border-sky-100">
                            <span class="block text-sky-800 font-bold uppercase text-[10px] tracking-wider mb-0.5">Dirección Base / NEX:</span>
                            <p class="text-sm font-semibold text-slate-800">{{ $client->direccion_base }}</p>
                        </div>
                    @endif

                    @if($client->direccion_local)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <span class="block text-slate-500 font-bold uppercase text-[10px] tracking-wider mb-0.5">Dirección Local (España):</span>
                            <p class="text-sm font-semibold text-slate-800">{{ $client->direccion_local }}</p>
                        </div>
                    @endif

                    @if(!$client->direccion_base && !$client->direccion_local)
                        <p class="text-slate-400 italic">No hay direcciones registradas.</p>
                    @endif
                </div>
            </div>

            <!-- Observaciones -->
            @if($client->observaciones)
                <div class="bg-amber-50/60 rounded-2xl border border-amber-200/70 p-5 text-xs text-amber-900 space-y-1">
                    <span class="font-bold uppercase tracking-wider text-[10px] text-amber-800 block">Observaciones:</span>
                    <p class="text-slate-700 leading-relaxed">{{ $client->observaciones }}</p>
                </div>
            @endif

            <!-- Zona de Gestión / Baja del Cliente -->
            <div class="bg-rose-50/60 rounded-2xl border border-rose-200/80 p-5 space-y-3">
                <span class="font-bold uppercase tracking-wider text-[11px] text-rose-800 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Gestión de Ficha</span>
                </span>
                <p class="text-[11px] text-slate-600 leading-normal">
                    Si el cliente se dio de alta por error o ha solicitado la baja total de su expediente en la correduría:
                </p>
                <button type="button" 
                        onclick="openDeleteClientModal()"
                        class="w-full py-2.5 px-3 bg-white hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-300 hover:border-rose-600 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Eliminar Ficha de Cliente</span>
                </button>
            </div>

        </div>

        <!-- Columna Derecha: Contrataciones / Pólizas -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Lista de Pólizas -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200/80 bg-slate-50/50 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">
                            Contrataciones Registradas ({{ $client->policies->count() }})
                        </h2>
                        <p class="text-xs text-slate-500">Histórico completo con estados de cobro y liquidación.</p>
                    </div>

                    <a href="#anadir-poliza" class="px-3 py-1.5 bg-kfm-primary hover:bg-kfm-cerulean text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        + Nueva Contratación
                    </a>
                </div>

                <div class="p-6 space-y-4">
                    @forelse($client->policies as $policy)
                        <div class="p-5 rounded-xl border border-slate-200 bg-white hover:border-kfm-primary/50 transition-all shadow-sm space-y-3">
                            <!-- Cabecera de la póliza -->
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-xl flex-shrink-0">
                                        @if(strtolower($policy->ramo) === 'vehiculo') 🚗
                                        @elseif(strtolower($policy->ramo) === 'hogar') 🏠
                                        @elseif(strtolower($policy->ramo) === 'rc') ⚖️
                                        @elseif(strtolower($policy->ramo) === 'empresa') 🏢
                                        @else 🛡️ @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-base font-bold text-slate-900 font-display">
                                                {{ $policy->marca }} {{ $policy->modelo }}
                                                @if($policy->tipo_vivienda)
                                                    {{ $policy->tipo_vivienda }}
                                                @endif
                                                @if($policy->detalles_cobertura)
                                                    <span class="text-xs font-normal text-slate-600">({{ $policy->detalles_cobertura }})</span>
                                                @endif
                                            </span>
                                            @if($policy->matricula)
                                                <span class="px-2 py-0.5 font-mono text-xs font-bold bg-slate-100 text-slate-800 rounded border border-slate-300">
                                                    {{ $policy->matricula }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-500 mt-0.5">
                                            Contratación: <span class="font-semibold uppercase text-slate-700">{{ $policy->ramo }}</span> • Cía: <strong class="text-kfm-primary">{{ $policy->codigo_aseguradora }} ({{ $policy->aseguradora }})</strong>
                                            @if($policy->agente)
                                                • Tramitado por: <strong>{{ $policy->agente }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end space-y-1">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $policy->estado === 'Activo' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-100 text-rose-800 border border-rose-200' }}">
                                        {{ $policy->estado }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase {{ $policy->balance === 'Paid Full' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' }}">
                                        Balance: {{ $policy->balance }}
                                    </span>
                                </div>
                            </div>

                            <!-- Facturación -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t border-slate-100 text-xs">
                                <div>
                                    <span class="block text-slate-400 font-medium">Nº Póliza:</span>
                                    <span class="font-mono font-bold text-slate-800">{{ $policy->numero_poliza ?: '—' }}</span>
                                </div>
                                <div>
                                    <span class="block text-slate-400 font-medium">Prima (€):</span>
                                    <span class="font-bold text-emerald-700 text-sm">{{ $policy->prima ?: '—' }}</span>
                                </div>
                                <div>
                                    <span class="block text-slate-400 font-medium">Tipo:</span>
                                    <span class="font-semibold text-slate-800">{{ $policy->tipo_facturacion ?: 'Annual' }}</span>
                                </div>
                                <div>
                                    <span class="block text-slate-400 font-medium">Vencimiento:</span>
                                    <span class="font-semibold text-slate-800">{{ $policy->fecha_vencimiento ?: '—' }}</span>
                                </div>
                            </div>

                            <!-- Liquidación & VIN -->
                            <div class="flex flex-wrap items-center justify-between gap-2 pt-1 text-[11px] text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                                <div>
                                    Liquidación Cía: 
                                    <span class="font-bold {{ $policy->liquidacion === 'Liquidado' ? 'text-emerald-700' : 'text-amber-700' }}">
                                        {{ $policy->liquidacion ?: 'Liquidado' }}
                                    </span>
                                    @if($policy->fecha_pago)
                                        (Pagado: {{ $policy->fecha_pago }})
                                    @endif
                                </div>
                                @if($policy->vin)
                                    <div class="font-mono">
                                        VIN (Bastidor): <strong>{{ $policy->vin }}</strong>
                                    </div>
                                @endif
                            </div>

                            @if($policy->observaciones)
                                <div class="text-xs text-slate-600 bg-amber-50/50 p-2.5 rounded-lg border border-amber-100">
                                    <strong class="text-amber-800">Nota:</strong> {{ $policy->observaciones }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs">
                            Este cliente no tiene pólizas registradas actualmente.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Formulario Rápido para Añadir Póliza a este Cliente con Secciones Dinámicas -->
            <div id="anadir-poliza" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center space-x-2">
                        <span>➕ Nueva Contratación para {{ $client->nombre }}</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Selecciona el ramo para desplegar los detalles específicos del bien o riesgo.</p>
                </div>

                <form method="POST" action="{{ route('clients.policies.store', $client) }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Contratación (Ramo)</label>
                            <select name="ramo" id="showRamoSelector" onchange="cambiarShowRamo(this.value)" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-kfm-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                                <option value="Vehiculo" selected>🚗 Vehículo (Auto/Moto)</option>
                                <option value="Hogar">🏠 Hogar / Inquilino</option>
                                <option value="RC">⚖️ Responsabilidad Civil (RC)</option>
                                <option value="Empresa">🏢 Empresa / Comercio</option>
                                <option value="Otros">🛡️ Otros Seguros</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Aseguradora (Código)</label>
                            <select name="codigo_aseguradora" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                                <option value="81">81 — Patria Hispana</option>
                                <option value="87">87 — Entidad 87 (Liberty)</option>
                                <option value="Allianz">Allianz Seguros</option>
                                <option value="Reale">Reale Seguros</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Agente Tramitador/a</label>
                            <select name="agente" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                                @foreach($agentes as $ag)
                                    <option value="{{ $ag }}" {{ $client->agente == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Pestañas Visuales de Selección Rápida -->
                    <div class="flex items-center space-x-2 border-b border-slate-200 pb-2 text-xs overflow-x-auto">
                        <button type="button" onclick="setShowRamo('Vehiculo')" id="show-tab-Vehiculo" class="px-3 py-1.5 rounded-lg font-bold transition flex items-center space-x-1.5 bg-kfm-primary text-white shadow-sm">
                            <span>🚗 Vehículo</span>
                        </button>
                        <button type="button" onclick="setShowRamo('Hogar')" id="show-tab-Hogar" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200">
                            <span>🏠 Hogar</span>
                        </button>
                        <button type="button" onclick="setShowRamo('RC')" id="show-tab-RC" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200">
                            <span>⚖️ RC</span>
                        </button>
                        <button type="button" onclick="setShowRamo('Empresa')" id="show-tab-Empresa" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200">
                            <span>🏢 Empresa</span>
                        </button>
                        <button type="button" onclick="setShowRamo('Otros')" id="show-tab-Otros" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200">
                            <span>🛡️ Otros</span>
                        </button>
                    </div>

                    <!-- ============================================== -->
                    <!-- DETALLES DEL BIEN ASEGURADO DINÁMICO (SHOW) -->
                    <!-- ============================================== -->
                    <div class="bg-slate-50 rounded-xl border border-slate-200 p-4 space-y-3">
                        <span id="showRamoHeaderTitle" class="text-xs font-bold uppercase tracking-wider text-kfm-navy block">
                            🚗 Detalles del Vehículo Asegurado
                        </span>

                        <!-- 1. VEHÍCULO -->
                        <div id="show-bloque-Vehiculo" class="space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nº Póliza</label>
                                    <input type="text" name="numero_poliza" placeholder="Ej: 1689234" 
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Marca</label>
                                    <input type="text" name="marca" placeholder="Ej: TOYOTA" 
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Modelo</label>
                                    <input type="text" name="modelo" placeholder="Ej: RAV4" 
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Matrícula</label>
                                    <input type="text" name="matricula" placeholder="Ej: 4159 JBC" 
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono uppercase">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">VIN (Bastidor)</label>
                                    <input type="text" name="vin" placeholder="17 caracteres" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono uppercase">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Categoría</label>
                                    <select class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                        <option>POV Militar (NAVSTA Base Rota)</option>
                                        <option>Turismo Particular (Residente)</option>
                                        <option>Motocicleta / Scooter</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- 2. HOGAR -->
                        <div id="show-bloque-Hogar" class="space-y-3 hidden">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Dirección de la Vivienda</label>
                                    <input type="text" name="direccion_riesgo" placeholder="Ej: C/ Corbeta 13, Rota" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tipo de Vivienda</label>
                                    <select name="tipo_vivienda" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                        <option value="UNIF ADOSADA">Unifamiliar Adosada</option>
                                        <option value="PISO MEDIO">Piso Intermedio</option>
                                        <option value="UNIF NO ADOSADA">Chalet Aislado</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- 3. RC -->
                        <div id="show-bloque-RC" class="space-y-3 hidden">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Modalidad RC</label>
                                    <select class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                        <option>RC Animales de Compañía / Perros (PPP)</option>
                                        <option>RC Familiar y Privada</option>
                                        <option>RC Profesional / Cazador</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Detalle del Riesgo (Mascota / Microchip)</label>
                                    <input type="text" name="detalles_cobertura" placeholder="Ej: Perro Pastor Alemán • Chip 94100..." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                </div>
                            </div>
                        </div>

                        <!-- 4. EMPRESA -->
                        <div id="show-bloque-Empresa" class="space-y-3 hidden">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nombre Comercial</label>
                                    <input type="text" placeholder="Ej: Rota Naval Services" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">CIF / NIF</label>
                                    <input type="text" placeholder="Ej: B-11223344" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono uppercase">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Actividad</label>
                                    <input type="text" placeholder="Ej: Comercio / Hostelería" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                </div>
                            </div>
                        </div>

                        <!-- 5. OTROS -->
                        <div id="show-bloque-Otros" class="space-y-3 hidden">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tipo de Seguro</label>
                                    <select class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                        <option>Salud Privada Bilingüe</option>
                                        <option>Embarcación / Moto de Agua</option>
                                        <option>Vida y Protección Familiar</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Descripción / Cláusulas</label>
                                    <input type="text" placeholder="Ej: Cobertura médica sin copago" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Facturación y Estados -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Prima (€)</label>
                            <input type="text" name="prima" placeholder="Ej: 587,16 €" 
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-emerald-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tipo Facturación</label>
                            <select name="tipo_facturacion" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                                <option value="Annual">Annual</option>
                                <option value="Semi-annual">Semi-annual</option>
                                <option value="Suplemento">Suplemento</option>
                                <option value="Prima aplicada">Prima aplicada</option>
                                <option value="Cedido">Cedido</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Balance</label>
                            <select name="balance" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                                <option value="Paid Full">Paid Full</option>
                                <option value="Partial">Partial</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Estado</label>
                            <select name="estado" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                                <option value="Activo">Activo</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Fecha Vencimiento</label>
                            <input type="text" name="fecha_vencimiento" placeholder="DD/MM/AAAA" 
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Liquidación Cía</label>
                            <select name="liquidacion" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                                <option value="Liquidado">Liquidado</option>
                                <option value="Pendiente">Pendiente</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-5 py-2.5 bg-kfm-primary hover:bg-kfm-cerulean text-white font-semibold text-xs rounded-xl shadow transition">
                            Guardar Contratación en la Ficha
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>

<!-- JavaScript para alternar entre ramos en show -->
<script>
    const showRamos = ['Vehiculo', 'Hogar', 'RC', 'Empresa', 'Otros'];
    const showTitulos = {
        'Vehiculo': '🚗 Detalles del Vehículo Asegurado',
        'Hogar': '🏠 Detalles del Inmueble Asegurado (Hogar)',
        'RC': '⚖️ Detalles de Responsabilidad Civil',
        'Empresa': '🏢 Detalles de la Empresa o Comercio',
        'Otros': '🛡️ Detalles de Cobertura Especial'
    };

    function setShowRamo(ramo) {
        document.getElementById('showRamoSelector').value = ramo;
        cambiarShowRamo(ramo);
    }

    function cambiarShowRamo(ramo) {
        document.getElementById('showRamoHeaderTitle').innerHTML = showTitulos[ramo] || 'Detalles del Bien Asegurado';
        showRamos.forEach(r => {
            const bloque = document.getElementById('show-bloque-' + r);
            const tab = document.getElementById('show-tab-' + r);
            if (bloque) {
                if (r === ramo) bloque.classList.remove('hidden');
                else bloque.classList.add('hidden');
            }
            if (tab) {
                if (r === ramo) {
                    tab.className = 'px-3 py-1.5 rounded-lg font-bold transition flex items-center space-x-1.5 bg-kfm-primary text-white shadow-sm';
                } else {
                    tab.className = 'px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200';
                }
            }
        });
    }

    // Modal de Borrado de Cliente
    function openDeleteClientModal() {
        document.getElementById('deleteClientModal').classList.remove('hidden');
    }
    function closeDeleteClientModal() {
        document.getElementById('deleteClientModal').classList.add('hidden');
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteClientModal();
    });
</script>

<!-- Modal de Confirmación de Borrado con Advertencia -->
<div id="deleteClientModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop oscuro con desenfoque -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteClientModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
            <div class="bg-white px-6 pb-6 pt-6 sm:p-7">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-rose-100 sm:mx-0 sm:h-12 sm:w-12">
                        <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                        <h3 class="text-lg font-bold leading-6 text-slate-900 font-display" id="modal-title">
                            ¿Eliminar permanentemente este cliente?
                        </h3>
                        <div class="mt-2 text-sm text-slate-600 space-y-3">
                            <p>
                                Vas a eliminar la ficha completa de:
                                <strong class="text-slate-900 block mt-1 font-display text-base font-bold text-kfm-navy">
                                    {{ $client->full_name }}
                                </strong>
                            </p>
                            <div class="bg-rose-50 border border-rose-200 rounded-xl p-3.5 text-xs text-rose-900 space-y-1">
                                <p class="font-bold flex items-center gap-1.5 text-rose-800">
                                    <span>⚠️</span> Advertencia de borrado irreversible:
                                </p>
                                <p class="text-rose-700 leading-relaxed">
                                    Se borrará todo el historial del cliente, incluyendo sus <strong>{{ $client->policies->count() }} póliza(s) vinculada(s)</strong>, datos de vehículos, coberturas y registros de cobro. Esta acción no se puede deshacer.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-6 gap-2 border-t border-slate-100">
                <form action="{{ route('clients.destroy', $client) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex w-full justify-center items-center gap-1.5 rounded-xl bg-rose-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-rose-700 sm:w-auto transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Sí, Eliminar Definitivamente</span>
                    </button>
                </form>
                <button type="button" 
                        onclick="closeDeleteClientModal()"
                        class="mt-2 sm:mt-0 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-100 sm:w-auto transition-colors cursor-pointer">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
