@extends('layouts.app')

@section('title', ($tab === 'multas' ? 'Recursos de Multas ARAG' : 'Siniestros de Tráfico') . ' — KFM Insurance')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $tab === 'multas' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-rose-100 text-rose-800 border border-rose-200' }}">
                    {{ $tab === 'multas' ? 'Defensa Jurídica ARAG' : 'Gestión de Partes & Peritos' }}
                </span>
                <span class="text-xs text-slate-400">Excel {{ $tab === 'multas' ? 'MULTAS' : 'SINIESTROS' }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 font-display mt-1">
                {{ $tab === 'multas' ? 'Recursos de Multas de Tráfico (ARAG)' : 'Siniestros de Tráfico' }}
            </h1>
            <p class="text-xs text-slate-500">
                {{ $tab === 'multas' ? 'Gestión y tramitación de sanciones de tráfico con la defensa jurídica de ARAG' : 'Partes de accidentes, peritaciones de daños y tramitación de expedientes' }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            @if($tab === 'multas')
                <button type="button" onclick="openNuevoSiniestroModal('Multa')"
                        class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-1.5 cursor-pointer">
                    <span>+ Nuevo Recurso de Multa</span>
                </button>
                <a href="{{ route('operations.siniestros.index') }}" 
                   class="px-3.5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-xl text-xs font-bold transition">
                    Ver Siniestros →
                </a>
            @else
                <button type="button" onclick="openNuevoSiniestroModal('Siniestro')"
                        class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-1.5 cursor-pointer">
                    <span>+ Nuevo Siniestro</span>
                </button>
                <a href="{{ route('operations.multas.index') }}" 
                   class="px-3.5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-xl text-xs font-bold transition">
                    Ver Multas →
                </a>
            @endif
        </div>
    </div>

    <!-- Alerta de Seguimiento Automático (+5 días desde Cliente Avisado) -->
    @if(($stats['siniestros_reaviso'] ?? 0) > 0 && $tab !== 'multas')
        <div class="bg-gradient-to-r from-rose-600 via-rose-700 to-amber-600 text-white p-4 rounded-2xl shadow-lg border border-rose-400 flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-pulse">
            <div class="flex items-start sm:items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-2xl flex-shrink-0">
                    ⚠️
                </div>
                <div>
                    <h3 class="font-bold text-sm text-white font-display">
                        ¡Atención! Hay {{ $stats['siniestros_reaviso'] }} siniestro(s) pendiente(s) de reavisar al cliente
                    </h3>
                    <p class="text-xs text-rose-100">
                        Estado Abierto con fecha de reclamación y más de 5 días transcurridos desde la fecha en que se avisó al cliente sin respuesta.
                    </p>
                </div>
            </div>
            <div class="flex-shrink-0">
                @if(request('filter') === 'reaviso')
                    <a href="{{ route('operations.siniestros.index') }}" 
                       class="px-4 py-2 bg-white text-rose-800 hover:bg-rose-50 rounded-xl text-xs font-bold shadow-md transition inline-block">
                        Mostrar Todos los Siniestros
                    </a>
                @else
                    <a href="{{ route('operations.siniestros.index', ['filter' => 'reaviso']) }}" 
                       class="px-4 py-2 bg-white text-rose-800 hover:bg-rose-50 rounded-xl text-xs font-bold shadow-md transition inline-block">
                        Filtrar Pendientes de Reaviso ({{ $stats['siniestros_reaviso'] }}) →
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- Navigation Tabs: Siniestros vs Multas -->
    <div class="flex border-b border-slate-200 space-x-6 text-sm font-bold">
        <a href="{{ route('operations.siniestros.index') }}"
           class="pb-3 border-b-2 flex items-center gap-2 {{ $tab !== 'multas' ? 'border-rose-600 text-rose-700' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
            <span>💥 Siniestros de Tráfico (Patria / ARAG)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">{{ $stats['siniestros_abiertos'] }} abiertos</span>
            @if(($stats['siniestros_reaviso'] ?? 0) > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-600 text-white animate-pulse">
                    ⚠️ {{ $stats['siniestros_reaviso'] }} reavisar
                </span>
            @endif
        </a>

        <a href="{{ route('operations.multas.index') }}"
           class="pb-3 border-b-2 flex items-center gap-2 {{ $tab === 'multas' ? 'border-amber-600 text-amber-700' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
            <span>⚖️ Recursos de Multas (ARAG Defensa)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">{{ $stats['multas_activas'] }} en trámite</span>
        </a>
    </div>

    <!-- Table of Siniestros / Multas -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">Póliza / Matrícula</th>
                        <th class="py-3.5 px-4">Asegurado (Titular)</th>
                        @if($tab !== 'multas')
                            <th class="py-3.5 px-4">Ref. Patria / ARAG</th>
                            <th class="py-3.5 px-4">Tramitador</th>
                            <th class="py-3.5 px-4">Reclamación / Cliente Avisado</th>
                            <th class="py-3.5 px-4">Estado Siniestro</th>
                        @else
                            <th class="py-3.5 px-4">Fecha Infracción</th>
                            <th class="py-3.5 px-4">Envío a ARAG</th>
                            <th class="py-3.5 px-4">Estado Recurso</th>
                        @endif
                        <th class="py-3.5 px-4">Observaciones & Taller</th>
                        <th class="py-3.5 px-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($items as $item)
                        <tr class="{{ $item->requiere_reaviso ? 'bg-rose-50/70 border-l-4 border-l-rose-600 hover:bg-rose-100/60' : 'hover:bg-slate-50/80' }} transition">
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-bold text-sm text-slate-900 block">#{{ $item->numero_poliza }}</span>
                                <span class="font-mono text-xs font-semibold bg-slate-100 px-1.5 py-0.5 rounded text-slate-700">
                                    {{ $item->matricula }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($item->client_id)
                                    <a href="{{ route('clients.show', $item->client_id) }}" class="font-bold text-sky-800 hover:text-sky-900 hover:underline block">
                                        {{ $item->client_name }}
                                    </a>
                                @else
                                    <strong class="text-slate-900 block">{{ $item->client_name }}</strong>
                                @endif
                                <span class="text-[10px] text-slate-400">Aseguradora: <strong class="text-slate-700">{{ $item->cartera }}</strong> • {{ $item->office }}</span>
                            </td>

                            @if($tab !== 'multas')
                                <td class="py-3.5 px-4">
                                    @if($item->referencia_patria)
                                        <div class="font-mono font-bold text-rose-900 bg-rose-50 px-2 py-0.5 rounded border border-rose-200 inline-block text-xs">
                                            Patria: {{ $item->referencia_patria }}
                                        </div>
                                    @elseif($item->referencia_siniestro)
                                        <div class="font-mono font-bold text-rose-900 bg-rose-50 px-2 py-0.5 rounded border border-rose-200 inline-block text-xs">
                                            Patria: {{ $item->referencia_siniestro }}
                                        </div>
                                    @endif
                                    @if($item->referencia_arag)
                                        <div class="font-mono font-bold text-amber-900 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 inline-block text-[11px] mt-0.5">
                                            ARAG: {{ $item->referencia_arag }}
                                        </div>
                                    @endif
                                    @if(!$item->referencia_patria && !$item->referencia_siniestro && !$item->referencia_arag)
                                        <span class="text-slate-400 text-xs italic">Pendiente Ref.</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-slate-800 font-semibold block">{{ $item->tramitador ?: ($item->tramitadora ?: 'Diego') }}</span>
                                    @if($item->fecha_email_diego)
                                        <span class="text-[10px] text-slate-400">Email: {{ $item->fecha_email_diego }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($item->fecha_reclamacion_recibida)
                                        <div class="text-[11px] text-slate-700">
                                            Recibida: <strong class="font-mono font-bold text-slate-900">{{ $item->fecha_reclamacion_recibida }}</strong>
                                        </div>
                                    @endif
                                    @if($item->fecha_cliente_avisado)
                                        <div class="text-[11px] text-slate-700 mt-0.5">
                                            Avisado: <strong class="font-mono font-bold text-slate-900">{{ $item->fecha_cliente_avisado }}</strong>
                                            @if($item->dias_desde_aviso !== null)
                                                <span class="text-[10px] text-slate-500 font-semibold">({{ $item->dias_desde_aviso }} días)</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if(!$item->fecha_reclamacion_recibida && !$item->fecha_cliente_avisado)
                                        <span class="text-slate-400 text-xs">—</span>
                                    @endif

                                    @if($item->requiere_reaviso)
                                        <div class="mt-1 flex flex-col items-start gap-1">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-600 text-white shadow-xs">
                                                <span>⚠️</span>
                                                <span>REAVISAR CLIENTE (+{{ $item->dias_desde_aviso }}d)</span>
                                            </span>
                                            @if($item->client && $item->client->telefono)
                                                @php
                                                    $telLimpio = preg_replace('/[^0-9]/', '', $item->client->telefono);
                                                    if (strlen($telLimpio) === 9) $telLimpio = '34' . $telLimpio;
                                                    
                                                    $fAvisado = $item->fecha_cliente_avisado ?: date('d/m/Y');
                                                    $fAvisadoFormat = $fAvisado;
                                                    if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($fAvisado), $m)) {
                                                        // Formatear en estándar US (MM/DD/YYYY) para el personal naval US Navy
                                                        $fAvisadoFormat = sprintf('%02d/%02d/%04d', $m[2], $m[1], $m[3]);
                                                    }
                                                    
                                                    $msgWaText = "Hello " . strtoupper($item->client_name) . ", we are contacting you from KFM Insurance regarding your claim (Policy #" . ($item->numero_poliza ?: '-') . ", License Plate " . ($item->matricula ?: '-') . "). We notified you on " . $fAvisadoFormat . " and are waiting for your response to continue the process. If you don't provide your incident version by today, we will have to accept fault. Thank you very much.";
                                                    $msgWa = rawurlencode($msgWaText);
                                                @endphp
                                                <a href="https://wa.me/{{ $telLimpio }}?text={{ $msgWa }}" target="_blank" rel="noopener noreferrer"
                                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition"
                                                   title="Enviar aviso por WhatsApp en inglés al cliente">
                                                    <span>📲 Enviar WhatsApp</span>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $item->status_badge_color }}">
                                        {{ $item->estado_siniestro }}
                                    </span>
                                </td>
                            @else
                                <td class="py-3.5 px-4">
                                    <span class="font-mono text-slate-700 font-semibold">{{ $item->ticket_date ?: '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-mono text-amber-800 font-bold">{{ $item->date_sent_arag ?: 'Pendiente' }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $item->status_badge_color }}">
                                        {{ $item->estado_multa }}
                                    </span>
                                </td>
                            @endif

                            <td class="py-3.5 px-4">
                                @if($item->observaciones)
                                    <div class="text-xs bg-amber-50 text-amber-950 border border-amber-200 rounded px-2.5 py-1 flex items-start gap-1">
                                        <span class="font-bold text-amber-800 flex-shrink-0">📝</span>
                                        <span class="text-slate-800 leading-snug">{{ $item->observaciones }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                @if($item->client_id)
                                    <a href="{{ route('clients.show', $item->client_id) }}" class="px-2.5 py-1 bg-sky-50 hover:bg-sky-100 border border-sky-200 text-sky-800 rounded-lg text-xs font-semibold mr-1 inline-block">
                                        Ficha Cliente →
                                    </a>
                                @endif
                                <button type="button" onclick="openEditarSiniestroModal({{ $item->id }}, '{{ $item->tipo }}', '{{ $item->estado_siniestro }}', '{{ $item->estado_multa }}', '{{ addslashes($item->referencia_patria ?? '') }}', '{{ addslashes($item->referencia_arag ?? '') }}', '{{ addslashes($item->tramitador ?? ($item->tramitadora ?? '')) }}', '{{ addslashes($item->fecha_reclamacion_recibida ?? '') }}', '{{ addslashes($item->fecha_cliente_avisado ?? '') }}', '{{ addslashes($item->observaciones ?? '') }}')"
                                        class="px-2.5 py-1 bg-white hover:bg-slate-100 border border-slate-300 rounded-lg text-slate-700 font-semibold text-xs shadow-xs transition cursor-pointer">
                                    Actualizar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                No hay registros en esta sección
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal: + Nuevo Siniestro o Multa -->
<div id="nuevoSiniestroModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeNuevoSiniestroModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full border border-slate-200 overflow-hidden">
            <div id="modalSiniestroHeader" class="bg-rose-700 text-white p-5 flex items-center justify-between">
                <h3 id="modalSiniestroTitle" class="font-bold text-base">Nuevo Siniestro de Tráfico</h3>
                <button type="button" onclick="closeNuevoSiniestroModal()" class="text-white/80 hover:text-white">✕</button>
            </div>
            <form method="POST" action="{{ route('operations.siniestros.store') }}">
                @csrf
                <input type="hidden" name="tipo" id="input_tipo" value="Siniestro">
                <div class="p-6 space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Nº Póliza *</label>
                            <input type="text" name="numero_poliza" id="sin_numero_poliza" required oninput="lookupPolicyForSiniestro(this.value)" placeholder="1839101" class="w-full font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Matrícula Vehículo *</label>
                            <input type="text" name="matricula" id="sin_matricula" required placeholder="7192LMB" class="w-full uppercase font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    <div id="sin_lookup_pill" class="hidden text-xs bg-emerald-50 text-emerald-900 border border-emerald-300 rounded-lg px-3 py-1.5 font-medium"></div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Asegurado (Titular) *</label>
                        <input type="text" name="client_name" id="sin_client_name" required placeholder="PO1 ROBERT DAVIS" class="w-full uppercase font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Oficina</label>
                            <select name="office" id="sin_office" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-semibold">
                                <option value="NEX">NEX (Base Naval)</option>
                                <option value="Central">Central (Pueblo)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Aseguradora</label>
                            <select name="cartera" id="sin_cartera" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-bold">
                                <option value="81 (Patria 81)">81 (Patria 81)</option>
                                <option value="87 (GEICO 87)">87 (GEICO 87)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Campos de Siniestro -->
                    <div id="fieldsSiniestro" class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Referencia Patria</label>
                                <input type="text" name="referencia_patria" placeholder="SIN-PAT-84192/26" class="w-full font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Referencia ARAG</label>
                                <input type="text" name="referencia_arag" placeholder="ARAG-9281/26" class="w-full font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Tramitador</label>
                                <input type="text" name="tramitador" value="Diego" placeholder="Diego" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-medium">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Fecha Email a Tramitador</label>
                                <input type="text" name="fecha_email_diego" value="{{ date('d/m/Y') }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Reclamación Recibida (Fecha)</label>
                                <input type="text" name="fecha_reclamacion_recibida" value="{{ date('d/m/Y') }}" placeholder="dd/mm/aaaa" class="w-full font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Cliente Avisado (Fecha)</label>
                                <input type="text" name="fecha_cliente_avisado" placeholder="dd/mm/aaaa" class="w-full font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Estado</label>
                            <select name="estado_siniestro" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-bold">
                                <option value="Abierto" selected>Abierto</option>
                                <option value="Parte enviado">Parte enviado</option>
                                <option value="Declaracion de Inexistencia">Declaracion de Inexistencia</option>
                                <option value="Peritacion">Peritacion</option>
                                <option value="Cerrado">Cerrado</option>
                            </select>
                        </div>
                    </div>

                    <!-- Campos de Multa -->
                    <div id="fieldsMulta" class="hidden space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Fecha Infracción (Ticket Date)</label>
                                <input type="text" name="ticket_date" placeholder="dd/mm/aaaa" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Fecha Notificación Cliente</label>
                                <input type="text" name="date_client_notified" value="{{ date('d/m/Y') }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Fecha Envío a ARAG</label>
                                <input type="text" name="date_sent_arag" value="{{ date('d/m/Y') }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Estado Recurso ARAG</label>
                                <select name="estado_multa" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-bold">
                                    <option value="En Trámite ARAG">En Trámite ARAG</option>
                                    <option value="Estimada">Estimada (Anulada) ✓</option>
                                    <option value="Pagada">Pagada con Bonificación</option>
                                    <option value="Archivada">Archivada</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Observaciones / Taller Concertado</label>
                        <textarea name="observaciones" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg" placeholder="Detalles de la colisión, taller concertado, fotograma radar..."></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-between border-t border-slate-200">
                    <button type="button" onclick="closeNuevoSiniestroModal()" class="text-slate-500 font-bold">Cancelar</button>
                    <button type="submit" id="btnSubmitSiniestro" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Editar Siniestro / Multa -->
<div id="editarSiniestroModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeEditarSiniestroModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden">
            <div class="bg-slate-900 text-white p-5 flex items-center justify-between">
                <h3 class="font-bold text-base">Actualizar Expediente</h3>
                <button type="button" onclick="closeEditarSiniestroModal()" class="text-white/80 hover:text-white">✕</button>
            </div>
            <form id="editarSiniestroForm" method="POST" action="">
                @csrf @method('PATCH')
                <div class="p-6 space-y-3 text-xs">
                    <div id="editFieldsSiniestro" class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Referencia Patria</label>
                                <input type="text" name="referencia_patria" id="edit_referencia_patria" class="w-full font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Referencia ARAG</label>
                                <input type="text" name="referencia_arag" id="edit_referencia_arag" class="w-full font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Tramitador</label>
                                <input type="text" name="tramitador" id="edit_tramitador" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-medium">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Estado</label>
                                <select name="estado_siniestro" id="edit_estado_siniestro" class="w-full px-3 py-1.5 border border-slate-300 rounded-xl font-bold">
                                    <option value="Abierto">Abierto</option>
                                    <option value="Parte enviado">Parte enviado</option>
                                    <option value="Declaracion de Inexistencia">Declaracion de Inexistencia</option>
                                    <option value="Peritacion">Peritacion</option>
                                    <option value="Cerrado">Cerrado ✓</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Reclamación Recibida</label>
                                <input type="text" name="fecha_reclamacion_recibida" id="edit_fecha_reclamacion_recibida" placeholder="dd/mm/aaaa" class="w-full font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Cliente Avisado</label>
                                <input type="text" name="fecha_cliente_avisado" id="edit_fecha_cliente_avisado" placeholder="dd/mm/aaaa" class="w-full font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                    </div>
                    <div id="editFieldsMulta" class="hidden">
                        <label class="block text-slate-700 font-bold mb-1">Estado Recurso ARAG</label>
                        <select name="estado_multa" id="edit_estado_multa" class="w-full px-3 py-2 border border-slate-300 rounded-xl font-bold">
                            <option value="En Trámite ARAG">En Trámite ARAG</option>
                            <option value="Estimada">Estimada (Anulada) ✓</option>
                            <option value="Pagada">Pagada</option>
                            <option value="Archivada">Archivada</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Observaciones</label>
                        <textarea name="observaciones" id="edit_sin_obs" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-xl"></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-between border-t border-slate-200">
                    <button type="button" onclick="closeEditarSiniestroModal()" class="text-slate-500 font-bold">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-slate-900 text-white font-bold rounded-xl">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let lookupSinTimer = null;
    function lookupPolicyForSiniestro(val) {
        clearTimeout(lookupSinTimer);
        const pill = document.getElementById('sin_lookup_pill');
        if (!val || val.trim().length < 2) {
            if (pill) pill.classList.add('hidden');
            return;
        }
        lookupSinTimer = setTimeout(() => {
            fetch(`/operations/extornos/lookup-policy?numero_poliza=${encodeURIComponent(val.trim())}`)
                .then(r => r.json())
                .then(data => {
                    if (data.found) {
                        if (data.matricula) document.getElementById('sin_matricula').value = data.matricula;
                        if (data.client_name) document.getElementById('sin_client_name').value = data.client_name;
                        if (data.office) document.getElementById('sin_office').value = data.office;
                        if (data.cartera) {
                            const cVal = (data.cartera === '87' || data.cartera.includes('87') || data.cartera.includes('GEICO')) ? '87 (GEICO 87)' : '81 (Patria 81)';
                            document.getElementById('sin_cartera').value = cVal;
                        }
                        if (pill) {
                            pill.innerHTML = `✓ Cliente: <strong>${data.client_name}</strong> · ${data.ramo} ${data.vehiculo ? `(${data.vehiculo})` : ''} · Cartera: ${data.cartera}`;
                            pill.classList.remove('hidden');
                        }
                    } else {
                        if (pill) pill.classList.add('hidden');
                    }
                })
                .catch(err => console.error(err));
        }, 200);
    }

    function openNuevoSiniestroModal(tipo) {
        document.getElementById('input_tipo').value = tipo;
        if (tipo === 'Multa') {
            document.getElementById('modalSiniestroTitle').textContent = 'Nuevo Recurso de Multa (ARAG)';
            document.getElementById('modalSiniestroHeader').className = 'bg-amber-600 text-white p-5 flex items-center justify-between';
            document.getElementById('btnSubmitSiniestro').className = 'px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl';
            document.getElementById('fieldsSiniestro').classList.add('hidden');
            document.getElementById('fieldsMulta').classList.remove('hidden');
        } else {
            document.getElementById('modalSiniestroTitle').textContent = 'Nuevo Siniestro de Tráfico';
            document.getElementById('modalSiniestroHeader').className = 'bg-rose-700 text-white p-5 flex items-center justify-between';
            document.getElementById('btnSubmitSiniestro').className = 'px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl';
            document.getElementById('fieldsSiniestro').classList.remove('hidden');
            document.getElementById('fieldsMulta').classList.add('hidden');
        }
        document.getElementById('nuevoSiniestroModal').classList.remove('hidden');
    }
    function closeNuevoSiniestroModal() {
        document.getElementById('nuevoSiniestroModal').classList.add('hidden');
    }
    function openEditarSiniestroModal(id, tipo, estadoSin, estadoMul, refPatria, refArag, tramitador, fReclamacion, fAvisado, obs) {
        document.getElementById('editarSiniestroForm').action = "/operations/siniestros/" + id;
        if (tipo === 'Multa') {
            document.getElementById('editFieldsSiniestro').classList.add('hidden');
            document.getElementById('editFieldsMulta').classList.remove('hidden');
            document.getElementById('edit_estado_multa').value = estadoMul;
        } else {
            document.getElementById('editFieldsSiniestro').classList.remove('hidden');
            document.getElementById('editFieldsMulta').classList.add('hidden');
            document.getElementById('edit_estado_siniestro').value = estadoSin;
            document.getElementById('edit_referencia_patria').value = refPatria || '';
            document.getElementById('edit_referencia_arag').value = refArag || '';
            document.getElementById('edit_tramitador').value = tramitador || 'Diego';
            document.getElementById('edit_fecha_reclamacion_recibida').value = fReclamacion || '';
            document.getElementById('edit_fecha_cliente_avisado').value = fAvisado || '';
        }
        document.getElementById('edit_sin_obs').value = obs;
        document.getElementById('editarSiniestroModal').classList.remove('hidden');
    }
    function closeEditarSiniestroModal() {
        document.getElementById('editarSiniestroModal').classList.add('hidden');
    }
</script>
@endsection
