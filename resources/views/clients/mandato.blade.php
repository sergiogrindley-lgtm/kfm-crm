<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandato Oficial de Representación DGT — {{ $policy->matricula }} — {{ $client->full_name }}</title>
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
                <span>Imprimir Mandato DGT (Ctrl + P)</span>
            </button>
            <a href="{{ route('clients.cesion', [$client, $policy]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md transition-all cursor-pointer">
                <span>📄 Ver Hoja de Cesión</span>
            </a>
        </div>
    </div>

    <!-- Official Document Sheet -->
    <div class="page-container max-w-4xl w-full bg-white rounded-2xl shadow-xl border border-slate-200 p-8 sm:p-12 text-slate-900">
        
        <!-- Header con Gestoría Colegiada -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 border-b-2 border-slate-900 gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xl">⚖️</span>
                    <h1 class="font-display font-extrabold text-lg text-[#0c3547]">MANDATO DE REPRESENTACIÓN</h1>
                </div>
                <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">COLEGIO OFICIAL DE GESTORES ADMINISTRATIVOS</p>
                <p class="text-[11px] text-slate-400">Trámites ante la Jefatura Provincial de Tráfico (DGT) — Ministerio del Interior</p>
            </div>

            <div class="text-left sm:text-right text-xs">
                <div class="font-bold text-slate-800 text-sm">{{ $gestor['despacho'] }}</div>
                <div class="text-slate-500">Gestor Colegiado Nº: <strong class="text-slate-800">{{ $gestor['colegiado'] }}</strong></div>
                <div class="text-slate-400 text-[10px]">{{ $gestor['colegio'] }}</div>
            </div>
        </div>

        <!-- Cuerpo Legal del Mandato -->
        <div class="mt-6 space-y-4 text-xs text-slate-700 leading-relaxed text-justify">
            <p>
                <strong>D./Dña. {{ $client->full_name }}</strong>, con documento de identidad / NIE / Pasaporte nº <strong class="font-mono">{{ $client->doc_identidad ?: 'Z1928374P' }}</strong>, y domicilio a efectos de notificaciones en <strong class="font-mono">{{ $client->direccion_local ?: 'C/ San Juan de Puerto Rico 12, Rota (Cádiz)' }}</strong>, en concepto de <strong>MANDANTE</strong>, dice y otorga:
            </p>

            <p>
                Que por el presente documento confiere, con carácter específico, <strong>MANDATO DE REPRESENTACIÓN</strong> a favor del Gestor Administrativo en ejercicio <strong>D. {{ $gestor['nombre'] }}</strong>, con número de colegiado <strong>{{ $gestor['colegiado'] }}</strong>, perteneciente al <em>{{ $gestor['colegio'] }}</em>, y al despacho profesional <strong>{{ $gestor['despacho'] }}</strong>, con domicilio en {{ $gestor['direccion'] }}, en concepto de <strong>MANDATARIO</strong>.
            </p>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">ASUNTO OBJETO DEL MANDATO:</span>
                <div class="font-bold text-slate-900 text-sm">
                    TRANSFERENCIA, CAMBIO DE TITULARIDAD Y LIQUIDACIÓN TRIBUTARIA ANTE LA DIRECCIÓN GENERAL DE TRÁFICO (DGT) Y CONSEJERÍA DE HACIENDA DE LA JUNTA DE ANDALUCÍA
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 text-[11px]">
                    <div>Matrícula: <strong class="font-mono text-slate-900">{{ $policy->matricula }}</strong></div>
                    <div>Marca/Modelo: <strong class="text-slate-900">{{ $policy->marca }} {{ $policy->modelo }}</strong></div>
                    <div class="col-span-2">Bastidor (VIN): <strong class="font-mono text-cyan-900">{{ $policy->vin }}</strong></div>
                </div>
            </div>

            <p>
                Para que promueva, solicite y realice cuantos trámites sean precisos ante cualesquiera órganos de la Administración General del Estado, Autonómica y Local, y muy especialmente ante la <strong>Jefatura Provincial de Tráfico</strong> y la <strong>Agencia Tributaria</strong>, pudiendo firmar impresos, presentar solicitudes, recibir notificaciones, subsanar deficiencias y retirar el nuevo Permiso de Circulación o documentación acreditativa.
            </p>

            <p class="text-[11px] text-slate-500 italic bg-amber-50/50 p-2.5 rounded-lg border border-amber-100">
                El presente mandato se confiere al amparo del artículo 5 de la Ley 39/2015, de 1 de octubre, del Procedimiento Administrativo Común de las Administraciones Públicas, de los artículos 1709 a 1739 del Código Civil, y del artículo 1 del Estatuto Orgánico de la Profesión de Gestor Administrativo (Decreto 424/1963).
            </p>
        </div>

        <!-- Lugar, Fecha y Firmas -->
        <div class="mt-8 pt-4 border-t border-slate-200">
            <div class="text-xs text-slate-600 mb-6">
                En <strong>ROTA / EL PUERTO DE SANTA MARÍA (CÁDIZ)</strong>, a {{ date('d') }} de {{ \Carbon\Carbon::now()->translatedFormat('F') }} de {{ date('Y') }}.
            </div>

            <div class="grid grid-cols-2 gap-10 text-center text-xs">
                <div class="border-t border-dashed border-slate-400 pt-3">
                    <span class="block font-bold text-slate-800">{{ $client->full_name }}</span>
                    <span class="text-[10px] text-slate-500 uppercase">Firma del Mandante (Titular / Transmitente)</span>
                </div>

                <div class="border-t border-dashed border-slate-400 pt-3">
                    <span class="block font-bold text-slate-800">{{ $gestor['nombre'] }}</span>
                    <span class="text-[10px] text-slate-500 uppercase">Firma y Sello del Gestor Administrativo Colegiado</span>
                </div>
            </div>
        </div>

        <div class="mt-10 text-center text-[10px] text-slate-400 border-t border-slate-100 pt-3">
            Expediente tramitado telemáticamente a través del servicio integrado de KFM Insurance & Patria Gestoría • Sede NEX NAVSTA Rota
        </div>
    </div>

</body>
</html>
