<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado Internacional de Seguro (Carta Verde) — {{ $policy->matricula }} — {{ $client->full_name }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #0f172a;
        }
        .font-display {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .page-container {
                box-shadow: none !important;
                border: 2px solid #16a34a !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            @page {
                size: A4 portrait;
                margin: 12mm 15mm 12mm 15mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 px-4 flex flex-col items-center">

    <!-- Action Bar (Hidden on print) -->
    <div class="no-print max-w-4xl w-full mb-6 flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Volver a la Ficha de {{ $client->full_name }}</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-md transition-all cursor-pointer">
                <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Imprimir Carta Verde Oficial (Ctrl + P)</span>
            </button>
        </div>
    </div>

    <!-- Official Document Sheet (Green Border Format) -->
    <div class="page-container max-w-4xl w-full bg-white rounded-2xl shadow-xl border-4 border-emerald-600 p-8 sm:p-10 text-slate-900">
        
        <!-- Header -->
        <div class="bg-emerald-50 border-b-2 border-emerald-600 p-4 -m-8 sm:-m-10 mb-6 text-center">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-800 block mb-0.5">SISTEMA INTERNACIONAL DE SEGURO DEL AUTOMÓVIL • CARTA VERDE</span>
            <h1 class="font-display font-black text-lg text-emerald-950 uppercase tracking-tight">CERTIFICADO INTERNACIONAL DE SEGURO (CIS)</h1>
            <span class="text-[10px] text-emerald-700 font-medium">INTERNATIONAL MOTOR INSURANCE CARD • CARTE INTERNATIONALE D'ASSURANCE AUTOMOBILE</span>
        </div>

        <!-- Box 1 to 4 -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs border border-slate-300 p-3 rounded-xl bg-slate-50 mb-4">
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 block">1. País Emisor</span>
                <strong class="text-sm font-black text-slate-800">E (ESPAÑA)</strong>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 block">2. Código Aseguradora</span>
                <strong class="text-sm font-mono text-slate-800">C-04 / KFM 81</strong>
            </div>
            <div class="col-span-2">
                <span class="text-[9px] uppercase font-bold text-slate-400 block">3. Número de Póliza</span>
                <strong class="text-sm font-mono text-emerald-900">#{{ $policy->numero_poliza }}</strong>
            </div>
        </div>

        <!-- Box 5: Período de Validez -->
        <div class="border border-slate-300 p-3 rounded-xl mb-4 text-xs">
            <span class="text-[9px] uppercase font-bold text-slate-400 block mb-1">4. Período de Validez de la Cobertura (Válido desde / hasta)</span>
            <div class="flex items-center justify-between font-mono font-bold text-sm bg-emerald-50/60 p-2 rounded-lg border border-emerald-200">
                <div>DESDE: <span class="text-emerald-900">{{ $policy->fecha_pago ?: '15/11/2025' }}</span></div>
                <div>HASTA: <span class="text-emerald-900">{{ $policy->fecha_vencimiento ?: '15/11/2026' }}</span></div>
            </div>
        </div>

        <!-- Box 6: Matrícula y Vehículo -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 border border-slate-300 p-3 rounded-xl mb-4 text-xs bg-slate-50">
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 block">5. Matrícula</span>
                <strong class="text-base font-mono font-bold text-slate-900 bg-white px-2 py-0.5 rounded border border-slate-300 inline-block">{{ $policy->matricula }}</strong>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 block">6. Categoría y Marca</span>
                <strong class="text-xs text-slate-800 block">TURISMO • {{ $policy->marca }} {{ $policy->modelo }}</strong>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 block">7. Número de Bastidor (VIN)</span>
                <strong class="text-xs font-mono text-cyan-900 block truncate" title="{{ $policy->vin }}">{{ $policy->vin }}</strong>
            </div>
        </div>

        <!-- Box 7: Tomador y Dirección -->
        <div class="border border-slate-300 p-3 rounded-xl mb-4 text-xs">
            <span class="text-[9px] uppercase font-bold text-slate-400 block">8. Nombre y Dirección del Tomador del Seguro (Titular)</span>
            <div class="font-bold text-sm text-slate-900 mt-1">{{ $client->full_name }}</div>
            <div class="text-slate-600 text-xs">{{ $client->direccion_local ?: 'C/ San Juan de Puerto Rico 12, 11520 Rota (Cádiz)' }}</div>
            <div class="text-slate-500 text-[11px]">Personal Militar NAVSTA Rota • DNI/NIE: <strong class="font-mono">{{ $client->doc_identidad ?: 'Z1928374P' }}</strong></div>
        </div>

        <!-- Box 8: Tabla de Países Válidos (Sello UNECE) -->
        <div class="border border-slate-300 rounded-xl overflow-hidden mb-4 text-center">
            <div class="bg-slate-100 p-1.5 text-[9px] font-bold uppercase text-slate-500 border-b border-slate-300">
                9. Validez Territorial de la Cobertura (Países Miembros del Convenio Inter-Bureaux)
            </div>
            <div class="grid grid-cols-6 sm:grid-cols-12 gap-1 p-2 text-[10px] font-bold font-mono">
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">A</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">B</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">BG</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">CY</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">CZ</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">D</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">DK</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded font-black border border-emerald-400">E</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">EST</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">F</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">FIN</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded font-black border border-emerald-400">GB</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">GR</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">H</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">HR</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">I</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">IRL</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">IS</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">L</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">LT</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">LV</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">M</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">N</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">NL</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded font-black border border-emerald-400">P</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">PL</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">RO</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">S</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">SK</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">SLO</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">CH</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">AL</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded font-black border border-emerald-400">AND</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">BIH</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">MA</span>
                <span class="bg-emerald-100 text-emerald-800 p-1 rounded">TN</span>
            </div>
        </div>

        <!-- Sello de la Aseguradora y Firma -->
        <div class="grid grid-cols-2 gap-6 pt-3 text-xs border-t border-slate-200">
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 block mb-0.5">Entidad Aseguradora Emisora</span>
                <strong class="text-slate-800 block">PATRIA HISPANA SEGUROS / ALLIANZ</strong>
                <span class="text-slate-500 text-[11px]">Agencia Exclusiva KFM Insurance • Código Oficial DGSFP: 81 / 87</span>
            </div>
            <div class="text-right">
                <span class="text-[9px] uppercase font-bold text-slate-400 block mb-0.5">Sede Operativa Emisora</span>
                <strong class="text-slate-800 block">OFICINA NEX NAVAL STATION ROTA</strong>
                <span class="text-slate-500 text-[11px]">Agente Autorizado: Chari • NAVSTA Rota</span>
            </div>
        </div>

    </div>

</body>
</html>
