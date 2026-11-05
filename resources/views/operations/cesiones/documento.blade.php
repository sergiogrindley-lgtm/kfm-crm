<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesión de Póliza de Seguro — {{ $cesion->matricula }} — KFM Insurance</title>
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
            <span class="w-2.5 h-2.5 rounded-full bg-sky-400 animate-pulse"></span>
            <span class="font-bold uppercase tracking-wider text-slate-200">Hoja Oficial de Cesión de Póliza</span>
            <span class="text-slate-400">• KFM Insurance Rota</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-sky-600 hover:bg-sky-500 font-bold text-xs rounded-lg transition flex items-center gap-1.5 shadow-md cursor-pointer">
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
                    {{ $cesion->matricula }}
                </span>
                <span class="block text-xs font-mono font-bold text-indigo-700 mt-1 uppercase">
                    PÓLIZA Nº {{ $cesion->numero_poliza }}
                </span>
                <span class="block text-[10px] text-slate-400 mt-1 uppercase font-semibold">
                    {{ $cesion->cartera === '87' ? 'GEICO 87' : 'Patria Hispana 81' }}
                </span>
            </div>
        </div>

        <!-- Fecha Oficial -->
        <div class="text-right text-sm">
            <span>En Rota, a <strong>{{ $dia }} DE {{ $mesNombre }} DE {{ $ano }}</strong></span>
        </div>

        <!-- Declaración Principal de Cesión de Uso, Derechos y Obligaciones -->
        <div class="bg-slate-50/80 p-6 rounded-xl border border-slate-200 space-y-4 text-sm leading-relaxed text-slate-900 text-justify">
            <p>
                Yo, <strong class="uppercase text-black">{{ $cesion->cedente_nombre }}</strong>
                @if($cesion->cedente_dni) (DNI/SSN: <span class="font-mono">{{ $cesion->cedente_dni }}</span>)@endif, 
                con póliza <strong class="font-mono text-indigo-700">#{{ $cesion->numero_poliza }}</strong>,
            </p>
            <p>
                cedo del uso, derechos y obligaciones a <strong class="uppercase text-black">{{ $cesion->cesionario_nombre }}</strong>
            </p>
            <p>
                de mi vehículo <strong class="uppercase text-black">{{ trim("{$cesion->marca} {$cesion->modelo}") ?: 'VEHÍCULO' }}</strong> 
                con matrícula <strong class="font-mono font-bold text-black uppercase">{{ $cesion->matricula }}</strong> 
                @if($cesion->vin) (Bastidor: <span class="font-mono">{{ $cesion->vin }}</span>)@endif.
            </p>

            @if($cesion->tiene_suplemento || $cesion->suplemento_recibo || $cesion->suplemento_cantidad)
                <div class="mt-3 p-3 bg-white rounded-lg border border-slate-300 text-xs space-y-1">
                    <span class="font-bold text-slate-800 uppercase tracking-wider text-[11px] block">Pago de Suplemento de Póliza:</span>
                    <p class="text-slate-700">
                        @if($cesion->suplemento_recibo)
                            Recibo Nº: <strong class="font-mono text-slate-900">{{ $cesion->suplemento_recibo }}</strong>
                        @endif
                        @if($cesion->suplemento_cantidad)
                            <span class="ml-3">Importe: <strong class="text-emerald-800">{{ $cesion->suplemento_cantidad }} €</strong></span>
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Firmas Cruzadas Oficiales -->
        <div class="pt-8 pb-4 flex justify-between items-start text-xs font-semibold text-black">
            <div class="w-5/12">
                <p>Firmado (Nuevo Tomador / Cesionario):</p>
                <p class="text-sm font-bold uppercase mt-1 text-slate-900">{{ $cesion->cesionario_nombre }}</p>
                <div class="h-20 border-b border-dashed border-slate-300 mt-4"></div>
            </div>
            <div class="w-5/12 text-right">
                <p>Firmado (Cedente / Tomador Original):</p>
                <p class="text-sm font-bold uppercase mt-1 text-slate-900">{{ $cesion->cedente_nombre }}</p>
                <div class="h-20 border-b border-dashed border-slate-300 mt-4"></div>
            </div>
        </div>

        <!-- Tarjeta de Datos Militares / Identificación del Nuevo Tomador -->
        <div class="border border-slate-200 rounded-xl p-5 bg-slate-50 text-xs space-y-2 font-medium text-slate-800">
            <div class="font-bold text-[11px] text-slate-500 uppercase tracking-wider mb-2">
                Datos de Identificación del Nuevo Titular (Identity Details)
            </div>
            <p>
                <span class="inline-block w-24 font-bold text-slate-600">SSN / FA:</span> 
                <strong class="font-mono text-black">{{ $cesion->cesionario_ssn ?: '***-**-****' }}</strong>
            </p>
            <p>
                <span class="inline-block w-24 font-bold text-slate-600">DOB:</span> 
                <strong class="font-mono text-black">{{ $cesion->cesionario_dob ?: '-' }}</strong>
            </p>
            <p>
                <span class="inline-block w-24 font-bold text-slate-600">ADDRESS:</span> 
                <strong class="uppercase text-black">{{ $cesion->cesionario_direccion ?: 'LU BASE NAVAL DE ROTA 999' }}</strong>
            </p>
            @if($cesion->cesionario_telefono)
            <p>
                <span class="inline-block w-24 font-bold text-slate-600">PHONE:</span> 
                <strong class="text-black">{{ $cesion->cesionario_telefono }}</strong>
            </p>
            @endif
            @if($cesion->cesionario_email)
            <p>
                <span class="inline-block w-24 font-bold text-slate-600">EMAIL:</span> 
                <strong class="text-black">{{ $cesion->cesionario_email }}</strong>
            </p>
            @endif
        </div>

        <!-- Footer Discreto -->
        <div class="border-t border-slate-200 pt-3 flex items-center justify-between text-[10px] text-slate-400">
            <span>KFM Insurance • Agencia de Seguros Oficial Patria & GEICO</span>
            <span>Plaza del Triunfo Nº 7, Rota • Tel: +34 956 84 00 50</span>
        </div>

    </div>

</body>
</html>
