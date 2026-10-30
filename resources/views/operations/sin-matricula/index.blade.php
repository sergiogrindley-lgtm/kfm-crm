@extends('layouts.app')

@section('title', 'Vehículos Sin Matrícula / Provisionales — KFM Insurance')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                    Placas Temporales & DGT
                </span>
                <span class="text-xs text-slate-400">Excel SIN MATRICULA</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 font-display mt-1">Vehículos Sin Matrícula Definitiva / Placas Provisionales</h1>
            <p class="text-xs text-slate-500">Control de regularización de vehículos importados de EE.UU. y placas turísticas/verdes</p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="openNuevoSinMatriculaModal()"
                    class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-1.5 cursor-pointer">
                <span>+ Añadir Caso Provisional</span>
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Total en Seguimiento</span>
            <span class="text-2xl font-black text-slate-800 mt-1 block">{{ $stats['total'] }}</span>
            <span class="text-[10px] text-slate-500 block">Vehículos registrados</span>
        </div>

        <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-rose-700 block">Pendientes de Placa</span>
            <span class="text-2xl font-black text-rose-900 mt-1 block">{{ $stats['pendientes'] }}</span>
            <span class="text-[10px] text-rose-700 block">Circulando con bastidor/provisional</span>
        </div>

        <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-amber-700 block">En Trámite DGT / ITV</span>
            <span class="text-2xl font-black text-amber-900 mt-1 block">{{ $stats['en_tramite'] }}</span>
            <span class="text-[10px] text-amber-700 block">Expediente en curso</span>
        </div>

        <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-emerald-700 block">Regularizados</span>
            <span class="text-2xl font-black text-emerald-900 mt-1 block">{{ $stats['regularizados'] }}</span>
            <span class="text-[10px] text-emerald-700 block">Matrícula definitiva asignada ✓</span>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">Póliza / Cía</th>
                        <th class="py-3.5 px-4">Identificación Provisional / Bastidor</th>
                        <th class="py-3.5 px-4">Asegurado (Titular)</th>
                        <th class="py-3.5 px-4">Fecha Notificación</th>
                        <th class="py-3.5 px-4">Estado Regularización</th>
                        <th class="py-3.5 px-4">Observaciones & Seguimiento</th>
                        <th class="py-3.5 px-4 text-right">Acción</th>
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
                                <span class="font-mono font-bold text-sm bg-amber-100 text-amber-900 px-2 py-0.5 rounded border border-amber-300">
                                    {{ $item->matricula_provisional }}
                                </span>
                                @if($item->matricula_definitiva)
                                    <div class="mt-1">
                                        <span class="text-[10px] text-emerald-600 font-bold">Definitiva:</span>
                                        <span class="font-mono font-bold text-xs bg-emerald-50 text-emerald-800 px-1.5 py-0.2 rounded border border-emerald-200">
                                            {{ $item->matricula_definitiva }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <strong class="text-slate-900 block">{{ $item->client_name }}</strong>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-slate-600 font-mono">{{ $item->date_notification ?: '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $item->status_badge_color }}">
                                    {{ $item->estado }}
                                </span>
                            </td>
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
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openRegularizarModal({{ $item->id }}, '{{ $item->estado }}', '{{ $item->matricula_definitiva }}', '{{ addslashes($item->observaciones ?? '') }}')"
                                        class="px-2.5 py-1 bg-white hover:bg-slate-100 border border-slate-300 rounded-lg text-slate-700 font-semibold text-xs shadow-xs transition">
                                    Asignar Matrícula
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                No hay vehículos con matrícula provisional pendientes
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal: + Añadir Caso Sin Matrícula -->
<div id="nuevoSinMatriculaModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeNuevoSinMatriculaModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden">
            <div class="bg-amber-600 text-white p-5 flex items-center justify-between">
                <h3 class="font-bold text-base">Nuevo Caso de Placa Provisional</h3>
                <button type="button" onclick="closeNuevoSinMatriculaModal()" class="text-white/80 hover:text-white">✕</button>
            </div>
            <form method="POST" action="{{ route('operations.sin-matricula.store') }}">
                @csrf
                <div class="p-6 space-y-3 text-xs">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Nº Póliza *</label>
                        <input type="text" name="numero_poliza" required placeholder="Ej: 1841029" class="w-full font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Aseguradora (P / G) *</label>
                        <select name="aseguradora" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-bold">
                            <option value="Patria">Patria Hispana (P)</option>
                            <option value="GEICO">GEICO (G)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Matrícula Provisional o Bastidor *</label>
                        <input type="text" name="matricula_provisional" required placeholder="T-8419-BBC o VIN US" class="w-full uppercase font-mono px-3 py-1.5 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Nombre Completo Asegurado *</label>
                        <input type="text" name="client_name" required placeholder="SGT. TYLER BENNETT" class="w-full uppercase font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Fecha de Notificación</label>
                        <input type="text" name="date_notification" value="{{ date('d/m/Y') }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Estado</label>
                        <select name="estado" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-semibold">
                            <option value="Pendiente">Pendiente de Aportar</option>
                            <option value="En Trámite DGT">En Trámite DGT / ITV</option>
                            <option value="Regularizado">Regularizado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Observaciones</label>
                        <textarea name="observaciones" rows="2" placeholder="Vehículo en ITV VEIASA..." class="w-full px-3 py-2 border border-slate-300 rounded-lg"></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-between border-t border-slate-200">
                    <button type="button" onclick="closeNuevoSinMatriculaModal()" class="text-slate-500 font-bold">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl">Guardar</button>
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
                <h3 class="font-bold text-base">Asignar Matrícula Definitiva</h3>
                <button type="button" onclick="closeRegularizarModal()" class="text-white/80 hover:text-white">✕</button>
            </div>
            <form id="regularizarForm" method="POST" action="">
                @csrf @method('PATCH')
                <div class="p-6 space-y-3 text-xs">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Estado de Regularización *</label>
                        <select name="estado" id="reg_estado" class="w-full px-3 py-2 border border-slate-300 rounded-xl font-bold">
                            <option value="Regularizado">Regularizado (Matrícula asignada) ✓</option>
                            <option value="En Trámite DGT">En Trámite DGT</option>
                            <option value="Pendiente">Pendiente</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Nueva Matrícula Española Oficial</label>
                        <input type="text" name="matricula_definitiva" id="reg_matricula" placeholder="Ej: 9942MPK" class="w-full font-mono uppercase font-black text-base px-3 py-2 border border-slate-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Notas de Actualización</label>
                        <textarea name="observaciones" id="reg_obs" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-xl"></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-between border-t border-slate-200">
                    <button type="button" onclick="closeRegularizarModal()" class="text-slate-500 font-bold">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl">Actualizar Ficha</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openNuevoSinMatriculaModal() {
        document.getElementById('nuevoSinMatriculaModal').classList.remove('hidden');
    }
    function closeNuevoSinMatriculaModal() {
        document.getElementById('nuevoSinMatriculaModal').classList.add('hidden');
    }
    function openRegularizarModal(id, estado, mat, obs) {
        document.getElementById('regularizarForm').action = "/operations/sin-matricula/" + id;
        document.getElementById('reg_estado').value = estado;
        document.getElementById('reg_matricula').value = mat;
        document.getElementById('reg_obs').value = obs;
        document.getElementById('regularizarModal').classList.remove('hidden');
    }
    function closeRegularizarModal() {
        document.getElementById('regularizarModal').classList.add('hidden');
    }
</script>
@endsection
