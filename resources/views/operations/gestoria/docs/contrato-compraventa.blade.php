<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato de Compra-Venta Oficial — {{ $tramite->matricula }} — KFM Rota</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; line-height: 1.6; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            body { background: white; padding: 0 !important; }
            .no-print { display: none !important; }
            .print-border-none { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="p-4 sm:p-8">

    <!-- Action Bar (No Print) -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-lg">
        <div class="flex items-center space-x-3 text-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 animate-pulse"></span>
            <span class="font-bold uppercase tracking-wider text-slate-300">Contrato Oficial de Compra-Venta (3 Ejemplares)</span>
            <span class="text-slate-400">• Modelo Legal DGT Rota</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 font-bold text-xs rounded-xl transition flex items-center gap-1.5 shadow-md cursor-pointer">
                <span>🖨️ Imprimir Contrato (3 Ejemplares)</span>
            </button>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl transition">
                Cerrar
            </button>
        </div>
    </div>

    <!-- Official Document Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-10 sm:p-14 rounded-2xl shadow-xl border border-slate-200 print-border-none space-y-6 text-sm">
        
        <!-- Document Title -->
        <div class="text-center border-b-2 border-slate-900 pb-4">
            <h1 class="text-2xl font-black uppercase tracking-wider text-slate-900">CONTRATO DE COMPRA-VENTA</h1>
            <p class="text-xs text-slate-500 uppercase tracking-widest mt-1">Vehículo Automóvil Usado • Término Municipal de Rota (Cádiz)</p>
        </div>

        <!-- Lugar y Fecha -->
        <p class="text-justify">
            En <strong>Rota (Cádiz)</strong>, a las <strong>{{ date('H:i') }} horas</strong>, a <strong>{{ $tramite->fecha_tramite ?: date('d/m/Y') }}</strong>, reunidos:
        </p>

        <!-- Comparecientes -->
        <div class="space-y-4">
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <p class="text-justify">
                    <strong>DE UNA PARTE, D./Dña.</strong> <span class="font-bold uppercase">{{ $tramite->buyer_nombre }}</span>, 
                    con domicilio en <span class="uppercase">{{ $tramite->buyer_direccion }}</span>, 
                    C.P. <strong>{{ $tramite->buyer_cp ?: '11520' }}</strong>, 
                    con DNI / NIE / CIF número <strong class="font-mono">{{ $tramite->buyer_dni }}</strong> @if($tramite->buyer_fa) (DOD ID / FA: <span class="font-mono">{{ $tramite->buyer_fa }}</span>)@endif, 
                    en su propio nombre y concepto de <strong>COMPRADOR</strong>.
                </p>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <p class="text-justify">
                    <strong>Y DE OTRA PARTE, D./Dña.</strong> <span class="font-bold uppercase">{{ $tramite->seller_nombre }}</span>, 
                    con domicilio en <span class="uppercase">{{ $tramite->seller_direccion }}</span>, 
                    C.P. <strong>{{ $tramite->seller_cp ?: '11520' }}</strong>, 
                    con DNI / NIE / CIF número <strong class="font-mono">{{ $tramite->seller_dni }}</strong> @if($tramite->seller_fa) (DOD ID / FA: <span class="font-mono">{{ $tramite->seller_fa }}</span>)@endif, 
                    en su propio nombre y concepto de <strong>VENDEDOR</strong>.
                </p>
            </div>
        </div>

        <!-- Cláusula Manifiestan -->
        <div class="space-y-3 pt-2">
            <h3 class="text-center font-black tracking-widest uppercase text-xs text-slate-800">MANIFIESTAN</h3>
            
            <p class="text-justify leading-relaxed text-xs">
                Declarando el vendedor en el presente acto, que el vehículo es de su completa y exclusiva propiedad y se halla totalmente libre de embargos, cargas y gravámenes incluidos los derivados de la legislación vigente, así como queda el vendedor obligado a atender las obligaciones contraídas hasta la firma del presente contrato, respondiendo el vendedor en todo momento de las responsabilidades que por cualquier motivo, causa o razón apareciesen contra el vehículo automóvil objeto del presente contrato, obligándose a indemnizar al comprador por cualquier reclamación aun las injustificadas que pudieran hacerse y que correspondan a actos anteriores al día de la fecha de la firma del contrato.
            </p>

            <p class="text-justify leading-relaxed text-xs">
                El comprador queda obligado a efectuar la transferencia que la legislación vigente ordena ante la Jefatura Provincial de Tráfico, aceptando cuantas responsabilidades se originen contra el vehículo a partir del día y de la fecha, quedando conforme de las condiciones mecánicas y generales de conservación con que recibe el vehículo y los accesorios correspondientes.
            </p>
        </div>

        <!-- Identificación del Vehículo -->
        <div class="border-2 border-slate-900 rounded-xl p-4 my-4 bg-slate-50/50">
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-500">Matrícula:</span>
                    <strong class="font-mono text-lg text-slate-900">{{ $tramite->matricula }}</strong>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-500">Marca:</span>
                    <strong class="text-base text-slate-900 uppercase">{{ $tramite->marca }}</strong>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-500">Modelo:</span>
                    <strong class="text-base text-slate-900 uppercase">{{ $tramite->modelo }}</strong>
                </div>
            </div>
            @if($tramite->vin)
                <div class="text-center pt-2 mt-2 border-t border-slate-200">
                    <span class="text-[10px] uppercase font-bold text-slate-500">Número de Bastidor (VIN):</span>
                    <span class="font-mono font-bold text-xs text-slate-800 ml-2">{{ $tramite->vin }}</span>
                </div>
            @endif
        </div>

        <!-- Precio de Compraventa -->
        <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-xl text-center">
            <span class="text-xs uppercase font-bold text-indigo-900">El precio convenido para la compraventa es de:</span>
            <div class="text-2xl font-black text-indigo-950 font-mono mt-0.5">
                {{ $tramite->precio_venta ?: '1.000,00 €' }}
            </div>
            <span class="text-[11px] text-indigo-700">Importe satisfecho íntegramente en este acto por el comprador al vendedor</span>
        </div>

        <p class="text-justify text-xs">
            Y para que así conste y surta los efectos oportunos ante la Dirección General de Tráfico, organismos tributarios y autoridades competentes, se firman tres ejemplares a un solo efecto en el lugar y fecha arriba indicados.
        </p>

        <!-- Firmas -->
        <div class="pt-8 grid grid-cols-2 gap-12 text-center text-xs">
            <div>
                <span class="block font-bold text-slate-900 uppercase">EL COMPRADOR</span>
                <span class="text-[11px] text-slate-500 block uppercase">{{ $tramite->buyer_nombre }}</span>
                <span class="text-[10px] font-mono text-slate-400 block">DNI/NIE: {{ $tramite->buyer_dni }}</span>
                <div class="h-20 border-b border-dotted border-slate-400 mx-6 mt-4"></div>
            </div>

            <div>
                <span class="block font-bold text-slate-900 uppercase">EL VENDEDOR</span>
                <span class="text-[11px] text-slate-500 block uppercase">{{ $tramite->seller_nombre }}</span>
                <span class="text-[10px] font-mono text-slate-400 block">DNI/NIE: {{ $tramite->seller_dni }}</span>
                <div class="h-20 border-b border-dotted border-slate-400 mx-6 mt-4"></div>
            </div>
        </div>

        <div class="pt-6 text-center text-[10px] text-slate-400 border-t border-slate-200">
            Documento formalizado en las oficinas de KFM Insurance • Agencia de Seguros y Gestoría Oficial • Rota (Cádiz)
        </div>

    </div>

</body>
</html>
