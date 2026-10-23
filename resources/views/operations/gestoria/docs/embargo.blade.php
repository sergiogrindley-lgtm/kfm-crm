<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunicación de Embargos y/o Precintos — {{ $tramite->matricula }} — KFM Insurance</title>
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
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
            <span class="font-bold uppercase tracking-wider text-slate-200">Comunicación de Cargas DGT (Embargos / Precintos)</span>
            <span class="text-slate-400">• Modelo KFM</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 font-bold text-xs rounded-lg transition flex items-center gap-1.5 shadow-md cursor-pointer">
                <span>🖨️ Imprimir Documento</span>
            </button>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-lg transition cursor-pointer">
                Cerrar
            </button>
        </div>
    </div>

    <!-- Official Document Sheet -->
    <div class="max-w-3xl mx-auto bg-white p-12 sm:p-16 rounded-xl shadow-xl border border-slate-200 print-sheet space-y-7 text-slate-900">
        
        <!-- Header con Logo de KFM Insurance -->
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-4">
            <div class="flex items-center gap-4">
                <img src="{{ asset('img/logo.png') }}" alt="KFM Insurance" class="h-11 w-auto object-contain">
                <div>
                    <h2 class="text-xs font-black tracking-wider text-slate-900 uppercase">KFM INSURANCE</h2>
                    <p class="text-[10px] text-slate-500 font-semibold uppercase">Gestoría DGT & Trámites Telemáticos • Rota & NEX Base Naval</p>
                </div>
            </div>
            <div class="text-right">
                <span class="font-mono text-xs font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded border border-slate-300">
                    {{ $tramite->matricula }}
                </span>
                <span class="block text-[9.5px] text-slate-400 mt-1 uppercase font-semibold">Registro de Vehículos DGT</span>
            </div>
        </div>

        <!-- Título Recuadrado Oficial (Exacto al modelo) -->
        <div class="text-center pt-2">
            <div class="border-2 border-slate-900 py-2.5 px-6 inline-block font-bold tracking-wider text-sm uppercase text-slate-900">
                COMUNICACIÓN DE EMBARGOS Y/O PRECINTOS
            </div>
        </div>

        <!-- Cuerpo del Documento -->
        <div class="space-y-5 text-justify text-slate-800 leading-relaxed pt-2">
            
            <!-- Declaración del Comprador -->
            <div>
                <p>
                    <strong class="uppercase text-black">{{ $tramite->buyer_nombre }}</strong>, con DNI/NIE 
                    <strong class="font-mono text-black">{{ $tramite->buyer_dni ?: ($tramite->buyer_fa ?: 'No aportado') }}</strong>,
                </p>
                
                <div class="mt-2.5 ml-4 space-y-1.5 text-xs text-slate-700">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-4 h-4 border border-slate-400 rounded-sm bg-slate-900 text-white flex items-center justify-center text-[10px] font-bold">✓</span>
                        <span>En nombre propio</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-4 h-4 border border-slate-300 rounded-sm bg-white"></span>
                        <span>En representación de <span class="border-b border-dotted border-slate-400 inline-block w-48"></span>, con CIF <span class="border-b border-dotted border-slate-400 inline-block w-28"></span></span>
                    </div>
                </div>
            </div>

            <p>
                declaro que he sido informado/a apropiadamente por la gestoría administrativa de D. / Dña. 
                <strong class="uppercase text-black">FERNANDO SÁNCHEZ DE NIEVA BALLESTA</strong>
            </p>

            <p>
                de que sobre el vehículo matrícula <strong class="font-mono text-black font-bold uppercase">{{ $tramite->matricula }}</strong>, 
                cuya transmisión ante la Dirección General de Tráfico está siendo gestionada por dicha gestoría, 
                existe(n) anotado(s) <span class="bg-amber-50 px-2 py-0.5 border border-amber-300 font-bold font-mono text-slate-900 rounded">1 UN EMBARGO</span> 
                en el Registro de Vehículos de la Jefatura Central de Tráfico, cuyo detalle también me ha sido comunicado.
            </p>

            <p>
                <strong>AUTORIZO</strong> a dicha gestoría administrativa, mediante la firma de este documento, a continuar con la tramitación del expediente.
            </p>

            <p class="pt-2">
                En <strong>ROTA</strong>, a <strong>{{ $dia }}</strong> de <strong>{{ $mesNombre }}</strong> de <strong>{{ $ano }}</strong>
            </p>

            <!-- Firma del Comprador -->
            <div class="pt-12 pb-4">
                <p class="font-bold text-slate-900">
                    Fdo.: <span class="uppercase">{{ $tramite->buyer_nombre }}</span>
                </p>
                <div class="h-24 border-b border-dashed border-slate-300 w-72 mt-4"></div>
            </div>

        </div>

        <!-- Nota al pie de página (Exacta a la imagen) -->
        <div class="border-t border-slate-200 pt-3 text-[10px] text-slate-500">
            <p>¹ Especificar en este campo el número y tipo de carga de que se trate. Ej. "2 EMBARGOS Y 1 PRECINTO"</p>
        </div>

    </div>

</body>
</html>
