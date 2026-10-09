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
                                    <div class="font-mono flex flex-wrap items-center gap-1.5">
                                        <span>VIN: <strong>{{ $policy->vin }}</strong></span>
                                        <button type="button" 
                                           onclick="openVinDecoderModal('{{ $policy->vin }}')"
                                           class="inline-flex items-center gap-1 font-sans text-[10px] bg-gradient-to-r from-cyan-600 to-emerald-600 hover:from-cyan-700 hover:to-emerald-700 text-white font-bold px-2 py-0.5 rounded shadow-xs transition cursor-pointer"
                                           title="Decodificar especificaciones completas de este bastidor en vivo">
                                            <span>⚡ Decodificar Bastidor</span>
                                        </button>
                                        <button type="button" 
                                           onclick="openItpCalculatorModal({{ substr($policy->fecha_efecto ?? '2013', 0, 4) ?: 2013 }}, 21000)"
                                           class="inline-flex items-center gap-1 font-sans text-[10px] bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 font-bold px-2 py-0.5 rounded shadow-xs transition cursor-pointer"
                                           title="Calcular tasas e ITP Junta de Andalucía Modelo 620">
                                            <span>📊 Tasas & ITP</span>
                                        </button>
                                        <button type="button" 
                                           onclick="openItvCalculatorModal({{ substr($policy->fecha_efecto ?? '2013', 0, 4) ?: 2013 }})"
                                           class="inline-flex items-center gap-1 font-sans text-[10px] bg-emerald-50 hover:bg-emerald-100 text-emerald-900 border border-emerald-300 font-bold px-2 py-0.5 rounded shadow-xs transition cursor-pointer"
                                           title="Calcular periodicidad y requisitos ITV">
                                            <span>🚗 ITV</span>
                                        </button>
                                        <button type="button" 
                                           onclick="openPcsTrackingModal('{{ $policy->vin }}')"
                                           class="inline-flex items-center gap-1 font-sans text-[10px] bg-sky-50 hover:bg-sky-100 text-sky-900 border border-sky-300 font-bold px-2 py-0.5 rounded shadow-xs transition cursor-pointer"
                                           title="Rastrear estado de transporte militar PCS My POV">
                                            <span>⚓ Tracking PCS</span>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            @if($policy->observaciones)
                                <div class="text-xs text-slate-600 bg-amber-50/50 p-2.5 rounded-lg border border-amber-100">
                                    <strong class="text-amber-800">Nota:</strong> {{ $policy->observaciones }}
                                </div>
                            @endif

                            <!-- Acciones Operativas KFM & Tramitación Militar -->
                            <div class="pt-3 border-t border-slate-100 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                        <span>🛠️</span> Operaciones de Póliza (Oficina NAVSTA Rota & NEX)
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">Automatizado • Sin rellenado manual</span>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    @if($policy->matricula || strtolower($policy->ramo) === 'vehiculo')
                                        <!-- 1. Traspaso Gestoría -->
                                        <button type="button" 
                                                onclick="openTraspasoModal('{{ $client->id }}', '{{ $policy->id }}', '{{ addslashes($client->full_name) }}', '{{ addslashes($policy->matricula) }}', '{{ addslashes($policy->marca . ' ' . $policy->modelo) }}', '{{ substr($policy->vin ?: '8491', -4) }}', '{{ $policy->numero_poliza }}')"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-[#9b1c31] hover:bg-[#7f1627] text-white shadow-sm transition cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                            <span>⚡ Traspaso Gestoría</span>
                                        </button>

                                        <!-- 2. Formulario GEICO Military -->
                                        <a href="{{ route('clients.geico', [$client, $policy]) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-[#002868] hover:bg-[#001b44] text-white shadow-sm transition">
                                            <span>🇺🇸</span>
                                            <span>Formulario GEICO</span>
                                        </a>

                                        <!-- 3. Mandato DGT Colegiado -->
                                        <a href="{{ route('clients.mandato', [$client, $policy]) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-800 hover:bg-slate-900 text-white shadow-sm transition">
                                            <span>⚖️</span>
                                            <span>Mandato DGT</span>
                                        </a>

                                        <!-- 4. Carta Verde (CIS) -->
                                        <a href="{{ route('clients.carta-verde', [$client, $policy]) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-700 hover:bg-emerald-800 text-white shadow-sm transition">
                                            <span>🟢</span>
                                            <span>Carta Verde (CIS)</span>
                                        </a>
                                    @endif

                                    <!-- 5. Baja PCS / Reembolso Prorrateado -->
                                    <button type="button" 
                                            onclick="openPcsRefundModal('{{ addslashes($client->full_name) }}', '{{ $policy->numero_poliza }}', '{{ addslashes($policy->matricula ?: ($policy->marca . ' ' . $policy->modelo)) }}', '{{ $policy->prima ?: '642,50 €' }}', '{{ $policy->fecha_pago ?: '15/11/2025' }}', '{{ $policy->fecha_vencimiento ?: '15/11/2026' }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-600 hover:bg-amber-700 text-white shadow-sm transition cursor-pointer">
                                        <span>🛫</span>
                                        <span>Baja PCS / Devolución</span>
                                    </button>
                                </div>
                            </div>
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
                                @foreach($agentes ?? ['Kerry', 'Coral', 'Conchi', 'Chari'] as $ag)
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

    // Modal Unificado de Traspaso a Gestoría
    let currentTraspaso = {};

    function openTraspasoModal(clientId, policyId, clientName, matricula, vehiculo, bastidor, poliza) {
        currentTraspaso = { clientId, policyId, clientName, matricula, vehiculo, bastidor, poliza };

        document.getElementById('traspaso_client_id').value = clientId;
        document.getElementById('traspaso_policy_id').value = policyId;
        document.getElementById('traspaso_client_name').innerText = clientName;
        document.getElementById('traspaso_vehiculo').innerText = vehiculo;
        document.getElementById('traspaso_matricula').innerText = matricula;
        document.getElementById('traspaso_bastidor').innerText = '...' + bastidor;
        document.getElementById('traspaso_poliza').innerText = poliza || '1842910';

        // Se abre limpio para rellenar en el momento con el comprador que está en la mesa
        document.getElementById('input_buyer_nombre').value = '';
        document.getElementById('input_buyer_apellido').value = '';
        document.getElementById('input_buyer_doc').value = '';
        document.getElementById('input_buyer_telefono').value = '';
        document.getElementById('input_buyer_dob').value = '';
        document.getElementById('input_buyer_direccion').value = '';
        document.getElementById('input_buyer_precio').value = '1.000,00 €';

        document.getElementById('traspasoModal').classList.remove('hidden');
    }

    function closeTraspasoModal() {
        document.getElementById('traspasoModal').classList.add('hidden');
    }

    function cargarCompradorDemo() {
        document.getElementById('input_buyer_nombre').value = 'EMILY SARAH';
        document.getElementById('input_buyer_apellido').value = 'JOHNSON';
        document.getElementById('input_buyer_doc').value = 'Z4192048E';
        document.getElementById('input_buyer_telefono').value = '671-998877';
        document.getElementById('input_buyer_dob').value = '19/11/1995';
        document.getElementById('input_buyer_direccion').value = 'LG PSC 819 BOX 999 - NAVSTA ROTA';
        document.getElementById('input_buyer_precio').value = '1.000,00 €';
    }

    function imprimirCesionConComprador() {
        const nombre = encodeURIComponent(document.getElementById('input_buyer_nombre').value || 'EMILY SARAH');
        const apellido = encodeURIComponent(document.getElementById('input_buyer_apellido').value || 'JOHNSON');
        const doc = encodeURIComponent(document.getElementById('input_buyer_doc').value || 'Z4192048E');
        const dob = encodeURIComponent(document.getElementById('input_buyer_dob').value || '19/11/1995');
        const dir = encodeURIComponent(document.getElementById('input_buyer_direccion').value || 'LG PSC 819 BOX 999 - NAVSTA ROTA');

        const url = `/clients/${currentTraspaso.clientId}/cesion/${currentTraspaso.policyId}?buyer_nombre=${nombre}&buyer_apellido=${apellido}&buyer_doc=${doc}&buyer_dob=${dob}&buyer_direccion=${dir}`;
        window.open(url, '_blank');
    }

    function imprimirMandatoConComprador() {
        const nombre = encodeURIComponent(document.getElementById('input_buyer_nombre').value || 'EMILY SARAH');
        const apellido = encodeURIComponent(document.getElementById('input_buyer_apellido').value || 'JOHNSON');
        const doc = encodeURIComponent(document.getElementById('input_buyer_doc').value || 'Z4192048E');
        const dob = encodeURIComponent(document.getElementById('input_buyer_dob').value || '19/11/1995');
        const dir = encodeURIComponent(document.getElementById('input_buyer_direccion').value || 'LG PSC 819 BOX 999 - NAVSTA ROTA');

        const url = `/clients/${currentTraspaso.clientId}/mandato/${currentTraspaso.policyId}?buyer_nombre=${nombre}&buyer_apellido=${apellido}&buyer_doc=${doc}&buyer_dob=${dob}&buyer_direccion=${dir}`;
        window.open(url, '_blank');
    }

    // Modal & Calculadora de Devolución PCS (Permanent Change of Station)
    let currentPcsData = {};

    function openPcsRefundModal(clientName, poliza, vehiculo, primaStr, fechaEfecto, fechaVencimiento) {
        currentPcsData = { clientName, poliza, vehiculo, primaStr, fechaEfecto, fechaVencimiento };
        
        document.getElementById('pcs_client_name').innerText = clientName;
        document.getElementById('pcs_poliza').innerText = poliza || '1842910';
        document.getElementById('pcs_vehiculo').innerText = vehiculo;
        
        // Extraer número de prima limpia
        let rawNum = String(primaStr).replace(/[^\d.,]/g, '').replace(',', '.');
        let numPrima = parseFloat(rawNum) || 642.50;
        document.getElementById('pcs_prima_input').value = numPrima.toFixed(2);
        document.getElementById('pcs_dias_consumidos').value = 142; // preset típico ~4.5 meses
        
        recalcularPcsRefund();
        document.getElementById('pcsRefundModal').classList.remove('hidden');
    }

    function closePcsRefundModal() {
        document.getElementById('pcsRefundModal').classList.add('hidden');
    }

    function setPcsDias(dias) {
        document.getElementById('pcs_dias_consumidos').value = dias;
        recalcularPcsRefund();
    }

    function recalcularPcsRefund() {
        const primaTotal = parseFloat(document.getElementById('pcs_prima_input').value) || 0;
        const diasConsumidos = parseInt(document.getElementById('pcs_dias_consumidos').value) || 0;
        const diasTotales = 365;
        const diasRestantes = Math.max(0, diasTotales - diasConsumidos);
        
        const pctDevolucion = diasTotales > 0 ? ((diasRestantes / diasTotales) * 100).toFixed(1) : 0;
        const importeReembolso = diasTotales > 0 ? ((primaTotal / diasTotales) * diasRestantes).toFixed(2) : 0;
        const importeConsumido = (primaTotal - importeReembolso).toFixed(2);

        document.getElementById('pcs_dias_restantes').innerText = diasRestantes + ' días';
        document.getElementById('pcs_pct_devolucion').innerText = pctDevolucion + '%';
        document.getElementById('pcs_reembolso_resultado').innerText = new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(importeReembolso);
        document.getElementById('pcs_consumido_resultado').innerText = new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(importeConsumido);
    }

    function copiarLiquidacionPcs() {
        const motivo = document.getElementById('pcs_motivo').value;
        const primaTotal = document.getElementById('pcs_prima_input').value;
        const diasConsumidos = document.getElementById('pcs_dias_consumidos').value;
        const diasRestantes = document.getElementById('pcs_dias_restantes').innerText;
        const reembolso = document.getElementById('pcs_reembolso_resultado').innerText;
        
        const texto = `🛫 LIQUIDACIÓN DEVOLUCIÓN DE PRIMA NO CONSUMIDA (PCS MOVE)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
👤 Titular: ${currentPcsData.clientName}
📄 Póliza: ${currentPcsData.poliza} (${currentPcsData.vehiculo})
📋 Motivo: ${motivo}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
💵 Prima Anual Pagada: ${primaTotal} €
⏱️ Período Consumido: ${diasConsumidos} días
⏳ Días No Consumidos: ${diasRestantes}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
💰 IMPORTE A REEMBOLSAR AL MILITAR: ${reembolso}
Liquidado por: KFM Insurance Agency · Base Naval de Rota (NEX)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`;

        navigator.clipboard.writeText(texto).then(() => {
            const btnLabel = document.getElementById('copyPcsBtnLabel');
            btnLabel.innerText = '✓ ¡Copiado!';
            setTimeout(() => { btnLabel.innerText = '📋 Copiar Liquidación'; }, 2000);
        });
    }

    function enviarWhatsAppConComprador() {
        const buyerName = (document.getElementById('input_buyer_nombre').value + ' ' + document.getElementById('input_buyer_apellido').value).trim() || 'Emily Sarah Johnson';
        const doc = document.getElementById('input_buyer_doc').value || 'Z4192048E';
        const tel = document.getElementById('input_buyer_telefono').value || '671-998877';
        const precio = document.getElementById('input_buyer_precio').value || '1.000,00 €';
        const dir = document.getElementById('input_buyer_direccion').value || 'LG PSC 819 BOX 999 - NAVSTA ROTA';

        const text = `🚗 *NUEVO EXPEDIENTE DE TRASPASO · KFM INSURANCE*\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n📍 *Oficina Emisora:* NAVSTA Rota (NEX) · Chari\n\n👤 *TITULAR SALIENTE (VENDEDOR)*\n• Nombre: ${currentTraspaso.clientName}\n• Póliza KFM: #${currentTraspaso.poliza || '1842910'}\n\n🚘 *DATOS DEL VEHÍCULO*\n• Modelo: ${currentTraspaso.vehiculo}\n• Matrícula: ${currentTraspaso.matricula}\n• Bastidor VIN: ${currentTraspaso.bastidor}\n\n🤝 *DATOS DEL COMPRADOR (CESIONARIO)*\n• Nombre: ${buyerName}\n• DNI / NIE / DOD ID: ${doc}\n• Teléfono: ${tel}\n• Domicilio: ${dir}\n• Precio Declarado: ${precio}\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n✅ *Expediente sincronizado con Gestoría Bahía & Naval*\n📄 *Mandato Colegiado DGT y Hoja de Cesión Oficial emitidos en el CRM.*`;
        
        document.getElementById('waMessageText').value = text;
        document.getElementById('whatsappGestoriaModal').classList.remove('hidden');
    }

    // Modal de WhatsApp para Gestoría
    function openWhatsAppModal(clientName, matricula, vehiculo, bastidor, poliza) {
        currentTraspaso = { clientName, matricula, vehiculo, bastidor, poliza };
        enviarWhatsAppConComprador();
    }

    function closeWhatsAppModal() {
        document.getElementById('whatsappGestoriaModal').classList.add('hidden');
    }

    function copyWaText() {
        const el = document.getElementById('waMessageText');
        navigator.clipboard.writeText(el.value).then(() => {
            document.getElementById('copyWaBtnLabel').innerText = '✓ Copiado';
            setTimeout(() => document.getElementById('copyWaBtnLabel').innerText = '📋 Copiar Texto', 2000);
        });
    }

    function openInWhatsAppWeb() {
        const text = encodeURIComponent(document.getElementById('waMessageText').value);
        window.open(`https://web.whatsapp.com/send?text=${text}`, '_blank');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteClientModal();
            closeTraspasoModal();
            closeWhatsAppModal();
            closePcsRefundModal();
        }
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

<!-- Modal de WhatsApp para Gestoría Bahía & Naval -->
<div id="whatsappGestoriaModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeWhatsAppModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white p-5 flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.679.116-.174.232-.145.39-.087s1.011.477 1.184.564.289.13.332.203c.044.072.044.419-.1.824z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm font-display">Aviso WhatsApp para Gestoría Bahía & Naval</h3>
                        <p class="text-[11px] text-emerald-100">Envío instantáneo de datos sin capturas de pantalla</p>
                    </div>
                </div>
                <button type="button" onclick="closeWhatsAppModal()" class="text-white/80 hover:text-white cursor-pointer text-lg leading-none">✕</button>
            </div>

            <div class="p-6 space-y-4 text-xs">
                <div>
                    <label class="block text-slate-500 font-semibold mb-1">Mensaje estructurado generado por el CRM:</label>
                    <textarea id="waMessageText" rows="7" readonly
                        class="w-full font-mono text-[11px] bg-slate-50 border border-slate-300 rounded-xl p-3 text-slate-800 leading-relaxed select-all focus:outline-none"></textarea>
                </div>
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-emerald-900 text-[11px]">
                    💡 <strong>Ahorro para Chari:</strong> Ya no hace falta hacer capturas de pantalla ni recortar fotos borrosas. El gestor recibe la matrícula y el bastidor en texto digital para copiar y pegar.
                </div>
            </div>

            <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-2 border-t border-slate-100 text-xs font-bold">
                <button type="button" onclick="openInWhatsAppWeb()"
                    class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2.5 rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                    <span>📲 Abrir y Enviar en WhatsApp Web</span>
                </button>
                <button type="button" onclick="copyWaText()"
                    class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2.5 rounded-xl transition cursor-pointer">
                    <span id="copyWaBtnLabel">📋 Copiar Texto</span>
                </button>
                <button type="button" onclick="closeWhatsAppModal()"
                    class="text-slate-500 hover:text-slate-700 px-3 py-2.5 transition cursor-pointer">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Unificado de Traspaso a Gestoría y Cesión -->
<div id="traspasoModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeTraspasoModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
            
            <!-- Header Modal -->
            <div class="bg-gradient-to-r from-[#9b1c31] to-[#0c3547] text-white p-5 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl">
                        🚗
                    </div>
                    <div>
                        <h3 class="font-bold text-base font-display">Traspaso de Vehículo & Envío a Gestoría</h3>
                        <p class="text-xs text-rose-100">Introduce los datos del comprador que está en tu mesa para sincronizar con Gestoría Bahía & Naval</p>
                    </div>
                </div>
                <button type="button" onclick="closeTraspasoModal()" class="text-white/80 hover:text-white cursor-pointer text-xl leading-none">✕</button>
            </div>

            <!-- Body Form -->
            <form id="traspasoForm" action="{{ route('gestoria.demo') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="client_id" id="traspaso_client_id">
                <input type="hidden" name="policy_id" id="traspaso_policy_id">

                <div class="p-6 space-y-5 text-xs">
                    
                    <!-- Fila 1: Datos del Vehículo y Vendedor (KFM CRM) -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                        <div>
                            <span class="block text-slate-400 font-semibold text-[10px] uppercase">Titular Saliente:</span>
                            <strong id="traspaso_client_name" class="text-slate-900 block truncate"></strong>
                        </div>
                        <div>
                            <span class="block text-slate-400 font-semibold text-[10px] uppercase">Vehículo:</span>
                            <strong id="traspaso_vehiculo" class="text-slate-900 block truncate"></strong>
                        </div>
                        <div>
                            <span class="block text-slate-400 font-semibold text-[10px] uppercase">Matrícula:</span>
                            <span id="traspaso_matricula" class="font-mono bg-white px-2 py-0.5 rounded border border-slate-300 font-bold text-slate-900"></span>
                        </div>
                        <div>
                            <span class="block text-slate-400 font-semibold text-[10px] uppercase">Bastidor / Póliza:</span>
                            <span class="font-mono text-slate-700 text-[11px]"><span id="traspaso_bastidor"></span> (<span id="traspaso_poliza"></span>)</span>
                        </div>
                    </div>

                    <!-- Fila 2: Cabecera Comprador + Botón Cargar Demo -->
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                        <span class="font-bold text-sm text-slate-900 font-display flex items-center gap-1.5">
                            <span>👤</span> Datos del Nuevo Comprador (Cesionario)
                        </span>
                        <button type="button" onclick="cargarCompradorDemo()" 
                                class="text-xs text-cyan-700 hover:text-cyan-900 bg-cyan-50 hover:bg-cyan-100 px-3 py-1 rounded-lg border border-cyan-200 font-bold transition cursor-pointer">
                            ⚡ Rellenar con Comprador Demo
                        </button>
                    </div>

                    <!-- Formulario Nuevo Comprador (Vacío para rellenar en el acto) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-slate-600 font-semibold mb-1">Nombre del Comprador *</label>
                            <input type="text" name="buyer_nombre" id="input_buyer_nombre" placeholder="ej: EMILY SARAH" required
                                   class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#9b1c31]">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-semibold mb-1">Apellidos *</label>
                            <input type="text" name="buyer_apellido" id="input_buyer_apellido" placeholder="ej: JOHNSON" required
                                   class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#9b1c31]">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-semibold mb-1">DNI / NIE / Pasaporte / DOD ID *</label>
                            <input type="text" name="buyer_doc" id="input_buyer_doc" placeholder="ej: Z4192048E" required
                                   class="w-full text-xs font-mono font-bold px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#9b1c31]">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-semibold mb-1">Teléfono Móvil</label>
                            <input type="text" name="buyer_telefono" id="input_buyer_telefono" placeholder="ej: 671-998877"
                                   class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#9b1c31]">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-semibold mb-1">Fecha Nacimiento (DOB)</label>
                            <input type="text" name="buyer_dob" id="input_buyer_dob" placeholder="ej: 19/11/1995"
                                   class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#9b1c31]">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-semibold mb-1">Precio Acordado (€)</label>
                            <input type="text" name="buyer_precio" id="input_buyer_precio" value="1.000,00 €"
                                   class="w-full text-xs font-bold px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#9b1c31]">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-slate-600 font-semibold mb-1">Domicilio / Unidad Base Naval</label>
                            <input type="text" name="buyer_direccion" id="input_buyer_direccion" placeholder="ej: LG PSC 819 BOX 999 - NAVSTA ROTA"
                                   class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#9b1c31]">
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-amber-900 text-[11px] flex items-center gap-2">
                        <span>⚡</span>
                        <span>Al enviar a la gestoría se abre el portal con todos estos campos volcados. También puedes imprimir la cesión oficial o enviar por WhatsApp.</span>
                    </div>

                </div>

                <!-- Footer Actions -->
                <div class="bg-slate-50 px-6 py-4 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 text-xs font-bold">
                    <button type="button" onclick="closeTraspasoModal()"
                            class="text-slate-500 hover:text-slate-700 px-3 py-2.5 transition cursor-pointer">
                        Cancelar
                    </button>
                    
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" onclick="imprimirCesionConComprador()"
                                class="px-4 py-2.5 rounded-xl bg-[#0c3547] hover:bg-[#145a78] text-white shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>🖨️ Imprimir Cesión Oficial</span>
                        </button>

                        <button type="button" onclick="imprimirMandatoConComprador()"
                                class="px-3.5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                            <span>⚖️ Mandato DGT</span>
                        </button>
                        
                        <button type="button" onclick="enviarWhatsAppConComprador()"
                                class="px-3.5 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 transition flex items-center gap-1.5 cursor-pointer">
                            <span>📲 WhatsApp</span>
                        </button>

                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-[#9b1c31] hover:bg-[#7f1627] text-white shadow-md transition flex items-center gap-1.5 cursor-pointer">
                            <span>🚀 Enviar a Gestoría Bahía & Naval</span>
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Modal de Liquidación y Reembolso por Traslado Militar PCS (Permanent Change of Station) -->
<div id="pcsRefundModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closePcsRefundModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
            
            <!-- Header Modal -->
            <div class="bg-gradient-to-r from-amber-600 via-amber-700 to-[#0c3547] text-white p-5 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl">
                        🛫
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-base font-display">Liquidación y Reembolso por Traslado PCS</h3>
                            <span class="bg-amber-400 text-amber-950 text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase">DoD Move</span>
                        </div>
                        <p class="text-xs text-amber-100">Cálculo en vivo de prima no consumida por cambio de destino o entrega en VPC</p>
                    </div>
                </div>
                <button type="button" onclick="closePcsRefundModal()" class="text-white/80 hover:text-white cursor-pointer text-xl leading-none">✕</button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-5 text-xs">
                
                <!-- Datos del Militar y Póliza -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <div>
                        <span class="block text-slate-400 font-semibold text-[10px] uppercase">Militar / Titular:</span>
                        <strong id="pcs_client_name" class="text-slate-900 block truncate"></strong>
                    </div>
                    <div>
                        <span class="block text-slate-400 font-semibold text-[10px] uppercase">Póliza / Ramo:</span>
                        <span class="font-mono text-slate-800 font-bold">#<span id="pcs_poliza"></span></span>
                    </div>
                    <div>
                        <span class="block text-slate-400 font-semibold text-[10px] uppercase">Vehículo / Riesgo:</span>
                        <strong id="pcs_vehiculo" class="text-slate-900 block truncate"></strong>
                    </div>
                </div>

                <!-- Parámetros de la Liquidación -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Prima Anual Pagada (€)</label>
                        <div class="relative">
                            <input type="number" step="0.01" id="pcs_prima_input" oninput="recalcularPcsRefund()"
                                   class="w-full text-sm font-bold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 pr-8">
                            <span class="absolute right-3 top-2.5 text-slate-400 font-bold text-xs">€</span>
                        </div>
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Importe íntegro anual abonado por el militar</span>
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Días de Cobertura Consumidos (1 a 365)</label>
                        <input type="number" id="pcs_dias_consumidos" min="1" max="365" oninput="recalcularPcsRefund()"
                               class="w-full text-sm font-mono font-bold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <!-- Presets de días rápidos -->
                        <div class="flex items-center gap-1.5 mt-1.5">
                            <button type="button" onclick="setPcsDias(90)" class="text-[10px] bg-slate-100 hover:bg-slate-200 text-slate-700 px-2 py-0.5 rounded cursor-pointer">3 meses (90d)</button>
                            <button type="button" onclick="setPcsDias(120)" class="text-[10px] bg-slate-100 hover:bg-slate-200 text-slate-700 px-2 py-0.5 rounded cursor-pointer">4 meses (120d)</button>
                            <button type="button" onclick="setPcsDias(142)" class="text-[10px] bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold px-2 py-0.5 rounded cursor-pointer">142d (Media)</button>
                            <button type="button" onclick="setPcsDias(180)" class="text-[10px] bg-slate-100 hover:bg-slate-200 text-slate-700 px-2 py-0.5 rounded cursor-pointer">6 meses (180d)</button>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-slate-700 font-bold mb-1">Motivo de Baja / Justificante DoD</label>
                        <select id="pcs_motivo" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="Órdenes de Traslado PCS (Permanent Change of Station - US Navy)">🛫 Órdenes de Traslado PCS (Permanent Change of Station - US Navy)</option>
                            <option value="Embarque de Vehículo en VPC (Vehicle Processing Center - Rota Port)">🚢 Embarque de Vehículo en VPC (Vehicle Processing Center - Rota Port)</option>
                            <option value="Venta / Cesión a otro Militar en Base Naval">🤝 Venta / Cesión a otro Militar en Base Naval</option>
                            <option value="Baja Definitiva y Repatriación a EE.UU.">🇺🇸 Baja Definitiva y Repatriación a EE.UU.</option>
                        </select>
                    </div>
                </div>

                <!-- Panel de Resultado Dinámico -->
                <div class="bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 border-2 border-emerald-300 rounded-2xl p-5 shadow-inner">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-800 block">IMPORTE CALCULADO A REEMBOLSAR AL MILITAR:</span>
                            <div class="text-3xl font-black text-emerald-950 font-display mt-0.5" id="pcs_reembolso_resultado">
                                0,00 €
                            </div>
                            <div class="text-[11px] text-emerald-800 mt-1 flex items-center gap-2">
                                <span>⏳ Días no consumidos: <strong id="pcs_dias_restantes">0 días</strong></span>
                                <span>•</span>
                                <span>Porcentaje restante: <strong id="pcs_pct_devolucion">0%</strong></span>
                            </div>
                        </div>

                        <div class="bg-white/80 backdrop-blur-sm border border-emerald-200 rounded-xl p-3 text-[11px] space-y-1 text-slate-700 w-full sm:w-auto">
                            <div class="flex justify-between sm:justify-start sm:gap-4">
                                <span class="text-slate-400">Prima Devengada:</span>
                                <strong id="pcs_consumido_resultado" class="text-slate-900 font-mono">0,00 €</strong>
                            </div>
                            <div class="flex justify-between sm:justify-start sm:gap-4">
                                <span class="text-slate-400">Retención de Emisión:</span>
                                <strong class="text-emerald-700 font-mono">0,00 € (Exenta DoD)</strong>
                            </div>
                            <div class="flex justify-between sm:justify-start sm:gap-4">
                                <span class="text-slate-400">Canal de Devolución:</span>
                                <strong class="text-slate-900">Transferencia / Tarjeta</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 text-blue-900 text-[11px] flex items-center gap-2">
                    <span>💡</span>
                    <span><strong>Ahorro Operativo para KFM:</strong> Evita cálculos a mano y disputas con el asegurado. El importe se calcula de forma fehaciente por días naturales para tramitar con Patria Hispana / Allianz.</span>
                </div>

            </div>

            <!-- Footer Actions -->
            <div class="bg-slate-50 px-6 py-4 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 text-xs font-bold">
                <button type="button" onclick="closePcsRefundModal()"
                        class="text-slate-500 hover:text-slate-700 px-3 py-2.5 transition cursor-pointer">
                    Cerrar
                </button>
                
                <div class="flex items-center gap-2">
                    <button type="button" onclick="copiarLiquidacionPcs()"
                            class="px-4 py-2.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                        <span id="copyPcsBtnLabel">📋 Copiar Liquidación</span>
                    </button>

                    <button type="button" onclick="window.print()"
                            class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white shadow-md transition flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>🖨️ Imprimir Liquidación</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

