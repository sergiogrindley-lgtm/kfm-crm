@extends('layouts.app')

@section('title', 'Gestoría DGT & Transferencias — KFM Insurance')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-800 border border-indigo-200">
                    DGT & Traspasos Oficiales
                </span>
                <span class="text-xs text-slate-400">GESTORÍA DGT • KFM ROTA & NEX BASE NAVAL</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 font-display mt-1">Gestoría DGT & Transferencias de Vehículos</h1>
            <p class="text-xs text-slate-500">Expedientes telemáticos de compraventa, mandatos oficiales y enlace con Gestoría Bahía & Naval</p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="openNuevoTramiteModal()"
                    class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-1.5 cursor-pointer">
                <span>+ Nuevo Expediente DGT</span>
            </button>
            <a href="{{ route('gestoria.demo') }}" target="_blank"
               class="px-3 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-xl text-xs font-semibold shadow-xs transition flex items-center gap-1.5">
                <span>⚡ Simulador Gestoría</span>
            </a>
        </div>
    </div>

    <!-- Pipeline KPI Cards (Nuevos Estados Oficiales) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2.5 text-xs">
        <!-- Total -->
        <a href="{{ route('operations.gestoria.index') }}" 
           class="p-3 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-slate-400 transition {{ !request('estado') ? 'ring-2 ring-indigo-500/30' : '' }}">
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Trámites</span>
            <span class="text-xl font-black text-slate-800">{{ $stats['total'] }}</span>
            <span class="text-[10px] text-slate-500 block mt-0.5">Cartera global</span>
        </a>

        <!-- 1. Preparado (Verde) -->
        <a href="{{ route('operations.gestoria.index', ['estado' => 'PREPARADO']) }}" 
           class="p-3 rounded-2xl bg-emerald-50/70 border border-emerald-300 shadow-xs hover:bg-emerald-50 transition {{ request('estado') === 'PREPARADO' ? 'ring-2 ring-emerald-500' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-[10px] uppercase font-bold text-emerald-800 block">1. Preparado</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            </div>
            <span class="text-xl font-black text-emerald-950">{{ $stats['preparado'] }}</span>
            <span class="text-[10px] text-emerald-800 block mt-0.5">Listo para enviar</span>
        </a>

        <!-- 2. Pendiente de documentación (Amarillo) -->
        <a href="{{ route('operations.gestoria.index', ['estado' => 'PENDIENTE_DOC']) }}" 
           class="p-3 rounded-2xl bg-amber-50/70 border border-amber-300 shadow-xs hover:bg-amber-50 transition {{ in_array(request('estado'), ['PENDIENTE_DOC', 'DOCUMENTOS']) ? 'ring-2 ring-amber-500' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-[10px] uppercase font-bold text-amber-800 block">2. Pendiente doc.</span>
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            </div>
            <span class="text-xl font-black text-amber-950">{{ $stats['pendiente_doc'] }}</span>
            <span class="text-[10px] text-amber-800 block mt-0.5">Falta papeles</span>
        </a>

        <!-- 3. Enviado a gestoría (Morado) -->
        <a href="{{ route('operations.gestoria.index', ['estado' => 'ENVIADO_GESTORIA']) }}" 
           class="p-3 rounded-2xl bg-purple-50/70 border border-purple-300 shadow-xs hover:bg-purple-50 transition {{ in_array(request('estado'), ['ENVIADO_GESTORIA', 'OUT_GESTORIA']) ? 'ring-2 ring-purple-500' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-[10px] uppercase font-bold text-purple-800 block">3. Enviado gestoría</span>
                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
            </div>
            <span class="text-xl font-black text-purple-950">{{ $stats['enviado_gestoria'] }}</span>
            <span class="text-[10px] text-purple-800 block mt-0.5">En Gestoría Bahía</span>
        </a>

        <!-- 4. Permiso de circulación recibido (Blanco) -->
        <a href="{{ route('operations.gestoria.index', ['estado' => 'PERMISO_RECIBIDO']) }}" 
           class="p-3 rounded-2xl bg-white border-2 border-slate-300 shadow-xs hover:border-slate-400 transition {{ in_array(request('estado'), ['PERMISO_RECIBIDO', 'BACK_AVISADO', 'RECOGIDO', 'LIQUIDADO', 'TERMINADAS']) ? 'ring-2 ring-slate-600' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-[10px] uppercase font-bold text-slate-700 block">4. Permiso recibido</span>
                <span class="w-2 h-2 rounded-full bg-slate-400 border border-slate-500"></span>
            </div>
            <span class="text-xl font-black text-slate-900">{{ $stats['permiso_recibido'] }}</span>
            <span class="text-[10px] text-slate-500 block mt-0.5">Terminadas en oficina</span>
        </a>

        <!-- 5. Cancelado (Rojo) -->
        <a href="{{ route('operations.gestoria.index', ['estado' => 'CANCELADO']) }}" 
           class="p-3 rounded-2xl bg-rose-50/70 border border-rose-300 shadow-xs hover:bg-rose-50 transition {{ request('estado') === 'CANCELADO' ? 'ring-2 ring-rose-500' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-[10px] uppercase font-bold text-rose-800 block">5. Cancelado</span>
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            </div>
            <span class="text-xl font-black text-rose-950">{{ $stats['cancelado'] }}</span>
            <span class="text-[10px] text-rose-800 block mt-0.5">Anulado</span>
        </a>

        <!-- 6. Cesión de póliza (Gris) -->
        <a href="{{ route('operations.gestoria.index', ['estado' => 'CESION_POLIZA']) }}" 
           class="p-3 rounded-2xl bg-slate-100/90 border border-slate-300 shadow-xs hover:bg-slate-200 transition {{ request('estado') === 'CESION_POLIZA' ? 'ring-2 ring-slate-500' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-[10px] uppercase font-bold text-slate-700 block">6. Cesión póliza</span>
                <span class="w-2 h-2 rounded-full bg-slate-500"></span>
            </div>
            <span class="text-xl font-black text-slate-900">{{ $stats['cesion_poliza'] }}</span>
            <span class="text-[10px] text-slate-600 block mt-0.5">Con seguro KFM</span>
        </a>
    </div>

    <!-- Search & Filter Toolbar Completa -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-3 text-xs">
        <form method="GET" action="{{ route('operations.gestoria.index') }}" class="flex flex-wrap items-center gap-2.5">
            
            <!-- Buscador de texto -->
            <div class="relative flex-1 min-w-[220px]">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Buscar matrícula, bastidor, cliente..."
                       class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Filtro por Estado (con Terminadas) -->
            <div class="min-w-[170px]">
                <select name="estado" onchange="this.form.submit()" 
                        class="w-full py-2 px-2.5 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-700 focus:bg-white focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                    <option value="">Todos los Estados</option>
                    <option value="PREPARADO" {{ request('estado') === 'PREPARADO' ? 'selected' : '' }}>🟢 1. Preparado</option>
                    <option value="PENDIENTE_DOC" {{ in_array(request('estado'), ['PENDIENTE_DOC', 'DOCUMENTOS']) ? 'selected' : '' }}>🟡 2. Pendiente doc.</option>
                    <option value="ENVIADO_GESTORIA" {{ in_array(request('estado'), ['ENVIADO_GESTORIA', 'OUT_GESTORIA']) ? 'selected' : '' }}>🟣 3. Enviado gestoría</option>
                    <option value="PERMISO_RECIBIDO" {{ in_array(request('estado'), ['PERMISO_RECIBIDO', 'BACK_AVISADO', 'RECOGIDO', 'LIQUIDADO', 'TERMINADAS']) ? 'selected' : '' }}>⚪ 4. Permiso recibido (Terminadas)</option>
                    <option value="CANCELADO" {{ request('estado') === 'CANCELADO' ? 'selected' : '' }}>🔴 5. Cancelado</option>
                    <option value="CESION_POLIZA" {{ request('estado') === 'CESION_POLIZA' ? 'selected' : '' }}>🔘 6. Cesión de póliza</option>
                </select>
            </div>

            <!-- Filtro por Tipo de Trámite -->
            <div class="min-w-[170px]">
                <select name="tipo" onchange="this.form.submit()" 
                        class="w-full py-2 px-2.5 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-700 focus:bg-white focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                    <option value="">Todos los Trámites</option>
                    <option value="Transferencia" {{ request('tipo') === 'Transferencia' ? 'selected' : '' }}>🚗 Solo Transferencias</option>
                    <option value="Transferencia ordinaria" {{ request('tipo') === 'Transferencia ordinaria' ? 'selected' : '' }}>Transferencia ordinaria</option>
                    <option value="Duplicado permiso circulación" {{ request('tipo') === 'Duplicado permiso circulación' ? 'selected' : '' }}>Duplicado permiso circulación</option>
                    <option value="Duplicado ITV" {{ request('tipo') === 'Duplicado ITV' ? 'selected' : '' }}>Duplicado ITV</option>
                    <option value="Cesión de póliza" {{ request('tipo') === 'Cesión de póliza' ? 'selected' : '' }}>Cesión de póliza</option>
                </select>
            </div>

            <!-- Filtro por Sede -->
            <div class="min-w-[130px]">
                <select name="sede_id" onchange="this.form.submit()" 
                        class="w-full py-2 px-2.5 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-700 focus:bg-white focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                    <option value="">Todas las Sedes</option>
                    @foreach($sedes as $s)
                        <option value="{{ $s->id }}" {{ request('sede_id') == $s->id ? 'selected' : '' }}>📍 {{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Agente -->
            <div class="min-w-[120px]">
                <select name="agente" onchange="this.form.submit()" 
                        class="w-full py-2 px-2.5 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-700 focus:bg-white focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                    <option value="">Todos los Agentes</option>
                    @foreach(['Kerry', 'Coral', 'Conchi', 'Chari', 'Silvia'] as $ag)
                        <option value="{{ $ag }}" {{ request('agente') === $ag ? 'selected' : '' }}>👤 {{ $ag }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-3.5 py-2 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-900 transition cursor-pointer">
                Filtrar
            </button>

            @if(request('q') || request('estado') || request('tipo') || request('sede_id') || request('agente'))
                <a href="{{ route('operations.gestoria.index') }}" class="text-rose-600 hover:text-rose-800 font-bold px-2 py-1">
                    ✕ Limpiar
                </a>
            @endif
        </form>

        <!-- Filtros Rápidos (Pills) directos -->
        <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2">
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-slate-400 font-bold text-[10px] uppercase mr-1">Accesos directos:</span>
                
                <a href="{{ route('operations.gestoria.index') }}" 
                   class="px-2.5 py-1 rounded-lg border text-[11px] font-bold transition {{ !request('estado') && !request('tipo') ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                    Todos los Expedientes ({{ $stats['total'] }})
                </a>

                <a href="{{ route('operations.gestoria.index', ['estado' => 'PENDIENTE_DOC']) }}" 
                   class="px-2.5 py-1 rounded-lg border text-[11px] font-bold transition {{ request('estado') == 'PENDIENTE_DOC' ? 'bg-amber-500 text-white border-amber-500' : 'bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100' }}">
                    🟡 Documentos Pendientes ({{ $stats['pendiente_doc'] }})
                </a>

                <a href="{{ route('operations.gestoria.index', ['estado' => 'PERMISO_RECIBIDO']) }}" 
                   class="px-2.5 py-1 rounded-lg border text-[11px] font-bold transition {{ request('estado') == 'PERMISO_RECIBIDO' ? 'bg-slate-800 text-white border-slate-800' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50 shadow-2xs' }}">
                    ⚪ Expedientes Terminados ({{ $stats['permiso_recibido'] }})
                </a>

                <a href="{{ route('operations.gestoria.index', ['estado' => 'CESION_POLIZA']) }}" 
                   class="px-2.5 py-1 rounded-lg border text-[11px] font-bold transition {{ request('estado') == 'CESION_POLIZA' || request('tipo') == 'cesion' ? 'bg-slate-800 text-white border-slate-800' : 'bg-slate-100 text-slate-700 border-slate-300 hover:bg-slate-200' }}">
                    🛡️ Con Cesión de Póliza ({{ $stats['cesion_poliza'] }})
                </a>
            </div>

            <div class="text-[11px] text-slate-400 font-medium">
                Mostrando <strong>{{ $tramites->total() }}</strong> expedientes
            </div>
        </div>
    </div>

    <!-- Table of Gestoría Expedientes -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">Vehículo / Trámite</th>
                        <th class="py-3.5 px-4">Comprador (Buyer)</th>
                        <th class="py-3.5 px-4">Vendedor (Seller)</th>
                        <th class="py-3.5 px-4">Importes, Fecha & Recibo</th>
                        <th class="py-3.5 px-4">Estado Gestoría</th>
                        <th class="py-3.5 px-4 text-right">Acciones & Documentos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($tramites as $tramite)
                        <tr class="hover:bg-slate-50/80 transition group">
                            
                            <!-- Vehículo & Trámite -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-sm bg-slate-900 text-white px-2 py-0.5 rounded shadow-xs">
                                        {{ $tramite->matricula }}
                                    </span>
                                    @if($tramite->tiene_cesion || $tramite->tipo === 'Cesión de póliza')
                                        <span class="text-[9px] bg-slate-200 text-slate-800 border border-slate-300 px-1.5 py-0.5 rounded font-bold" title="Cesión de póliza incluida">
                                            + Cesión @if($tramite->numero_poliza)<span class="font-mono text-[8px] text-slate-600">({{ $tramite->numero_poliza }})</span>@endif
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs font-bold text-slate-800 mt-1">
                                    {{ $tramite->marca }} {{ $tramite->modelo }}
                                </div>
                                <div class="text-[10px] font-mono text-slate-400 truncate max-w-[180px]" title="{{ $tramite->vin }}">
                                    VIN: {{ $tramite->vin ?: 'No especificado' }}
                                </div>
                                <div class="mt-1 flex flex-wrap items-center gap-1">
                                    <span class="text-[10px] font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 px-1.5 py-0.2 rounded">
                                        {{ $tramite->tipo }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded">
                                        📍 {{ $tramite->sede ? $tramite->sede->name : 'Rota' }}
                                    </span>
                                    @if($tramite->agente)
                                        <span class="text-[10px] text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded">
                                            👤 {{ $tramite->agente }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Comprador -->
                            <td class="py-3 px-4">
                                <strong class="text-slate-900 block">{{ $tramite->buyer_nombre }}</strong>
                                <span class="text-slate-500 font-mono text-[11px] block">
                                    {{ $tramite->buyer_dni }} @if($tramite->buyer_fa) • <span class="text-indigo-600 font-semibold">{{ $tramite->buyer_fa }}</span> @endif
                                </span>
                                @if($tramite->ssn)
                                    <span class="inline-block bg-slate-100 text-slate-700 font-mono text-[10px] px-1.5 py-0.2 rounded border border-slate-200 mt-0.5">
                                        SSN: {{ $tramite->ssn }}
                                    </span>
                                @endif
                                <span class="text-[10px] text-slate-400 block truncate max-w-[180px] mt-0.5">
                                    {{ $tramite->buyer_telefono }} • {{ $tramite->buyer_direccion }}
                                </span>
                            </td>

                            <!-- Vendedor -->
                            <td class="py-3 px-4">
                                <span class="text-slate-800 block font-semibold">{{ $tramite->seller_nombre }}</span>
                                <span class="text-slate-500 font-mono text-[11px] block">
                                    {{ $tramite->seller_dni }} @if($tramite->seller_fa) • <span class="text-slate-600">{{ $tramite->seller_fa }}</span> @endif
                                </span>
                                <span class="text-[10px] text-slate-400 block truncate max-w-[180px] mt-0.5">
                                    {{ $tramite->seller_contacto }}
                                </span>
                            </td>

                            <!-- Importes, Recibo & Fecha -->
                            <td class="py-3 px-4">
                                @if($tramite->recibo_numero)
                                    <div class="mb-1">
                                        <span class="inline-block bg-indigo-50 text-indigo-700 border border-indigo-200 px-2 py-0.5 rounded font-mono font-bold text-[10px]">
                                            🧾 Recibo #{{ $tramite->recibo_numero }}
                                        </span>
                                    </div>
                                @endif
                                <div class="text-slate-900 font-bold">
                                    Venta: {{ $tramite->precio_venta ?: '1.000 €' }}
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    Trámite: <strong>{{ $tramite->precio_tramite ?: '120 €' }}</strong>
                                </div>
                                @if($tramite->itp_cuota)
                                    <div class="text-[10px] text-amber-700 font-semibold">
                                        ITP (4%): {{ $tramite->itp_cuota }}
                                    </div>
                                @endif
                                <div class="mt-1.5">
                                    <span class="inline-flex items-center gap-1 text-[10.5px] font-bold text-slate-800 bg-slate-100 border border-slate-300 px-2 py-0.5 rounded shadow-2xs" title="Fecha en que se formalizó la transferencia">
                                        <span class="text-indigo-600">📅</span>
                                        <span>Fecha: <strong class="font-mono text-slate-950">{{ $tramite->fecha_tramite ?: ($tramite->created_at ? $tramite->created_at->format('d/m/Y') : date('d/m/Y')) }}</strong></span>
                                    </span>
                                </div>
                            </td>

                            <!-- Estado & Pipeline Switcher -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $tramite->status_badge_color }}">
                                        {{ $tramite->status_label }}
                                    </span>
                                </div>
                                
                                <!-- Selector Rápido de Estado Oficial -->
                                <form method="POST" action="{{ route('operations.gestoria.status', $tramite) }}" class="mt-2">
                                    @csrf @method('PATCH')
                                    <select name="estado" onchange="this.form.submit()" 
                                            class="text-[10px] font-semibold py-1 px-1.5 border rounded-lg bg-slate-50 border-slate-300 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 cursor-pointer">
                                        <option value="PREPARADO" {{ $tramite->estado === 'PREPARADO' ? 'selected' : '' }}>🟢 Preparado</option>
                                        <option value="PENDIENTE_DOC" {{ in_array($tramite->estado, ['PENDIENTE_DOC', 'DOCUMENTOS']) ? 'selected' : '' }}>🟡 Pendiente doc.</option>
                                        <option value="ENVIADO_GESTORIA" {{ in_array($tramite->estado, ['ENVIADO_GESTORIA', 'OUT_GESTORIA']) ? 'selected' : '' }}>🟣 Enviado gestoría</option>
                                        <option value="PERMISO_RECIBIDO" {{ in_array($tramite->estado, ['PERMISO_RECIBIDO', 'BACK_AVISADO', 'LIQUIDADO', 'RECOGIDO']) ? 'selected' : '' }}>⚪ Permiso recibido</option>
                                        <option value="CANCELADO" {{ $tramite->estado === 'CANCELADO' ? 'selected' : '' }}>🔴 Cancelado</option>
                                        <option value="CESION_POLIZA" {{ $tramite->estado === 'CESION_POLIZA' ? 'selected' : '' }}>🔘 Cesión de póliza</option>
                                    </select>
                                </form>
                            </td>

                            <!-- Acciones & Documentos Oficiales -->
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    <!-- Botón EDITAR EXPEDIENTE -->
                                    <button type="button" onclick="openEditarTramiteModal({{ json_encode($tramite) }})"
                                            class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold text-[11px] shadow-xs flex items-center gap-1 cursor-pointer transition"
                                            title="Editar expediente completo">
                                        <span>✏️ Editar</span>
                                    </button>

                                    <!-- Portada Expediente DGT -->
                                    <a href="{{ route('operations.gestoria.portada', $tramite) }}" target="_blank"
                                       class="px-2 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-slate-700 font-bold text-[11px] shadow-xs flex items-center gap-1"
                                       title="Portada oficial para entregar a la Gestoría con checklist">
                                        <span>📁 Portada</span>
                                    </a>

                                    <!-- Contrato Compraventa -->
                                    <a href="{{ route('operations.gestoria.contrato-compraventa', $tramite) }}" target="_blank"
                                       class="px-2 py-1 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg text-indigo-700 font-bold text-[11px] shadow-xs flex items-center gap-1"
                                       title="Contrato legal de compraventa oficial">
                                        <span>📝 Contrato</span>
                                    </a>

                                    <!-- Mandato Comprador -->
                                    <a href="{{ route('operations.gestoria.mandato-comprador', $tramite) }}" target="_blank"
                                       class="px-2 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-slate-600 font-medium text-[10px]"
                                       title="Mandato DGT Comprador">
                                        <span>M. Comp</span>
                                    </a>

                                    <!-- Mandato Vendedor -->
                                    <a href="{{ route('operations.gestoria.mandato-vendedor', $tramite) }}" target="_blank"
                                       class="px-2 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-slate-600 font-medium text-[10px]"
                                       title="Mandato DGT Vendedor">
                                        <span>M. Vend</span>
                                    </a>

                                    <!-- Extravío Vendedor -->
                                    <a href="{{ route('operations.gestoria.extravio-vendedor', $tramite) }}" target="_blank"
                                       class="px-2 py-1 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg text-amber-800 font-medium text-[10px]"
                                       title="Declaración jurada de extravío DGT">
                                        <span>Extravío</span>
                                    </a>

                                    <!-- Comunicación de Embargos y Precintos -->
                                    <a href="{{ route('operations.gestoria.embargo', $tramite) }}" target="_blank"
                                       class="px-2 py-1 bg-amber-50 hover:bg-amber-100 border border-amber-300 rounded-lg text-amber-900 font-bold text-[10px] shadow-2xs"
                                       title="Comunicación oficial de embargos y/o precintos">
                                        <span>⚠️ Embargos</span>
                                    </a>

                                    <!-- Cesión de Póliza -->
                                    @if($tramite->tiene_cesion || $tramite->tipo === 'Cesión de póliza')
                                        <a href="{{ route('operations.gestoria.cesion', $tramite) }}" target="_blank"
                                           class="px-2.5 py-1 bg-slate-200 hover:bg-slate-300 border border-slate-400 rounded-lg text-slate-800 font-bold text-[10px]"
                                           title="Cesión de póliza de seguro KFM">
                                            <span>🛡️ Cesión</span>
                                        </a>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <p class="text-base font-semibold">No se encontraron expedientes de gestoría</p>
                                <p class="text-xs mt-1">Crea un nuevo expediente para comenzar a tramitar traspasos DGT</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tramites->hasPages())
            <div class="p-4 bg-slate-50 border-t border-slate-200">
                {{ $tramites->links() }}
            </div>
        @endif
    </div>

</div>

<!-- ============================================================== -->
<!-- Modal 1: + Nuevo Expediente Gestoría DGT                        -->
<!-- ============================================================== -->
<div id="nuevoTramiteModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeNuevoTramiteModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
            
            <div class="bg-gradient-to-r from-indigo-700 via-indigo-800 to-slate-900 text-white p-5 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-xl">
                        📋
                    </div>
                    <div>
                        <h3 class="font-bold text-base font-display">Nuevo Expediente Gestoría DGT & Traspaso</h3>
                        <p class="text-xs text-indigo-200">Registro telemático con generación automática de contratos y mandatos</p>
                    </div>
                </div>
                <button type="button" onclick="closeNuevoTramiteModal()" class="text-white/80 hover:text-white text-xl">✕</button>
            </div>

            <form method="POST" action="{{ route('operations.gestoria.store') }}">
                @csrf
                <div class="p-6 space-y-4 text-xs max-h-[75vh] overflow-y-auto">
                    
                    <!-- Oficina, Tipo & Agente -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Tipo de Trámite *</label>
                            <select name="tipo" id="create_tipo" onchange="toggleSSNField('create')" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="Transferencia ordinaria">Transferencia ordinaria</option>
                                <option value="Duplicado de permiso de circulación">Duplicado de permiso de circulación</option>
                                <option value="Duplicado ITV">Duplicado ITV</option>
                                <option value="Cesión de póliza">Cesión de póliza</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Oficina KFM *</label>
                            <select name="sede_id" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @foreach($sedes as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Agente Tramitador *</label>
                            <select name="agente" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="Kerry">Kerry</option>
                                <option value="Coral">Coral</option>
                                <option value="Conchi">Conchi</option>
                                <option value="Chari">Chari</option>
                                <option value="Silvia">Silvia</option>
                            </select>
                        </div>
                    </div>

                    <!-- Datos del Vehículo -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">1. Datos del Vehículo</span>
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Matrícula *</label>
                                <input type="text" name="matricula" required placeholder="Ej: 5931LKP" class="w-full uppercase font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Bastidor (VIN)</label>
                                <input type="text" name="vin" placeholder="17 caracteres" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Marca *</label>
                                <input type="text" name="marca" required placeholder="Ej: FORD" class="w-full uppercase px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Modelo *</label>
                                <input type="text" name="modelo" required placeholder="Ej: FOCUS 1.5" class="w-full uppercase px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-2">
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Fecha Transferencia *</label>
                                <input type="text" name="fecha_tramite" value="{{ date('d/m/Y') }}" placeholder="d/m/Y" class="w-full font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Precio Venta</label>
                                <input type="text" name="precio_venta" value="1.000,00 €" class="w-full font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Precio Trámite</label>
                                <input type="text" name="precio_tramite" value="120,00 €" class="w-full font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Cuota ITP (4%)</label>
                                <input type="text" name="itp_cuota" value="84,00 €" class="w-full font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-indigo-700 font-bold mb-1">Número de Recibo</label>
                                <input type="text" name="recibo_numero" placeholder="Ej: 2026-0412" class="w-full font-mono font-bold px-3 py-1.5 border border-indigo-300 bg-indigo-50/50 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Comprador -->
                    <div class="bg-indigo-50/50 p-3.5 rounded-xl border border-indigo-100 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-700 block">2. Comprador (Buyer)</span>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Nombre Completo *</label>
                                <input type="text" name="buyer_nombre" required placeholder="EMILY SARAH JOHNSON" class="w-full uppercase font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">DNI / NIE / Passport</label>
                                <input type="text" name="buyer_dni" placeholder="Z4192048E" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">FA # (Militar DOD)</label>
                                <input type="text" name="buyer_fa" placeholder="FA-99120" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Fecha Nacimiento (DOB)</label>
                                <input type="text" name="buyer_dob" placeholder="19/11/1995" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Email</label>
                                <input type="email" name="buyer_email" placeholder="emily.johnson@navy.mil" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Teléfono</label>
                                <input type="text" name="buyer_telefono" placeholder="671-998877" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div class="sm:col-span-2">
                                <label class="block text-slate-700 font-semibold mb-1">Dirección (Local o Base)</label>
                                <input type="text" name="buyer_direccion" placeholder="Ej. LU BASE NAVAL DE ROTA 999 o USS OSCAR AUSTIN" class="w-full uppercase px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">C.P.</label>
                                <input type="text" name="buyer_cp" value="11520" class="w-full font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Vendedor -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">3. Vendedor (Seller)</span>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Nombre Completo *</label>
                                <input type="text" name="seller_nombre" required placeholder="DAVID ALEXANDER MILLER" class="w-full uppercase font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">DNI / NIE / Passport</label>
                                <input type="text" name="seller_dni" placeholder="Z1928374P" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">FA # (Militar DOD)</label>
                                <input type="text" name="seller_fa" placeholder="FA-84912" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div class="sm:col-span-2">
                                <label class="block text-slate-700 font-semibold mb-1">Dirección Vendedor</label>
                                <input type="text" name="seller_direccion" placeholder="Ej. C/ SAN JUAN DE PUERTO RICO 12, ROTA" class="w-full uppercase px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Contacto Vendedor</label>
                                <input type="text" name="seller_contacto" placeholder="671-234567" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Cesión de Póliza & Campo SSN Dinámico -->
                    <div class="bg-slate-100 p-3.5 rounded-xl border border-slate-300 space-y-2">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="tiene_cesion" id="create_tiene_cesion" value="1" onchange="toggleSSNField('create')" class="w-4 h-4 text-indigo-600 rounded cursor-pointer">
                            <label for="create_tiene_cesion" class="cursor-pointer font-bold text-slate-800 text-xs">
                                ¿Incluye Cesión de Póliza de Seguro KFM?
                            </label>
                        </div>
                        <div id="create_ssn_container" class="pt-2 border-t border-slate-200 grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                                <label class="block text-slate-800 font-bold mb-1">
                                    Nº Póliza a Ceder <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" name="numero_poliza" id="create_numero_poliza" placeholder="Ej. POL-2026 / 8492" class="w-full font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg bg-white uppercase">
                                <span class="text-[9px] text-slate-500 block mt-0.5">Póliza del vendedor que se cede</span>
                            </div>
                            <div>
                                <label class="block text-slate-800 font-bold mb-1">
                                    SSN Comprador <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" name="ssn" id="create_ssn" placeholder="***-**-4912" class="w-full font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg bg-white">
                                <span class="text-[9px] text-slate-500 block mt-0.5">SSN militar en lugar de NIE</span>
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Box / Ship (Navy DoD)</label>
                                <input type="text" name="box_ship" placeholder="Ej. USS OSCAR AUSTIN DDG 79" class="w-full font-mono px-3 py-1.5 border border-slate-300 rounded-lg bg-white uppercase">
                                <span class="text-[9px] text-slate-500 block mt-0.5">Destino militar o barco</span>
                            </div>
                        </div>
                    </div>

                    <!-- Estado Inicial -->
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Estado Inicial del Expediente</label>
                        <select name="estado" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="PREPARADO" selected>🟢 Preparado</option>
                            <option value="PENDIENTE_DOC">🟡 Pendiente de documentación</option>
                            <option value="ENVIADO_GESTORIA">🟣 Enviado a gestoría</option>
                            <option value="PERMISO_RECIBIDO">⚪ Permiso de circulación recibido</option>
                            <option value="CANCELADO">🔴 Cancelado</option>
                            <option value="CESION_POLIZA">🔘 Cesión de póliza</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-700 font-semibold mb-1">Observaciones / Notas del Expediente</label>
                        <textarea name="observaciones" rows="2" placeholder="Notas internas para la gestoría o recordatorios para el cliente..." class="w-full px-3 py-2 border border-slate-300 rounded-xl"></textarea>
                    </div>

                </div>

                <div class="bg-slate-50 px-6 py-4 flex items-center justify-between border-t border-slate-100 font-bold">
                    <button type="button" onclick="closeNuevoTramiteModal()" class="text-slate-500 hover:text-slate-700 cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md cursor-pointer">
                        Guardar Expediente
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- ============================================================== -->
<!-- Modal 2: ✏️ Editar Expediente Gestoría DGT                      -->
<!-- ============================================================== -->
<div id="editarTramiteModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditarTramiteModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
            
            <div class="bg-gradient-to-r from-amber-600 via-amber-700 to-slate-900 text-white p-5 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-xl">
                        ✏️
                    </div>
                    <div>
                        <h3 class="font-bold text-base font-display">
                            Editar Expediente Gestoría DGT — <span id="edit_matricula_title" class="font-mono underline"></span>
                        </h3>
                        <p class="text-xs text-amber-200">Modifica cualquier dato del trámite, comprador, vendedor, recibo o estado</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditarTramiteModal()" class="text-white/80 hover:text-white text-xl cursor-pointer">✕</button>
            </div>

            <form id="editarTramiteForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4 text-xs max-h-[75vh] overflow-y-auto">
                    
                    <!-- Oficina, Tipo, Agente & Estado -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2.5">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Tipo de Trámite *</label>
                            <select name="tipo" id="edit_tipo" onchange="toggleSSNField('edit')" class="w-full text-xs font-semibold px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg">
                                <option value="Transferencia ordinaria">Transferencia ordinaria</option>
                                <option value="Duplicado de permiso de circulación">Duplicado de permiso de circulación</option>
                                <option value="Duplicado ITV">Duplicado ITV</option>
                                <option value="Cesión de póliza">Cesión de póliza</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Oficina KFM *</label>
                            <select name="sede_id" id="edit_sede_id" class="w-full text-xs font-semibold px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg">
                                @foreach($sedes as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Agente *</label>
                            <select name="agente" id="edit_agente" class="w-full text-xs font-semibold px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg">
                                <option value="Kerry">Kerry</option>
                                <option value="Coral">Coral</option>
                                <option value="Conchi">Conchi</option>
                                <option value="Chari">Chari</option>
                                <option value="Silvia">Silvia</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Estado Gestoría *</label>
                            <select name="estado" id="edit_estado" class="w-full text-xs font-bold px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg">
                                <option value="PREPARADO">🟢 Preparado</option>
                                <option value="PENDIENTE_DOC">🟡 Pendiente doc.</option>
                                <option value="ENVIADO_GESTORIA">🟣 Enviado gestoría</option>
                                <option value="PERMISO_RECIBIDO">⚪ Permiso recibido</option>
                                <option value="CANCELADO">🔴 Cancelado</option>
                                <option value="CESION_POLIZA">🔘 Cesión de póliza</option>
                            </select>
                        </div>
                    </div>

                    <!-- Datos del Vehículo -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">1. Datos del Vehículo</span>
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Matrícula *</label>
                                <input type="text" name="matricula" id="edit_matricula" required class="w-full uppercase font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Bastidor (VIN)</label>
                                <input type="text" name="vin" id="edit_vin" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Marca *</label>
                                <input type="text" name="marca" id="edit_marca" required class="w-full uppercase px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Modelo *</label>
                                <input type="text" name="modelo" id="edit_modelo" required class="w-full uppercase px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-2">
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Fecha Transferencia *</label>
                                <input type="text" name="fecha_tramite" id="edit_fecha_tramite" placeholder="d/m/Y" class="w-full font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Precio Venta</label>
                                <input type="text" name="precio_venta" id="edit_precio_venta" class="w-full font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Precio Trámite</label>
                                <input type="text" name="precio_tramite" id="edit_precio_tramite" class="w-full font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Cuota ITP (4%)</label>
                                <input type="text" name="itp_cuota" id="edit_itp_cuota" class="w-full font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-indigo-700 font-bold mb-1">Número de Recibo</label>
                                <input type="text" name="recibo_numero" id="edit_recibo_numero" placeholder="Ej: 2026-0412" class="w-full font-mono font-bold px-3 py-1.5 border border-indigo-300 bg-indigo-50/50 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Comprador -->
                    <div class="bg-indigo-50/50 p-3.5 rounded-xl border border-indigo-100 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-700 block">2. Comprador (Buyer)</span>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Nombre Completo *</label>
                                <input type="text" name="buyer_nombre" id="edit_buyer_nombre" required class="w-full uppercase font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">DNI / NIE / Passport</label>
                                <input type="text" name="buyer_dni" id="edit_buyer_dni" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">FA # (Militar DOD)</label>
                                <input type="text" name="buyer_fa" id="edit_buyer_fa" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Fecha Nacimiento (DOB)</label>
                                <input type="text" name="buyer_dob" id="edit_buyer_dob" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Email</label>
                                <input type="email" name="buyer_email" id="edit_buyer_email" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Teléfono</label>
                                <input type="text" name="buyer_telefono" id="edit_buyer_telefono" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div class="sm:col-span-2">
                                <label class="block text-slate-700 font-semibold mb-1">Dirección (Local o Base)</label>
                                <input type="text" name="buyer_direccion" id="edit_buyer_direccion" class="w-full uppercase px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">C.P.</label>
                                <input type="text" name="buyer_cp" id="edit_buyer_cp" class="w-full font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Vendedor -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">3. Vendedor (Seller)</span>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Nombre Completo *</label>
                                <input type="text" name="seller_nombre" id="edit_seller_nombre" required class="w-full uppercase font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">DNI / NIE / Passport</label>
                                <input type="text" name="seller_dni" id="edit_seller_dni" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">FA # (Militar DOD)</label>
                                <input type="text" name="seller_fa" id="edit_seller_fa" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div class="sm:col-span-2">
                                <label class="block text-slate-700 font-semibold mb-1">Dirección Vendedor</label>
                                <input type="text" name="seller_direccion" id="edit_seller_direccion" class="w-full uppercase px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Contacto Vendedor</label>
                                <input type="text" name="seller_contacto" id="edit_seller_contacto" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Cesión de Póliza & Campo SSN Dinámico -->
                    <div class="bg-slate-100 p-3.5 rounded-xl border border-slate-300 space-y-2">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="tiene_cesion" id="edit_tiene_cesion" value="1" onchange="toggleSSNField('edit')" class="w-4 h-4 text-indigo-600 rounded cursor-pointer">
                            <label for="edit_tiene_cesion" class="cursor-pointer font-bold text-slate-800 text-xs">
                                ¿Incluye Cesión de Póliza de Seguro KFM?
                            </label>
                        </div>
                        <div id="edit_ssn_container" class="pt-2 border-t border-slate-200 grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                                <label class="block text-slate-800 font-bold mb-1">
                                    Nº Póliza a Ceder <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" name="numero_poliza" id="edit_numero_poliza" placeholder="Ej. POL-2026 / 8492" class="w-full font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg bg-white uppercase">
                                <span class="text-[9px] text-slate-500 block mt-0.5">Póliza del vendedor que se cede</span>
                            </div>
                            <div>
                                <label class="block text-slate-800 font-bold mb-1">
                                    SSN Comprador <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" name="ssn" id="edit_ssn" placeholder="***-**-4912" class="w-full font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg bg-white">
                                <span class="text-[9px] text-slate-500 block mt-0.5">SSN militar en lugar de NIE</span>
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Box / Ship (Navy DoD)</label>
                                <input type="text" name="box_ship" id="edit_box_ship" placeholder="Ej. USS OSCAR AUSTIN DDG 79" class="w-full font-mono px-3 py-1.5 border border-slate-300 rounded-lg bg-white uppercase">
                                <span class="text-[9px] text-slate-500 block mt-0.5">Destino militar o barco</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-700 font-semibold mb-1">Observaciones / Notas del Expediente</label>
                        <textarea name="observaciones" id="edit_observaciones" rows="2" placeholder="Notas internas para la gestoría..." class="w-full px-3 py-2 border border-slate-300 rounded-xl"></textarea>
                    </div>

                </div>

                <div class="bg-slate-50 px-6 py-4 flex items-center justify-between border-t border-slate-100 font-bold">
                    <button type="button" onclick="closeEditarTramiteModal()" class="text-slate-500 hover:text-slate-700 cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl shadow-md cursor-pointer">
                        Guardar Cambios
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    function openNuevoTramiteModal() {
        document.getElementById('nuevoTramiteModal').classList.remove('hidden');
        toggleSSNField('create');
    }
    function closeNuevoTramiteModal() {
        document.getElementById('nuevoTramiteModal').classList.add('hidden');
    }

    function openEditarTramiteModal(tramite) {
        document.getElementById('editarTramiteModal').classList.remove('hidden');
        document.getElementById('edit_matricula_title').textContent = tramite.matricula || 'SIN MATRÍCULA';
        
        const form = document.getElementById('editarTramiteForm');
        form.action = '/operations/gestoria/' + tramite.id;

        document.getElementById('edit_tipo').value = tramite.tipo || 'Transferencia ordinaria';
        document.getElementById('edit_sede_id').value = tramite.sede_id || '1';
        document.getElementById('edit_agente').value = tramite.agente || 'Kerry';
        document.getElementById('edit_estado').value = tramite.estado || 'PREPARADO';
        
        document.getElementById('edit_matricula').value = tramite.matricula || '';
        document.getElementById('edit_vin').value = tramite.vin || '';
        document.getElementById('edit_marca').value = tramite.marca || '';
        document.getElementById('edit_modelo').value = tramite.modelo || '';
        document.getElementById('edit_precio_venta').value = tramite.precio_venta || '1.000,00 €';
        document.getElementById('edit_precio_tramite').value = tramite.precio_tramite || '120,00 €';
        document.getElementById('edit_itp_cuota').value = tramite.itp_cuota || '84,00 €';
        document.getElementById('edit_recibo_numero').value = tramite.recibo_numero || '';
        document.getElementById('edit_fecha_tramite').value = tramite.fecha_tramite || '';

        document.getElementById('edit_buyer_nombre').value = tramite.buyer_nombre || '';
        document.getElementById('edit_buyer_dni').value = tramite.buyer_dni || '';
        document.getElementById('edit_buyer_fa').value = tramite.buyer_fa || '';
        document.getElementById('edit_buyer_dob').value = tramite.buyer_dob || '';
        document.getElementById('edit_buyer_email').value = tramite.buyer_email || '';
        document.getElementById('edit_buyer_telefono').value = tramite.buyer_telefono || '';
        document.getElementById('edit_buyer_direccion').value = tramite.buyer_direccion || '';
        document.getElementById('edit_buyer_cp').value = tramite.buyer_cp || '11520';

        document.getElementById('edit_seller_nombre').value = tramite.seller_nombre || '';
        document.getElementById('edit_seller_dni').value = tramite.seller_dni || '';
        document.getElementById('edit_seller_fa').value = tramite.seller_fa || '';
        document.getElementById('edit_seller_direccion').value = tramite.seller_direccion || '';
        document.getElementById('edit_seller_contacto').value = tramite.seller_contacto || '';

        const hasCesion = tramite.tiene_cesion || tramite.tipo === 'Cesión de póliza';
        document.getElementById('edit_tiene_cesion').checked = hasCesion;
        document.getElementById('edit_numero_poliza').value = tramite.numero_poliza || (tramite.policy ? tramite.policy.policy_number : '');
        document.getElementById('edit_ssn').value = tramite.ssn || '';
        document.getElementById('edit_box_ship').value = tramite.box_ship || '';
        document.getElementById('edit_observaciones').value = tramite.observaciones || '';

        toggleSSNField('edit');
    }

    function closeEditarTramiteModal() {
        document.getElementById('editarTramiteModal').classList.add('hidden');
    }

    function toggleSSNField(prefix) {
        const checkbox = document.getElementById(prefix + '_tiene_cesion');
        const tipoSelect = document.getElementById(prefix + '_tipo');
        const container = document.getElementById(prefix + '_ssn_container');
        
        const isCesion = (checkbox && checkbox.checked) || (tipoSelect && tipoSelect.value === 'Cesión de póliza');
        if (container) {
            if (isCesion) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }
    }

    // Inicializar listeners al cargar
    document.addEventListener('DOMContentLoaded', function() {
        toggleSSNField('create');
    });
</script>
@endsection
