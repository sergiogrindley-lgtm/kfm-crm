<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesión de Póliza de Seguro — {{ $tramite->matricula }} — KFM Insurance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 14mm 18mm 14mm 18mm;
        }
        body {
            font-family: Arial, 'Helvetica Neue', Helvetica, 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
            background-color: #f8fafc;
            line-height: 1.6;
        }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            body { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .print-sheet {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="p-4 sm:p-8 text-[13px]">

    <!-- Action Bar (No Print) -->
    <div class="max-w-3xl mx-auto mb-6 flex items-center justify-between no-print bg-slate-900 text-white px-5 py-3 rounded-xl shadow-lg">
        <div class="flex items-center space-x-3 text-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="font-bold uppercase tracking-wider text-slate-200">Documento Oficial de Cesión de Póliza</span>
            <span class="text-slate-400">• KFM Insurance Rota</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 font-bold text-xs rounded-lg transition flex items-center gap-1.5 shadow-md cursor-pointer">
                <span>🖨️ Imprimir Cesión</span>
            </button>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-lg transition cursor-pointer">
                Cerrar
            </button>
        </div>
    </div>

    <!-- Official Document Sheet -->
    <div class="max-w-3xl mx-auto bg-white p-12 sm:p-16 rounded-xl shadow-xl border border-slate-200 print-sheet space-y-8 text-slate-900">
        
        <!-- Header Corporativo Oficial con Logo KFM -->
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-5">
            <div class="flex items-center gap-4">
                <img src="{{ asset('img/logo.png') }}" alt="KFM Insurance" class="h-12 w-auto object-contain">
                <div>
                    <h1 class="text-base font-black tracking-wider text-slate-900 uppercase">KFM INSURANCE</h1>
                    <p class="text-xs font-bold text-slate-700 uppercase">PLAZA DEL TRIUNFO Nº 7</p>
                    <p class="text-xs font-semibold text-slate-600 uppercase">11520 ROTA (CÁDIZ)</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-block bg-slate-900 text-white font-mono font-bold text-xs px-2.5 py-1 rounded">
                    {{ $tramite->matricula }}
                </span>
                @if($tramite->numero_poliza)
                    <span class="block text-xs font-mono font-bold text-indigo-700 mt-1 uppercase">
                        PÓLIZA Nº {{ $tramite->numero_poliza }}
                    </span>
                @endif
                <span class="block text-[10px] text-slate-400 mt-1 uppercase font-semibold">Oficina Central & NEX Base Naval</span>
            </div>
        </div>

        <!-- Fecha Oficial (Alineada a la derecha como en el documento modelo) -->
        <div class="text-right text-sm">
            <span>En Rota, a <strong>{{ $dia }} DE {{ $mesNombre }} DE {{ $ano }}</strong></span>
        </div>

        <!-- Declaración Principal de Cesión de Uso y Derechos -->
        <div class="bg-slate-50/80 p-6 rounded-xl border border-slate-200 space-y-4 text-sm leading-relaxed text-slate-900 text-justify">
            <p>
                Yo, <strong class="uppercase text-black">{{ $tramite->seller_nombre }}</strong>, 
                con póliza <strong class="font-mono text-indigo-700">{{ $tramite->numero_poliza ?: ($tramite->policy ? $tramite->policy->policy_number : '__________________') }}</strong>,
            </p>
            <p>
                cedo el uso, derechos y obligaciones a <strong class="uppercase text-black">{{ $tramite->buyer_nombre }}</strong>
            </p>
            <p>
                de mi vehículo <strong class="uppercase text-black">{{ $tramite->marca }} {{ $tramite->modelo }}</strong> 
                con matrícula <strong class="font-mono font-bold text-black uppercase">{{ $tramite->matricula }}</strong> 
                @if($tramite->vin) (Bastidor: <span class="font-mono">{{ $tramite->vin }}</span>)@endif.
            </p>
        </div>

        <!-- Firmas Cruzadas Oficiales -->
        <div class="pt-8 pb-4 flex justify-between items-start text-xs font-semibold text-black">
            <div class="w-5/12">
                <p>Firmado (Comprador / Receptor):</p>
                <p class="text-sm font-bold uppercase mt-1 text-slate-900">{{ $tramite->buyer_nombre }}</p>
                <div class="h-20 border-b border-dashed border-slate-300 mt-4"></div>
            </div>
            <div class="w-5/12 text-right">
                <p>Firmado (Vendedor / Cedente):</p>
                <p class="text-sm font-bold uppercase mt-1 text-slate-900">{{ $tramite->seller_nombre }}</p>
                <div class="h-20 border-b border-dashed border-slate-300 mt-4"></div>
            </div>
        </div>

        <!-- Tarjeta de Datos Militares / Identificación del Receptor (Buyer) -->
        <div class="border border-slate-200 rounded-xl p-5 bg-slate-50 text-xs space-y-1.5 font-medium text-slate-800">
            <div class="font-bold text-[11px] text-slate-500 uppercase tracking-wider mb-2">
                Datos de Identificación del Nuevo Titular (Buyer Identity Details)
            </div>
            <p>
                <span class="inline-block w-20 font-bold text-slate-600">SSN:</span> 
                <strong class="font-mono text-black">{{ $tramite->ssn ?: ($tramite->buyer_fa ?: '***-**-****') }}</strong>
            </p>
            <p>
                <span class="inline-block w-20 font-bold text-slate-600">DOB:</span> 
                <strong class="font-mono text-black">{{ $tramite->buyer_dob ?: '04/04/1995' }}</strong>
            </p>
            @php
                $displayAddress = trim($tramite->box_ship ?: ($tramite->buyer_direccion ?: 'LU BASE NAVAL DE ROTA 999'));
            @endphp
            <p>
                <span class="inline-block w-20 font-bold text-slate-600">ADDRESS:</span> 
                <strong class="uppercase text-black">{{ $displayAddress }}</strong>
            </p>
        </div>

        <!-- Footer Discreto -->
        <div class="border-t border-slate-200 pt-3 flex items-center justify-between text-[10px] text-slate-400">
            <span>KFM Insurance • Agencia de Seguros Oficial Patria & GEICO</span>
            <span>Plaza del Triunfo Nº 7, Rota • Tel: +34 956 84 00 50</span>
        </div>

    </div>

</body>
</html>
