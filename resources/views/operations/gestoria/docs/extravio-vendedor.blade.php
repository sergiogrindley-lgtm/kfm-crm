<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Declaración de Extravío DGT — {{ $tramite->matricula }} — KFM Rota</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm 15mm 20mm;
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
            <span class="w-2.5 h-2.5 rounded-full bg-rose-400 animate-pulse"></span>
            <span class="font-bold uppercase tracking-wider text-slate-200">Declaración Oficial de Extravío (DGT)</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 font-bold text-xs rounded-lg transition flex items-center gap-1.5 shadow-md cursor-pointer">
                <span>🖨️ Imprimir Declaración</span>
            </button>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-lg transition cursor-pointer">
                Cerrar
            </button>
        </div>
    </div>

    <!-- Official Document Sheet -->
    <div class="max-w-3xl mx-auto bg-white p-12 sm:p-16 rounded-xl shadow-xl border border-slate-200 print-sheet space-y-8 text-slate-900">
        
        <!-- Logos Oficiales: Ministerio del Interior (Izquierda) + DGT (Derecha) -->
        <div class="flex items-center justify-between pb-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/ministerio-interior.jpg') }}" alt="Ministerio del Interior" class="h-14 w-auto object-contain">
            </div>
            <div>
                <img src="{{ asset('img/logo-dgt.png') }}" alt="Dirección General de Tráfico" class="h-10 w-auto object-contain">
            </div>
        </div>

        <!-- Título Centrado Oficial -->
        <div class="text-center pt-2 pb-4">
            <h1 class="text-base font-bold uppercase tracking-wider text-black">
                DECLARACIÓN DE EXTRAVÍO
            </h1>
        </div>

        <!-- Texto Oficial Exacto al Modelo DGT KFM -->
        <div class="space-y-6 leading-relaxed text-justify text-slate-900 text-sm">
            <p>
                D. / Dña. <span class="bg-slate-50 px-2 py-0.5 border-b border-slate-400 font-bold uppercase text-black inline-block min-w-[240px]">{{ $tramite->seller_nombre }}</span> con 
                DNI / NIE <span class="bg-slate-50 px-2 py-0.5 border-b border-slate-400 font-mono font-bold text-black inline-block min-w-[120px]">{{ $tramite->seller_dni ?: ($tramite->seller_fa ?: 'No aportado') }}</span>, 
                DECLARA haber extraviado la documentación del vehículo matrícula 
                <span class="bg-slate-50 px-2 py-0.5 border-b border-slate-400 font-mono font-bold text-black uppercase inline-block min-w-[100px] text-center">{{ $tramite->matricula }}</span>, 
                marca <span class="bg-slate-50 px-2 py-0.5 border-b border-slate-400 font-bold uppercase text-black inline-block min-w-[180px]">{{ $tramite->marca }} {{ $tramite->modelo }}</span>, 
                requerida en el trámite de <span class="bg-slate-50 px-2 py-0.5 border-b border-slate-400 font-bold uppercase text-black inline-block min-w-[140px] text-center">{{ strtoupper($tramite->tipo ?: 'TRANSFERENCIA') }}</span> 
                que se solicita ante la Jefatura Provincial de Tráfico de <span class="bg-slate-50 px-2 py-0.5 border-b border-slate-400 font-bold uppercase text-black inline-block min-w-[100px] text-center">CÁDIZ</span>.
            </p>
        </div>

        <!-- Fecha y Lugar -->
        <div class="pt-8 text-sm">
            <p>
                En <span class="bg-slate-50 px-3 py-0.5 border-b border-slate-400 font-bold uppercase inline-block min-w-[100px] text-center">ROTA</span>, 
                a <span class="bg-slate-50 px-2 py-0.5 border-b border-slate-400 font-bold inline-block min-w-[36px] text-center">{{ $dia }}</span> 
                de <span class="bg-slate-50 px-3 py-0.5 border-b border-slate-400 font-bold uppercase inline-block min-w-[110px] text-center">{{ $mesNombre }}</span> 
                de 20<span class="bg-slate-50 px-2 py-0.5 border-b border-slate-400 font-bold inline-block min-w-[30px] text-center">{{ substr($ano, -2) }}</span>
            </p>
        </div>

        <!-- Firma -->
        <div class="pt-12 pb-6 text-center">
            <p class="text-xs text-slate-500 font-semibold mb-12">(firma)</p>
            <div class="h-20 border-b border-dashed border-slate-300 w-64 mx-auto"></div>
            <p class="text-[11px] font-bold uppercase text-slate-800 mt-2">{{ $tramite->seller_nombre }}</p>
        </div>

        <!-- Pie de página discreto -->
        <div class="border-t border-slate-200 pt-3 flex items-center justify-between text-[10px] text-slate-400">
            <span>DGT • Ministerio del Interior • Gobierno de España</span>
            <span>Tramitado vía KFM Insurance Rota</span>
        </div>

    </div>

</body>
</html>
