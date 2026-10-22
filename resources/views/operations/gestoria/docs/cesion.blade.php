<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesión de Póliza KFM — {{ $tramite->matricula }}</title>
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
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="font-bold uppercase tracking-wider text-slate-300">Documento Oficial de Cesión de Póliza KFM</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 font-bold text-xs rounded-xl transition flex items-center gap-1.5 shadow-md cursor-pointer">
                <span>🖨️ Imprimir Hoja de Cesión</span>
            </button>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl transition">
                Cerrar
            </button>
        </div>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-10 sm:p-14 rounded-2xl shadow-xl border border-slate-200 print-border-none space-y-6 text-xs">
        
        <!-- Header Corporate -->
        <div class="flex items-start justify-between border-b-2 border-slate-900 pb-5">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-slate-900 font-display">KFM INSURANCE</h1>
                <p class="text-xs font-semibold text-slate-600 mt-0.5">PLAZA DEL TRIUNFO Nº 7 • 11520 ROTA (CÁDIZ)</p>
                <p class="text-[11px] text-slate-400">Oficina NEX Navy Exchange Base Naval Rota • Tel: 956 84 00 50</p>
            </div>
            <div class="text-right">
                <span class="inline-block bg-emerald-600 text-white font-bold text-xs uppercase px-3 py-1 rounded-lg">
                    CESIÓN DE SEGURO
                </span>
                <span class="block text-[11px] font-mono text-slate-500 mt-1">En Rota, a {{ $tramite->fecha_tramite ?: date('d/m/Y') }}</span>
            </div>
        </div>

        <div class="space-y-6 text-sm leading-relaxed text-justify py-4">
            <p>
                <strong>Yo, D./Dña.</strong> <span class="uppercase font-bold text-slate-900">{{ $tramite->seller_nombre }}</span>, 
                con SSN / DOD ID <strong class="font-mono text-slate-900">{{ $tramite->seller_fa ?: ($tramite->seller_dni ?: '***-**-****') }}</strong>, 
                en calidad de tomador de la póliza de seguro asociada al vehículo reseñado,
            </p>

            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl font-medium text-emerald-950">
                <strong>CEDO EL USO, DERECHOS Y OBLIGACIONES DE LA PÓLIZA DE SEGURO A:</strong>
            </div>

            <p>
                <strong>D./Dña.</strong> <span class="uppercase font-bold text-slate-900">{{ $tramite->buyer_nombre }}</span>, 
                con SSN (Social Security Number) <strong class="font-mono text-slate-900">{{ $tramite->ssn ?: '***-**-****' }}</strong>@if($tramite->buyer_fa) (DOD ID: <strong class="font-mono">{{ $tramite->buyer_fa }}</strong>)@endif, 
                con fecha de nacimiento <strong>{{ $tramite->buyer_dob ?: 'No indicada' }}</strong> y domicilio en <span class="uppercase font-semibold">{{ $tramite->buyer_direccion }}</span> (CP: {{ $tramite->buyer_cp ?: '11520' }}), 
                correspondiente a mi vehículo:
            </p>

            <!-- Vehicle Identification Box -->
            <div class="bg-slate-50 border border-slate-300 rounded-xl p-4 grid grid-cols-2 sm:grid-cols-4 gap-3 text-center text-xs">
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Matrícula:</span>
                    <strong class="font-mono text-base text-slate-900">{{ $tramite->matricula }}</strong>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Marca:</span>
                    <strong class="text-sm text-slate-900 uppercase">{{ $tramite->marca }}</strong>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Modelo:</span>
                    <strong class="text-sm text-slate-900 uppercase">{{ $tramite->modelo }}</strong>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Bastidor:</span>
                    <span class="font-mono text-[11px] text-slate-800 block truncate">{{ $tramite->vin ?: 'S/D' }}</span>
                </div>
            </div>

            <p class="text-xs text-slate-600">
                Ambas partes aceptan la cesión de la cobertura aseguradora en los mismos términos vigentes hasta el vencimiento natural de la anualidad de la póliza, autorizando a <strong>KFM Insurance</strong> a gestionar el correspondiente suplemento de cambio de tomador y conductor habitual ante la compañía aseguradora correspondiente (Patria Hispana / GEICO).
            </p>
        </div>

        <!-- Military Identity Box (Fiel al Excel Cesion Poliza) -->
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 grid grid-cols-3 gap-3 text-xs">
            <div>
                <span class="block text-[10px] uppercase font-bold text-slate-400">SSN (Social Security):</span>
                <span class="font-mono font-bold text-slate-800">{{ $tramite->ssn ?: '***-**-****' }}</span>
            </div>
            <div>
                <span class="block text-[10px] uppercase font-bold text-slate-400">DOB (Date of Birth):</span>
                <span class="font-mono font-bold text-slate-800">{{ $tramite->buyer_dob ?: '19/11/1995' }}</span>
            </div>
            <div>
                <span class="block text-[10px] uppercase font-bold text-slate-400">BOX / SHIP (Navy):</span>
                <span class="font-mono font-bold text-slate-800">{{ $tramite->box_ship ?: 'PSC 819 / NAVSTA ROTA' }}</span>
            </div>
        </div>

        <!-- Signatures -->
        <div class="pt-8 border-t border-slate-200 grid grid-cols-2 gap-12 text-center text-xs">
            <div>
                <span class="block font-bold text-slate-900 uppercase">FIRMADO (CEDENTE / TOMADOR ANTERIOR):</span>
                <span class="text-[11px] text-slate-500 block uppercase mt-0.5">{{ $tramite->seller_nombre }}</span>
                <div class="h-20 border-b border-dotted border-slate-400 mx-6 mt-4"></div>
            </div>

            <div>
                <span class="block font-bold text-slate-900 uppercase">FIRMADO (CESIONARIO / NUEVO TOMADOR):</span>
                <span class="text-[11px] text-slate-500 block uppercase mt-0.5">{{ $tramite->buyer_nombre }}</span>
                <div class="h-20 border-b border-dotted border-slate-400 mx-6 mt-4"></div>
            </div>
        </div>

        <div class="pt-4 text-center text-[10px] text-slate-400 border-t border-slate-100">
            Diligencia KFM Insurance Rota • Trámite integrado con Gestoría Bahía & Naval
        </div>

    </div>

</body>
</html>
