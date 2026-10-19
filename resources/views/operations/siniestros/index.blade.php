@extends('layouts.app')

@section('title', 'Siniestros & Multas ARAG — KFM Insurance')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-200">
                    Gestión de Partes & Recursos
                </span>
                <span class="text-xs text-slate-400">Excel SINIESTROS + MULTAS</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 font-display mt-1">Siniestros de Tráfico & Recursos de Multas (ARAG)</h1>
            <p class="text-xs text-slate-500">Tramitación con peritos/Diego y gestión de sanciones de tráfico con la defensa jurídica de ARAG</p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="openNuevoSiniestroModal('Siniestro')"
                    class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-1.5 cursor-pointer">
                <span>+ Nuevo Siniestro</span>
            </button>
            <button type="button" onclick="openNuevoSiniestroModal('Multa')"
                    class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-1.5 cursor-pointer">
                <span>+ Nueva Multa ARAG</span>
            </button>
        </div>
    </div>

    <!-- Navigation Tabs: Siniestros vs Multas -->
    <div class="flex border-b border-slate-200 space-x-6 text-sm font-bold">
        <a href="{{ route('operations.siniestros.index', ['tab' => 'siniestros']) }}"
           class="pb-3 border-b-2 flex items-center gap-2 {{ $tab !== 'multas' ? 'border-rose-600 text-rose-700' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
            <span>💥 Siniestros de Tráfico (Diego / Peritos)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">{{ $stats['siniestros_abiertos'] }} abiertos</span>
        </a>

        <a href="{{ route('operations.siniestros.index', ['tab' => 'multas']) }}"
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
                            <th class="py-3.5 px-4">Referencia Siniestro</th>
                            <th class="py-3.5 px-4">Tramitadora / Diego</th>
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
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-bold text-sm text-slate-900 block">#{{ $item->numero_poliza }}</span>
                                <span class="font-mono text-xs font-semibold bg-slate-100 px-1.5 py-0.5 rounded text-slate-700">
                                    {{ $item->matricula }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <strong class="text-slate-900 block">{{ $item->client_name }}</strong>
                                <span class="text-[10px] text-slate-400">{{ $item->cartera }} • {{ $item->office }}</span>
                            </td>

                            @if($tab !== 'multas')
                                <td class="py-3.5 px-4">
                                    <span class="font-mono font-bold text-rose-900 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">
                                        {{ $item->referencia_siniestro ?: 'Pendiente Ref.' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-slate-800 font-semibold block">{{ $item->tramitadora ?: 'Diego' }}</span>
                                    @if($item->fecha_email_diego)
                                        <span class="text-[10px] text-slate-400">Email: {{ $item->fecha_email_diego }}</span>
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
                                <span class="text-slate-600 text-xs block max-w-xs">{{ $item->observaciones ?: '-' }}</span>
                            </td>

                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openEditarSiniestroModal({{ $item->id }}, '{{ $item->tipo }}', '{{ $item->estado_siniestro }}', '{{ $item->estado_multa }}', '{{ addslashes($item->observaciones ?? '') }}')"
                                        class="px-2.5 py-1 bg-white hover:bg-slate-100 border border-slate-300 rounded-lg text-slate-700 font-semibold text-xs shadow-xs transition">
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
                            <input type="text" name="numero_poliza" required placeholder="1839101" class="w-full font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Matrícula Vehículo *</label>
                            <input type="text" name="matricula" required placeholder="7192LMB" class="w-full uppercase font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Asegurado (Titular) *</label>
                        <input type="text" name="client_name" required placeholder="PO1 ROBERT DAVIS" class="w-full uppercase font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Oficina</label>
                            <select name="office" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-semibold">
                                <option value="NEX">NEX (Base Naval)</option>
                                <option value="Central">Central (Pueblo)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Aseguradora</label>
                            <select name="cartera" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                                <option value="Patria Hispana">Patria Hispana</option>
                                <option value="GEICO">GEICO</option>
                            </select>
                        </div>
                    </div>

                    <!-- Campos de Siniestro -->
                    <div id="fieldsSiniestro" class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Referencia Siniestro</label>
                                <input type="text" name="referencia_siniestro" placeholder="SIN-PAT-84192/26" class="w-full font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Tramitadora / Diego</label>
                                <input type="text" name="tramitadora" value="Diego Fernández / Tramitadora Sur" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Fecha Email a Diego</label>
                                <input type="text" name="fecha_email_diego" value="{{ date('d/m/Y') }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-slate-700 font-bold mb-1">Estado Siniestro</label>
                                <select name="estado_siniestro" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-bold">
                                    <option value="Abierto">Abierto</option>
                                    <option value="En Peritación">En Peritación</option>
                                    <option value="Indemnizado">Indemnizado</option>
                                    <option value="Cerrado">Cerrado</option>
                                </select>
                            </div>
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
                <h3 class="font-bold text-base">Actualizar Estado de Expediente</h3>
                <button type="button" onclick="closeEditarSiniestroModal()" class="text-white/80 hover:text-white">✕</button>
            </div>
            <form id="editarSiniestroForm" method="POST" action="">
                @csrf @method('PATCH')
                <div class="p-6 space-y-3 text-xs">
                    <div id="editFieldsSiniestro">
                        <label class="block text-slate-700 font-bold mb-1">Estado Siniestro</label>
                        <select name="estado_siniestro" id="edit_estado_siniestro" class="w-full px-3 py-2 border border-slate-300 rounded-xl font-bold">
                            <option value="Abierto">Abierto</option>
                            <option value="En Peritación">En Peritación</option>
                            <option value="Indemnizado">Indemnizado</option>
                            <option value="Cerrado">Cerrado ✓</option>
                        </select>
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
    function openEditarSiniestroModal(id, tipo, estadoSin, estadoMul, obs) {
        document.getElementById('editarSiniestroForm').action = "/operations/siniestros/" + id;
        if (tipo === 'Multa') {
            document.getElementById('editFieldsSiniestro').classList.add('hidden');
            document.getElementById('editFieldsMulta').classList.remove('hidden');
            document.getElementById('edit_estado_multa').value = estadoMul;
        } else {
            document.getElementById('editFieldsSiniestro').classList.remove('hidden');
            document.getElementById('editFieldsMulta').classList.add('hidden');
            document.getElementById('edit_estado_siniestro').value = estadoSin;
        }
        document.getElementById('edit_sin_obs').value = obs;
        document.getElementById('editarSiniestroModal').classList.remove('hidden');
    }
    function closeEditarSiniestroModal() {
        document.getElementById('editarSiniestroModal').classList.add('hidden');
    }
</script>
@endsection
