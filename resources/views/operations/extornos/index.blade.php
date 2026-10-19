@extends('layouts.app')

@section('title', 'Extornos & Cancelaciones PCS — KFM Insurance')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-200">
                    Bajas Militares PCS & Reembolsos
                </span>
                <span class="text-xs text-slate-400">Excel EXTORNOS</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 font-display mt-1">Extornos & Devoluciones por Traslado PCS</h1>
            <p class="text-xs text-slate-500">Liquidación de prima no consumida por repatriación militar y enlace con GEICO Stateside</p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="openNuevoExtornoModal()"
                    class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-1.5 cursor-pointer">
                <span>+ Nuevo Extorno PCS</span>
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Extornos</span>
            <span class="text-2xl font-black text-slate-800 mt-1 block">{{ $stats['total'] }}</span>
            <span class="text-[10px] text-slate-500 block">Expedientes registrados</span>
        </div>

        <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-amber-700 block">Pendientes de Confirmar</span>
            <span class="text-2xl font-black text-amber-900 mt-1 block">{{ $stats['pendientes'] }}</span>
            <span class="text-[10px] text-amber-700 block">En espera de Patria Hispana</span>
        </div>

        <div class="p-4 rounded-2xl bg-sky-50/60 border border-sky-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-sky-700 block">Tramitados en Central</span>
            <span class="text-2xl font-black text-sky-900 mt-1 block">{{ $stats['tramitados'] }}</span>
            <span class="text-[10px] text-sky-700 block">Confirmados / Pendiente pago</span>
        </div>

        <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-emerald-700 block">Abonados al Cliente</span>
            <span class="text-2xl font-black text-emerald-900 mt-1 block">{{ $stats['reembolsados'] }}</span>
            <span class="text-[10px] text-emerald-700 block">Liquidación finalizada ✓</span>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between gap-3 text-xs">
        <form method="GET" action="{{ route('operations.extornos.index') }}" class="flex-1 flex items-center gap-2 max-w-md">
            <div class="relative w-full">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Buscar por póliza, asegurado, destino PCS..."
                       class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500 font-medium">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-900 transition">
                Buscar
            </button>
        </form>
    </div>

    <!-- Table of Extornos -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">Póliza / Oficina</th>
                        <th class="py-3.5 px-4">Asegurado (Militar)</th>
                        <th class="py-3.5 px-4">Fecha Salida / Destino PCS</th>
                        <th class="py-3.5 px-4">Email Patria</th>
                        <th class="py-3.5 px-4">Importe Reembolso</th>
                        <th class="py-3.5 px-4">GEICO US Form</th>
                        <th class="py-3.5 px-4">Estado</th>
                        <th class="py-3.5 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($extornos as $extorno)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-bold text-sm text-slate-900">#{{ $extorno->numero_poliza }}</span>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    {{ $extorno->cartera }} • Sede: <strong>{{ $extorno->office }}</strong>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <strong class="text-slate-900 block">{{ $extorno->client_name }}</strong>
                                @if($extorno->recibo_numero)
                                    <span class="text-[10px] font-mono text-slate-400 block">Recibo: {{ $extorno->recibo_numero }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-rose-700 font-semibold block">{{ $extorno->projected_departure ?: 'No indicada' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-slate-600 font-mono">{{ $extorno->email_sent_patria_date ?: 'Pendiente' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-bold text-emerald-700 text-sm block">{{ $extorno->amount_reimbursed ?: 'A calcular' }}</span>
                                <span class="text-[10px] text-slate-400">Prima orig: {{ $extorno->patria_amount ?: '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($extorno->stateside_geico_form)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                        ✓ Rellenado
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-full border border-slate-200">
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $extorno->status_badge_color }}">
                                    {{ $extorno->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openEditarExtornoModal({{ $extorno->id }}, '{{ $extorno->status }}', '{{ $extorno->amount_reimbursed }}', '{{ $extorno->recibo_numero }}', {{ $extorno->stateside_geico_form ? 'true' : 'false' }}, '{{ addslashes($extorno->observaciones ?? '') }}')"
                                        class="px-2.5 py-1 bg-white hover:bg-slate-100 border border-slate-300 rounded-lg text-slate-700 font-semibold text-xs shadow-xs transition">
                                    Editar / Liquidar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                No se encontraron registros de extornos por traslado militar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($extornos->hasPages())
            <div class="p-4 bg-slate-50 border-t border-slate-200">
                {{ $extornos->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal: + Nuevo Extorno PCS -->
<div id="nuevoExtornoModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeNuevoExtornoModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-rose-700 to-slate-900 text-white p-5 flex items-center justify-between">
                <h3 class="font-bold text-base">Nuevo Registro de Extorno PCS</h3>
                <button type="button" onclick="closeNuevoExtornoModal()" class="text-white/80 hover:text-white">✕</button>
            </div>
            <form method="POST" action="{{ route('operations.extornos.store') }}">
                @csrf
                <div class="p-6 space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Nº Póliza *</label>
                            <input type="text" name="numero_poliza" required placeholder="Ej: 1849102" class="w-full font-mono font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Oficina KFM *</label>
                            <select name="office" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-semibold">
                                <option value="NEX">NEX (Base Naval)</option>
                                <option value="Central">Central (Pueblo)</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Nombre Completo Asegurado (Militar) *</label>
                        <input type="text" name="client_name" required placeholder="PO1 ROBERT DAVIS" class="w-full uppercase font-bold px-3 py-1.5 border border-slate-300 rounded-lg">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Cartera / Aseguradora</label>
                            <select name="cartera" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                                <option value="Patria Hispana">Patria Hispana</option>
                                <option value="GEICO">GEICO Overseas</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Prima Original (€)</label>
                            <input type="text" name="patria_amount" placeholder="642,50 €" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Fecha Salida / Destino PCS</label>
                            <input type="text" name="projected_departure" placeholder="15/11/2026 (Norfolk)" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Email enviado a Patria (Fecha)</label>
                            <input type="text" name="email_sent_patria_date" value="{{ date('d/m/Y') }}" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Importe Reembolso (€)</label>
                            <input type="text" name="amount_reimbursed" placeholder="285,40 €" class="w-full font-bold text-emerald-700 px-3 py-1.5 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Estado Inicial</label>
                            <select name="status" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg font-bold">
                                <option value="Pendiente">Pendiente</option>
                                <option value="Tramitado">Tramitado</option>
                                <option value="Reembolsado">Reembolsado</option>
                            </select>
                        </div>
                    </div>
                    <div class="pt-1">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="checkbox" name="stateside_geico_form" value="1" class="w-4 h-4 text-rose-600 rounded">
                            <span>¿Formulario GEICO Stateside cumplimentado?</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Observaciones</label>
                        <textarea name="observaciones" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg" placeholder="Entregado recibo VPC, devuelto por transferencia..."></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-between border-t border-slate-200">
                    <button type="button" onclick="closeNuevoExtornoModal()" class="text-slate-500 font-bold">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Editar / Liquidar Extorno -->
<div id="editarExtornoModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeEditarExtornoModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden">
            <div class="bg-slate-900 text-white p-5 flex items-center justify-between">
                <h3 class="font-bold text-base">Actualizar Estado de Extorno</h3>
                <button type="button" onclick="closeEditarExtornoModal()" class="text-white/80 hover:text-white">✕</button>
            </div>
            <form id="editarExtornoForm" method="POST" action="">
                @csrf @method('PATCH')
                <div class="p-6 space-y-3 text-xs">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Estado del Extorno *</label>
                        <select name="status" id="edit_status" class="w-full px-3 py-2 border border-slate-300 rounded-xl font-bold">
                            <option value="Pendiente">Pendiente</option>
                            <option value="Tramitado">Tramitado</option>
                            <option value="Reembolsado">Reembolsado al Cliente ✓</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Importe Reembolsado Definitivo (€)</label>
                        <input type="text" name="amount_reimbursed" id="edit_amount" class="w-full font-bold text-emerald-700 px-3 py-2 border border-slate-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Nº Recibo / Liquidación</label>
                        <input type="text" name="recibo_numero" id="edit_recibo" placeholder="REC-EXT-2026-..." class="w-full font-mono px-3 py-2 border border-slate-300 rounded-xl">
                    </div>
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                            <input type="checkbox" name="stateside_geico_form" id="edit_geico" value="1" class="w-4 h-4 text-emerald-600 rounded">
                            <span>Formulario GEICO Stateside Completado</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Observaciones</label>
                        <textarea name="observaciones" id="edit_obs" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-xl"></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-between border-t border-slate-200">
                    <button type="button" onclick="closeEditarExtornoModal()" class="text-slate-500 font-bold">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openNuevoExtornoModal() {
        document.getElementById('nuevoExtornoModal').classList.remove('hidden');
    }
    function closeNuevoExtornoModal() {
        document.getElementById('nuevoExtornoModal').classList.add('hidden');
    }
    function openEditarExtornoModal(id, status, amount, recibo, geico, obs) {
        document.getElementById('editarExtornoForm').action = "/operations/extornos/" + id;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_amount').value = amount;
        document.getElementById('edit_recibo').value = recibo;
        document.getElementById('edit_geico').checked = geico;
        document.getElementById('edit_obs').value = obs;
        document.getElementById('editarExtornoModal').classList.remove('hidden');
    }
    function closeEditarExtornoModal() {
        document.getElementById('editarExtornoModal').classList.add('hidden');
    }
</script>
@endsection
