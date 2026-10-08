<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesión de Póliza KFM — {{ $policy->matricula }} — {{ $client->full_name }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #0f172a;
        }
        .font-display {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .page-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            @page {
                size: A4 portrait;
                margin: 15mm 15mm 15mm 15mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 px-4 flex flex-col items-center">

    <!-- Action Bar (Hidden on print) -->
    <div class="no-print max-w-4xl w-full mb-6 flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Volver a la Ficha de {{ $client->full_name }}</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0c3547] hover:bg-[#145a78] text-white text-xs font-bold shadow-md transition-all cursor-pointer">
                <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Imprimir Documento Oficial (Ctrl + P)</span>
            </button>
            <a href="{{ route('gestoria.demo', ['client_id' => $client->id, 'policy_id' => $policy->id]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-700 hover:bg-rose-800 text-white text-xs font-bold shadow-md transition-all cursor-pointer">
                <span>⚡ Ver en Gestoría Demo</span>
            </a>
        </div>
    </div>

    <!-- Official Printable Document Page (A4 Formatted) -->
    <div class="page-container max-w-4xl w-full bg-white rounded-2xl shadow-xl border border-slate-200/90 p-10 md:p-14 relative overflow-hidden">
        
        <!-- Subtle Top Border Brand Accent -->
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#071f2b] via-[#0c3547] to-[#00739c]"></div>

        <!-- Corporate Header -->
        <div class="flex items-start justify-between border-b-2 border-slate-900/10 pb-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="p-2 bg-white rounded-xl border border-slate-200 shadow-sm">
                    <img src="{{ asset('img/logo.png') }}" alt="KFM Insurance" class="h-16 w-auto object-contain">
                </div>
                <div>
                    <h1 class="text-xl font-bold font-display tracking-tight text-[#0c3547]">KFM INSURANCE</h1>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Correduría de Seguros & Gestoría Patria</p>
                    <p class="text-[11px] text-slate-600 mt-1 font-mono">
                        Plaza del Triunfo Nº 7 · 11520 Rota (Cádiz)<br>
                        Oficina NEX: Shopping Complex NAVSTA Rota · Tel: 956 84 00 50
                    </p>
                </div>
            </div>

            <div class="text-right">
                <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 border border-slate-200 rounded-lg text-[11px] font-mono font-bold uppercase">
                    Ref: KFM-CES-{{ date('Y') }}-{{ str_pad($policy->id, 5, '0', STR_PAD_LEFT) }}
                </span>
                <p class="text-xs text-slate-500 mt-2 font-medium">
                    Oficina Emisora: <strong class="text-slate-800">{{ $client->sede?->name ?: 'NEX (Base Naval)' }}</strong>
                </p>
                <p class="text-xs text-slate-500 font-medium">
                    Agente Gestor: <strong class="text-slate-800">{{ $policy->agente ?: 'Chari' }}</strong>
                </p>
            </div>
        </div>

        <!-- Date & Location -->
        <div class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider mb-6">
            En Rota (Cádiz), a <span class="text-slate-900 font-bold underline decoration-slate-300 underline-offset-4">{{ \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y') }}</span>
        </div>

        <!-- Document Title -->
        <div class="text-center mb-8">
            <h2 class="text-base sm:text-lg font-bold font-display text-slate-900 tracking-wide uppercase border-y border-slate-200 py-2.5 bg-slate-50">
                Documento de Cesión de Uso, Derechos y Obligaciones de Vehículo / Póliza
            </h2>
            <p class="text-[11px] text-slate-500 mt-1 italic">
                Régimen SOFA / Base Naval de Rota — Notificación oficial para Correduría de Seguros y Tramitación Administrativa
            </p>
        </div>

        <!-- Legal Statement Body -->
        <div class="space-y-4 text-xs md:text-sm text-slate-700 leading-relaxed text-justify mb-8">
            <p>
                Por medio del presente documento, yo, <strong class="text-slate-900 font-bold uppercase">{{ $client->full_name }}</strong>, 
                con documento de identidad / pasaporte <strong class="font-mono text-slate-900 font-bold">{{ $client->doc_identidad ?: 'N/D' }}</strong>, 
                titular de la póliza de seguro número <strong class="font-mono text-slate-900 font-bold">{{ $policy->numero_poliza ?: '1842910' }}</strong> 
                suscrita con la entidad aseguradora <strong class="text-slate-900 font-bold">{{ $policy->aseguradora ?: 'GEICO Overseas / Patria' }}</strong>,
            </p>

            <p class="bg-cyan-50/50 p-4 rounded-xl border border-cyan-100 text-slate-800">
                Declaro libre y voluntariamente que <strong>CEDO Y TRANSMITO el pleno uso, derechos, responsabilidades y obligaciones</strong> dimanantes de la citada póliza y del vehículo asegurado a favor de:
            </p>

            <!-- Receptor / Cesionario Card -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="block text-slate-400 font-medium">Nombre completo del Cesionario:</span>
                    <span class="font-bold text-slate-900 text-sm uppercase">{{ $buyer['nombre'] }} {{ $buyer['apellido'] }}</span>
                </div>
                <div>
                    <span class="block text-slate-400 font-medium">Documento / NIE / ID:</span>
                    <span class="font-mono font-bold text-slate-900 text-sm">{{ $buyer['doc_identidad'] }}</span>
                </div>
                <div>
                    <span class="block text-slate-400 font-medium">Fecha de Nacimiento (DOB):</span>
                    <span class="font-semibold text-slate-800">{{ $buyer['dob'] }}</span>
                </div>
                <div>
                    <span class="block text-slate-400 font-medium">Domicilio / Unidad:</span>
                    <span class="font-semibold text-slate-800">{{ $buyer['direccion'] }}</span>
                </div>
            </div>

            <!-- Vehicle Summary -->
            <div class="border border-slate-200 rounded-xl overflow-hidden mt-4">
                <div class="bg-slate-800 text-white px-4 py-2 font-display font-bold text-xs uppercase tracking-wider flex justify-between">
                    <span>Datos del Vehículo Objeto de la Cesión</span>
                    <span class="font-mono font-normal text-cyan-300">POV Particular</span>
                </div>
                <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-3 text-xs bg-white">
                    <div>
                        <span class="block text-slate-400">Marca:</span>
                        <strong class="text-slate-800 uppercase">{{ $policy->marca ?: 'FORD' }}</strong>
                    </div>
                    <div>
                        <span class="block text-slate-400">Modelo:</span>
                        <strong class="text-slate-800 uppercase">{{ $policy->modelo ?: 'FOCUS TITANIUM' }}</strong>
                    </div>
                    <div>
                        <span class="block text-slate-400">Matrícula:</span>
                        <strong class="font-mono bg-slate-100 px-2 py-0.5 rounded border border-slate-300 text-slate-900 text-sm">{{ $policy->matricula ?: '5931LKP' }}</strong>
                    </div>
                    <div>
                        <span class="block text-slate-400">Nº Bastidor (VIN):</span>
                        <strong class="font-mono text-slate-800">{{ $policy->vin ?: '1FADP5CU3DL298491' }}</strong>
                    </div>
                </div>
            </div>

            <p class="text-[11px] text-slate-500 leading-normal pt-2">
                Ambas partes manifiestan su conformidad con las condiciones estipuladas, comprometiéndose el cesionario al cumplimiento de cuantas obligaciones deriven del contrato de seguro y la tenencia del vehículo, facultando a <strong>KFM Insurance & Gestoría Patria</strong> para la tramitación de este expediente.
            </p>
        </div>

        <!-- Signatures Blocks (3 Columns: Cedente, Cesionario, Sello KFM) -->
        <div class="grid grid-cols-3 gap-6 pt-6 border-t-2 border-slate-200 text-center">
            <!-- Firma Cedente -->
            <div class="flex flex-col justify-between h-36 border border-dashed border-slate-300 rounded-xl p-3 bg-slate-50/50">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-700">El Cedente (Titular Saliente)</span>
                <div class="h-14"></div>
                <div class="border-t border-slate-400 pt-1 text-[11px]">
                    <strong class="block text-slate-900 font-bold uppercase">{{ $client->full_name }}</strong>
                    <span class="text-slate-500 font-mono text-[10px]">{{ $client->doc_identidad }}</span>
                </div>
            </div>

            <!-- Firma Cesionario -->
            <div class="flex flex-col justify-between h-36 border border-dashed border-slate-300 rounded-xl p-3 bg-slate-50/50">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-700">El Cesionario (Nuevo Titular)</span>
                <div class="h-14"></div>
                <div class="border-t border-slate-400 pt-1 text-[11px]">
                    <strong class="block text-slate-900 font-bold uppercase">{{ $buyer['nombre'] }} {{ $buyer['apellido'] }}</strong>
                    <span class="text-slate-500 font-mono text-[10px]">{{ $buyer['doc_identidad'] }}</span>
                </div>
            </div>

            <!-- Sello Correduría KFM -->
            <div class="flex flex-col justify-between h-36 border border-slate-200 rounded-xl p-3 bg-cyan-50/30">
                <span class="text-[11px] font-bold uppercase tracking-wider text-[#0c3547]">Sello & Validación KFM</span>
                <div class="flex items-center justify-center h-14">
                    <span class="text-[10px] font-mono text-cyan-800 uppercase px-2 py-1 border border-cyan-300 rounded bg-white/80">
                        ✓ TRAMITADO EN {{ strtoupper($client->sede?->slug === 'central' ? 'PUEBLO' : 'NEX ROTA') }}
                    </span>
                </div>
                <div class="border-t border-cyan-300 pt-1 text-[11px]">
                    <strong class="block text-[#0c3547]">KFM INSURANCE & PATRIA</strong>
                    <span class="text-slate-500 text-[10px]">Agente: {{ $policy->agente ?: 'Chari' }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400 font-mono">
            <span>KFM INSURANCE SERVICE CENTER · NAVSTA ROTA BASE EXCHANGE (NEX)</span>
            <span>COD. ASEGURADORA: {{ $policy->codigo_aseguradora ?: '87' }} · REG. ROTA 11520</span>
        </div>

    </div>

</body>
</html>
