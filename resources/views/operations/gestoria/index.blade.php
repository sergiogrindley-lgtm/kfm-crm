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
                <span class="text-xs text-slate-400">Excel GESTORIA + PORTADA + CONTRATOS</span>
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

    <!-- Pipeline KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 text-xs">
        <a href="{{ route('operations.gestoria.index') }}" 
           class="p-3 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-slate-400 transition {{ !request('estado') ? 'ring-2 ring-indigo-500/30' : '' }}">
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Trámites</span>
            <span class="text-xl font-black text-slate-800">{{ $stats['total'] }}</span>
            <span class="text-[10px] text-slate-500 block mt-0.5">Cartera global</span>
        </a>

        <a href="{{ route('operations.gestoria.index', ['estado' => 'DOCUMENTOS']) }}" 
           class="p-3 rounded-2xl bg-amber-50/60 border border-amber-200 shadow-xs hover:bg-amber-50 transition {{ request('estado') === 'DOCUMENTOS' ? 'ring-2 ring-amber-500' : '' }}">
            <span class="text-[10px] uppercase font-bold text-amber-700 block">1. Documentación</span>
            <span class="text-xl font-black text-amber-900">{{ $stats['documentos'] }}</span>
            <span class="text-[10px] text-amber-700/80 block mt-0.5">Recopilando papeles</span>
        </a>

        <a href="{{ route('operations.gestoria.index', ['estado' => 'OUT_GESTORIA']) }}" 
           class="p-3 rounded-2xl bg-indigo-50/60 border border-indigo-200 shadow-xs hover:bg-indigo-50 transition {{ request('estado') === 'OUT_GESTORIA' ? 'ring-2 ring-indigo-500' : '' }}">
            <span class="text-[10px] uppercase font-bold text-indigo-700 block">2. Out Gestoría</span>
            <span class="text-xl font-black text-indigo-900">{{ $stats['out_gestoria'] }}</span>
            <span class="text-[10px] text-indigo-700/80 block mt-0.5">Enviado a Bahía</span>
        </a>

        <a href="{{ route('operations.gestoria.index', ['estado' => 'BACK_AVISADO']) }}" 
           class="p-3 rounded-2xl bg-cyan-50/60 border border-cyan-200 shadow-xs hover:bg-cyan-50 transition {{ request('estado') === 'BACK_AVISADO' ? 'ring-2 ring-cyan-500' : '' }}">
            <span class="text-[10px] uppercase font-bold text-cyan-700 block">3. Back / Avisado</span>
            <span class="text-xl font-black text-cyan-900">{{ $stats['back_avisado'] }}</span>
            <span class="text-[10px] text-cyan-700/80 block mt-0.5">Cliente avisado</span>
        </a>

        <a href="{{ route('operations.gestoria.index', ['estado' => 'LIQUIDADO']) }}" 
           class="p-3 rounded-2xl bg-blue-50/60 border border-blue-200 shadow-xs hover:bg-blue-50 transition {{ request('estado') === 'LIQUIDADO' ? 'ring-2 ring-blue-500' : '' }}">
            <span class="text-[10px] uppercase font-bold text-blue-700 block">4. Liquidado</span>
            <span class="text-xl font-black text-blue-900">{{ $stats['liquidado'] }}</span>
            <span class="text-[10px] text-blue-700/80 block mt-0.5">Tasas & ITP abonados</span>
        </a>

        <a href="{{ route('operations.gestoria.index', ['estado' => 'RECOGIDO']) }}" 
           class="p-3 rounded-2xl bg-emerald-50/60 border border-emerald-200 shadow-xs hover:bg-emerald-50 transition {{ request('estado') === 'RECOGIDO' ? 'ring-2 ring-emerald-500' : '' }}">
            <span class="text-[10px] uppercase font-bold text-emerald-700 block">5. Recogido</span>
            <span class="text-xl font-black text-emerald-900">{{ $stats['recogido'] }}</span>
            <span class="text-[10px] text-emerald-700/80 block mt-0.5">Entregado al cliente</span>
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-3 text-xs">
        <form method="GET" action="{{ route('operations.gestoria.index') }}" class="flex-1 flex items-center gap-2 max-w-md">
            @if(request('estado'))
                <input type="hidden" name="estado" value="{{ request('estado') }}">
            @endif
            <div class="relative w-full">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Buscar por matrícula, bastidor, comprador, vendedor..."
                       class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-900 transition">
                Filtrar
            </button>
            @if(request('q') || request('estado'))
                <a href="{{ route('operations.gestoria.index') }}" class="text-slate-400 hover:text-slate-600 px-2 py-1">
                    Limpiar
                </a>
            @endif
        </form>

        <div class="text-[11px] text-slate-400">
            Mostrando <strong>{{ $tramites->total() }}</strong> expedientes de transferencia
        </div>
    </div>

    <!-- Table of Gestoría Expedientes -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">Vehículo / Bastidor</th>
                        <th class="py-3.5 px-4">Comprador (Buyer)</th>
                        <th class="py-3.5 px-4">Vendedor (Seller)</th>
                        <th class="py-3.5 px-4">Importes & ITP</th>
                        <th class="py-3.5 px-4">Estado Gestoría</th>
                        <th class="py-3.5 px-4 text-right">Documentos Oficiales</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($tramites as $tramite)
                        <tr class="hover:bg-slate-50/80 transition group">
                            
                            <!-- Vehículo -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-sm bg-slate-900 text-white px-2 py-0.5 rounded shadow-xs">
                                        {{ $tramite->matricula }}
                                    </span>
                                    @if($tramite->tiene_cesion)
                                        <span class="text-[9px] bg-emerald-100 text-emerald-800 border border-emerald-300 px-1.5 py-0.5 rounded font-bold" title="Cesión de póliza incluida">
                                            + Cesión
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs font-bold text-slate-800 mt-0.5">
                                    {{ $tramite->marca }} {{ $tramite->modelo }}
                                </div>
                                <div class="text-[10px] font-mono text-slate-400 truncate max-w-[180px]" title="{{ $tramite->vin }}">
                                    VIN: {{ $tramite->vin ?: 'No especificado' }}
                                </div>
                            </td>

                            <!-- Comprador -->
                            <td class="py-3 px-4">
                                <strong class="text-slate-900 block">{{ $tramite->buyer_nombre }}</strong>
                                <span class="text-slate-500 font-mono text-[11px] block">
                                    {{ $tramite->buyer_dni }} @if($tramite->buyer_fa) • <span class="text-indigo-600 font-semibold">{{ $tramite->buyer_fa }}</span> @endif
                                </span>
                                <span class="text-[10px] text-slate-400 block truncate max-w-[180px]">
                                    {{ $tramite->buyer_telefono }} • {{ $tramite->buyer_direccion }}
                                </span>
                            </td>

                            <!-- Vendedor -->
                            <td class="py-3 px-4">
                                <span class="text-slate-800 block font-semibold">{{ $tramite->seller_nombre }}</span>
                                <span class="text-slate-500 font-mono text-[11px] block">
                                    {{ $tramite->seller_dni }} @if($tramite->seller_fa) • <span class="text-slate-600">{{ $tramite->seller_fa }}</span> @endif
                                </span>
                                <span class="text-[10px] text-slate-400 block truncate max-w-[180px]">
                                    {{ $tramite->seller_contacto }}
                                </span>
                            </td>

                            <!-- Importes & Trámite -->
                            <td class="py-3 px-4">
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
                            </td>

                            <!-- Estado & Pipeline Switcher -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[11px] font-bold border {{ $tramite->status_badge_color }}">
                                        {{ $tramite->status_label }}
                                    </span>
                                </div>
                                
                                <!-- Acciones Rápidas de Cambio de Estado -->
                                <div class="mt-2 flex flex-wrap items-center gap-1">
                                    @if($tramite->estado === 'DOCUMENTOS')
                                        <form method="POST" action="{{ route('operations.gestoria.status', $tramite) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="estado" value="OUT_GESTORIA">
                                            <button type="submit" class="px-2 py-0.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded border border-indigo-200 text-[10px] font-bold cursor-pointer">
                                                ➔ Enviar a Gestoría
                                            </button>
                                        </form>
                                    @elseif($tramite->estado === 'OUT_GESTORIA')
                                        <form method="POST" action="{{ route('operations.gestoria.status', $tramite) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="estado" value="BACK_AVISADO">
                                            <button type="submit" class="px-2 py-0.5 bg-cyan-50 hover:bg-cyan-100 text-cyan-800 rounded border border-cyan-200 text-[10px] font-bold cursor-pointer">
                                                ➔ Recibido / Avisar Cliente
                                            </button>
                                        </form>
                                    @elseif($tramite->estado === 'BACK_AVISADO')
                                        <form method="POST" action="{{ route('operations.gestoria.status', $tramite) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="estado" value="RECOGIDO">
                                            <button type="submit" class="px-2 py-0.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 rounded border border-emerald-200 text-[10px] font-bold cursor-pointer">
                                                ➔ Marcar Recogido
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-slate-400">Completado ✓</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Botones Documentos Oficiales -->
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    <!-- Portada Expediente DGT -->
                                    <a href="{{ route('operations.gestoria.portada', $tramite) }}" target="_blank"
                                       class="px-2.5 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-slate-700 font-bold text-[11px] shadow-xs flex items-center gap-1"
                                       title="Portada oficial para entregar a la Gestoría con checklist">
                                        <span>📁 Portada</span>
                                    </a>

                                    <!-- Contrato Compraventa 3x -->
                                    <a href="{{ route('operations.gestoria.contrato-compraventa', $tramite) }}" target="_blank"
                                       class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg text-indigo-700 font-bold text-[11px] shadow-xs flex items-center gap-1"
                                       title="Contrato legal de compraventa oficial">
                                        <span>📝 Contrato</span>
                                    </a>

                                    <!-- Mandato Comprador -->
                                    <a href="{{ route('operations.gestoria.mandato-comprador', $tramite) }}" target="_blank"
                                       class="px-2 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-slate-600 font-medium text-[10px]"
                                       title="Mandato DGT Comprador">
                                        <span>M. Comprador</span>
                                    </a>

                                    <!-- Mandato Vendedor -->
                                    <a href="{{ route('operations.gestoria.mandato-vendedor', $tramite) }}" target="_blank"
                                       class="px-2 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-slate-600 font-medium text-[10px]"
                                       title="Mandato DGT Vendedor">
                                        <span>M. Vendedor</span>
                                    </a>

                                    <!-- Extravío Vendedor -->
                                    <a href="{{ route('operations.gestoria.extravio-vendedor', $tramite) }}" target="_blank"
                                       class="px-2 py-1 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg text-amber-800 font-medium text-[10px]"
                                       title="Declaración jurada de extravío DGT">
                                        <span>Extravío</span>
                                    </a>

                                    <!-- Cesión de Póliza -->
                                    @if($tramite->tiene_cesion)
                                        <a href="{{ route('operations.gestoria.cesion', $tramite) }}" target="_blank"
                                           class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg text-emerald-800 font-bold text-[10px]"
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

<!-- Modal: + Nuevo Expediente Gestoría DGT -->
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
                    
                    <!-- Oficina & Agente -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Tipo de Trámite *</label>
                            <select name="tipo" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="Transferencia">Transferencia Ordinaria (Compraventa)</option>
                                <option value="Baja Definitiva">Baja Definitiva por Exportación / Desguace</option>
                                <option value="Matriculación">Matriculación Turística / Ordinaria</option>
                                <option value="Extravío Documentación">Duplicado por Extravío</option>
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
                            <label class="block text-slate-700 font-bold mb-1">Agente Tramitador</label>
                            <select name="agente" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="Chari">Chari (NEX)</option>
                                <option value="Kerry">Kerry</option>
                                <option value="Coral">Coral</option>
                                <option value="Conchi">Conchi</option>
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
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Precio Venta Declarado</label>
                                <input type="text" name="precio_venta" value="1.000,00 €" class="w-full font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Precio Trámite Gestoría</label>
                                <input type="text" name="precio_tramite" value="120,00 €" class="w-full font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Cuota ITP Junta (4%)</label>
                                <input type="text" name="itp_cuota" value="84,00 €" class="w-full font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
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
                                <label class="block text-slate-700 font-semibold mb-1">DNI / NIE / Passport *</label>
                                <input type="text" name="buyer_dni" required placeholder="Z4192048E" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
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
                                <input type="text" name="buyer_direccion" value="LU BASE NAVAL DE ROTA 999" class="w-full uppercase px-3 py-1.5 border border-slate-300 rounded-lg">
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
                                <label class="block text-slate-700 font-semibold mb-1">DNI / NIE / Passport *</label>
                                <input type="text" name="seller_dni" required placeholder="Z1928374P" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">FA # (Militar DOD)</label>
                                <input type="text" name="seller_fa" placeholder="FA-84912" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div class="sm:col-span-2">
                                <label class="block text-slate-700 font-semibold mb-1">Dirección Vendedor</label>
                                <input type="text" name="seller_direccion" value="C/ SAN JUAN DE PUERTO RICO 12, ROTA" class="w-full uppercase px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-semibold mb-1">Contacto Vendedor</label>
                                <input type="text" name="seller_contacto" placeholder="671-234567" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Check Cesión & Notas -->
                    <div class="flex items-center gap-4 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                            <input type="checkbox" name="tiene_cesion" value="1" checked class="w-4 h-4 text-indigo-600 rounded">
                            <span>¿Incluye Cesión de Póliza de Seguro KFM?</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-slate-700 font-semibold mb-1">Observaciones / Notas del Expediente</label>
                        <textarea name="observaciones" rows="2" placeholder="Notas internas para la gestoría o recordatorios para el cliente..." class="w-full px-3 py-2 border border-slate-300 rounded-xl"></textarea>
                    </div>

                </div>

                <div class="bg-slate-50 px-6 py-4 flex items-center justify-between border-t border-slate-100 font-bold">
                    <button type="button" onclick="closeNuevoTramiteModal()" class="text-slate-500 hover:text-slate-700">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md">
                        Guardar Expediente
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    function openNuevoTramiteModal() {
        document.getElementById('nuevoTramiteModal').classList.remove('hidden');
    }
    function closeNuevoTramiteModal() {
        document.getElementById('nuevoTramiteModal').classList.add('hidden');
    }
</script>
@endsection
