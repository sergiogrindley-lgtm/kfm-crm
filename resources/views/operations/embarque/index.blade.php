@extends('layouts.app')

@section('title', 'Pólizas de Embarque / Shipping POV — KFM Insurance')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-800 border border-indigo-200">
                    Tránsito Marítimo POV (VPC Rota)
                </span>
                <span class="text-xs text-slate-400">Excel POLIZAS EMBARQUE</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 font-display mt-1">Pólizas de Embarque & Transporte Marítimo</h1>
            <p class="text-xs text-slate-500">Gestión de cobertura en tránsito marítimo militar, peritación en puerto y ajuste de prima en renovación</p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="openNuevoEmbarqueModal()"
                    class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-1.5 cursor-pointer">
                <span>+ Nueva Póliza Embarque</span>
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Pólizas Embarque</span>
            <span class="text-2xl font-black text-slate-800 mt-1 block">{{ $stats['total'] }}</span>
            <span class="text-[10px] text-slate-500 block">Coches en operativa</span>
        </div>

        <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-indigo-700 block">En Tránsito (Buque USNS)</span>
            <span class="text-2xl font-black text-indigo-900 mt-1 block">{{ $stats['en_transito'] }}</span>
            <span class="text-[10px] text-indigo-700 block">Póliza desactivada en mar</span>
        </div>

        <div class="p-4 rounded-2xl bg-cyan-50/60 border border-cyan-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-cyan-700 block">En VPC Rota / Peritación</span>
            <span class="text-2xl font-black text-cyan-900 mt-1 block">{{ $stats['en_vpc'] + $stats['peritados'] }}</span>
            <span class="text-[10px] text-cyan-700 block">Llegados a puerto</span>
        </div>

        <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-emerald-700 block">Reactivados / Liquidados</span>
            <span class="text-2xl font-black text-emerald-900 mt-1 block">{{ $stats['liquidados'] }}</span>
            <span class="text-[10px] text-emerald-700 block">Prima ajustada a renovación ✓</span>
        </div>
    </div>

    <!-- Table of Embarques -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">Póliza Embarque</th>
                        <th class="py-3.5 px-4">Asegurado (Militar)</th>
                        <th class="py-3.5 px-4">Fechas Tránsito (Desact / Reactiv)</th>
                        <th class="py-3.5 px-4">Peritación Daños</th>
                        <th class="py-3.5 px-4">Ajuste Económico Renovación</th>
                        <th class="py-3.5 px-4">Estado</th>
                        <th class="py-3.5 px-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-bold text-sm text-slate-900">#{{ $item->numero_poliza }}</span>
                                <div class="text-[10px] text-slate-400 mt-0.5">Prima: <strong>{{ $item->prima }}</strong></div>
                            </td>
                            <td class="py-3.5 px-4">
                                <strong class="text-slate-900 block">{{ $item->client_name }}</strong>
                                <span class="text-[10px] text-slate-400 block max-w-xs truncate">{{ $item->comments ?: '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-[11px] text-slate-600">
                                    Desactivado: <strong>{{ $item->fecha_desactivacion ?: '-' }}</strong>
                                </div>
                                <div class="text-[11px] text-cyan-800 font-semibold">
                                    Reactivado: <strong>{{ $item->fecha_reactivacion ?: 'En barco' }}</strong>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($item->fecha_peritacion)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                        ✓ {{ $item->fecha_peritacion }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">Pendiente puerto</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-[11px]">
                                    No consumida: <strong class="text-emerald-700">{{ $item->importe_prima_no_consumida ?: '-' }}</strong>
                                </div>
                                <div class="text-[11px]">
                                    A pagar renovación: <strong class="text-slate-900">{{ $item->importe_a_pagar ?: '-' }}</strong>
                                </div>
                                @if($item->prima_aplicada_renovacion)
                                    <span class="text-[9px] bg-emerald-100 text-emerald-800 px-1 rounded font-bold">Aplicada a renovación</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $item->status_badge_color }}">
                                    {{ $item->estado }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openEditarEmbarqueModal({{ $item->id }}, '{{ $item->estado }}', '{{ $item->fecha_reactivacion }}', '{{ $item->fecha_peritacion }}', '{{ $item->importe_prima_no_consumida }}', '{{ $item->importe_a_pagar }}', {{ $item->prima_aplicada_renovacion ? 'true' : 'false' }}, '{{ addslashes($item->comments ?? '') }}')"
                                        class="px-2.5 py-1 bg-white hover:bg-slate-100 border border-slate-300 rounded-lg text-slate-700 font-semibold text-xs shadow-xs transition">
                                    Gestionar VPC
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                No se encontraron pólizas de embarque activas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal: + Nueva Póliza Embarque -->
<div id="nuevoEmbarqueModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeNuevoEmbarqueModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full border border-slate-200 overflow-hidden">
            <div class="bg-indigo-700 text-white p-5 flex items-center justify-between">
                <h3 class="font-bold text-base">Nueva Póliza de Embarque Marítimo</h3>
                <button type="button" onclick="closeNuevoEmbarqueModal()" class="text-white/80 hover:text-white">✕</button>
            </div>
            <form method="POST" action="{{ route('operations.embarque.store') }}">
                @csrf
                <div class="p-6 space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Nº Póliza Embarque *</label>
                            <input type="text" name="numero_poliza" required placeholder="EMB-2026-..." class="w-full font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Prima Total Tránsito (€) *</label>
                            <input type="text" name="prima" required placeholder="450,00 €" class="w-full font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Asegurado (Militar) *</label>
                        <input type="text" name="client_name" required placeholder="CHIEF DANIEL MORGAN" class="w-full uppercase font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Fecha Emisión</label>
                            <input type="text" name="fecha_emision" value="{{ date('d/m/Y') }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Fecha Desactivación (Embarque)</label>
                            <input type="text" name="fecha_desactivacion" placeholder="dd/mm/aaaa" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Estado</label>
                        <select name="estado" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-bold">
                            <option value="En Tránsito">En Tránsito (Buque USNS)</option>
                            <option value="Llegado a VPC">Llegado a VPC Rota</option>
                            <option value="Peritado">Peritado en Puerto</option>
                            <option value="Liquidado">Liquidado / Renovado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Comentarios / Barco</label>
                        <textarea name="comments" rows="2" placeholder="Vehículo en buque USNS Leroy Grumman, llegada a VPC Rota..." class="w-full px-3 py-2 border border-slate-300 rounded-lg"></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-between border-t border-slate-200">
                    <button type="button" onclick="closeNuevoEmbarqueModal()" class="text-slate-500 font-bold">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Editar / Gestionar VPC Embarque -->
<div id="editarEmbarqueModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeEditarEmbarqueModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden">
            <div class="bg-slate-900 text-white p-5 flex items-center justify-between">
                <h3 class="font-bold text-base">Gestión de Llegada VPC & Liquidación</h3>
                <button type="button" onclick="closeEditarEmbarqueModal()" class="text-white/80 hover:text-white">✕</button>
            </div>
            <form id="editarEmbarqueForm" method="POST" action="">
                @csrf @method('PATCH')
                <div class="p-6 space-y-3 text-xs">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Estado de la Póliza de Embarque *</label>
                        <select name="estado" id="emb_estado" class="w-full px-3 py-2 border border-slate-300 rounded-xl font-bold">
                            <option value="En Tránsito">En Tránsito (Buque USNS)</option>
                            <option value="Llegado a VPC">Llegado a VPC Rota</option>
                            <option value="Peritado">Peritado en Puerto (Inspección OK)</option>
                            <option value="Reactivado">Reactivado</option>
                            <option value="Liquidado">Liquidado / Aplicado a Renovación ✓</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Fecha Reactivación</label>
                            <input type="text" name="fecha_reactivacion" id="emb_reactivacion" placeholder="dd/mm/aaaa" class="w-full px-3 py-2 border border-slate-300 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Fecha Peritación</label>
                            <input type="text" name="fecha_peritacion" id="emb_peritacion" placeholder="dd/mm/aaaa" class="w-full px-3 py-2 border border-slate-300 rounded-xl">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Prima No Consumida (€)</label>
                            <input type="text" name="importe_prima_no_consumida" id="emb_no_consumida" placeholder="185,00 €" class="w-full font-bold text-emerald-700 px-3 py-2 border border-slate-300 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Importe a Pagar Renovación</label>
                            <input type="text" name="importe_a_pagar" id="emb_a_pagar" placeholder="465,00 €" class="w-full font-bold text-slate-800 px-3 py-2 border border-slate-300 rounded-xl">
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="checkbox" name="prima_aplicada_renovacion" id="emb_aplicada" value="1" class="w-4 h-4 text-emerald-600 rounded">
                            <span>Prima no consumida aplicada a renovación anual</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Comentarios</label>
                        <textarea name="comments" id="emb_comments" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-xl"></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-between border-t border-slate-200">
                    <button type="button" onclick="closeEditarEmbarqueModal()" class="text-slate-500 font-bold">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openNuevoEmbarqueModal() {
        document.getElementById('nuevoEmbarqueModal').classList.remove('hidden');
    }
    function closeNuevoEmbarqueModal() {
        document.getElementById('nuevoEmbarqueModal').classList.add('hidden');
    }
    function openEditarEmbarqueModal(id, estado, reactiv, perit, noConsumida, aPagar, aplicada, comments) {
        document.getElementById('editarEmbarqueForm').action = "/operations/embarque/" + id;
        document.getElementById('emb_estado').value = estado;
        document.getElementById('emb_reactivacion').value = reactiv;
        document.getElementById('emb_peritacion').value = perit;
        document.getElementById('emb_no_consumida').value = noConsumida;
        document.getElementById('emb_a_pagar').value = aPagar;
        document.getElementById('emb_aplicada').checked = aplicada;
        document.getElementById('emb_comments').value = comments;
        document.getElementById('editarEmbarqueModal').classList.remove('hidden');
    }
    function closeEditarEmbarqueModal() {
        document.getElementById('editarEmbarqueModal').classList.add('hidden');
    }
</script>
@endsection
