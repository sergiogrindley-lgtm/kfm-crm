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

        <div class="flex items-center space-x-2 sm:space-x-3">
            <span class="text-xs text-slate-400">ID Cliente: #{{ $client->id }}</span>
            <button type="button" 
                    onclick="openEditClientModal()"
                    class="px-3 py-1.5 text-xs font-semibold text-kfm-navy hover:text-white bg-slate-100 hover:bg-kfm-navy border border-slate-300 rounded-lg transition-all shadow-sm flex items-center gap-1.5 cursor-pointer"
                    title="Editar los datos personales y de contacto de este cliente">
                <svg class="w-3.5 h-3.5 text-kfm-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>✏️ Editar Cliente</span>
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
                </div>

                @if($client->nombre_familiar)
                    <div class="text-xs text-purple-800 bg-purple-50 px-2.5 py-1 rounded-lg border border-purple-200 inline-flex items-center space-x-1.5 mt-2 font-medium">
                        <span>👥 Familiar / Cónyuge registrado:</span>
                        <strong class="font-bold">{{ $client->nombre_familiar }}</strong>
                    </div>
                @endif

                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 mt-2">
                    <span class="font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-700 font-semibold border border-slate-200">
                        DNI/NIE: {{ $client->doc_identidad ?: 'NO ESPECIFICADO' }}
                    </span>
                    @if($client->ssn)
                        <span class="font-mono bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-semibold border border-blue-200">
                            SSN: {{ $client->ssn }}
                        </span>
                    @endif
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
                        <div class="p-5 rounded-xl border {{ strtolower($policy->estado) === 'activo' ? 'border-slate-200 bg-white hover:border-kfm-primary/50' : 'border-rose-300 bg-rose-50/20 hover:border-rose-400' }} transition-all shadow-sm space-y-3">
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
                                                @if(strtolower($policy->ramo) === 'hogar')
                                                    <span>🏠 {{ $policy->tipo_vivienda ?: 'Seguro de Hogar' }}</span>
                                                    @if($policy->direccion_riesgo)
                                                        <span class="text-xs font-normal text-slate-600 block sm:inline"> — {{ $policy->direccion_riesgo }}</span>
                                                    @endif
                                                @elseif(strtolower($policy->ramo) === 'rc')
                                                    <span>⚖️ RC {{ $policy->categoria ?: 'Responsabilidad Civil' }}</span>
                                                    @if($policy->detalles_cobertura)
                                                        <span class="text-xs font-normal text-slate-600">({{ $policy->detalles_cobertura }})</span>
                                                    @endif
                                                @else
                                                    <span>{{ $policy->marca }} {{ $policy->modelo }}</span>
                                                    @if($policy->detalles_cobertura)
                                                        <span class="text-xs font-normal text-slate-600">({{ $policy->detalles_cobertura }})</span>
                                                    @endif
                                                @endif
                                            </span>
                                            @if($policy->matricula)
                                                <span class="px-2 py-0.5 font-mono text-xs font-bold bg-slate-100 text-slate-800 rounded border border-slate-300">
                                                    {{ $policy->matricula }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-500 mt-0.5">
                                            Contratación: <span class="font-semibold uppercase text-slate-700">{{ $policy->ramo }}</span>
                                            @if($policy->categoria)
                                                • Categoría: <span class="font-semibold text-kfm-primary">{{ $policy->categoria }}</span>
                                            @endif
                                            • Cía: <strong class="text-kfm-primary">{{ $policy->codigo_aseguradora }} ({{ $policy->aseguradora }})</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end space-y-1">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ strtolower($policy->estado) === 'activo' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-100 text-rose-800 border border-rose-200' }}">
                                        {{ strtolower($policy->estado) === 'activo' ? '● ' . $policy->estado : '🛑 ' . $policy->estado }}
                                    </span>
                                    @if(strtolower($policy->estado) === 'activo' || (strtolower($policy->balance) !== 'cancelled' && strtolower($policy->balance) !== 'cancelado'))
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase {{ $policy->balance === 'Paid Full' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' }}">
                                            Balance: {{ $policy->balance }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Facturación -->
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 pt-3 border-t border-slate-100 text-xs">
                                <div>
                                    <span class="block text-slate-400 font-medium">Nº Póliza:</span>
                                    @if(!$policy->numero_poliza || str_starts_with(strtoupper($policy->numero_poliza), 'PENDIENTE'))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                            ⏳ Pendiente de Nº
                                        </span>
                                    @else
                                        <span class="font-mono font-bold text-slate-800">{{ $policy->numero_poliza }}</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="block text-slate-400 font-medium">Nº Recibo:</span>
                                    <span class="font-mono font-bold {{ $policy->recibo_numero ? 'text-indigo-700' : 'text-slate-400' }}">{{ $policy->recibo_numero ?: '—' }}</span>
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
                                <div class="flex flex-wrap items-center gap-2">
                                    <span>Liquidación Cía:</span>
                                    @if($policy->liquidacion === 'Liquidado')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            ✓ Liquidado
                                        </span>
                                        @if($policy->fecha_liquidacion)
                                            <span class="font-semibold text-emerald-800">(Fecha Liq: {{ $policy->fecha_liquidacion }})</span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                            ⏳ Pendiente
                                        </span>
                                    @endif

                                    @if($policy->fecha_pago)
                                        <span class="text-slate-400">•</span>
                                        <span>Fecha Pago: <strong class="text-slate-700">{{ $policy->fecha_pago }}</strong></span>
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
                                <div class="text-xs bg-amber-50 text-amber-950 p-2.5 rounded-lg border border-amber-200/90 flex items-start gap-1.5 shadow-2xs">
                                    <span class="text-amber-800 font-bold flex-shrink-0">📝 Obs:</span>
                                    <span class="text-slate-800 leading-snug">{{ $policy->observaciones }}</span>
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
                                    @if(strtolower($policy->estado) === 'activo')
                                        <!-- 0. Editar Póliza -->
                                        <button type="button" 
                                                onclick="openEditPolicyModal({{ json_encode($policy) }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-white hover:bg-slate-100 text-slate-800 border border-slate-300 shadow-sm transition cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-kfm-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>✏️ Editar Póliza</span>
                                        </button>

                                        @if($policy->matricula || strtolower($policy->ramo) === 'vehiculo')
                                            <!-- 1. Traspaso Gestoría -->
                                            <button type="button" 
                                                    onclick="openTraspasoModal('{{ $client->id }}', '{{ $policy->id }}', '{{ addslashes($client->full_name) }}', '{{ addslashes($client->doc_identidad ?: ($client->ssn ? 'SSN: ' . $client->ssn : '')) }}', '{{ addslashes($client->direccion_base ?: ($client->direccion_local ?: 'LU BASE NAVAL DE ROTA 999, 11520 ROTA, Cádiz')) }}', '{{ addslashes($policy->matricula) }}', '{{ addslashes($policy->marca) }}', '{{ addslashes($policy->modelo) }}', '{{ substr($policy->vin ?: '8491', -4) }}', '{{ $policy->numero_poliza }}', '{{ $policy->recibo_numero ?: '' }}')"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-[#9b1c31] hover:bg-[#7f1627] text-white shadow-sm transition cursor-pointer">
                                                <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                                <span>⚡ Traspaso Gestoría</span>
                                            </button>
                                        @endif

                                        <!-- 5. Baja PCS / Reembolso Prorrateado -->
                                        <button type="button" 
                                                onclick="openNuevoExtornoDesdePoliza('{{ $policy->numero_poliza }}', '{{ ($client->sede && $client->sede->slug === 'central') ? 'Central' : 'NEX' }}', '{{ addslashes(strtoupper($client->full_name)) }}', '{{ ($policy->codigo_aseguradora == '87' || str_contains($policy->aseguradora ?? '', 'GEICO')) ? 'GEICO' : 'Patria Hispana' }}', '{{ $policy->prima }}', '{{ $policy->id }}')"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-600 hover:bg-amber-700 text-white shadow-sm transition cursor-pointer">
                                            <span>🛫</span>
                                            <span>Baja PCS / Devolución</span>
                                        </button>

                                        <!-- 6. Botón Cancelar Póliza -->
                                        <button type="button" 
                                                onclick="openCancelPolicyModal('{{ $policy->id }}', '{{ $policy->numero_poliza }}', '{{ addslashes($policy->marca . ' ' . $policy->modelo) }}', '{{ addslashes($policy->matricula ?: $policy->ramo) }}')"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-white hover:bg-rose-50 text-rose-700 border border-rose-300 hover:border-rose-400 shadow-sm transition cursor-pointer ml-auto">
                                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            <span>🚫 Cancelar Póliza</span>
                                        </button>
                                    @else
                                        <!-- Panel de Póliza Cancelada / En Baja -->
                                        <div class="w-full bg-rose-50/80 border border-rose-200 rounded-xl p-3 flex flex-wrap items-center justify-between gap-2.5">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                                                <span class="text-xs font-bold text-rose-900">
                                                    Póliza Cancelada / En Baja
                                                </span>
                                                <span class="text-[11px] text-rose-700 hidden sm:inline">
                                                    (Historial preservado para auditoría y DGT)
                                                </span>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <button type="button" 
                                                        onclick="openEditPolicyModal({{ json_encode($policy) }})"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 shadow-xs transition cursor-pointer">
                                                    <span>✏️ Editar Póliza</span>
                                                </button>

                                                <form action="{{ route('clients.policies.reactivate', [$client, $policy]) }}" method="POST" onsubmit="return confirm('¿Reactivar la póliza #{{ $policy->numero_poliza }} a estado Activo?');">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition cursor-pointer">
                                                        <span>🔄 Reactivar a Activo</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
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

                    <input type="hidden" name="agente" value="{{ $client->agente ?? 'Kerry' }}">
                    <input type="hidden" name="estado" value="Activo">

                    <!-- Selector de Ramo, Aseguradora y Nº Póliza -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Contratación (Ramo)</label>
                            <select name="ramo" id="showRamoSelector" onchange="cambiarShowRamo(this.value)" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-kfm-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                                <option value="Vehiculo" selected>🚗 Vehículo (Coche, Moto, POV)</option>
                                <option value="Hogar">🏠 Hogar / Inquilino (Renters)</option>
                                <option value="RC">⚖️ Responsabilidad Civil (RC)</option>
                                <option value="Empresa">🏢 Empresa / Comercio</option>
                                <option value="Otros">🛡️ Otros Seguros (Salud, Embarcación)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Aseguradora (Código)</label>
                            <select name="codigo_aseguradora" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                                <option value="81" selected>Patria 81</option>
                                <option value="87">GEICO 87</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Número de Póliza</label>
                            <input type="text" name="numero_poliza" placeholder="Ej: 1661462" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                        </div>
                    </div>

                    <!-- Pestañas Visuales de Selección Rápida de Ramo -->
                    <div class="flex items-center space-x-2 border-b border-slate-200 pb-2 text-xs overflow-x-auto">
                        <button type="button" onclick="setShowRamo('Vehiculo')" id="show-tab-Vehiculo" class="px-3 py-1.5 rounded-lg font-bold transition flex items-center space-x-1.5 bg-kfm-primary text-white shadow-sm">
                            <span>🚗 Vehículo</span>
                        </button>
                        <button type="button" onclick="setShowRamo('Hogar')" id="show-tab-Hogar" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200">
                            <span>🏠 Hogar / Inquilino</span>
                        </button>
                        <button type="button" onclick="setShowRamo('RC')" id="show-tab-RC" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200">
                            <span>⚖️ Responsabilidad Civil</span>
                        </button>
                        <button type="button" onclick="setShowRamo('Empresa')" id="show-tab-Empresa" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200">
                            <span>🏢 Empresa</span>
                        </button>
                        <button type="button" onclick="setShowRamo('Otros')" id="show-tab-Otros" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200">
                            <span>🛡️ Otros Seguros</span>
                        </button>
                    </div>

                    <!-- ============================================== -->
                    <!-- DETALLES DEL BIEN ASEGURADO DINÁMICO (SHOW) -->
                    <!-- ============================================== -->
                    <div class="bg-slate-50 rounded-2xl border border-slate-200/90 p-5 space-y-4">
                        <div class="flex items-center justify-between">
                            <span id="showRamoHeaderTitle" class="text-xs font-bold uppercase tracking-wider text-kfm-navy flex items-center space-x-2">
                                <span>🚗 Detalles del Vehículo Asegurado</span>
                            </span>
                            <span class="text-[11px] text-slate-400">Campos adaptados al ramo</span>
                        </div>

                        <!-- 1. BLOQUE VEHÍCULO -->
                        <div id="show-bloque-Vehiculo" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Marca</label>
                                    <input type="text" name="marca" placeholder="Ej: HONDA / BMW" 
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase focus:ring-1 focus:ring-kfm-primary">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Modelo</label>
                                    <input type="text" name="modelo" placeholder="Ej: CIVIC 1.5 / CR-V" 
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase focus:ring-1 focus:ring-kfm-primary">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Matrícula (Española / TA / US)</label>
                                    <input type="text" name="matricula" placeholder="Ej: 0680 JCL" 
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono uppercase focus:ring-1 focus:ring-kfm-primary">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">VIN (Bastidor Oficial)</label>
                                    <input type="text" name="vin" placeholder="17 caracteres alfanuméricos" 
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono uppercase focus:ring-1 focus:ring-kfm-primary">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Categoría del Vehículo</label>
                                    <select name="categoria_vehiculo" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold text-slate-800 focus:ring-1 focus:ring-kfm-primary">
                                        <option value="Turismo" selected>Turismo</option>
                                        <option value="Furgoneta">Furgoneta</option>
                                        <option value="Camiones">Camiones</option>
                                        <option value="Autocaravana">Autocaravana</option>
                                        <option value="Microcar">Microcar</option>
                                        <option value="Motocicleta">Motocicleta</option>
                                        <option value="Ciclomotores">Ciclomotores</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- 2. BLOQUE HOGAR / INQUILINO -->
                        <div id="show-bloque-Hogar" class="space-y-4 hidden">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Dirección de la Vivienda Asegurada</label>
                                    <input type="text" name="direccion_riesgo" placeholder="Ej: C/ Corbeta 13, Urb. Vistahermosa, El Puerto o Rota Naval Housing" 
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs focus:ring-1 focus:ring-kfm-primary">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tipo de Vivienda</label>
                                    <select name="tipo_vivienda" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                        <option value="">-- Seleccionar --</option>
                                        <option value="PISO MEDIO">Piso (Planta Intermedia)</option>
                                        <option value="PISO BAJO">Piso (Bajo / Con Jardín)</option>
                                        <option value="ATICO">Ático</option>
                                        <option value="UNIF ADOSADA">Unifamiliar Adosada</option>
                                        <option value="UNIF NO ADOSADA">Chalet / Vivienda Aislada</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Categoría (Régimen)</label>
                                    <select name="categoria_hogar" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold text-slate-800 focus:ring-1 focus:ring-kfm-primary">
                                        <option value="Owner" selected>Owner</option>
                                        <option value="Renter">Renter</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Capital Continente (€)</label>
                                    <input type="text" name="capital_continente" placeholder="Ej: 120.000 €" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Capital Contenido (€)</label>
                                    <input type="text" name="capital_contenido" placeholder="Ej: 30.000 €" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                </div>
                            </div>
                        </div>

                        <!-- 3. BLOQUE RESPONSABILIDAD CIVIL (RC) -->
                        <div id="show-bloque-RC" class="space-y-4 hidden">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Categoría (Modalidad de RC)</label>
                                    <select name="categoria_rc" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold text-slate-800 focus:ring-1 focus:ring-kfm-primary">
                                        <option value="Animales de compañía/perros" selected>Animales de compañía/perros</option>
                                        <option value="Profesional/autónomo">Profesional/autónomo</option>
                                        <option value="Otros">Otros</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Detalle del Riesgo (Mascota / Actividad / Microchip)</label>
                                    <input type="text" name="detalles_cobertura" placeholder="Ej: Perro Pastor Alemán • Nº Microchip 94100002891 • Cobertura en Base y Territorio Español" 
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs focus:ring-1 focus:ring-kfm-primary">
                                </div>
                            </div>
                        </div>

                        <!-- 4. BLOQUE EMPRESA / COMERCIO -->
                        <div id="show-bloque-Empresa" class="space-y-4 hidden">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nombre Comercial / Razón Social</label>
                                    <input type="text" placeholder="Ej: Rota Naval Services S.L." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">CIF / NIF Empresa</label>
                                    <input type="text" placeholder="Ej: B-11223344" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase font-mono">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Actividad / Epígrafe</label>
                                    <input type="text" placeholder="Ej: Hostelería, Taller naval, Comercio" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Dirección del Local o Nave</label>
                                    <input type="text" placeholder="Ej: Polígono Industrial La Marina, Rota" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Capital Continente / Local (€)</label>
                                    <input type="text" placeholder="Ej: 200.000 €" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Existencias y Mobiliario (€)</label>
                                    <input type="text" placeholder="Ej: 60.000 €" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                </div>
                            </div>
                        </div>

                        <!-- 5. BLOQUE OTROS SEGUROS -->
                        <div id="show-bloque-Otros" class="space-y-4 hidden">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tipo de Seguro Especial</label>
                                    <select class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                        <option>Salud Privada Bilingüe (Cobertura Internacional)</option>
                                        <option>Embarcación de Recreo / Moto de Agua</option>
                                        <option>Vida y Protección Familiar</option>
                                        <option>Accidentes Personales</option>
                                        <option>Decesos y Repatriación Militar</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Compañía Especializada</label>
                                    <input type="text" placeholder="Ej: Sanitas / Adeslas / DKV" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nº Asegurados / Beneficiarios</label>
                                    <input type="text" placeholder="Ej: Titular + 2 beneficiarios" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Descripción del Riesgo y Cláusulas</label>
                                <input type="text" placeholder="Ej: Cobertura médica completa sin copago + repatriación aérea a EE.UU." 
                                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                            </div>
                        </div>

                    </div>

                    <!-- Facturación -->
                    <div class="pt-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-2">Información de Facturación</span>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Nº de Recibo</label>
                                <input type="text" name="recibo_numero" placeholder="Ej: 2026-0814" 
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Prima (€)</label>
                                <input type="text" name="prima" placeholder="Ej: 406,05 €" 
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-emerald-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Tipo de Pago</label>
                                <select name="tipo_facturacion" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                                    <option value="Anual" selected>Anual</option>
                                    <option value="Semianual">Semianual</option>
                                    <option value="Suplemento">Suplemento</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Balance</label>
                                <select name="balance" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold">
                                    <option value="Paid Full">Paid Full (Pagado Completo)</option>
                                    <option value="Partial">Partial (Pago Parcial)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-2">
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Fecha de Pago</label>
                                <input type="text" name="fecha_pago" placeholder="DD/MM/AAAA" 
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Fecha de Vencimiento</label>
                                <input type="text" name="fecha_vencimiento" placeholder="DD/MM/AAAA" 
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Liquidación Cía.</label>
                                <select name="liquidacion" id="showLiquidacionSelect" onchange="toggleShowLiquidacionFecha(this.value)" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold">
                                    <option value="Pendiente" selected>Pendiente</option>
                                    <option value="Liquidado">Liquidado</option>
                                </select>
                            </div>
                            <div id="showFechaLiquidacionCol" class="hidden">
                                <label class="block text-xs font-semibold text-emerald-700 mb-1">Fecha de Liquidación</label>
                                <input type="text" name="fecha_liquidacion" id="showFechaLiquidacionInput" placeholder="DD/MM/AAAA" 
                                       class="w-full px-3 py-2 bg-emerald-50 border border-emerald-300 rounded-xl text-xs font-medium text-emerald-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones de la Póliza -->
                    <div class="pt-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Observaciones / Notas de la Póliza</label>
                        <textarea name="observaciones" rows="2" placeholder="Notas internas de contratación, número anterior, condiciones especiales..." 
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary"></textarea>
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

    function toggleShowLiquidacionFecha(val) {
        const col = document.getElementById('showFechaLiquidacionCol');
        if (!col) return;
        if (val === 'Liquidado') {
            col.classList.remove('hidden');
            const input = document.getElementById('showFechaLiquidacionInput');
            if (input && !input.value) {
                const today = new Date();
                const d = String(today.getDate()).padStart(2, '0');
                const m = String(today.getMonth() + 1).padStart(2, '0');
                const y = today.getFullYear();
                input.value = `${d}/${m}/${y}`;
            }
        } else {
            col.classList.add('hidden');
        }
    }

    // Modal Unificado de Traspaso a Gestoría
    let currentTraspaso = {};

    function openTraspasoModal(clientId, policyId, clientName, clientDoc, clientDir, matricula, marca, modelo, bastidor, poliza, recibo) {
        currentTraspaso = { clientId, policyId, clientName, clientDoc, clientDir, matricula, marca, modelo, bastidor, poliza, recibo };

        document.getElementById('traspaso_client_id').value = clientId;
        document.getElementById('traspaso_policy_id').value = policyId;
        document.getElementById('traspaso_client_name').innerText = clientName;
        document.getElementById('traspaso_vehiculo').innerText = `${marca} ${modelo}`.trim();
        document.getElementById('traspaso_matricula').innerText = matricula;
        document.getElementById('traspaso_bastidor').innerText = '...' + bastidor;
        document.getElementById('traspaso_poliza').innerText = poliza || '1842910';

        // Se abre limpio para rellenar en el momento con el comprador que está en la mesa
        document.getElementById('input_buyer_nombre').value = '';
        document.getElementById('input_buyer_apellido').value = '';
        document.getElementById('input_buyer_doc').value = '';
        document.getElementById('input_buyer_telefono').value = '';
        document.getElementById('input_buyer_dob').value = '';
        document.getElementById('input_buyer_direccion').value = 'LU BASE NAVAL DE ROTA 999, 11520 ROTA, Cádiz';
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

    // Modal de Extorno PCS (Captura 3 Directa)
    function openNuevoExtornoDesdePoliza(poliza, office, clientName, cartera, prima, policyId) {
        document.getElementById('modal_extorno_numero_poliza').value = poliza || '';
        document.getElementById('modal_extorno_office').value = office || 'NEX';
        document.getElementById('modal_extorno_client_name').value = clientName || '';
        document.getElementById('modal_extorno_cartera').value = cartera || 'Patria Hispana';
        document.getElementById('modal_extorno_patria_amount').value = prima || '';
        document.getElementById('modal_extorno_policy_id').value = policyId || '';
        document.getElementById('modal_extorno_amount_reimbursed').value = prima || '';
        document.getElementById('modal_extorno_status').value = 'Pendiente';
        document.getElementById('modal_extorno_projected_departure').value = 'Órdenes de Traslado PCS (US Navy)';
        document.getElementById('modal_extorno_observaciones').value = 'Baja PCS y liquidación de prima no consumida.';
        document.getElementById('nuevoExtornoModal').classList.remove('hidden');
    }

    function closeNuevoExtornoModal() {
        document.getElementById('nuevoExtornoModal').classList.add('hidden');
    }

    let lookupExtornoTimer = null;
    function lookupPolicyForExtornoModal(val) {
        clearTimeout(lookupExtornoTimer);
        if (!val || val.trim().length < 3) return;
        lookupExtornoTimer = setTimeout(() => {
            fetch(`/api/lookup-policy?numero_poliza=${encodeURIComponent(val.trim())}`)
                .then(res => res.json())
                .then(data => {
                    if (data.found) {
                        if (data.client_name) document.getElementById('modal_extorno_client_name').value = data.client_name;
                        if (data.office) document.getElementById('modal_extorno_office').value = data.office;
                        if (data.cartera) document.getElementById('modal_extorno_cartera').value = data.cartera;
                        if (data.patria_amount) document.getElementById('modal_extorno_patria_amount').value = data.patria_amount;
                        if (data.policy_id) document.getElementById('modal_extorno_policy_id').value = data.policy_id;
                    }
                })
                .catch(err => console.error(err));
        }, 300);
    }

    function enviarWhatsAppConComprador() {
        const fecha = new Date().toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
        const lugar = 'ROTA';
        const precio = document.getElementById('input_buyer_precio').value || '1.000,00 €';
        const recibo = currentTraspaso.recibo ? (currentTraspaso.recibo.trim() + ' ') : '';
        const provision = `${recibo}120.00 EUROS`;

        const matricula = currentTraspaso.matricula || '';
        const marca = (currentTraspaso.marca || '').toUpperCase();
        const modelo = (currentTraspaso.modelo || '').toUpperCase();
        const bastidor = currentTraspaso.bastidor || '';

        const sellerDoc = currentTraspaso.clientDoc || '';
        const sellerName = (currentTraspaso.clientName || '').toUpperCase();
        const sellerDir = currentTraspaso.clientDir || 'LU BASE NAVAL DE ROTA 999, 11520 ROTA, Cádiz';

        const buyerNombreVal = document.getElementById('input_buyer_nombre').value || '';
        const buyerApellidoVal = document.getElementById('input_buyer_apellido').value || '';
        const buyerName = (buyerNombreVal + ' ' + buyerApellidoVal).trim().toUpperCase() || 'EMILY SARAH JOHNSON';
        const buyerDoc = (document.getElementById('input_buyer_doc').value || 'Z4192048E').toUpperCase();
        const buyerDir = document.getElementById('input_buyer_direccion').value || 'LU BASE NAVAL DE ROTA 999, 11520 ROTA, Cádiz';

        const text = `Fecha: ${fecha}\n`
            + `Lugar: ${lugar}\n`
            + `Precio: ${precio}\n`
            + `Provisión de fondos: ${provision}\n\n`
            + `Vehículo\n`
            + `Matrícula: ${matricula}\n`
            + `Marca: ${marca}\n`
            + `Modelo: ${modelo}\n`
            + `Bastidor: ${bastidor}\n\n`
            + `Vendedor\n`
            + `${sellerDoc ? (sellerDoc + ' — ') : ''}${sellerName}\n`
            + `${sellerDir}\n\n`
            + `Comprador\n`
            + `${buyerDoc ? (buyerDoc + ' — ') : ''}${buyerName}\n`
            + `${buyerDir}`;
        
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

    // Modal de Cancelación de Póliza
    function openCancelPolicyModal(policyId, numeroPoliza, vehiculo, matricula) {
        document.getElementById('cancel_modal_poliza').innerText = '#' + (numeroPoliza || '1842910');
        document.getElementById('cancel_modal_vehiculo').innerText = vehiculo + (matricula ? ' (' + matricula + ')' : '');
        document.getElementById('cancelPolicyForm').action = `/clients/{{ $client->id }}/policies/${policyId}/cancel`;
        document.getElementById('cancelPolicyModal').classList.remove('hidden');
    }

    function closeCancelPolicyModal() {
        document.getElementById('cancelPolicyModal').classList.add('hidden');
    }

    // Modal de Edición de Cliente
    function openEditClientModal() {
        document.getElementById('editClientModal').classList.remove('hidden');
    }
    function closeEditClientModal() {
        document.getElementById('editClientModal').classList.add('hidden');
    }

    // Modal de Edición de Póliza
    function openEditPolicyModal(policy) {
        document.getElementById('editPolicyForm').action = `/clients/{{ $client->id }}/policies/${policy.id}`;
        document.getElementById('edit_policy_title_sub').innerText = `Póliza #${policy.numero_poliza || 'Pendiente'} · ID #${policy.id}`;
        
        document.getElementById('edit_ramo').value = policy.ramo || 'Vehiculo';
        document.getElementById('edit_numero_poliza').value = policy.numero_poliza || '';
        document.getElementById('edit_codigo_aseguradora').value = policy.codigo_aseguradora || '81';
        
        document.getElementById('edit_marca').value = policy.marca || '';
        document.getElementById('edit_modelo').value = policy.modelo || '';
        document.getElementById('edit_matricula').value = policy.matricula || '';
        document.getElementById('edit_vin').value = policy.vin || '';
        
        document.getElementById('edit_direccion_riesgo').value = policy.direccion_riesgo || '';
        document.getElementById('edit_tipo_vivienda').value = policy.tipo_vivienda || '';
        document.getElementById('edit_detalles_cobertura').value = policy.detalles_cobertura || '';
        
        document.getElementById('edit_recibo_numero').value = policy.recibo_numero || '';
        document.getElementById('edit_prima').value = policy.prima || '';
        document.getElementById('edit_tipo_facturacion').value = policy.tipo_facturacion || 'Anual';
        document.getElementById('edit_balance').value = policy.balance || 'Paid Full';
        
        document.getElementById('edit_fecha_pago').value = policy.fecha_pago || '';
        document.getElementById('edit_fecha_vencimiento').value = policy.fecha_vencimiento || '';
        document.getElementById('edit_liquidacion').value = policy.liquidacion || 'Pendiente';
        document.getElementById('edit_fecha_liquidacion').value = policy.fecha_liquidacion || '';
        document.getElementById('edit_estado').value = policy.estado || 'Activo';
        document.getElementById('edit_policy_observaciones').value = policy.observaciones || '';
        
        cambiarEditRamo(policy.ramo || 'Vehiculo');
        toggleEditLiquidacionFecha(policy.liquidacion || 'Pendiente');
        
        document.getElementById('editPolicyModal').classList.remove('hidden');
    }

    function closeEditPolicyModal() {
        document.getElementById('editPolicyModal').classList.add('hidden');
    }

    function cambiarEditRamo(ramo) {
        const bloqueVehiculo = document.getElementById('editBloqueVehiculo');
        const bloqueHogar = document.getElementById('editBloqueHogar');
        const bloqueRC = document.getElementById('editBloqueRC');
        
        if (bloqueVehiculo) bloqueVehiculo.classList.add('hidden');
        if (bloqueHogar) bloqueHogar.classList.add('hidden');
        if (bloqueRC) bloqueRC.classList.add('hidden');
        
        if (ramo === 'Vehiculo') {
            if (bloqueVehiculo) bloqueVehiculo.classList.remove('hidden');
        } else if (ramo === 'Hogar') {
            if (bloqueHogar) bloqueHogar.classList.remove('hidden');
        } else if (ramo === 'RC') {
            if (bloqueRC) bloqueRC.classList.remove('hidden');
        }
    }

    function toggleEditLiquidacionFecha(val) {
        const col = document.getElementById('editFechaLiquidacionCol');
        if (!col) return;
        if (val === 'Liquidado') {
            col.classList.remove('hidden');
            const input = document.getElementById('edit_fecha_liquidacion');
            if (input && !input.value) {
                const today = new Date();
                const d = String(today.getDate()).padStart(2, '0');
                const m = String(today.getMonth() + 1).padStart(2, '0');
                const y = today.getFullYear();
                input.value = `${d}/${m}/${y}`;
            }
        } else {
            col.classList.add('hidden');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTraspasoModal();
            closeWhatsAppModal();
            closeNuevoExtornoModal();
            closeCancelPolicyModal();
            closeEditClientModal();
            closeEditPolicyModal();
        }
    });
</script>

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

<!-- Modal: + Nuevo Extorno PCS (Captura 3 Directa desde Baja PCS) -->
<div id="nuevoExtornoModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeNuevoExtornoModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-rose-700 to-slate-900 text-white p-5 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-base">Nuevo Registro de Extorno PCS</h3>
                    <p class="text-xs text-rose-100">Baja militar, traslado DoD y devolución de prima no consumida</p>
                </div>
                <button type="button" onclick="closeNuevoExtornoModal()" class="text-white/80 hover:text-white cursor-pointer text-xl leading-none">✕</button>
            </div>
            <form method="POST" action="{{ route('operations.extornos.store') }}">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="policy_id" id="modal_extorno_policy_id" value="">
                <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

                <div class="p-6 space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Nº Póliza *</label>
                            <input type="text" name="numero_poliza" id="modal_extorno_numero_poliza" required oninput="lookupPolicyForExtornoModal(this.value)" placeholder="Ej: 1849102" class="w-full font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Oficina KFM *</label>
                            <select name="office" id="modal_extorno_office" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-semibold">
                                <option value="NEX">NEX (Base Naval)</option>
                                <option value="Central">Central (Pueblo)</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Nombre Completo Asegurado (Militar) *</label>
                        <input type="text" name="client_name" id="modal_extorno_client_name" required placeholder="PO1 ROBERT DAVIS" class="w-full uppercase font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Cartera / Aseguradora</label>
                            <select name="cartera" id="modal_extorno_cartera" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                                <option value="Patria Hispana">Patria Hispana</option>
                                <option value="GEICO">GEICO Overseas</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Prima Original (€)</label>
                            <input type="text" name="patria_amount" id="modal_extorno_patria_amount" placeholder="642,50 €" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Fecha Salida / Destino PCS</label>
                            <input type="text" name="projected_departure" id="modal_extorno_projected_departure" placeholder="Órdenes de Traslado PCS (US Navy)" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Email enviado a Patria (Fecha)</label>
                            <input type="text" name="email_sent_patria_date" id="modal_extorno_email_sent_patria_date" value="{{ date('d/m/Y') }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Importe Reembolso (€)</label>
                            <input type="text" name="amount_reimbursed" id="modal_extorno_amount_reimbursed" placeholder="Importe a devolver (€)" class="w-full font-bold text-emerald-700 px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Estado Inicial</label>
                            <select name="status" id="modal_extorno_status" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-bold">
                                <option value="Pendiente" selected>Pendiente</option>
                                <option value="Tramitado">Tramitado</option>
                                <option value="Reembolsado">Reembolsado</option>
                            </select>
                        </div>
                    </div>
                    <div class="pt-1 flex flex-col gap-2">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="checkbox" name="stateside_geico_form" value="1" class="w-4 h-4 text-rose-600 rounded">
                            <span>¿Formulario GEICO Stateside cumplimentado?</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-rose-800 bg-rose-50 p-2 rounded-lg border border-rose-200">
                            <input type="checkbox" name="cancelar_poliza" value="1" checked class="w-4 h-4 text-rose-600 rounded">
                            <span>Dar de baja / cancelar la póliza simultáneamente en la ficha del cliente</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Observaciones</label>
                        <textarea name="observaciones" id="modal_extorno_observaciones" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg" placeholder="Entregado recibo VPC, devuelto por transferencia..."></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-between border-t border-slate-200">
                    <button type="button" onclick="closeNuevoExtornoModal()" class="text-slate-500 font-bold cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl cursor-pointer">Guardar Extorno</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Cancelación / Baja de Póliza -->
<div id="cancelPolicyModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeCancelPolicyModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
            
            <!-- Header Modal -->
            <div class="bg-gradient-to-r from-rose-600 via-rose-700 to-slate-900 text-white p-5 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl">
                        🛑
                    </div>
                    <div>
                        <h3 class="font-bold text-base font-display">Cancelar / Dar de Baja Póliza</h3>
                        <p class="text-xs text-rose-100">Registra el motivo y la fecha efectiva de baja en el expediente</p>
                    </div>
                </div>
                <button type="button" onclick="closeCancelPolicyModal()" class="text-white/80 hover:text-white cursor-pointer text-xl leading-none">✕</button>
            </div>

            <!-- Body Form -->
            <form id="cancelPolicyForm" method="POST" action="">
                @csrf
                <div class="p-6 space-y-4 text-xs">
                    
                    <!-- Resumen de la Póliza a Cancelar -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="block text-slate-400 font-semibold text-[10px] uppercase">Póliza:</span>
                            <span class="font-mono font-bold text-slate-900" id="cancel_modal_poliza"></span>
                        </div>
                        <div>
                            <span class="block text-slate-400 font-semibold text-[10px] uppercase">Riesgo / Vehículo:</span>
                            <strong class="text-slate-900 block truncate" id="cancel_modal_vehiculo"></strong>
                        </div>
                    </div>

                    <!-- Motivo de Cancelación -->
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Motivo de la Cancelación / Baja *</label>
                        <select name="motivo" id="cancel_motivo" required class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500">
                            <option value="Órdenes de Traslado PCS (Permanent Change of Station - DoD)">🛫 Órdenes de Traslado PCS (Permanent Change of Station - DoD)</option>
                            <option value="Venta / Traspaso de Vehículo">🤝 Venta / Traspaso de Vehículo a otro Titular</option>
                            <option value="No Renovación a Vencimiento">⏳ No Renovación a Vencimiento (Petición Cliente)</option>
                            <option value="Baja Anticipada por Repatriación a EE.UU.">🇺🇸 Baja Anticipada por Repatriación a EE.UU.</option>
                            <option value="Cambio de Compañía Aseguradora">🔄 Cambio de Compañía Aseguradora</option>
                            <option value="Siniestro Total o Desguace de Vehículo">💥 Siniestro Total o Desguace de Vehículo</option>
                            <option value="Impago de Recibo / Rechazo Bancario">⚠️ Impago de Recibo / Rechazo Bancario</option>
                            <option value="Baja Voluntaria">📝 Baja Voluntaria Solicitada por el Tomador</option>
                        </select>
                    </div>

                    <!-- Fecha de Baja Efectiva -->
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Fecha Efectiva de la Baja</label>
                        <input type="text" name="fecha_baja" id="cancel_fecha_baja" value="{{ date('d/m/Y') }}"
                               placeholder="dd/mm/aaaa"
                               class="w-full text-xs font-mono font-bold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500">
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Fecha en la que cesan los efectos de la cobertura</span>
                    </div>

                    <!-- Notas Adicionales -->
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Observaciones / Notas para el Registro</label>
                        <textarea name="notas" id="cancel_notas" rows="2" placeholder="ej: Militar entregó coche en VPC el día 19/10. Solicitada carta de no siniestralidad."
                                  class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-amber-900 text-[11px] flex items-center gap-2">
                        <span>💡</span>
                        <span>La póliza quedará marcada en rojo como <strong>Cancelada</strong> manteniendo todo el historial, matrículas y datos para futuras consultas o gestiones tributarias. Puedes reactivarla en cualquier momento si fue un error.</span>
                    </div>

                </div>

                <!-- Footer Actions -->
                <div class="bg-slate-50 px-6 py-4 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 text-xs font-bold">
                    <button type="button" onclick="closeCancelPolicyModal()"
                            class="text-slate-500 hover:text-slate-700 px-3 py-2.5 transition cursor-pointer">
                        Volver sin Cancelar
                    </button>
                    
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white shadow-md transition flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        <span>Confirmar Baja de Póliza</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Modal de Edición de Cliente -->
<div id="editClientModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditClientModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-slate-200">
            
            <div class="bg-gradient-to-r from-kfm-navy via-[#0c3547] to-[#145a78] text-white p-5 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl">
                        ✏️
                    </div>
                    <div>
                        <h3 class="font-bold text-base font-display">Editar Datos del Cliente</h3>
                        <p class="text-xs text-cyan-200">Ficha #{{ $client->id }} · {{ $client->full_name }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditClientModal()" class="text-white/80 hover:text-white cursor-pointer text-xl leading-none">✕</button>
            </div>

            <form method="POST" action="{{ route('clients.update', $client) }}" class="p-6 space-y-4 text-xs">
                @csrf
                @method('PUT')

                <input type="hidden" name="sede_id" value="{{ $client->sede_id ?? 2 }}">
                <input type="hidden" name="agente" value="{{ $client->agente ?? 'Kerry' }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-1">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Nombre *</label>
                        <input type="text" name="nombre" value="{{ $client->nombre }}" required class="w-full text-xs uppercase px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Apellidos *</label>
                        <input type="text" name="apellido" value="{{ $client->apellido }}" required class="w-full text-xs uppercase px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">DNI / NIE / CIF</label>
                        <input type="text" name="doc_identidad" value="{{ $client->doc_identidad }}" class="w-full text-xs uppercase font-mono px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">SSN (Social Security)</label>
                        <input type="text" name="ssn" value="{{ $client->ssn }}" class="w-full text-xs font-mono px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div>
                        <label class="block text-purple-900 font-bold mb-1">👥 Nombre Familiar / Cónyuge</label>
                        <input type="text" name="nombre_familiar" value="{{ $client->nombre_familiar }}" placeholder="ej: Mary Ferguson (Esposa)" class="w-full text-xs px-3 py-2 bg-purple-50/50 border border-purple-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-400">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Fecha de Nacimiento (DOB)</label>
                        <input type="text" name="dob" value="{{ $client->dob }}" placeholder="DD/MM/AAAA" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Teléfono Móvil (Número 1)</label>
                        <input type="text" name="telefono_movil" value="{{ $client->telefono_movil }}" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Teléfono Fijo / Base (Número 2)</label>
                        <input type="text" name="telefono_fijo" value="{{ $client->telefono_fijo }}" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Email Personal</label>
                        <input type="email" name="email_personal" value="{{ $client->email_personal }}" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Email Trabajo (Militar / Oficial)</label>
                        <input type="text" name="email_trabajo" value="{{ $client->email_trabajo }}" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Dirección Base (NEX / PSC Box / Buque USS)</label>
                        <input type="text" name="direccion_base" value="{{ $client->direccion_base }}" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Dirección Local (España)</label>
                        <input type="text" name="direccion_local" value="{{ $client->direccion_local }}" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                </div>

                <div class="pt-1">
                    <label class="block text-slate-700 font-bold mb-1">Observaciones / Notas Internas</label>
                    <textarea name="observaciones" rows="2" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">{{ $client->observaciones }}</textarea>
                </div>

                <div class="bg-slate-50 -mx-6 -mb-6 px-6 py-4 flex items-center justify-between border-t border-slate-200 mt-4 rounded-b-2xl">
                    <button type="button" onclick="closeEditClientModal()" class="px-4 py-2 text-slate-600 hover:text-slate-800 font-semibold cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-kfm-primary hover:bg-kfm-cerulean text-white font-bold shadow-md transition flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Guardar Cambios del Cliente</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Edición de Póliza -->
<div id="editPolicyModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditPolicyModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
            
            <div class="bg-gradient-to-r from-emerald-700 via-teal-800 to-slate-900 text-white p-5 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white text-xl">
                        ✏️
                    </div>
                    <div>
                        <h3 class="font-bold text-base font-display">Editar Contratación / Póliza</h3>
                        <p class="text-xs text-emerald-200" id="edit_policy_title_sub">Actualiza número de póliza, ramo, liquidación y datos del bien</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditPolicyModal()" class="text-white/80 hover:text-white cursor-pointer text-xl leading-none">✕</button>
            </div>

            <form id="editPolicyForm" method="POST" action="" class="p-6 space-y-4 text-xs">
                @csrf
                @method('PUT')

                <!-- Ramo y Aseguradora -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Contratación (Ramo) *</label>
                        <select name="ramo" id="edit_ramo" onchange="cambiarEditRamo(this.value)" required class="w-full text-xs font-bold text-kfm-primary px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                            <option value="Vehiculo">🚗 Vehículo (Auto / Moto / POV)</option>
                            <option value="Hogar">🏠 Hogar / Inquilino</option>
                            <option value="RC">⚖️ Responsabilidad Civil (RC)</option>
                            <option value="Empresa">🏢 Empresa / Comercio</option>
                            <option value="Otros">🛡️ Otros Seguros</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Número de Póliza</label>
                        <input type="text" name="numero_poliza" id="edit_numero_poliza" placeholder="Ej: 1661462" class="w-full text-xs font-mono font-bold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Aseguradora</label>
                        <select name="codigo_aseguradora" id="edit_codigo_aseguradora" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                            <option value="81">Patria 81</option>
                            <option value="87">GEICO 87</option>
                        </select>
                    </div>
                </div>

                <!-- Bloque Dinámico según Ramo -->
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                    <!-- Vehículo -->
                    <div id="editBloqueVehiculo" class="space-y-3">
                        <span class="block text-[11px] font-bold text-slate-600 uppercase">Datos del Vehículo</span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <div>
                                <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Marca</label>
                                <input type="text" name="marca" id="edit_marca" placeholder="HONDA / BMW" class="w-full text-xs uppercase px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Modelo</label>
                                <input type="text" name="modelo" id="edit_modelo" placeholder="CIVIC" class="w-full text-xs uppercase px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Matrícula</label>
                                <input type="text" name="matricula" id="edit_matricula" placeholder="0680 JCL" class="w-full text-xs font-mono uppercase px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">VIN (Bastidor)</label>
                                <input type="text" name="vin" id="edit_vin" placeholder="17 caracteres" class="w-full text-xs font-mono uppercase px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Hogar -->
                    <div id="editBloqueHogar" class="space-y-3 hidden">
                        <span class="block text-[11px] font-bold text-slate-600 uppercase">Datos del Inmueble Asegurado</span>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div class="sm:col-span-2">
                                <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Dirección de la Vivienda</label>
                                <input type="text" name="direccion_riesgo" id="edit_direccion_riesgo" placeholder="C/ Corbeta 13..." class="w-full text-xs px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Tipo de Vivienda</label>
                                <select name="tipo_vivienda" id="edit_tipo_vivienda" class="w-full text-xs px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg">
                                    <option value="">-- Seleccionar --</option>
                                    <option value="PISO MEDIO">Piso (Planta Intermedia)</option>
                                    <option value="PISO BAJO">Piso (Bajo / Con Jardín)</option>
                                    <option value="ATICO">Ático</option>
                                    <option value="UNIF ADOSADA">Unifamiliar Adosada</option>
                                    <option value="UNIF NO ADOSADA">Chalet / Vivienda Aislada</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- RC -->
                    <div id="editBloqueRC" class="space-y-3 hidden">
                        <span class="block text-[11px] font-bold text-slate-600 uppercase">Datos de Responsabilidad Civil</span>
                        <div>
                            <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Detalles del Riesgo / Cobertura</label>
                            <input type="text" name="detalles_cobertura" id="edit_detalles_cobertura" placeholder="Perro Pastor Alemán..." class="w-full text-xs px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Facturación, Prima y Liquidación -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                    <div>
                        <label class="block text-slate-700 font-semibold mb-1">Nº Recibo</label>
                        <input type="text" name="recibo_numero" id="edit_recibo_numero" placeholder="2026-0814" class="w-full text-xs font-mono font-bold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-semibold mb-1">Prima (€)</label>
                        <input type="text" name="prima" id="edit_prima" placeholder="406,05 €" class="w-full text-xs font-bold text-emerald-700 px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-semibold mb-1">Tipo de Pago</label>
                        <select name="tipo_facturacion" id="edit_tipo_facturacion" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl">
                            <option value="Anual">Anual</option>
                            <option value="Semianual">Semianual</option>
                            <option value="Suplemento">Suplemento</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-semibold mb-1">Balance</label>
                        <select name="balance" id="edit_balance" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl">
                            <option value="Paid Full">Paid Full</option>
                            <option value="Partial">Partial</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                    <div>
                        <label class="block text-slate-700 font-semibold mb-1">Fecha de Pago</label>
                        <input type="text" name="fecha_pago" id="edit_fecha_pago" placeholder="DD/MM/AAAA" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-semibold mb-1">Fecha Vencimiento</label>
                        <input type="text" name="fecha_vencimiento" id="edit_fecha_vencimiento" placeholder="DD/MM/AAAA" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Liquidación Cía.</label>
                        <select name="liquidacion" id="edit_liquidacion" onchange="toggleEditLiquidacionFecha(this.value)" class="w-full text-xs font-bold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl">
                            <option value="Pendiente">Pendiente</option>
                            <option value="Liquidado">Liquidado</option>
                        </select>
                    </div>
                    <div id="editFechaLiquidacionCol">
                        <label class="block text-emerald-800 font-bold mb-1">Fecha Liquidación</label>
                        <input type="text" name="fecha_liquidacion" id="edit_fecha_liquidacion" placeholder="DD/MM/AAAA" class="w-full text-xs font-semibold bg-emerald-50 border border-emerald-300 text-emerald-900 px-3 py-2 rounded-xl">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Estado de la Póliza</label>
                        <select name="estado" id="edit_estado" class="w-full text-xs font-bold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl">
                            <option value="Activo">Activo</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                </div>

                <div class="pt-1">
                    <label class="block text-slate-700 font-bold mb-1">Observaciones / Notas de la Póliza</label>
                    <textarea name="observaciones" id="edit_policy_observaciones" rows="2" placeholder="Notas internas de la póliza..." class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div class="bg-slate-50 -mx-6 -mb-6 px-6 py-4 flex items-center justify-between border-t border-slate-200 mt-4 rounded-b-2xl">
                    <button type="button" onclick="closeEditPolicyModal()" class="px-4 py-2 text-slate-600 hover:text-slate-800 font-semibold cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-md transition flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Actualizar Póliza</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

