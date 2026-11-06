@extends('layouts.app')

@section('title', 'Cesiones de Póliza sin Transferencia — KFM Insurance')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-sky-100 text-sky-800 border border-sky-200">
                    Uso, Derechos & Obligaciones • Sin Transferencia DGT
                </span>
                <span class="text-xs text-slate-400">Excel Cesion Poliza</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 font-display mt-1">Cesiones de Póliza sin Transferencia</h1>
            <p class="text-xs text-slate-500">Gestión y emisión de documentos de cesión de póliza entre miembros militares sin transferencia de vehículo en DGT</p>
        </div>

        <div>
            <button type="button" onclick="openNuevaCesionModal()" 
                    class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-1.5 cursor-pointer">
                <span>+ Registrar Cesión de Póliza</span>
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Cesiones</span>
            <span class="text-2xl font-black text-slate-800 mt-1 block">{{ $stats['total'] }}</span>
            <span class="text-[10px] text-slate-500 block">Expedientes registrados</span>
        </div>

        <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-amber-700 block">Pendientes</span>
            <span class="text-2xl font-black text-amber-900 mt-1 block">{{ $stats['pendientes'] }}</span>
            <span class="text-[10px] text-amber-700 block">En trámite / firma</span>
        </div>

        <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-emerald-700 block">Finalizadas</span>
            <span class="text-2xl font-black text-emerald-900 mt-1 block">{{ $stats['finalizadas'] }}</span>
            <span class="text-[10px] text-emerald-700 block">Cesión completada ✓</span>
        </div>

        <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-rose-700 block">Canceladas</span>
            <span class="text-2xl font-black text-rose-900 mt-1 block">{{ $stats['canceladas'] }}</span>
            <span class="text-[10px] text-rose-700 block">Anuladas o revocadas</span>
        </div>
    </div>

    <!-- Search Bar & Filters -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-3 text-xs">
        <form method="GET" action="{{ route('operations.cesiones.index') }}" class="flex-1 flex flex-wrap items-center gap-2">
            <div class="relative min-w-[240px] flex-1">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Buscar por Póliza, Cedente, Cesionario, Matrícula o SSN..."
                       class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-sky-500 focus:bg-white transition">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="estado" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700">
                <option value="">Todos los Estados</option>
                <option value="Pendiente" {{ request('estado') === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="Finalizada" {{ request('estado') === 'Finalizada' ? 'selected' : '' }}>Finalizada</option>
                <option value="Cancelada" {{ request('estado') === 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>

            <select name="sede_id" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700">
                <option value="">Todas las Sedes</option>
                @foreach($sedes as $s)
                    <option value="{{ $s->id }}" {{ request('sede_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold cursor-pointer transition">
                Filtrar
            </button>
            @if(request()->hasAny(['q', 'estado', 'sede_id']))
                <a href="{{ route('operations.cesiones.index') }}" class="px-3 py-2 text-slate-500 hover:text-slate-800 font-medium">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Table of Cesiones -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3 px-4">Póliza / Cartera</th>
                        <th class="py-3 px-4">Cedente (Titular Actual)</th>
                        <th class="py-3 px-4">Nuevo Tomador (Cesionario)</th>
                        <th class="py-3 px-4">Vehículo</th>
                        <th class="py-3 px-4">Suplemento</th>
                        <th class="py-3 px-4">Fecha Cesión</th>
                        <th class="py-3 px-4">Estado</th>
                        <th class="py-3 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cesiones as $c)
                        <tr class="hover:bg-slate-50/70 transition">
                            <!-- Póliza -->
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                <div class="flex items-center gap-1.5">
                                    <span>#{{ $c->numero_poliza }}</span>
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase {{ $c->cartera === '87' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ $c->cartera === '87' ? 'GEICO 87' : 'Patria 81' }}
                                    </span>
                                </div>
                                @if($c->agente)
                                    <span class="block text-[10px] text-slate-400 font-sans font-normal">Agente: {{ $c->agente }}</span>
                                @endif
                            </td>

                            <!-- Cedente -->
                            <td class="py-3.5 px-4">
                                @if($c->client_id)
                                    <a href="{{ route('clients.show', $c->client_id) }}" class="font-bold text-slate-900 hover:text-sky-600 transition block">
                                        {{ $c->cedente_nombre }}
                                    </a>
                                @else
                                    <span class="font-bold text-slate-900 block">{{ $c->cedente_nombre }}</span>
                                @endif
                                <div class="text-[11px] text-slate-500">
                                    @if($c->cedente_dni)<span>{{ $c->cedente_dni }}</span>@endif
                                    @if($c->cedente_telefono)<span> • {{ $c->cedente_telefono }}</span>@endif
                                </div>
                            </td>

                            <!-- Cesionario / Nuevo Tomador -->
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-sky-950 block">{{ $c->cesionario_nombre }}</span>
                                <div class="text-[11px] text-slate-500">
                                    @if($c->cesionario_ssn)<span class="font-mono text-[10px] bg-slate-100 px-1 rounded">SSN: {{ $c->cesionario_ssn }}</span>@endif
                                    @if($c->cesionario_dob)<span class="text-[10px]">DOB: {{ $c->cesionario_dob }}</span>@endif
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    @if($c->cesionario_telefono)<span>Tel: {{ $c->cesionario_telefono }}</span>@endif
                                    @if($c->cesionario_email)<span class="ml-1">({{ $c->cesionario_email }})</span>@endif
                                </div>
                            </td>

                            <!-- Vehículo -->
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-bold text-slate-800 block">{{ $c->matricula }}</span>
                                <span class="text-[11px] text-slate-500 block">{{ trim("{$c->marca} {$c->modelo}") ?: 'Vehículo' }}</span>
                                @if($c->vin)
                                    <span class="font-mono text-[9px] text-slate-400 block truncate max-w-[140px]" title="{{ $c->vin }}">VIN: {{ $c->vin }}</span>
                                @endif
                            </td>

                            <!-- Suplemento -->
                            <td class="py-3.5 px-4 text-xs">
                                @if($c->tiene_suplemento || $c->suplemento_recibo || $c->suplemento_cantidad)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span>💶</span>
                                        <span>{{ $c->suplemento_cantidad ? $c->suplemento_cantidad . ' €' : 'Sí' }}</span>
                                    </span>
                                    @if($c->suplemento_recibo)
                                        <span class="block font-mono text-[10px] text-slate-500 mt-0.5">Recibo: {{ $c->suplemento_recibo }}</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 text-[11px]">Sin suplemento</span>
                                @endif
                            </td>

                            <!-- Fecha Cesión -->
                            <td class="py-3.5 px-4 font-mono text-slate-700">
                                {{ $c->fecha_cesion ?: '-' }}
                            </td>

                            <!-- Estado -->
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $c->status_badge_color }}">
                                    {{ $c->estado }}
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Imprimir Documento Oficial -->
                                    <a href="{{ route('operations.cesiones.documento', $c) }}" target="_blank"
                                       class="px-2.5 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-800 border border-sky-300 rounded-lg text-xs font-bold transition flex items-center gap-1 shadow-2xs"
                                       title="Imprimir Hoja de Cesión Oficial">
                                        <span>🖨️ Documento</span>
                                    </a>

                                    <!-- Editar Estado / Datos -->
                                    <button type="button" 
                                            onclick="openEditarCesionModal({{ json_encode($c) }})"
                                            class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition">
                                        ✏️
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <div class="text-3xl mb-2">🤝</div>
                                <p class="text-sm font-semibold text-slate-600">No hay cesiones de póliza registradas</p>
                                <p class="text-xs text-slate-400 mt-1">Registra una nueva cesión pulsando en el botón superior</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cesiones->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $cesiones->links() }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL: NUEVA CESIÓN DE PÓLIZA -->
<div id="nuevaCesionModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs overflow-y-auto flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 space-y-4 my-8">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                <h3 class="text-base font-black text-slate-900 font-display">Registrar Cesión de Póliza sin Transferencia</h3>
            </div>
            <button type="button" onclick="closeNuevaCesionModal()" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer">&times;</button>
        </div>

        <form method="POST" action="{{ route('operations.cesiones.store') }}" class="space-y-4 text-xs" id="nuevaCesionForm">
            @csrf

            <!-- Hidden relationship inputs -->
            <input type="hidden" name="client_id" id="nc_client_id" value="">
            <input type="hidden" name="policy_id" id="nc_policy_id" value="">
            <input type="hidden" name="sede_id" id="nc_sede_id" value="">
            <input type="hidden" name="agente" id="nc_agente" value="">

            <!-- 1. BÚSQUEDA Y VINCULACIÓN DE PÓLIZA (AUTOCOMPLETADO INSTANTÁNEO) -->
            <div class="bg-sky-50/70 border border-sky-200 rounded-xl p-3.5 space-y-2">
                <label class="block font-bold text-sky-950 uppercase tracking-wider text-[10px]">
                    1. Número de Póliza (Autocompleta directamente Matrícula, Marca, Modelo y Asegurado)
                </label>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <input type="text" name="numero_poliza" id="nc_numero_poliza" required
                               placeholder="Introduce número de póliza o matrícula..." 
                               onblur="lookupCesionPolicy()"
                               class="w-full px-3 py-2 bg-white border border-sky-300 rounded-lg text-xs font-mono font-bold text-slate-900 focus:ring-2 focus:ring-sky-500">
                    </div>
                    <button type="button" onclick="lookupCesionPolicy()" class="px-3.5 py-2 bg-sky-700 hover:bg-sky-800 text-white rounded-lg font-bold transition flex items-center gap-1 cursor-pointer">
                        <span>🔍 Buscar</span>
                    </button>
                </div>
                <div id="nc_lookup_feedback" class="text-[11px] font-semibold text-slate-500 hidden"></div>
            </div>

            <!-- 2. DATOS DEL CLIENTE ASEGURADO (CEDENTE / TOMADOR ACTUAL) -->
            <div class="border border-slate-200 rounded-xl p-3.5 space-y-3 bg-slate-50/50">
                <span class="block font-bold text-slate-700 uppercase tracking-wider text-[10px]">2. Cliente Asegurado (Tomador Actual de la Póliza)</span>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-600 mb-1">Nombre Completo del Asegurado Actual *</label>
                        <input type="text" name="cedente_nombre" id="nc_cedente_nombre" required
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-bold uppercase">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Cartera</label>
                        <select name="cartera" id="nc_cartera" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-bold">
                            <option value="81">Patria 81</option>
                            <option value="87">GEICO 87</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">DNI / NIE / SSN del Asegurado</label>
                        <input type="text" name="cedente_dni" id="nc_cedente_dni"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Teléfono del Asegurado</label>
                        <input type="text" name="cedente_telefono" id="nc_cedente_telefono"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                    </div>
                </div>
            </div>

            <!-- 3. DATOS DEL VEHÍCULO (AUTOCOMPLETADO) -->
            <div class="border border-slate-200 rounded-xl p-3.5 space-y-3 bg-slate-50/50">
                <span class="block font-bold text-slate-700 uppercase tracking-wider text-[10px]">3. Vehículo Vinculado a la Póliza</span>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Matrícula *</label>
                        <input type="text" name="matricula" id="nc_matricula" required
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono font-bold uppercase">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Marca</label>
                        <input type="text" name="marca" id="nc_marca"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Modelo</label>
                        <input type="text" name="modelo" id="nc_modelo"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Bastidor (VIN)</label>
                        <input type="text" name="vin" id="nc_vin"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono uppercase">
                    </div>
                </div>
            </div>

            <!-- 4. DATOS DEL NUEVO TOMADOR DE LA PÓLIZA (CESIONARIO) -->
            <div class="border-2 border-indigo-200 rounded-xl p-3.5 space-y-3 bg-indigo-50/40">
                <div class="flex items-center justify-between">
                    <span class="block font-bold text-indigo-950 uppercase tracking-wider text-[10px]">
                        4. Datos del Nuevo Tomador de la Póliza (Cesionario)
                    </span>
                    <span class="text-[10px] bg-indigo-200 text-indigo-900 font-bold px-2 py-0.5 rounded">Para Hoja Oficial</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Nombre Completo del Nuevo Tomador *</label>
                        <input type="text" name="cesionario_nombre" id="nc_cesionario_nombre" required
                               placeholder="Ej: JOHN DOE / EMILY JOHNSON"
                               class="w-full px-3 py-2 bg-white border border-indigo-300 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">SSN (Social Security / DOD ID / FA)</label>
                        <input type="text" name="cesionario_ssn" id="nc_cesionario_ssn"
                               placeholder="Ej: ***-**-4912 o FA-99120"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Fecha de Nacimiento (DOB)</label>
                        <input type="text" name="cesionario_dob" id="nc_cesionario_dob"
                               placeholder="DD/MM/AAAA"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Dirección (Address / Base Naval / Box Ship)</label>
                        <input type="text" name="cesionario_direccion" id="nc_cesionario_direccion"
                               placeholder="Ej: LU BASE NAVAL DE ROTA 999 o PSC 819 BOX 4120"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Teléfono</label>
                        <input type="text" name="cesionario_telefono" id="nc_cesionario_telefono"
                               placeholder="Ej: 671-889900"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Correo Electrónico (Email)</label>
                        <input type="email" name="cesionario_email" id="nc_cesionario_email"
                               placeholder="Ej: john.doe@navy.mil"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                    </div>
                </div>
            </div>

            <!-- 5. PAGO DE SUPLEMENTO (EN CASO DE QUE HUBIERA) -->
            <div class="border border-emerald-200 rounded-xl p-3.5 space-y-3 bg-emerald-50/50">
                <div class="flex items-center justify-between">
                    <span class="block font-bold text-emerald-950 uppercase tracking-wider text-[10px]">
                        5. Pago de Suplemento (Opcional - en caso de que hubiera)
                    </span>
                    <span class="text-[10px] text-emerald-800 font-semibold">Número de Recibo y Cantidad</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Número de Recibo de Suplemento</label>
                        <input type="text" name="suplemento_recibo" id="nc_suplemento_recibo"
                               placeholder="Ej: REC-2026-SUP-01"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Cantidad del Suplemento (€)</label>
                        <input type="text" name="suplemento_cantidad" id="nc_suplemento_cantidad"
                               placeholder="Ej: 45,00 €"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-bold text-emerald-900">
                    </div>
                </div>
            </div>

            <!-- 6. GESTIÓN Y ESTADO -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Fecha de Cesión</label>
                    <input type="text" name="fecha_cesion" id="nc_fecha_cesion" value="{{ date('d/m/Y') }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-mono">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Estado de la Cesión *</label>
                    <select name="estado" id="nc_estado" required
                            class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-bold text-slate-800">
                        <option value="Pendiente" selected>Pendiente</option>
                        <option value="Finalizada">Finalizada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Observaciones</label>
                <textarea name="observaciones" rows="2" 
                          placeholder="Notas internas sobre la cesión o el suplemento..."
                          class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeNuevaCesionModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold cursor-pointer transition">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl font-bold shadow-md cursor-pointer transition">
                    Guardar Cesión
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDITAR CESIÓN -->
<div id="editarCesionModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs overflow-y-auto flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4 my-8">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-black text-slate-900 font-display">Editar Cesión de Póliza</h3>
            <button type="button" onclick="closeEditarCesionModal()" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer">&times;</button>
        </div>

        <form method="POST" id="editarCesionForm" action="" class="space-y-4 text-xs">
            @csrf
            @method('PATCH')

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Estado de la Cesión *</label>
                <select name="estado" id="ec_estado" required
                        class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-bold text-slate-800">
                    <option value="Pendiente">Pendiente</option>
                    <option value="Finalizada">Finalizada</option>
                    <option value="Cancelada">Cancelada</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">SSN Nuevo Tomador</label>
                    <input type="text" name="cesionario_ssn" id="ec_cesionario_ssn"
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">DOB (Fecha Nacimiento)</label>
                    <input type="text" name="cesionario_dob" id="ec_cesionario_dob"
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Dirección (Address / Base Naval)</label>
                <input type="text" name="cesionario_direccion" id="ec_cesionario_direccion"
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Teléfono</label>
                    <input type="text" name="cesionario_telefono" id="ec_cesionario_telefono"
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Correo Electrónico (Email)</label>
                    <input type="email" name="cesionario_email" id="ec_cesionario_email"
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                </div>
            </div>

            <!-- Pago de suplemento en edición -->
            <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 space-y-2">
                <span class="font-bold text-emerald-950 uppercase tracking-wider text-[10px] block">Pago de Suplemento</span>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nº Recibo</label>
                        <input type="text" name="suplemento_recibo" id="ec_suplemento_recibo"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Cantidad (€)</label>
                        <input type="text" name="suplemento_cantidad" id="ec_suplemento_cantidad"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-bold text-emerald-900">
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Observaciones</label>
                <textarea name="observaciones" id="ec_observaciones" rows="3"
                          class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditarCesionModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold cursor-pointer transition">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold cursor-pointer transition">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openNuevaCesionModal() {
        document.getElementById('nuevaCesionModal').classList.remove('hidden');
        document.getElementById('nc_numero_poliza').focus();
    }

    function closeNuevaCesionModal() {
        document.getElementById('nuevaCesionModal').classList.add('hidden');
    }

    function openEditarCesionModal(cesion) {
        document.getElementById('editarCesionForm').action = "/operations/cesiones/" + cesion.id;
        let est = cesion.estado || 'Pendiente';
        if (est === 'Activa' || est === 'Pendiente de firma') est = 'Pendiente';
        if (est === 'Anulada') est = 'Cancelada';
        document.getElementById('ec_estado').value = est;
        document.getElementById('ec_cesionario_ssn').value = cesion.cesionario_ssn || '';
        document.getElementById('ec_cesionario_dob').value = cesion.cesionario_dob || '';
        document.getElementById('ec_cesionario_direccion').value = cesion.cesionario_direccion || '';
        document.getElementById('ec_cesionario_telefono').value = cesion.cesionario_telefono || '';
        document.getElementById('ec_cesionario_email').value = cesion.cesionario_email || '';
        document.getElementById('ec_suplemento_recibo').value = cesion.suplemento_recibo || '';
        document.getElementById('ec_suplemento_cantidad').value = cesion.suplemento_cantidad || '';
        document.getElementById('ec_observaciones').value = cesion.observaciones || '';
        document.getElementById('editarCesionModal').classList.remove('hidden');
    }

    function closeEditarCesionModal() {
        document.getElementById('editarCesionModal').classList.add('hidden');
    }

    function lookupCesionPolicy() {
        const val = document.getElementById('nc_numero_poliza').value.trim();
        const feedback = document.getElementById('nc_lookup_feedback');
        if (!val) return;

        feedback.classList.remove('hidden', 'text-emerald-700', 'text-rose-600');
        feedback.classList.add('text-slate-500');
        feedback.innerHTML = 'Buscando póliza...';

        fetch('/api/lookup-policy?q=' + encodeURIComponent(val))
            .then(res => res.json())
            .then(data => {
                if (data.found) {
                    feedback.classList.remove('text-slate-500', 'text-rose-600');
                    feedback.classList.add('text-emerald-700');
                    feedback.innerHTML = '✓ Póliza localizada: ' + (data.client_name || 'Cliente') + (data.matricula ? ' · ' + data.matricula : '') + (data.vehiculo ? ' (' + data.vehiculo + ')' : '');

                    if (data.numero_poliza) document.getElementById('nc_numero_poliza').value = data.numero_poliza;
                    if (data.client_name) document.getElementById('nc_cedente_nombre').value = data.client_name;
                    if (data.client_id) document.getElementById('nc_client_id').value = data.client_id;
                    if (data.policy_id) document.getElementById('nc_policy_id').value = data.policy_id;
                    if (data.sede_id) document.getElementById('nc_sede_id').value = data.sede_id;
                    if (data.agente) document.getElementById('nc_agente').value = data.agente;
                    if (data.cartera) document.getElementById('nc_cartera').value = data.cartera;
                    if (data.matricula) document.getElementById('nc_matricula').value = data.matricula;
                    if (data.marca) document.getElementById('nc_marca').value = data.marca;
                    if (data.modelo) document.getElementById('nc_modelo').value = data.modelo;
                    if (data.vin) document.getElementById('nc_vin').value = data.vin;
                    if (data.client_dni) document.getElementById('nc_cedente_dni').value = data.client_dni;
                    if (data.client_telefono) document.getElementById('nc_cedente_telefono').value = data.client_telefono;
                } else {
                    feedback.classList.remove('text-slate-500', 'text-emerald-700');
                    feedback.classList.add('text-rose-600');
                    feedback.innerHTML = 'Póliza no encontrada automáticamente. Puedes rellenar los datos manualmente.';
                }
            })
            .catch(() => {
                feedback.classList.remove('text-slate-500');
                feedback.classList.add('text-rose-600');
                feedback.innerHTML = 'Error al consultar la póliza.';
            });
    }
</script>
@endsection
