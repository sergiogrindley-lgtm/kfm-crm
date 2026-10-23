<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandato DGT Vendedor — {{ $tramite->matricula }} — KFM Rota</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 16mm 12mm 16mm;
        }
        body {
            font-family: Arial, 'Helvetica Neue', Helvetica, 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
            background-color: #f8fafc;
            line-height: 1.5;
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
<body class="p-3 sm:p-6 text-[12px]">

    <!-- Action Bar (No Print) -->
    <div class="max-w-3xl mx-auto mb-4 flex items-center justify-between no-print bg-slate-900 text-white px-5 py-2.5 rounded-xl shadow-lg">
        <div class="flex items-center space-x-3 text-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
            <span class="font-bold uppercase tracking-wider text-slate-200">Mandato Oficial DGT — Vendedor</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 font-bold text-xs rounded-lg transition flex items-center gap-1.5 shadow-md cursor-pointer">
                <span>🖨️ Imprimir Mandato Vendedor</span>
            </button>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-lg transition cursor-pointer">
                Cerrar
            </button>
        </div>
    </div>

    <!-- Official Document Sheet -->
    <div class="max-w-3xl mx-auto bg-white p-10 sm:p-12 rounded-xl shadow-xl border border-slate-200 print-sheet space-y-4 text-slate-900">
        
        <!-- Header Oficial Colegiado (QA + Escudo) -->
        <div class="text-center pb-2 border-b border-slate-200">
            <img src="{{ asset('img/logo-dgt-gestor.png') }}" alt="Consejo General de Colegios de Gestores Administrativos de España" class="h-14 w-auto mx-auto object-contain">
        </div>

        <div class="space-y-3 leading-relaxed text-justify text-[11.5px] text-slate-800">
            <p>
                D./Dña. <strong class="uppercase text-black">{{ $tramite->seller_nombre }}</strong> con DNI/NIE 
                <strong class="font-mono text-black">{{ $tramite->seller_dni ?: ($tramite->seller_fa ?: 'No aportado') }}</strong>, 
                que declara tener poder suficiente para actuar en su propio nombre y en concepto de <strong>MANDANTE</strong>, 
                con domicilio a efectos de notificaciones en <strong class="uppercase text-black">{{ $tramite->seller_direccion ?: 'ROTA (CÁDIZ)' }}</strong>, 
                CP <strong class="text-black">{{ $tramite->seller_cp ?: '11520' }}</strong> de ROTA (CÁDIZ), dice y otorga:
            </p>

            <p>
                Que por el presente documento confiere, con carácter específico, <strong>MANDATO CON REPRESENTACIÓN</strong> a favor de, ellos Gestor/es Administrativo/s en ejercicio, 
                D./Dña. <strong class="text-black">FERNANDO SÁNCHEZ DE NIEVA BALLESTA</strong>, con DNI <strong class="font-mono text-black">75150272G</strong>, 
                número de colegiado <strong class="font-mono text-black">1201</strong>, perteneciente al Ilustre Colegio Oficial de Gestores Administrativos de 
                <strong class="text-black">SEVILLA</strong>, y al despacho profesional con domicilio en <strong class="text-black">CALLE AURORA Nº 11, CP 11500, EL PUERTO DE SANTA MARÍA (CÁDIZ)</strong>, 
                en concepto de <strong>MANDATARIO/S</strong>, para su actuación ante todos los órganos y entidades de la Administración del Estado, Autonómica, Provincial y Local que resulten competentes, y específicamente ante la Dirección General de Tráfico del Ministerio del Interior del Gobierno de España, para que promueva, solicite y realice todos los trámites necesarios en relación con el siguiente <strong>ASUNTO</strong>:
            </p>

            <div class="p-2.5 bg-slate-50 border border-slate-300 rounded font-bold text-center text-xs text-black">
                SOLICITUD DE TRANSMISIÓN DE VEHÍCULO — MATRÍCULA: <span class="font-mono">{{ $tramite->matricula }}</span>
                @if($tramite->vin) (BASTIDOR: <span class="font-mono">{{ $tramite->vin }}</span>)@endif
            </div>

            <p class="text-[10px] text-slate-600 leading-normal">
                El presente mandato, que se regirá por los artículos 1709 a 1739 del Código Civil, se confiere al amparo del artículo 5 de la Ley 39/2015, de 1 de octubre, del Procedimiento Administrativo Común de las Administraciones Públicas, y del artículo 1 del Estatuto Orgánico de la Profesión de Gestor Administrativo, aprobado por Decreto 424/1963.
            </p>
        </div>

        <div class="pt-6 border-t border-slate-200 grid grid-cols-2 gap-8 text-center text-xs">
            <div>
                <p class="text-slate-500 mb-1">En Rota, a <strong>{{ $tramite->fecha_tramite ?: date('d/m/Y') }}</strong></p>
                <strong class="block text-black uppercase">EL MANDANTE (VENDEDOR)</strong>
                <p class="text-[11px] text-slate-600 uppercase mt-0.5">{{ $tramite->seller_nombre }}</p>
                <div class="h-20 border-b border-dashed border-slate-300 mx-4 mt-2"></div>
            </div>

            <div>
                <p class="text-slate-500 mb-1">En El Puerto de Santa María, a <strong>{{ $tramite->fecha_tramite ?: date('d/m/Y') }}</strong></p>
                <strong class="block text-black uppercase">EL MANDATARIO</strong>
                <p class="text-[11px] text-slate-600 uppercase mt-0.5">FERNANDO SÁNCHEZ DE NIEVA BALLESTA</p>
                <div class="h-20 border-b border-dashed border-slate-300 mx-4 mt-2"></div>
            </div>
        </div>

        <div class="border-t border-slate-200 pt-2 flex items-center justify-between text-[9px] text-slate-400">
            <span>Colegio Oficial de Gestores Administrativos • Consejo General</span>
            <span>Tramitado a través de KFM Insurance Rota</span>
        </div>

    </div>

</body>
</html>
