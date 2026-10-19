<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Declaración de Extravío DGT — {{ $tramite->matricula }} — KFM Rota</title>
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
            <span class="w-2.5 h-2.5 rounded-full bg-rose-400 animate-pulse"></span>
            <span class="font-bold uppercase tracking-wider text-slate-300">Declaración Jurada de Extravío de Documentación (DGT)</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 font-bold text-xs rounded-xl transition flex items-center gap-1.5 shadow-md cursor-pointer">
                <span>🖨️ Imprimir Declaración Extravío</span>
            </button>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl transition">
                Cerrar
            </button>
        </div>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-10 sm:p-14 rounded-2xl shadow-xl border border-slate-200 print-border-none space-y-6 text-xs">
        
        <div class="text-center border-b-2 border-slate-900 pb-4">
            <h1 class="text-xl font-black uppercase tracking-wider text-slate-900">DECLARACIÓN RESPONSABLE DE EXTRAVÍO DE DOCUMENTACIÓN</h1>
            <p class="text-[11px] text-slate-500 font-semibold uppercase mt-0.5">Jefatura Provincial de Tráfico de Cádiz • Dirección General de Tráfico</p>
        </div>

        <div class="space-y-4 leading-relaxed text-justify text-sm">
            <p>
                D./Dña. <strong class="uppercase text-slate-900 font-bold">{{ $tramite->seller_nombre }}</strong>, 
                mayor de edad, con documento de identidad nº <strong class="font-mono text-slate-900">{{ $tramite->seller_dni }}</strong> @if($tramite->seller_fa) (DOD ID: <strong class="font-mono">{{ $tramite->seller_fa }}</strong>)@endif, 
                con domicilio en <span class="uppercase font-semibold">{{ $tramite->seller_direccion }}</span>, C.P. <strong>{{ $tramite->seller_cp ?: '11520' }}</strong> de <strong>ROTA (CÁDIZ)</strong>.
            </p>

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-center font-bold uppercase tracking-widest text-xs text-slate-700">
                DECLARA BAJO JURAMENTO:
            </div>

            <p>
                Que es el actual titular o transmitente autorizado del vehículo automóvil cuyas características se reseñan a continuación:
            </p>

            <div class="bg-rose-50/60 border border-rose-200 rounded-xl p-4 my-2 grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs">
                <div>
                    <span class="block text-[10px] uppercase font-bold text-rose-800">Matrícula:</span>
                    <strong class="font-mono text-base text-rose-950">{{ $tramite->matricula }}</strong>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-rose-800">Marca:</span>
                    <strong class="text-sm text-rose-950 uppercase">{{ $tramite->marca }}</strong>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-rose-800">Modelo:</span>
                    <strong class="text-sm text-rose-950 uppercase">{{ $tramite->modelo }}</strong>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-rose-800">Bastidor (VIN):</span>
                    <span class="font-mono text-[11px] text-rose-900 block truncate" title="{{ $tramite->vin }}">{{ $tramite->vin ?: 'S/D' }}</span>
                </div>
            </div>

            <p>
                Y que por motivos ajenos a su voluntad, ha sufrido el <strong>EXTRAVÍO / DETERIORO / SUSTRACCIÓN</strong> del <strong>Permiso de Circulación original y/o Tarjeta de Inspección Técnica</strong> del citado vehículo, no obrando en su poder ni hallándose depositado o pignorado en entidad crediticia ni administrativa alguna.
            </p>

            <p>
                Asimismo, asume cuantas responsabilidades legales y administrativas pudieran derivarse de la presente declaración en caso de inexactitud o falsedad, comprometiéndose a entregar inmediatamente en dependencias de Tráfico la documentación extraviada en el supuesto de ser recuperada.
            </p>
        </div>

        <div class="pt-8 border-t border-slate-200 text-center">
            <span class="block text-xs text-slate-600 mb-2">En Rota (Cádiz), a <strong>{{ $tramite->fecha_tramite ?: date('d/m/Y') }}</strong></span>
            <span class="block font-bold text-slate-900 uppercase text-xs">FIRMA DEL DECLARANTE (TITULAR/VENDEDOR)</span>
            <div class="h-24 border-b border-dotted border-slate-400 max-w-xs mx-auto mt-4"></div>
            <span class="block text-[11px] font-mono text-slate-400 mt-2">DNI / NIE: {{ $tramite->seller_dni }}</span>
        </div>

        <div class="pt-4 text-center text-[10px] text-slate-400 border-t border-slate-100">
            Diligencia cotejada por KFM Insurance • Plaza del Triunfo 7, Rota • Remitida a Gestoría Bahía & Naval
        </div>

    </div>

</body>
</html>
