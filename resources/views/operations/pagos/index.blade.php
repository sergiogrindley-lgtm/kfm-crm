@extends('layouts.app')

@section('title', 'Financiación & Pagos Fraccionados — KFM Insurance')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                    Financiación en 2 Plazos
                </span>
                <span class="text-xs text-slate-400">Excel PAYMENTS</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 font-display mt-1">Pagos Fraccionados & Control de 2º Plazo</h1>
            <p class="text-xs text-slate-500">Gestión de cobros semestrales con tarjeta, vencimiento de segundos plazos y liquidación de recibos</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Pólizas Fraccionadas</span>
            <span class="text-2xl font-black text-slate-800 mt-1 block">{{ $stats['total'] }}</span>
            <span class="text-[10px] text-slate-500 block">Cartera financiada</span>
        </div>

        <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-amber-700 block">Pendientes de 2º Cobro</span>
            <span class="text-2xl font-black text-amber-900 mt-1 block">{{ $stats['pendientes'] }}</span>
            <span class="text-[10px] text-amber-700 block">Vencimiento pendiente</span>
        </div>

        <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-emerald-700 block">Cobrados Completos</span>
            <span class="text-2xl font-black text-emerald-900 mt-1 block">{{ $stats['cobrados'] }}</span>
            <span class="text-[10px] text-emerald-700 block">Liquidación finalizada ✓</span>
        </div>

        <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-200 shadow-xs">
            <span class="text-[10px] uppercase font-bold text-rose-700 block">Vencidos / Rechazos</span>
            <span class="text-2xl font-black text-rose-900 mt-1 block">{{ $stats['vencidos'] }}</span>
            <span class="text-[10px] text-rose-700 block">Cobro fallido</span>
        </div>
    </div>

    <!-- Table of Pagos Fraccionados -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">Póliza / Total Seguro</th>
                        <th class="py-3.5 px-4">Asegurado (Titular)</th>
                        <th class="py-3.5 px-4">1er Pago (Abonado)</th>
                        <th class="py-3.5 px-4">2º Pago (Vencimiento)</th>
                        <th class="py-3.5 px-4">Datos Tarjeta Cobro</th>
                        <th class="py-3.5 px-4">Estado</th>
                        <th class="py-3.5 px-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($pagos as $pago)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-bold text-sm text-slate-900 block">#{{ $pago->numero_poliza }}</span>
                                <span class="text-xs text-slate-500 font-semibold">Total: <strong>{{ $pago->total_seguro }}</strong></span>
                            </td>
                            <td class="py-3.5 px-4">
                                <strong class="text-slate-900 block">{{ $pago->client_name }}</strong>
                                <span class="text-[10px] text-slate-400 block truncate max-w-xs">{{ $pago->observaciones ?: '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-emerald-700 font-bold text-xs">{{ $pago->primer_pago_importe }}</div>
                                <span class="text-[10px] text-slate-500 block font-mono">Recibo: {{ $pago->primer_pago_recibo ?: '-' }}</span>
                                @if($pago->primer_pago_fecha)
                                    <span class="text-[9px] text-slate-400 block">Fecha: {{ $pago->primer_pago_fecha }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900 text-xs">{{ $pago->segundo_pago_importe }}</div>
                                <span class="text-[11px] font-mono text-amber-800 font-bold block">
                                    Vence: {{ $pago->segundo_pago_vencimiento ?: 'A fijar' }}
                                </span>
                                @if($pago->segundo_pago_recibo)
                                    <span class="text-[9px] text-slate-400 block font-mono">{{ $pago->segundo_pago_recibo }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-mono text-xs text-slate-800 font-semibold">{{ $pago->tarjeta_info ?: 'No registrada' }}</div>
                                @if($pago->tarjeta_exp)
                                    <span class="text-[10px] text-slate-400 font-mono">Cad: {{ $pago->tarjeta_exp }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $pago->status_badge_color }}">
                                    {{ $pago->estado }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openEditarPagoModal({{ $pago->id }}, '{{ $pago->estado }}', '{{ $pago->segundo_pago_recibo }}', '{{ addslashes($pago->observaciones ?? '') }}')"
                                        class="px-2.5 py-1 bg-white hover:bg-slate-100 border border-slate-300 rounded-lg text-slate-700 font-semibold text-xs shadow-xs transition">
                                    Cobrar / Liquidar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                No se encontraron pólizas en fraccionamiento
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal: Liquidar 2º Pago -->
<div id="editarPagoModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeEditarPagoModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden">
            <div class="bg-emerald-700 text-white p-5 flex items-center justify-between">
                <h3 class="font-bold text-base">Registrar Cobro de 2º Plazo</h3>
                <button type="button" onclick="closeEditarPagoModal()" class="text-white/80 hover:text-white">✕</button>
            </div>
            <form id="editarPagoForm" method="POST" action="">
                @csrf @method('PATCH')
                <div class="p-6 space-y-3 text-xs">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Estado de Pago *</label>
                        <select name="estado" id="pago_estado" class="w-full px-3 py-2 border border-slate-300 rounded-xl font-bold">
                            <option value="Cobrado">Cobrado Íntegro ✓</option>
                            <option value="Pendiente 2º Pago">Pendiente 2º Pago</option>
                            <option value="Vencido">Vencido / Rechazo Bancario</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Nº Recibo del 2º Pago</label>
                        <input type="text" name="segundo_pago_recibo" id="pago_recibo" placeholder="REC-PAY-2026-..." class="w-full font-mono px-3 py-2 border border-slate-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Observaciones</label>
                        <textarea name="observaciones" id="pago_obs" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-xl"></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-between border-t border-slate-200">
                    <button type="button" onclick="closeEditarPagoModal()" class="text-slate-500 font-bold">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl">Confirmar Cobro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditarPagoModal(id, estado, recibo, obs) {
        document.getElementById('editarPagoForm').action = "/operations/pagos/" + id;
        document.getElementById('pago_estado').value = estado;
        document.getElementById('pago_recibo').value = recibo;
        document.getElementById('pago_obs').value = obs;
        document.getElementById('editarPagoModal').classList.remove('hidden');
    }
    function closeEditarPagoModal() {
        document.getElementById('editarPagoModal').classList.add('hidden');
    }
</script>
@endsection
