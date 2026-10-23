<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portada Expediente DGT — {{ $tramite->matricula }} — KFM Insurance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .print-shadow-none { box-shadow: none !important; border-color: #cbd5e1 !important; }
        }
    </style>
</head>
<body class="p-4 sm:p-8">

    <!-- Action Bar (No Print) -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-lg">
        <div class="flex items-center space-x-3 text-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="font-bold uppercase tracking-wider text-slate-300">Expediente Oficial Gestoría DGT</span>
            <span class="text-slate-400">• Hoja de Ruta & Checklist</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-1.5 shadow-md cursor-pointer">
                <span>🖨️ Imprimir</span>
            </button>
            @php
                $waText = "Hola, os enviamos nuevo expediente de Gestoría DGT desde KFM Insurance:\n\n"
                    . "📋 Matrícula: " . $tramite->matricula . " (" . $tramite->marca . " " . $tramite->modelo . ")\n"
                    . "🔹 Trámite: " . $tramite->tipo . "\n"
                    . "👤 Comprador: " . $tramite->buyer_nombre . " (" . ($tramite->buyer_dni ?: 'DOD ID: ' . $tramite->buyer_fa) . ")\n"
                    . "🚗 Vendedor: " . $tramite->seller_nombre . " (" . ($tramite->seller_dni ?: 'DOD ID: ' . $tramite->seller_fa) . ")\n"
                    . ($tramite->recibo_numero ? ("🧾 Nº Recibo: " . $tramite->recibo_numero . "\n") : "")
                    . ($tramite->tiene_cesion ? "🛡️ Incluye Cesión de Póliza KFM\n" : "")
                    . "📍 Oficina: " . ($tramite->sede ? $tramite->sede->name : 'KFM Rota') . "\n"
                    . "👤 Agente KFM: " . ($tramite->agente ?: 'KFM') . "\n\n"
                    . "Toda la documentación y mandatos están preparados en oficina.";
                $waUrl = "https://api.whatsapp.com/send?text=" . urlencode($waText);
            @endphp
            <a href="{{ $waUrl }}" target="_blank" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5 shadow-md cursor-pointer">
                <span>📲 Mandar a gestoría por WhatsApp</span>
            </a>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl transition">
                Cerrar
            </button>
        </div>
    </div>

    <!-- Official Document Container -->
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-2xl shadow-xl border border-slate-200 print-shadow-none space-y-6">
        
        <!-- Header: KFM + DGT Info -->
        <div class="flex items-start justify-between border-b-2 border-slate-900 pb-5">
            <div class="flex items-start gap-4">
                <img src="{{ asset('img/logo.png') }}" alt="KFM Insurance" class="h-12 w-auto object-contain mt-0.5">
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-slate-900 font-display">KFM INSURANCE & PATRIA GESTORÍA</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Plaza del Triunfo nº 7, 11520 Rota (Cádiz) • Oficina NEX Base Naval Rota</p>
                    <p class="text-[11px] text-slate-400 font-mono">Destino: GESTORÍA BAHÍA & NAVAL • Fernando Sánchez de Nieva Ballesta (Col. 1201)</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-block bg-slate-900 text-white font-mono font-bold text-base px-3 py-1 rounded-lg">
                    {{ $tramite->matricula }}
                </span>
                <span class="block text-[10px] text-slate-400 mt-1 uppercase font-semibold">EXP: KFM-{{ date('Y') }}-{{ str_pad($tramite->id, 4, '0', STR_PAD_LEFT) }}</span>
                @if($tramite->recibo_numero)
                    <span class="inline-block bg-indigo-50 text-indigo-700 font-bold font-mono text-[10px] px-2 py-0.5 rounded border border-indigo-200 mt-1">RECIBO: #{{ $tramite->recibo_numero }}</span>
                @endif
            </div>
        </div>

        <!-- Vehicle Box -->
        <div class="bg-slate-50 border border-slate-300 rounded-xl p-4">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-2">DATOS DEL VEHÍCULO A TRANSFERIR</span>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Matrícula:</span>
                    <strong class="font-mono text-base text-slate-900">{{ $tramite->matricula }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Bastidor (VIN):</span>
                    <strong class="font-mono text-xs text-slate-800 break-all">{{ $tramite->vin ?: 'No especificado' }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Marca / Make:</span>
                    <strong class="text-slate-900 uppercase">{{ $tramite->marca }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Modelo / Model:</span>
                    <strong class="text-slate-900 uppercase">{{ $tramite->modelo }}</strong>
                </div>
            </div>
        </div>

        <!-- Two Columns: Buyer vs Seller -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            
            <!-- Comprador (Buyer) -->
            <div class="border border-indigo-200 bg-indigo-50/30 rounded-xl p-4 space-y-2">
                <div class="flex items-center justify-between border-b border-indigo-100 pb-1.5">
                    <strong class="text-indigo-900 font-bold uppercase text-[11px]">COMPRADOR / BUYER</strong>
                    <span class="text-[10px] bg-indigo-100 text-indigo-800 px-1.5 py-0.5 rounded font-mono font-bold">{{ $tramite->buyer_dni }}</span>
                </div>
                <div class="space-y-1">
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase block font-semibold">Nombre:</span>
                        <strong class="text-slate-900 uppercase">{{ $tramite->buyer_nombre }}</strong>
                    </div>
                    @if($tramite->buyer_fa)
                        <div>
                            <span class="text-slate-400 text-[10px] uppercase block font-semibold">FA # / Militar DoD:</span>
                            <span class="font-mono text-indigo-700 font-bold">{{ $tramite->buyer_fa }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase block font-semibold">Fecha Nacimiento (DOB):</span>
                        <span>{{ $tramite->buyer_dob ?: 'No indicada' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase block font-semibold">Domicilio:</span>
                        <span class="uppercase">{{ $tramite->buyer_direccion }}, {{ $tramite->buyer_cp }}</span>
                    </div>
                </div>
            </div>

            <!-- Vendedor (Seller) -->
            <div class="border border-slate-200 bg-slate-50/50 rounded-xl p-4 space-y-2">
                <div class="flex items-center justify-between border-b border-slate-200 pb-1.5">
                    <strong class="text-slate-800 font-bold uppercase text-[11px]">VENDEDOR / SELLER</strong>
                    <span class="text-[10px] bg-slate-200 text-slate-800 px-1.5 py-0.5 rounded font-mono font-bold">{{ $tramite->seller_dni }}</span>
                </div>
                <div class="space-y-1">
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase block font-semibold">Nombre:</span>
                        <strong class="text-slate-900 uppercase">{{ $tramite->seller_nombre }}</strong>
                    </div>
                    @if($tramite->seller_fa)
                        <div>
                            <span class="text-slate-400 text-[10px] uppercase block font-semibold">FA # / Militar DoD:</span>
                            <span class="font-mono text-slate-700 font-bold">{{ $tramite->seller_fa }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase block font-semibold">Domicilio:</span>
                        <span class="uppercase">{{ $tramite->seller_direccion }}, {{ $tramite->seller_cp }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Checklist Documental (Fiel reproducción del Excel PORTADA) -->
        <div class="border border-slate-300 rounded-xl p-5 space-y-3">
            <span class="text-[11px] font-black uppercase tracking-wider text-slate-700 block">
                CHECKLIST DOCUMENTAL PARA LA GESTORÍA (CONTROL INTERNO KFM)
            </span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                <label class="flex items-center space-x-2.5 p-2 bg-slate-50 rounded-lg border border-slate-200">
                    <input type="checkbox" checked class="w-4 h-4 text-emerald-600 rounded">
                    <span>Documentos de Identidad Comprador (DNI/NIE/Passport/FA)</span>
                </label>
                <label class="flex items-center space-x-2.5 p-2 bg-slate-50 rounded-lg border border-slate-200">
                    <input type="checkbox" checked class="w-4 h-4 text-emerald-600 rounded">
                    <span>Documentos de Identidad Vendedor (DNI/NIE/Passport/FA)</span>
                </label>
                <label class="flex items-center space-x-2.5 p-2 bg-slate-50 rounded-lg border border-slate-200">
                    <input type="checkbox" checked class="w-4 h-4 text-emerald-600 rounded">
                    <span>Permiso de Circulación Original / Ficha Técnica</span>
                </label>
                <label class="flex items-center space-x-2.5 p-2 bg-slate-50 rounded-lg border border-slate-200">
                    <input type="checkbox" checked class="w-4 h-4 text-emerald-600 rounded">
                    <span>Contrato de Compraventa Firmado (3 Ejemplares)</span>
                </label>
                <label class="flex items-center space-x-2.5 p-2 bg-slate-50 rounded-lg border border-slate-200">
                    <input type="checkbox" checked class="w-4 h-4 text-emerald-600 rounded">
                    <span>Mandato Colegiado DGT Comprador</span>
                </label>
                <label class="flex items-center space-x-2.5 p-2 bg-slate-50 rounded-lg border border-slate-200">
                    <input type="checkbox" checked class="w-4 h-4 text-emerald-600 rounded">
                    <span>Mandato Colegiado DGT Vendedor</span>
                </label>
                <label class="flex items-center space-x-2.5 p-2 bg-slate-50 rounded-lg border border-slate-200">
                    <input type="checkbox" checked class="w-4 h-4 text-emerald-600 rounded">
                    <span>Tasas DGT Abonadas (Tasa 4.1 Titularidad)</span>
                </label>
                <label class="flex items-center space-x-2.5 p-2 bg-slate-50 rounded-lg border border-slate-200">
                    <input type="checkbox" {{ $tramite->tiene_cesion ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded">
                    <span>Hoja de Cesión de Póliza KFM (si procede)</span>
                </label>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="bg-slate-900 text-white p-4 rounded-xl flex flex-wrap items-center justify-between text-xs gap-3">
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Importe de Venta Declarado:</span>
                <span class="text-base font-bold text-white">{{ $tramite->precio_venta ?: '1.000,00 €' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Precio Trámite Gestoría:</span>
                <span class="text-base font-bold text-cyan-300">{{ $tramite->precio_tramite ?: '120,00 €' }}</span>
            </div>
            @if($tramite->itp_cuota)
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Impuesto ITP Junta (4%):</span>
                    <span class="text-base font-bold text-amber-400">{{ $tramite->itp_cuota }}</span>
                </div>
            @endif
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Estado del Trámite:</span>
                <span class="inline-block bg-white/20 text-white font-bold px-2 py-0.5 rounded text-[11px]">
                    {{ $tramite->status_label }}
                </span>
            </div>
        </div>

        <!-- Signatures & Date -->
        <div class="pt-6 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500">
            <div>
                <span>En Rota (Cádiz), a <strong>{{ $tramite->fecha_tramite ?: date('d/m/Y') }}</strong></span>
                <span class="block text-[10px] text-slate-400">Tramitador: {{ $tramite->agente ?: 'Chari (NEX)' }}</span>
            </div>
            <div class="text-right">
                <span class="block font-bold text-slate-800">Conforme Gestoría Bahía & Naval</span>
                <div class="h-10 border-b border-dotted border-slate-400 w-44 mt-2"></div>
            </div>
        </div>

    </div>

</body>
</html>
