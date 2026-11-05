@extends('layouts.app')

@section('title', 'Pólizas Sin Matrículas — KFM Insurance')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                    Control de Vehículos SM
                </span>
                <span class="text-xs text-slate-400">Excel SIN MATRICULA</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 font-display mt-1">Pólizas Sin Matrículas</h1>
            <p class="text-xs text-slate-500">Pólizas de vehículos guardadas sin matrícula ("SM") o provisionales. Al añadir la matrícula definitiva en la contratación, desaparecen automáticamente de esta lista.</p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="openNuevoSinMatriculaModal()"
                    class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-1.5 cursor-pointer">
                <span>+ Registrar Póliza Sin Matrícula</span>
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
        <div class="p-4 rounded-2xl bg-rose-50/70 border border-rose-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-rose-700 block">Pendientes de Matrícula</span>
            <span class="text-2xl font-black text-rose-900 mt-1 block">{{ $stats['pendientes'] }}</span>
            <span class="text-[10px] text-rose-700 block">Vehículos con SM o provisional</span>
        </div>

        <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-blue-700 block">Cartera 81 (Patria 81)</span>
            <span class="text-2xl font-black text-blue-900 mt-1 block">{{ $stats['patria'] }}</span>
            <span class="text-[10px] text-blue-700 block">En espera de matrícula</span>
        </div>

        <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-indigo-700 block">Cartera 87 (GEICO 87)</span>
            <span class="text-2xl font-black text-indigo-900 mt-1 block">{{ $stats['geico'] }}</span>
            <span class="text-[10px] text-indigo-700 block">En espera de matrícula</span>
        </div>

        <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-emerald-700 block">Regularizadas (Historial)</span>
            <span class="text-2xl font-black text-emerald-900 mt-1 block">{{ $stats['regularizados'] }}</span>
            <span class="text-[10px] text-emerald-700 block">Matrícula ya asignada ✓</span>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3 text-xs">
        <form method="GET" action="{{ route('operations.sin-matricula.index') }}" class="flex-1 flex flex-wrap items-center gap-2">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="q" value="{{ request('q') }}" 
                       placeholder="Buscar por póliza, cliente, bastidor (VIN), modelo..." 
                       class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-amber-500 focus:bg-white">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            @if(request('estado'))
                <input type="hidden" name="estado" value="{{ request('estado') }}">
            @endif
            <button type="submit" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl transition cursor-pointer">
                Buscar
            </button>
            @if(request('q') || request('estado'))
                <a href="{{ route('operations.sin-matricula.index') }}" class="px-3 py-2 text-slate-600 hover:text-slate-900 font-semibold">
                    Limpiar
                </a>
            @endif
        </form>

        <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-xl self-start md:self-auto font-medium">
            <a href="{{ route('operations.sin-matricula.index', array_merge(request()->except('estado', 'page'), ['estado' => ''])) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ (!request('estado') || request('estado') === 'Pendiente') ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Solo Pendientes
            </a>
            <a href="{{ route('operations.sin-matricula.index', array_merge(request()->except('estado', 'page'), ['estado' => 'regularizados'])) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ request('estado') === 'regularizados' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Regularizados
            </a>
            <a href="{{ route('operations.sin-matricula.index', array_merge(request()->except('estado', 'page'), ['estado' => 'todos'])) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ request('estado') === 'todos' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Ver Todos
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">Póliza / Cartera</th>
                        <th class="py-3.5 px-4">Matrícula Actual</th>
                        <th class="py-3.5 px-4">Asegurado (Titular)</th>
                        <th class="py-3.5 px-4">Vehículo / Bastidor (VIN)</th>
                        <th class="py-3.5 px-4">Fecha Notif.</th>
                        <th class="py-3.5 px-4">Estado</th>
                        <th class="py-3.5 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-bold text-sm text-slate-900">#{{ $item->numero_poliza }}</span>
                                <span class="block text-[10px] font-bold text-indigo-700 mt-0.5">{{ $item->aseguradora }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($item->estado === 'Regularizado' && $item->matricula_definitiva)
                                    <span class="font-mono font-bold text-xs bg-emerald-100 text-emerald-900 px-2 py-1 rounded border border-emerald-300">
                                        {{ $item->matricula_definitiva }}
                                    </span>
                                    <span class="block text-[10px] text-slate-400 mt-0.5">Antes: {{ $item->matricula_provisional }}</span>
                                @else
                                    <span class="font-mono font-bold text-xs bg-amber-100 text-amber-900 px-2 py-0.5 rounded border border-amber-300">
                                        {{ $item->matricula_provisional }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($item->client_id)
                                    <a href="{{ route('clients.show', $item->client_id) }}" class="text-kfm-primary font-bold hover:underline block">
                                        {{ $item->client_name }} ↗
                                    </a>
                                @else
                                    <strong class="text-slate-900 block">{{ $item->client_name }}</strong>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-slate-800 font-bold block">{{ $item->observaciones ?: 'Vehículo KFM' }}</span>
                                @if($item->policy && $item->policy->vin)
                                    <span class="font-mono text-[10px] text-slate-500 block">VIN: {{ $item->policy->vin }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-slate-600 font-mono">{{ $item->date_notification ?: '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $item->status_badge_color }}">
                                    {{ $item->estado }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" onclick="openRegularizarModal({{ $item->id }}, '{{ $item->estado }}', '{{ $item->matricula_definitiva }}', '{{ addslashes($item->client_name ?? '') }}', '{{ $item->numero_poliza }}')"
                                            class="px-2.5 py-1 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow-xs transition cursor-pointer">
                                        ⚡ Asignar Matrícula
                                    </button>
                                    @if($item->client_id)
                                        <a href="{{ route('clients.show', $item->client_id) }}" 
                                           class="px-2.5 py-1 bg-white hover:bg-slate-100 border border-slate-300 rounded-lg text-slate-700 font-semibold text-xs shadow-xs transition">
                                            Ficha →
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="max-w-md mx-auto space-y-2">
                                    <span class="text-3xl block">🎉</span>
                                    <strong class="text-slate-700 block text-sm">No hay pólizas de vehículos sin matrícula pendientes</strong>
                                    <p class="text-xs text-slate-500">Todas las contrataciones de vehículos tienen su matrícula asignada. Si guardas una póliza con "SM", aparecerá aquí automáticamente.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $items->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal: + Añadir Caso Sin Matrícula -->
<div id="nuevoSinMatriculaModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeNuevoSinMatriculaModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden">
            <div class="bg-amber-600 text-white p-5 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-base">Registrar Póliza Sin Matrícula</h3>
                    <p class="text-[11px] text-amber-100 mt-0.5">Seguimiento provisional de vehículo "SM"</p>
                </div>
                <button type="button" onclick="closeNuevoSinMatriculaModal()" class="text-white/80 hover:text-white text-lg cursor-pointer">✕</button>
            </div>
            <form method="POST" action="{{ route('operations.sin-matricula.store') }}">
                @csrf
                <div class="p-6 space-y-3 text-xs">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Nº Póliza *</label>
                        <input type="text" name="numero_poliza" id="sm_numero_poliza" required oninput="lookupPolicyForSM(this.value)" onchange="lookupPolicyForSM(this.value)" placeholder="Ej: 1841029" class="w-full font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                    </div>
                    <div id="sm_lookup_pill" class="hidden text-xs bg-emerald-50 text-emerald-900 border border-emerald-300 rounded-lg px-3 py-1.5 font-medium"></div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Cartera / Aseguradora *</label>
                        <select name="aseguradora" id="sm_aseguradora" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-bold">
                            <option value="81 (Patria 81)">81 (Patria 81)</option>
                            <option value="87 (GEICO 87)">87 (GEICO 87)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Identificación Provisional o Bastidor (VIN) *</label>
                        <input type="text" name="matricula_provisional" id="sm_matricula_provisional" value="SM" required placeholder="SM o VIN US" class="w-full uppercase font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Nombre Completo Asegurado (Militar) *</label>
                        <input type="text" name="client_name" id="sm_client_name" required placeholder="Ej: MILLER, DAVID ALEXANDER" class="w-full uppercase font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Fecha de Notificación / Alta</label>
                        <input type="text" name="date_notification" value="{{ date('d/m/Y') }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Estado</label>
                        <select name="estado" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-semibold">
                            <option value="Pendiente">Pendiente de Matrícula (SM)</option>
                            <option value="En Trámite DGT">En Trámite DGT / Placas Verdes</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Vehículo / Observaciones</label>
                        <textarea name="observaciones" id="sm_observaciones" rows="2" placeholder="Marca, modelo, bastidor o notas..." class="w-full px-3 py-2 border border-slate-300 rounded-lg"></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-between border-t border-slate-200">
                    <button type="button" onclick="closeNuevoSinMatriculaModal()" class="text-slate-500 font-bold cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl cursor-pointer">Guardar Póliza</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Asignar Matrícula Definitiva -->
<div id="regularizarModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeRegularizarModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden">
            <div class="bg-emerald-600 text-white p-5 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-base">Asignar Matrícula Definitiva</h3>
                    <p class="text-[11px] text-emerald-100 mt-0.5" id="reg_subtitle">Actualizar póliza del vehículo</p>
                </div>
                <button type="button" onclick="closeRegularizarModal()" class="text-white/80 hover:text-white text-lg cursor-pointer">✕</button>
            </div>
            <form id="regularizarForm" method="POST" action="">
                @csrf @method('PATCH')
                <div class="p-6 space-y-3 text-xs">
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-950">
                        <span class="block text-[10px] font-bold uppercase text-emerald-700">Asegurado / Póliza</span>
                        <strong class="block text-sm font-bold text-slate-900 mt-0.5" id="reg_client_display">-</strong>
                        <span class="block text-xs text-slate-600 mt-0.5" id="reg_poliza_display">-</span>
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Nueva Matrícula Oficial Española *</label>
                        <input type="text" name="matricula_definitiva" id="reg_matricula" required placeholder="Ej: 9942MPK" 
                               class="w-full font-mono uppercase font-black text-base px-3 py-2 border-2 border-emerald-400 rounded-xl focus:ring-2 focus:ring-emerald-500">
                        <span class="text-[10px] text-slate-500 mt-1 block">Al guardar, se actualizará la contratación en la ficha del cliente y desaparecerá de la lista de pendientes.</span>
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Estado de Regularización</label>
                        <select name="estado" id="reg_estado" class="w-full px-3 py-2 border border-slate-300 rounded-xl font-bold">
                            <option value="Regularizado">Regularizado (Matrícula asignada) ✓</option>
                            <option value="En Trámite DGT">En Trámite DGT</option>
                            <option value="Pendiente">Pendiente</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Notas de Actualización (Opcional)</label>
                        <textarea name="observaciones" id="reg_obs" rows="2" placeholder="Notas sobre el cambio de placa o DGT..." class="w-full px-3 py-2 border border-slate-300 rounded-xl"></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-between border-t border-slate-200">
                    <button type="button" onclick="closeRegularizarModal()" class="text-slate-500 font-bold cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl cursor-pointer">Guardar Matrícula</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let lookupSmTimer = null;
    function lookupPolicyForSM(val) {
        clearTimeout(lookupSmTimer);
        const pill = document.getElementById('sm_lookup_pill');
        if (!val || val.trim().length < 2) {
            if (pill) pill.classList.add('hidden');
            return;
        }
        lookupSmTimer = setTimeout(() => {
            fetch(`/api/lookup-policy?numero_poliza=${encodeURIComponent(val.trim())}`)
                .then(r => r.json())
                .then(data => {
                    if (data.found) {
                        if (data.client_name) document.getElementById('sm_client_name').value = data.client_name;
                        if (data.matricula && (data.matricula.includes('SM') || data.matricula.includes('S/M'))) {
                            document.getElementById('sm_matricula_provisional').value = data.matricula;
                        }
                        if (data.cartera) {
                            const cVal = (data.cartera === '87' || data.cartera.includes('87') || data.cartera.includes('GEICO')) ? '87 (GEICO 87)' : '81 (Patria 81)';
                            document.getElementById('sm_aseguradora').value = cVal;
                        }
                        if (data.vehiculo) {
                            const obs = document.getElementById('sm_observaciones');
                            if (obs && !obs.value) obs.value = data.vehiculo;
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

    function openNuevoSinMatriculaModal() {
        document.getElementById('nuevoSinMatriculaModal').classList.remove('hidden');
    }
    function closeNuevoSinMatriculaModal() {
        document.getElementById('nuevoSinMatriculaModal').classList.add('hidden');
    }
    function openRegularizarModal(id, estado, mat, clientName, poliza) {
        document.getElementById('regularizarForm').action = "/operations/sin-matricula/" + id;
        document.getElementById('reg_estado').value = estado || 'Regularizado';
        document.getElementById('reg_matricula').value = (mat && mat !== 'SM' && mat !== 'S/M') ? mat : '';
        document.getElementById('reg_client_display').innerText = clientName || 'Asegurado KFM';
        document.getElementById('reg_poliza_display').innerText = 'Póliza #' + (poliza || '');
        document.getElementById('regularizarModal').classList.remove('hidden');
        setTimeout(() => document.getElementById('reg_matricula').focus(), 100);
    }
    function closeRegularizarModal() {
        document.getElementById('regularizarModal').classList.add('hidden');
    }
</script>
@endsection
