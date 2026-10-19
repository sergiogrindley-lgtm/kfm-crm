<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandato DGT Comprador — {{ $tramite->matricula }} — KFM Rota</title>
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
            <span class="w-2.5 h-2.5 rounded-full bg-cyan-400 animate-pulse"></span>
            <span class="font-bold uppercase tracking-wider text-slate-300">Mandato Oficial DGT — Comprador (Adquirente)</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 font-bold text-xs rounded-xl transition flex items-center gap-1.5 shadow-md cursor-pointer">
                <span>🖨️ Imprimir Mandato Comprador</span>
            </button>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl transition">
                Cerrar
            </button>
        </div>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-10 sm:p-14 rounded-2xl shadow-xl border border-slate-200 print-border-none space-y-6 text-xs">
        
        <div class="flex items-start justify-between border-b-2 border-slate-900 pb-4">
            <div>
                <h1 class="text-xl font-black uppercase tracking-tight text-slate-900">MANDATO DE REPRESENTACIÓN (DGT)</h1>
                <p class="text-[11px] text-slate-500 font-semibold uppercase mt-0.5">Colegio Oficial de Gestores Administrativos de Cádiz / Sevilla</p>
            </div>
            <div class="text-right">
                <span class="inline-block bg-slate-900 text-white font-mono font-bold text-sm px-2.5 py-1 rounded">
                    COMPRADOR
                </span>
            </div>
        </div>

        <div class="space-y-3 leading-relaxed text-justify">
            <p>
                D./Dña. <strong class="uppercase text-slate-900 font-bold">{{ $tramite->buyer_nombre }}</strong>, 
                mayor de edad, con documento de identidad nº <strong class="font-mono text-slate-900">{{ $tramite->buyer_dni }}</strong> @if($tramite->buyer_fa) (DOD ID: <strong class="font-mono">{{ $tramite->buyer_fa }}</strong>)@endif, 
                y con domicilio a efectos de notificaciones en <span class="uppercase font-semibold">{{ $tramite->buyer_direccion }}</span>, C.P. <strong>{{ $tramite->buyer_cp ?: '11520' }}</strong> de <strong>ROTA (CÁDIZ)</strong>.
            </p>

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-center font-bold uppercase tracking-widest text-[11px] text-slate-700">
                OTORGA SU REPRESENTACIÓN A:
            </div>

            <p>
                <strong>D. FERNANDO SÁNCHEZ NAVAL</strong>, Gestor Administrativo Colegiado nº <strong>1271</strong> del Ilustre Colegio Oficial de Gestores Administrativos, titular del despacho <strong>GESTORÍA BAHÍA & NAVAL</strong>, con CIF B-11928374 y domicilio profesional en Calle Aurora nº 11, El Puerto de Santa María (Cádiz).
            </p>

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-center font-bold uppercase tracking-widest text-[11px] text-slate-700">
                OBJETO DEL MANDATO:
            </div>

            <p>
                Para que en su nombre y representación promueva, tramite y culmine ante la <strong>Jefatura Provincial de Tráfico de Cádiz (DGT)</strong>, la <strong>Agencia Tributaria de Andalucía</strong> (Modelo 620 / ITP) y cuantas dependencias de la Administración Pública sean precisas, el trámite de <strong>CAMBIO DE TITULARIDAD / TRANSFERENCIA</strong> a favor del mandante del siguiente vehículo:
            </p>

            <div class="bg-indigo-50/60 border border-indigo-200 rounded-xl p-4 my-2 grid grid-cols-2 sm:grid-cols-4 gap-2 text-center">
                <div>
                    <span class="block text-[10px] uppercase font-bold text-indigo-700">Matrícula:</span>
                    <strong class="font-mono text-base text-indigo-950">{{ $tramite->matricula }}</strong>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-indigo-700">Marca:</span>
                    <strong class="text-sm text-indigo-950 uppercase">{{ $tramite->marca }}</strong>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-indigo-700">Modelo:</span>
                    <strong class="text-sm text-indigo-950 uppercase">{{ $tramite->modelo }}</strong>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-indigo-700">Bastidor:</span>
                    <span class="font-mono text-[11px] text-indigo-900 block truncate" title="{{ $tramite->vin }}">{{ $tramite->vin ?: 'S/D' }}</span>
                </div>
            </div>

            <p>
                El mandatario queda facultado para firmar solicitudes, autoliquidaciones, recibir notificaciones, interponer recursos y retirar permisos de circulación y distintivos ambientales definitivos expedidos por la DGT.
            </p>
        </div>

        <div class="pt-6 border-t border-slate-200 grid grid-cols-2 gap-10 text-center">
            <div>
                <span class="block font-bold text-slate-900 uppercase">EL MANDANTE (COMPRADOR)</span>
                <span class="block text-[11px] text-slate-500 uppercase">{{ $tramite->buyer_nombre }}</span>
                <span class="block font-mono text-[10px] text-slate-400">DNI/NIE: {{ $tramite->buyer_dni }}</span>
                <div class="h-20 border-b border-dotted border-slate-400 mx-6 mt-4"></div>
            </div>

            <div>
                <span class="block font-bold text-slate-900 uppercase">EL MANDATARIO</span>
                <span class="block text-[11px] text-slate-500">FERNANDO SÁNCHEZ NAVAL</span>
                <span class="block text-[10px] text-slate-400">Colegiado Nº 1271</span>
                <div class="h-20 border-b border-dotted border-slate-400 mx-6 mt-4"></div>
            </div>
        </div>

        <div class="pt-4 text-center text-[10px] text-slate-400 border-t border-slate-100">
            En Rota (Cádiz), a {{ $tramite->fecha_tramite ?: date('d/m/Y') }} • Gestión tramitada a través de KFM Insurance Rota
        </div>

    </div>

</body>
</html>
