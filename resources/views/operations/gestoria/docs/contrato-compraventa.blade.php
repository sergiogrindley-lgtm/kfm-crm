<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato de Compra-Venta — {{ $tramite->matricula }} — KFM Insurance</title>
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
            line-height: 1.45;
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
<body class="p-3 sm:p-6 text-[12.5px]">

    <!-- Action Bar (No Print) -->
    <div class="max-w-3xl mx-auto mb-4 flex items-center justify-between no-print bg-slate-900 text-white px-5 py-2.5 rounded-xl shadow-lg">
        <div class="flex items-center space-x-3 text-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="font-bold uppercase tracking-wider text-slate-200">Contrato Oficial de Compra-Venta</span>
            <span class="text-slate-400">• Modelo KFM Rota</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 font-bold text-xs rounded-lg transition flex items-center gap-1.5 shadow-md cursor-pointer">
                <span>🖨️ Imprimir Contrato</span>
            </button>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-lg transition cursor-pointer">
                Cerrar
            </button>
        </div>
    </div>

    <!-- Official Document Sheet -->
    <div class="max-w-3xl mx-auto bg-white p-10 sm:p-12 rounded-xl shadow-xl border border-slate-200 print-sheet space-y-4 text-slate-900">
        
        <!-- Header con Logo de KFM Insurance -->
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-3">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/logo.png') }}" alt="KFM Insurance" class="h-10 w-auto object-contain">
                <div>
                    <h2 class="text-xs font-black tracking-wider text-slate-900 uppercase">KFM INSURANCE</h2>
                    <p class="text-[10px] text-slate-500 font-semibold uppercase">Agencia de Seguros & Gestoría DGT • Plaza del Triunfo Nº 7, Rota</p>
                </div>
            </div>
            <div class="text-right">
                <span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-300">
                    {{ $tramite->matricula }}
                </span>
                <span class="block text-[9.5px] text-slate-400 mt-0.5 font-semibold">REF: {{ $tramite->recibo_numero ? 'REC #' . $tramite->recibo_numero : 'KFM-DGT' }}</span>
            </div>
        </div>

        <!-- Título Centrado Oficial -->
        <div class="text-center pt-1 pb-1">
            <h1 class="text-base font-bold uppercase tracking-wider text-black pb-1 inline-block border-b-2 border-black">
                CONTRATO DE COMPRA-VENTA
            </h1>
        </div>

        <!-- Lugar y Fecha -->
        <p class="text-slate-900 pt-1">
            En <strong>ROTA</strong>, a <strong>{{ $dia }}</strong> de <strong>{{ $mesNombre }}</strong> de <strong>{{ $ano }}</strong>, reunidos
        </p>

        @php
            $formatAddress = function($dir, $cp) {
                $dir = trim($dir ?: '');
                $cp = trim($cp ?: '11520');
                if (empty($dir)) {
                    return "ROTA (CÁDIZ), CP {$cp}";
                }
                if (stripos($dir, 'ROTA') !== false) {
                    return "{$dir}, CP {$cp}";
                }
                return "ROTA (CÁDIZ), CP {$cp}, {$dir}";
            };
        @endphp

        <!-- Comparecientes: VENDEDOR PRIMERO (Según modelo oficial KFM) -->
        <div class="space-y-3 text-justify">
            <p>
                <strong>DE UNA PARTE, D</strong> <strong class="uppercase text-black">{{ $tramite->seller_nombre }}</strong><br>
                con domicilio en <strong>{{ $formatAddress($tramite->seller_direccion, $tramite->seller_cp) }}</strong>, 
                con NIF/CIF con número <strong class="font-mono text-black">{{ $tramite->seller_dni ?: ($tramite->seller_fa ?: 'No aportado') }}</strong>, 
                en su propio nombre y en concepto de <strong>VENDEDOR</strong>.
            </p>

            <p>
                <strong>Y DE UNA PARTE, D</strong> <strong class="uppercase text-black">{{ $tramite->buyer_nombre }}</strong><br>
                con domicilio en <strong>{{ $formatAddress($tramite->buyer_direccion, $tramite->buyer_cp) }}</strong>, 
                con NIF/CIF con número <strong class="font-mono text-black">{{ $tramite->buyer_dni ?: ($tramite->buyer_fa ?: 'No aportado') }}</strong>, 
                en su propio nombre y en concepto de <strong>COMPRADOR</strong>.
            </p>
        </div>

        <!-- Manifiestan -->
        <div class="pt-2 text-center">
            <strong class="tracking-widest uppercase text-xs text-black">MANIFIESTAN</strong>
        </div>

        <p class="text-justify">
            Que han convenido y por el presente documento lo llevan a efecto formalizar contrato de compra-venta del vehículo cuyas características señalan a continuación:
        </p>

        <!-- Datos del Vehículo -->
        <div class="pl-8 sm:pl-12 space-y-1 font-semibold text-slate-900 my-2">
            <p>Bastidor Nº <span class="font-mono font-bold text-black">{{ $tramite->vin ?: 'No especificado' }}</span></p>
            <p>Matrícula <span class="font-mono font-bold text-black uppercase">{{ $tramite->matricula }}</span></p>
            <p>Marca <span class="uppercase font-bold text-black">{{ $tramite->marca }} {{ $tramite->modelo }}</span></p>
        </div>

        <!-- Cláusulas Exactas del Modelo KFM -->
        <div class="space-y-2.5 text-justify">
            <p>
                <strong>1º)</strong> El comprador, queda obligado a efectuar la transferencia que la legislación vigente ordena, aceptando cuantas responsabilidades se originen contra el vehículo a partir del día de la fecha, quedando conforme de las condiciones mecánicas y generales de conservación con que recibe el vehículo y los accesorios correspondientes, salvo aquellos ocultos que tengan su origen en dolo o mala fe del vendedor.
            </p>

            <p>
                <strong>2º)</strong> El vendedor declara que no pesa sobre el vehículo ninguna carga o gravamen ni impuesto, deuda o sanción pendientes de abono en la fecha de la firma de este contrato, comprometiéndose en caso contrario a regularizar tal situación a su exclusivo cargo.
            </p>
        </div>

        <!-- Precio Convenido -->
        <p class="text-justify pt-1 font-medium">
            El precio de la compraventa es de <strong class="uppercase text-black">{{ $precioLetras }} ({{ $precioFormatted }})</strong>
        </p>

        <p class="text-justify pt-1">
            Y para que así conste firma el presente contrato en el lugar y fecha arriba indicados.
        </p>

        <!-- Espacio de Firmas Oficiales -->
        <div class="pt-14 pb-4 flex justify-between items-start text-xs font-semibold text-black">
            <div class="w-2/5">
                <p class="font-bold">El Vendedor,</p>
                <p class="text-[11px] text-slate-500 font-normal uppercase mt-0.5">{{ $tramite->seller_nombre }}</p>
                <div class="h-16"></div>
            </div>
            <div class="w-2/5 text-right">
                <p class="font-bold">El Comprador,</p>
                <p class="text-[11px] text-slate-500 font-normal uppercase mt-0.5">{{ $tramite->buyer_nombre }}</p>
                <div class="h-16"></div>
            </div>
        </div>

        <!-- Pie de página discreto -->
        <div class="border-t border-slate-200 pt-2 flex items-center justify-between text-[9px] text-slate-400">
            <span>KFM Insurance • Gestoría DGT Rota & NEX Base Naval</span>
            <span>Documento formalizado conforme al Código Civil y Ley de Tráfico</span>
        </div>

    </div>

</body>
</html>
