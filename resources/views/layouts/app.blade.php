<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KFM Insurance — Panel CRM')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kfm: {
                            navy: '#0c3547',
                            dark: '#071f2b',
                            primary: '#145a78',
                            cerulean: '#00739c',
                            sky: '#0284c7',
                            light: '#f0f9ff',
                            slate: '#f8fafc',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800 antialiased">

    <!-- Top Corporate Bar -->
    <header class="bg-gradient-to-r from-kfm-dark via-kfm-navy to-kfm-primary text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="bg-white p-1.5 rounded-lg shadow-sm group-hover:scale-105 transition-transform flex items-center justify-center">
                            <img src="{{ asset('img/logo.png') }}" alt="KFM Insurance" class="h-10 w-auto object-contain">
                        </div>
                        <div>
                            <span class="block text-xl font-bold tracking-tight font-display text-white">KFM INSURANCE</span>
                            <span class="block text-xs font-medium text-cyan-200 tracking-wider uppercase">CRM Correduría de Seguros & NEX Rota</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Tabs -->
                <nav class="hidden md:flex items-center space-x-2">
                    <a href="{{ route('home') }}" 
                       class="px-3.5 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center space-x-2 {{ request()->routeIs('home') && !request('sede_id') && !request('agente') ? 'bg-white/20 text-white shadow-inner' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Buscador Clientes</span>
                    </a>

                    <a href="{{ route('clients.create') }}" 
                       class="px-3.5 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center space-x-2 {{ request()->routeIs('clients.create') ? 'bg-emerald-500 text-white shadow-md' : 'bg-emerald-600/80 hover:bg-emerald-500 text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Nuevo Cliente</span>
                    </a>

                    <!-- Dropdown Herramientas NEX (Accesos Rápidos Chari) -->
                    <div class="relative">
                        <button type="button" 
                                onclick="toggleHerramientasDropdown(event)" 
                                class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-200 hover:bg-white/10 hover:text-white transition-colors flex items-center space-x-1.5 cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            <span>Herramientas NEX</span>
                            <svg class="w-3.5 h-3.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        
                        <div id="herramientasDropdown" class="hidden absolute left-0 mt-2 w-84 bg-white rounded-2xl shadow-2xl border border-slate-200 py-1.5 z-50 text-xs divide-y divide-slate-100 overflow-hidden">
                            <div class="px-4 py-2 bg-slate-50 flex items-center justify-between text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                <span>Suite Integrada Oficina NEX</span>
                                <span class="bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded text-[9px] font-bold">100% Nativo</span>
                            </div>
                            
                            <!-- 1. Decodificador VIN -->
                            <button type="button" onclick="openVinDecoderModal()" class="w-full text-left flex items-start space-x-3 px-4 py-2.5 hover:bg-cyan-50/70 transition text-slate-800 cursor-pointer">
                                <span class="text-base p-1.5 rounded-lg bg-[#0c3547] text-cyan-300">⚡</span>
                                <div>
                                    <strong class="block text-[#0c3547] font-bold text-xs flex items-center gap-1.5">
                                        <span>Decodificador VIN (NHTSA)</span>
                                        <span class="text-[9px] bg-emerald-600 text-white px-1.5 py-0.5 rounded font-bold uppercase">En vivo</span>
                                    </strong>
                                    <span class="text-[11px] text-slate-600">Ficha técnica oficial de bastidores US en 0.2s</span>
                                </div>
                            </button>

                            <!-- 2. Calculadora Fiscal ITP & DGT -->
                            <button type="button" onclick="openItpCalculatorModal()" class="w-full text-left flex items-start space-x-3 px-4 py-2.5 hover:bg-amber-50/70 transition text-slate-800 cursor-pointer">
                                <span class="text-base p-1.5 rounded-lg bg-amber-100 text-amber-800">📊</span>
                                <div>
                                    <strong class="block text-slate-900 font-bold text-xs flex items-center gap-1.5">
                                        <span>Calculadora ITP Andalucía & DGT</span>
                                        <span class="text-[9px] bg-amber-600 text-white px-1.5 py-0.5 rounded font-bold uppercase">Modelo 620</span>
                                    </strong>
                                    <span class="text-[11px] text-slate-600">Tablas BOE depreciación, tasa 4.1 y total trámite</span>
                                </div>
                            </button>

                            <!-- 3. Asistente y Traductor Militar -->
                            <button type="button" onclick="openTranslatorModal()" class="w-full text-left flex items-start space-x-3 px-4 py-2.5 hover:bg-indigo-50/70 transition text-slate-800 cursor-pointer">
                                <span class="text-base p-1.5 rounded-lg bg-indigo-100 text-indigo-800">🔤</span>
                                <div>
                                    <strong class="block text-slate-900 font-bold text-xs flex items-center gap-1.5">
                                        <span>Asistente & Traductor Navy (EN ⇄ ES)</span>
                                        <span class="text-[9px] bg-indigo-600 text-white px-1.5 py-0.5 rounded font-bold uppercase">Plantillas</span>
                                    </strong>
                                    <span class="text-[11px] text-slate-600">Traducción rápida y emails tipo para marineros</span>
                                </div>
                            </button>

                            <!-- 4. Calculadora de ITV Militar -->
                            <button type="button" onclick="openItvCalculatorModal()" class="w-full text-left flex items-start space-x-3 px-4 py-2.5 hover:bg-emerald-50/70 transition text-slate-800 cursor-pointer">
                                <span class="text-base p-1.5 rounded-lg bg-emerald-100 text-emerald-800">🚗</span>
                                <div>
                                    <strong class="block text-slate-900 font-bold text-xs flex items-center gap-1.5">
                                        <span>Calculadora ITV Militar (VEIASA)</span>
                                        <span class="text-[9px] bg-slate-700 text-white px-1.5 py-0.5 rounded font-bold uppercase">Periodicidad</span>
                                    </strong>
                                    <span class="text-[11px] text-slate-600">Vencimientos según año y requisitos POV Rota</span>
                                </div>
                            </button>

                            <!-- 5. Tracking de Embarque PCS My POV -->
                            <button type="button" onclick="openPcsTrackingModal()" class="w-full text-left flex items-start space-x-3 px-4 py-2.5 hover:bg-sky-50/70 transition text-slate-800 cursor-pointer">
                                <span class="text-base p-1.5 rounded-lg bg-sky-100 text-sky-800">⚓</span>
                                <div>
                                    <strong class="block text-slate-900 font-bold text-xs flex items-center gap-1.5">
                                        <span>Rastreo Marítimo POV (VPC Rota)</span>
                                        <span class="text-[9px] bg-sky-600 text-white px-1.5 py-0.5 rounded font-bold uppercase">Aduana</span>
                                    </strong>
                                    <span class="text-[11px] text-slate-600">Seguimiento de llegada a Base y pase aduanero</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </nav>

                <!-- Team Badge -->
                <div class="flex items-center space-x-2">
                    <div class="hidden lg:flex items-center bg-white/10 rounded-full px-3 py-1.5 border border-white/10 text-xs">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                        <span class="text-slate-200">Equipo:</span>
                        <span class="font-bold text-white ml-1">Kerry • Coral • Conchi • Chari</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Subbar: Oficinas & Agentes -->
        <div class="bg-kfm-dark/60 border-t border-white/10 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-wrap items-center justify-between text-xs gap-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-slate-400 uppercase tracking-wider font-semibold mr-1">Oficina:</span>
                    <a href="{{ route('home') }}" 
                       class="px-2.5 py-1 rounded-md font-medium transition {{ !request('sede_id') ? 'bg-cyan-500 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        Todas
                    </a>
                    <a href="{{ route('home', ['sede_id' => 1]) }}" 
                       class="px-2.5 py-1 rounded-md font-medium transition flex items-center space-x-1.5 {{ request('sede_id') == 1 ? 'bg-cyan-500 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span>Central (Pueblo)</span>
                    </a>
                    <a href="{{ route('home', ['sede_id' => 2]) }}" 
                       class="px-2.5 py-1 rounded-md font-medium transition flex items-center space-x-1.5 {{ request('sede_id') == 2 ? 'bg-cyan-500 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <span class="w-2 h-2 rounded-full bg-sky-400"></span>
                        <span>NEX (Base Naval)</span>
                    </a>

                    <span class="text-slate-500 mx-2">|</span>

                    <span class="text-slate-400 uppercase tracking-wider font-semibold mr-1">Agente:</span>
                    @foreach(['Kerry', 'Coral', 'Conchi', 'Chari'] as $ag)
                        <a href="{{ route('home', array_merge(request()->only('sede_id'), ['agente' => $ag])) }}" 
                            class="px-2 py-0.5 rounded font-medium transition {{ request('agente') === $ag ? 'bg-white/20 text-white font-bold' : 'text-slate-400 hover:text-slate-200' }}">
                            {{ $ag }}
                        </a>
                    @endforeach
                </div>

                <div class="hidden sm:block text-slate-400 text-right">
                    <span>Correos: <strong>info@</strong> • <strong>patria@</strong></span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 7 0 0118 0z"/></svg>
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between space-y-2 sm:space-y-0">
            <div>
                <span class="font-semibold text-kfm-navy">KFM Insurance & Patria Gestoría</span> — Códigos Aseguradora: 81 (Patria) / 87
            </div>
            <div class="text-slate-400">
                Oficina Central: Plaza Triunfo, 7 | Oficina NEX: Navy Exchange NAVSTA Rota | Tel: 956 84 00 50
            </div>
        </div>
    </footer>

    <!-- Modal Decodificador de Bastidores Oficial (US DOT / NHTSA VPIC) -->
    <div id="vinDecoderModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full border border-slate-200 overflow-hidden transform transition-all">
            <!-- Modal Header -->
            <div class="bg-[#0c3547] text-white p-5 flex items-center justify-between border-b border-[#1b4b61]">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-300 flex items-center justify-center text-xl font-bold border border-cyan-400/30">
                        ⚡
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-base text-white tracking-wide">Decodificador Oficial de Bastidores (VIN)</h3>
                            <span class="text-[10px] bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 px-2 py-0.5 rounded-full font-bold uppercase">US DOT API</span>
                        </div>
                        <p class="text-xs text-cyan-100/80">Consulta instantánea de ficha técnica para vehículos de personal militar (POV)</p>
                    </div>
                </div>
                <button type="button" onclick="closeVinDecoderModal()" class="text-slate-300 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-5 max-h-[80vh] overflow-y-auto">
                <!-- Barra de Búsqueda de Bastidor -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Número de Bastidor (VIN de 17 caracteres)
                    </label>
                    <div class="flex items-center gap-2">
                        <div class="relative flex-1">
                            <input type="text" id="vinInput" maxlength="17" placeholder="Ej: 1FADP5CU3DL298491"
                                   class="w-full font-mono text-base font-bold uppercase tracking-widest px-4 py-2.5 rounded-xl border border-slate-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 outline-none transition uppercase"
                                   onkeydown="if(event.key === 'Enter') ejecutarDecodificarVin()">
                        </div>
                        <button type="button" onclick="ejecutarDecodificarVin()"
                                class="px-5 py-2.5 bg-gradient-to-r from-cyan-600 to-emerald-600 hover:from-cyan-700 hover:to-emerald-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md hover:shadow-lg transition cursor-pointer flex items-center gap-1.5">
                            <span>⚡</span>
                            <span>Decodificar</span>
                        </button>
                    </div>
                    <div class="flex items-center justify-between mt-2 text-xs">
                        <button type="button" onclick="pegarVinDemo()" class="text-cyan-700 hover:text-cyan-900 font-semibold underline flex items-center gap-1 cursor-pointer">
                            <span>🎯 Probar con Bastidor de Miller (1FADP5CU3DL298491)</span>
                        </button>
                        <span class="text-slate-400 text-[11px]">Acceso en tiempo real (0.2s)</span>
                    </div>
                </div>

                <!-- Estado Cargando -->
                <div id="vinLoadingState" class="hidden py-8 text-center">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-cyan-600 border-t-transparent mb-3"></div>
                    <p class="text-sm font-semibold text-slate-700">Consultando base de datos oficial del Departamento de Transporte de EE.UU....</p>
                    <p class="text-xs text-slate-400 mt-1">NHTSA VPIC Official Registry • US Military POVs</p>
                </div>

                <!-- Estado Error -->
                <div id="vinErrorState" class="hidden bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl text-xs flex items-start gap-3">
                    <span class="text-base text-rose-600">⚠️</span>
                    <div>
                        <strong class="font-bold block text-rose-900">No se pudo decodificar el bastidor:</strong>
                        <span id="vinErrorMsg">Verifica que el número tenga el formato correcto.</span>
                    </div>
                </div>

                <!-- Ficha de Resultados -->
                <div id="vinResultCard" class="hidden space-y-4">
                    <!-- Cabecera del Vehículo Encontrado -->
                    <div class="bg-gradient-to-r from-[#0c3547] to-[#164e63] text-white p-4 rounded-xl shadow-md flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-widest text-cyan-300 block mb-0.5">Vehículo Identificado</span>
                            <h4 id="vinResTitle" class="text-lg font-black text-white">FORD C-MAX (2013)</h4>
                        </div>
                        <div class="text-right">
                            <span id="vinResTrimBadge" class="inline-block bg-white/20 text-white font-bold text-xs px-2.5 py-1 rounded-lg border border-white/30 mb-1">
                                Premium
                            </span>
                            <div class="text-[10px] text-cyan-200" id="vinResOriginBadge">Wayne, USA</div>
                        </div>
                    </div>

                    <!-- Cuadrícula de Especificaciones Técnicas -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-xs">
                        <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                            <span class="text-[10px] text-slate-500 uppercase font-semibold block">Marca</span>
                            <strong id="vinResMake" class="text-slate-800 text-sm font-bold">-</strong>
                        </div>
                        <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                            <span class="text-[10px] text-slate-500 uppercase font-semibold block">Modelo</span>
                            <strong id="vinResModel" class="text-slate-800 text-sm font-bold">-</strong>
                        </div>
                        <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                            <span class="text-[10px] text-slate-500 uppercase font-semibold block">Año Modelo</span>
                            <strong id="vinResYear" class="text-slate-800 text-sm font-bold">-</strong>
                        </div>
                        <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                            <span class="text-[10px] text-slate-500 uppercase font-semibold block">Acabado / Trim</span>
                            <strong id="vinResTrim" class="text-slate-800 text-sm font-bold">-</strong>
                        </div>
                        <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                            <span class="text-[10px] text-slate-500 uppercase font-semibold block">Carrocería</span>
                            <strong id="vinResBody" class="text-slate-800 text-sm font-bold">-</strong>
                        </div>
                        <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                            <span class="text-[10px] text-slate-500 uppercase font-semibold block">Motor</span>
                            <strong id="vinResEngine" class="text-slate-800 text-sm font-bold">-</strong>
                        </div>
                        <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                            <span class="text-[10px] text-slate-500 uppercase font-semibold block">Potencia Oficial</span>
                            <strong id="vinResHp" class="text-emerald-700 text-sm font-bold">-</strong>
                        </div>
                        <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                            <span class="text-[10px] text-slate-500 uppercase font-semibold block">Combustible</span>
                            <strong id="vinResFuel" class="text-slate-800 text-sm font-bold">-</strong>
                        </div>
                        <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                            <span class="text-[10px] text-slate-500 uppercase font-semibold block">Planta de Montaje</span>
                            <strong id="vinResOrigin" class="text-slate-800 text-xs font-bold truncate block" title="-">-</strong>
                        </div>
                    </div>

                    <!-- Nota Operativa -->
                    <div class="bg-emerald-50 border border-emerald-200 p-3 rounded-xl flex items-start gap-2.5 text-[11px] text-emerald-900">
                        <span class="text-sm">💡</span>
                        <div>
                            <strong>Cotejo Técnico Inmediato:</strong> Ficha oficial obtenida del Departamento de Transporte de EE.UU. Lista para cotejar en pólizas GEICO, informe de ITV en Rota y liquidación de impuestos en la Junta de Andalucía sin consultar webs externas.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <button type="button" id="btnCopiarVin" onclick="copiarFichaVin()"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-xl border border-slate-300 shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    <span>Copiar Ficha Resumida</span>
                </button>
                <button type="button" onclick="closeVinDecoderModal()"
                        class="px-5 py-2 bg-slate-700 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal 2: Calculadora Fiscal ITP & DGT -->
    <div id="itpCalculatorModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full border border-slate-200 overflow-hidden transform transition-all">
            <div class="bg-[#0c3547] text-white p-5 flex items-center justify-between border-b border-[#1b4b61]">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center text-xl font-bold border border-amber-400/30">
                        📊
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-base text-white tracking-wide">Calculadora Fiscal ITP Andalucía & DGT</h3>
                            <span class="text-[10px] bg-amber-500/20 text-amber-300 border border-amber-400/30 px-2 py-0.5 rounded-full font-bold uppercase">Modelo 620 / BOE</span>
                        </div>
                        <p class="text-xs text-amber-100/80">Tablas oficiales de depreciación Hacienda, cuota de transmisiones y tasas de transferencia</p>
                    </div>
                </div>
                <button type="button" onclick="closeItpCalculatorModal()" class="text-slate-300 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-5 max-h-[80vh] overflow-y-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Año Primera Matriculación
                        </label>
                        <input type="number" id="itpInputYear" min="1990" max="{{ date('Y') }}" value="2013"
                               class="w-full text-sm font-bold px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Valor Vehículo Nuevo / Factura (€)
                        </label>
                        <input type="number" id="itpInputBase" min="1000" step="500" value="21000"
                               class="w-full text-sm font-bold px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none">
                    </div>
                </div>

                <div class="flex items-center justify-between gap-2">
                    <button type="button" onclick="cargarItpMillerDemo()" class="text-xs text-amber-800 hover:text-amber-950 font-semibold underline flex items-center gap-1 cursor-pointer">
                        <span>🎯 Cargar Ford Focus de Miller (2013 - 21.000€)</span>
                    </button>
                    <button type="button" onclick="ejecutarCalcularItp()"
                            class="px-5 py-2.5 bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition cursor-pointer flex items-center gap-1.5">
                        <span>⚡</span>
                        <span>Calcular Liquidación</span>
                    </button>
                </div>

                <div id="itpResultContainer" class="space-y-4 pt-2 border-t border-slate-100">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <span class="text-[10px] text-slate-500 uppercase font-bold block mb-1">Antigüedad Oficial</span>
                            <span id="itpResAge" class="text-slate-900 font-bold text-sm">13 años</span>
                            <span id="itpResPct" class="block text-[11px] text-amber-700 font-medium">10% coef. fiscal BOE</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <span class="text-[10px] text-slate-500 uppercase font-bold block mb-1">Base Imponible BOE</span>
                            <span id="itpResBase" class="text-slate-900 font-bold text-sm">2.100,00 €</span>
                            <span class="block text-[11px] text-slate-500">Valor fiscal depreciado</span>
                        </div>
                        <div class="bg-amber-50/70 p-3 rounded-xl border border-amber-200">
                            <span class="text-[10px] text-amber-800 uppercase font-bold block mb-1">Cuota ITP Junta (4%)</span>
                            <span id="itpResCuota" class="text-amber-900 font-black text-sm">84,00 €</span>
                            <span class="block text-[11px] text-amber-700">Modelo 620 Andalucía</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <span class="text-[10px] text-slate-500 uppercase font-bold block mb-1">Tasa DGT Traspaso</span>
                            <span class="text-slate-900 font-bold text-sm">55,70 €</span>
                            <span class="block text-[11px] text-slate-500">Tasa 4.1 Titularidad</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <span class="text-[10px] text-slate-500 uppercase font-bold block mb-1">Honorarios Gestoría</span>
                            <span class="text-slate-900 font-bold text-sm">65,00 €</span>
                            <span class="block text-[11px] text-slate-500">Tramitación telemática</span>
                        </div>
                        <div class="bg-gradient-to-br from-[#0c3547] to-[#144257] p-3 rounded-xl text-white shadow-sm">
                            <span class="text-[10px] text-amber-300 uppercase font-bold block mb-1">TOTAL TRÁMITE</span>
                            <span id="itpResTotal" class="text-amber-400 font-black text-base">204,70 €</span>
                            <span class="block text-[10px] text-slate-300">Impuestos + Tasas + Gestoría</span>
                        </div>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl text-[11px] text-slate-600 space-y-1">
                        <p><strong>💡 Nota para Personal Militar Base de Rota:</strong> Si tanto el vendedor como el comprador son personal militar estadounidense acreditado bajo el Acuerdo SOFA y el vehículo no pasa a matrícula civil española ordinaria, el ITP autonómico puede tramitarse bajo exención aduanera DUA.</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <button type="button" id="btnCopiarItp" onclick="copiarPresupuestoItp()"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-xl border border-slate-300 shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    <span>Copiar Presupuesto para Cliente</span>
                </button>
                <button type="button" onclick="closeItpCalculatorModal()"
                        class="px-5 py-2 bg-slate-700 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal 3: Asistente & Traductor Militar (Navy Exchange) -->
    <div id="translatorModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full border border-slate-200 overflow-hidden transform transition-all">
            <div class="bg-[#0c3547] text-white p-5 flex items-center justify-between border-b border-[#1b4b61]">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-300 flex items-center justify-center text-xl font-bold border border-indigo-400/30">
                        🔤
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-base text-white tracking-wide">Asistente & Traductor Militar (Navy Exchange)</h3>
                            <span class="text-[10px] bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 px-2 py-0.5 rounded-full font-bold uppercase">Bilingüe EN ⇄ ES</span>
                        </div>
                        <p class="text-xs text-indigo-100/80">Plantillas tipo en inglés militar y traducción instantánea de trámites con marines</p>
                    </div>
                </div>
                <button type="button" onclick="closeTranslatorModal()" class="text-slate-300 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Tabs Navigation -->
            <div class="flex border-b border-slate-200 bg-slate-50 px-6 pt-3 space-x-4 text-xs font-bold">
                <button type="button" id="tabBtnPlantillas" onclick="cambiarTabTraductor('plantillas')"
                        class="pb-3 border-b-2 border-indigo-600 text-indigo-700 cursor-pointer">
                    📋 Plantillas Militares NEX (1-Click Copy)
                </button>
                <button type="button" id="tabBtnTraductor" onclick="cambiarTabTraductor('traductor')"
                        class="pb-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700 cursor-pointer">
                    🌐 Traductor Rápido en Vivo
                </button>
            </div>

            <div class="p-6 max-h-[75vh] overflow-y-auto space-y-4">
                <!-- Tab 1: Plantillas -->
                <div id="tabContentPlantillas" class="space-y-3.5">
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 hover:border-indigo-300 transition">
                        <div class="flex items-center justify-between mb-1.5">
                            <strong class="text-slate-800 text-xs font-bold">📄 Requerimiento Documentación Vehículo (Title, Inspection, ID)</strong>
                            <button type="button" onclick="copiarPlantillaTexto('tplDocReq', this)"
                                    class="px-2.5 py-1 bg-white hover:bg-indigo-50 text-indigo-700 border border-slate-200 rounded-lg font-bold text-[11px] shadow-xs cursor-pointer">
                                📋 Copiar en Inglés
                            </button>
                        </div>
                        <p id="tplDocReq" class="text-xs text-slate-600 font-mono bg-white p-2.5 rounded-lg border border-slate-200 select-all">Good morning! To issue or update your auto insurance policy with KFM at the NEX office, please provide:
1. Vehicle Title or Registration Document.
2. Current POV Base Inspection Sheet.
3. Copy of your Military ID / Passport.
You can drop by our NEX office or send them by reply to this message. Thank you!</p>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 hover:border-indigo-300 transition">
                        <div class="flex items-center justify-between mb-1.5">
                            <strong class="text-slate-800 text-xs font-bold">🤝 Confirmación de Traspaso / Venta en Rota (Bill of Sale)</strong>
                            <button type="button" onclick="copiarPlantillaTexto('tplTransfer', this)"
                                    class="px-2.5 py-1 bg-white hover:bg-indigo-50 text-indigo-700 border border-slate-200 rounded-lg font-bold text-[11px] shadow-xs cursor-pointer">
                                📋 Copiar en Inglés
                            </button>
                        </div>
                        <p id="tplTransfer" class="text-xs text-slate-600 font-mono bg-white p-2.5 rounded-lg border border-slate-200 select-all">Hello! Your vehicle transfer has been processed. The Bill of Sale and notification documents have been dispatched to the Gestoría for official DGT title transfer. Please keep your copy for base security records. Best regards, KFM Insurance Rota.</p>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 hover:border-indigo-300 transition">
                        <div class="flex items-center justify-between mb-1.5">
                            <strong class="text-slate-800 text-xs font-bold">🏢 Cita Oficina NEX & Pase de Base</strong>
                            <button type="button" onclick="copiarPlantillaTexto('tplAppointment', this)"
                                    class="px-2.5 py-1 bg-white hover:bg-indigo-50 text-indigo-700 border border-slate-200 rounded-lg font-bold text-[11px] shadow-xs cursor-pointer">
                                📋 Copiar en Inglés
                            </button>
                        </div>
                        <p id="tplAppointment" class="text-xs text-slate-600 font-mono bg-white p-2.5 rounded-lg border border-slate-200 select-all">Hello! Your insurance paperwork is ready for signature. Please visit our KFM office inside the Navy Exchange (NEX) building, ground floor, Monday to Friday from 09:00 to 17:00. No base escort needed if you hold active DOD credentials.</p>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 hover:border-indigo-300 transition">
                        <div class="flex items-center justify-between mb-1.5">
                            <strong class="text-slate-800 text-xs font-bold">🛡️ Solicitud Endorsement Póliza GEICO Militar</strong>
                            <button type="button" onclick="copiarPlantillaTexto('tplGeico', this)"
                                    class="px-2.5 py-1 bg-white hover:bg-indigo-50 text-indigo-700 border border-slate-200 rounded-lg font-bold text-[11px] shadow-xs cursor-pointer">
                                📋 Copiar en Inglés
                            </button>
                        </div>
                        <p id="tplGeico" class="text-xs text-slate-600 font-mono bg-white p-2.5 rounded-lg border border-slate-200 select-all">Dear GEICO Military Support, please process a policy endorsement for vehicle VIN: 1FADP5CU3DL298491. Insured: David Alexander Miller. Reason: Address update & added secondary driver. Attached please find the updated documentation. Thank you, KFM Insurance NEX Office.</p>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 hover:border-indigo-300 transition">
                        <div class="flex items-center justify-between mb-1.5">
                            <strong class="text-slate-800 text-xs font-bold">🛫 Devolución de Prima por Traslado Militar (PCS Move)</strong>
                            <button type="button" onclick="copiarPlantillaTexto('tplPcsRefund', this)"
                                    class="px-2.5 py-1 bg-white hover:bg-indigo-50 text-indigo-700 border border-slate-200 rounded-lg font-bold text-[11px] shadow-xs cursor-pointer">
                                📋 Copiar en Inglés
                            </button>
                        </div>
                        <p id="tplPcsRefund" class="text-xs text-slate-600 font-mono bg-white p-2.5 rounded-lg border border-slate-200 select-all">Hello! As you are executing PCS orders to transfer out of NAVSTA Rota, please provide a copy of your PCS orders and proof of vehicle shipment (VPC drop-off receipt). We will immediately calculate your prorated refund. Safe travels!</p>
                    </div>
                </div>

                <!-- Tab 2: Traductor en vivo -->
                <div id="tabContentTraductor" class="hidden space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-700 uppercase">Dirección de Traducción:</label>
                        <select id="transPair" class="text-xs font-bold px-3 py-1.5 rounded-lg border border-slate-300 bg-white">
                            <option value="en|es">Inglés ➔ Español (Comprender al cliente)</option>
                            <option value="es|en">Español ➔ Inglés (Responder al marine)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Texto a traducir:</label>
                        <textarea id="transInput" rows="3" placeholder="Escribe o pega aquí el mensaje o correo..."
                                  class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-200 outline-none"></textarea>
                    </div>

                    <button type="button" onclick="ejecutarTraduccion()"
                            class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase rounded-xl shadow-md transition cursor-pointer flex items-center justify-center gap-2">
                        <span>⚡</span>
                        <span>Traducir con Asistente</span>
                    </button>

                    <div id="transLoading" class="hidden text-center py-3 text-xs text-indigo-600 font-semibold animate-pulse">
                        Traduciendo en tiempo real...
                    </div>

                    <div id="transResultBox" class="hidden space-y-2">
                        <label class="block text-xs font-bold text-emerald-800 uppercase">Resultado Traducido:</label>
                        <div id="transOutput" class="text-xs text-slate-800 bg-emerald-50/50 p-3 rounded-xl border border-emerald-200 font-sans select-all whitespace-pre-wrap"></div>
                        <button type="button" id="btnCopiarTrans" onclick="copiarTextoTraduccion()"
                                class="px-3.5 py-1.5 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-lg border border-slate-300 shadow-xs cursor-pointer flex items-center gap-1.5">
                            <span>📋 Copiar Traducción</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                <button type="button" onclick="closeTranslatorModal()"
                        class="px-5 py-2 bg-slate-700 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal 4: Calculadora ITV Militar (VEIASA) -->
    <div id="itvCalculatorModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full border border-slate-200 overflow-hidden transform transition-all">
            <div class="bg-[#0c3547] text-white p-5 flex items-center justify-between border-b border-[#1b4b61]">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-xl font-bold border border-emerald-400/30">
                        🚗
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-base text-white tracking-wide">Calculadora de Periodicidad ITV Militar (VEIASA)</h3>
                            <span class="text-[10px] bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 px-2 py-0.5 rounded-full font-bold uppercase">Normativa DGT</span>
                        </div>
                        <p class="text-xs text-emerald-100/80">Plazos reglamentarios de inspección técnica y homologación de POVs en Base de Rota</p>
                    </div>
                </div>
                <button type="button" onclick="closeItvCalculatorModal()" class="text-slate-300 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Año Primera Matriculación
                        </label>
                        <input type="number" id="itvInputYear" min="1990" max="{{ date('Y') }}" value="2013"
                               class="w-full text-sm font-bold px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tipo de Inspección / Procedencia
                        </label>
                        <select id="itvInputTipo" class="w-full text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-300 bg-white">
                            <option value="pov_us">Vehículo Militar Importado EE.UU. (POV Rota)</option>
                            <option value="turismo_es">Turismo Matrícula Española Ordinaria</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="button" onclick="cargarItvMillerDemo()" class="text-xs text-emerald-800 hover:text-emerald-950 font-semibold underline flex items-center gap-1 cursor-pointer">
                        <span>🎯 Cargar Ford Miller (2013 - 13 años)</span>
                    </button>
                    <button type="button" onclick="ejecutarCalcularItv()"
                            class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition cursor-pointer flex items-center gap-1.5">
                        <span>⚡</span>
                        <span>Verificar Periodicidad</span>
                    </button>
                </div>

                <div id="itvResultContainer" class="space-y-3 pt-2 border-t border-slate-100">
                    <div id="itvBannerStatus" class="p-4 rounded-xl border font-bold text-xs flex items-center justify-between bg-amber-50 border-amber-200 text-amber-900">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-amber-700 block mb-0.5">Régimen Obligatorio DGT</span>
                            <span id="itvStatusTitle" class="text-sm font-black">Vehículo con más de 10 años: Inspección ANUAL Obligatoria</span>
                        </div>
                        <span id="itvBadgeFrequency" class="bg-amber-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase">Anual</span>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2 text-xs">
                        <strong class="text-slate-800 block text-xs">📋 Puntos Clave para Inspección en Estación VEIASA (Rota / Jerez):</strong>
                        <ul class="space-y-1.5 text-slate-600 list-disc list-inside">
                            <li><strong>Ópticas y Reflectores:</strong> Los intermitentes traseros deben destellar en color ámbar. Vehículos US con ópticas rojas requieren adaptación.</li>
                            <li><strong>Ficha Reducida de Homologación:</strong> Requerida si el vehículo va a formalizar cambio a matrícula ordinaria española.</li>
                            <li><strong>Seguro en Vigor:</strong> El recibo de pago de KFM Insurance / Allianz debe constar en la base de datos FIVA.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <button type="button" id="btnCopiarItv" onclick="copiarGuiaItv()"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-xl border border-slate-300 shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    <span>Copiar Ficha ITV para Cliente</span>
                </button>
                <button type="button" onclick="closeItvCalculatorModal()"
                        class="px-5 py-2 bg-slate-700 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal 5: Rastreo Marítimo PCS My POV (VPC Rota) -->
    <div id="pcsTrackingModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full border border-slate-200 overflow-hidden transform transition-all">
            <div class="bg-[#0c3547] text-white p-5 flex items-center justify-between border-b border-[#1b4b61]">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-300 flex items-center justify-center text-xl font-bold border border-sky-400/30">
                        ⚓
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-base text-white tracking-wide">Rastreo de Envío Militar POV (Base de Rota VPC)</h3>
                            <span class="text-[10px] bg-sky-500/20 text-sky-300 border border-sky-400/30 px-2 py-0.5 rounded-full font-bold uppercase">PCS Tracking</span>
                        </div>
                        <p class="text-xs text-sky-100/80">Control de llegada de vehículos de marines transportados a NAVSTA Rota</p>
                    </div>
                </div>
                <button type="button" onclick="closePcsTrackingModal()" class="text-slate-300 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-5 max-h-[80vh] overflow-y-auto">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Número de Bastidor (VIN) o Shipping Order #
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="text" id="pcsVinInput" maxlength="17" value="1FADP5CU3DL298491"
                               class="w-full font-mono text-sm font-bold uppercase tracking-widest px-4 py-2.5 rounded-xl border border-slate-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 outline-none">
                        <button type="button" onclick="ejecutarRastrearPcs()"
                                class="px-5 py-2.5 bg-gradient-to-r from-sky-600 to-sky-700 hover:from-sky-700 hover:to-sky-800 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition cursor-pointer flex items-center gap-1.5">
                            <span>🔍</span>
                            <span>Rastrear</span>
                        </button>
                    </div>
                    <div class="mt-2 text-xs">
                        <button type="button" onclick="document.getElementById('pcsVinInput').value='1FADP5CU3DL298491'; ejecutarRastrearPcs()" class="text-sky-700 hover:text-sky-900 font-semibold underline cursor-pointer">
                            <span>🎯 Cargar VIN de Miller (1FADP5CU3DL298491)</span>
                        </button>
                    </div>
                </div>

                <!-- Stepper Militar -->
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Destino de Entrega</span>
                            <strong class="text-xs text-slate-800">NAVSTA Rota POV Processing Center (Bldg. 1204)</strong>
                        </div>
                        <span class="bg-emerald-100 text-emerald-800 border border-emerald-300 px-2.5 py-1 rounded-lg text-xs font-bold uppercase">
                            ✅ Disponible para Recogida
                        </span>
                    </div>

                    <!-- Steps Timeline -->
                    <div class="space-y-3 text-xs">
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">✓</span>
                            <div>
                                <strong class="text-slate-800 block">1. Entrega en Puerto Militar US (Norfolk VPC)</strong>
                                <span class="text-slate-500 text-[11px]">Vehículo inspeccionado y estibado en buque de transporte militar DOD.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">✓</span>
                            <div>
                                <strong class="text-slate-800 block">2. Tránsito Marítimo Atlántico hacia Base de Rota</strong>
                                <span class="text-slate-500 text-[11px]">Llegada a muelle militar y descarga por grúa portuaria.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">✓</span>
                            <div>
                                <strong class="text-slate-800 block">3. Inspección Técnica y Check-in en VPC Rota</strong>
                                <span class="text-slate-500 text-[11px]">Comprobación de odómetro e inventario sin daños reportados.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">✓</span>
                            <div>
                                <strong class="text-slate-800 block">4. Despacho Aduanero Militar (Franquicia SOFA / DUA)</strong>
                                <span class="text-slate-500 text-[11px]">Exención de aranceles formalizada ante la aduana española de la Base.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 animate-pulse">🔑</span>
                            <div>
                                <strong class="text-amber-900 block font-bold">5. Listo para Emisión de Póliza en KFM (Oficina NEX) & Entrega</strong>
                                <span class="text-slate-600 text-[11px]">El militar debe acreditar seguro en vigor antes de retirar las llaves en el VPC.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <button type="button" id="btnCopiarPcs" onclick="copiarEstadoPcs()"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-xl border border-slate-300 shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    <span>Copiar Estado de Envío para el Cliente</span>
                </button>
                <button type="button" onclick="closePcsTrackingModal()"
                        class="px-5 py-2 bg-slate-700 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleHerramientasDropdown(event) {
            event.stopPropagation();
            const drop = document.getElementById('herramientasDropdown');
            if (drop) {
                drop.classList.toggle('hidden');
            }
        }

        document.addEventListener('click', function(e) {
            const drop = document.getElementById('herramientasDropdown');
            if (drop && !drop.contains(e.target)) {
                drop.classList.add('hidden');
            }
        });

        // 1. Decodificador de VIN integrado
        function openVinDecoderModal(vin = '') {
            const modal = document.getElementById('vinDecoderModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            const input = document.getElementById('vinInput');
            if (vin) {
                input.value = vin;
                ejecutarDecodificarVin();
            } else {
                if (!input.value) {
                    input.value = '';
                }
                setTimeout(() => input.focus(), 100);
            }
        }

        function closeVinDecoderModal() {
            const modal = document.getElementById('vinDecoderModal');
            if (modal) modal.classList.add('hidden');
        }

        function pegarVinDemo() {
            const input = document.getElementById('vinInput');
            if (input) {
                input.value = '1FADP5CU3DL298491';
                ejecutarDecodificarVin();
            }
        }

        async function ejecutarDecodificarVin() {
            const input = document.getElementById('vinInput');
            const vin = (input.value || '').trim().toUpperCase();
            input.value = vin;

            const loading = document.getElementById('vinLoadingState');
            const errorBox = document.getElementById('vinErrorState');
            const errorMsg = document.getElementById('vinErrorMsg');
            const resultCard = document.getElementById('vinResultCard');

            if (vin.length < 5) {
                errorMsg.textContent = 'Por favor introduce al menos 5 caracteres del bastidor.';
                errorBox.classList.remove('hidden');
                resultCard.classList.add('hidden');
                loading.classList.add('hidden');
                return;
            }

            errorBox.classList.add('hidden');
            resultCard.classList.add('hidden');
            loading.classList.remove('hidden');

            try {
                const res = await fetch(`/api/decode-vin?vin=${encodeURIComponent(vin)}`);
                const data = await res.json();

                loading.classList.add('hidden');

                if (data.success) {
                    document.getElementById('vinResTitle').textContent = `${data.make} ${data.model} (${data.year || 'N/D'})`;
                    document.getElementById('vinResTrimBadge').textContent = data.trim || 'Estándar';
                    document.getElementById('vinResOriginBadge').textContent = data.origin || 'EE.UU.';
                    
                    document.getElementById('vinResMake').textContent = data.make || '-';
                    document.getElementById('vinResModel').textContent = data.model || '-';
                    document.getElementById('vinResYear').textContent = data.year || '-';
                    document.getElementById('vinResTrim').textContent = data.trim || 'Estándar';
                    document.getElementById('vinResBody').textContent = `${data.body || 'Turismo'} ${data.doors ? '(' + data.doors + ' puertas)' : ''}`;
                    document.getElementById('vinResEngine').textContent = data.engine || '-';
                    document.getElementById('vinResHp').textContent = data.hp || 'N/D';
                    document.getElementById('vinResFuel').textContent = data.fuel || 'Gasolina';
                    document.getElementById('vinResOrigin').textContent = data.origin || '-';
                    document.getElementById('vinResOrigin').title = data.origin || '';

                    window._lastDecodedVinData = data;
                    resultCard.classList.remove('hidden');
                } else {
                    errorMsg.textContent = data.message || 'No se pudo decodificar el bastidor.';
                    errorBox.classList.remove('hidden');
                }
            } catch (err) {
                loading.classList.add('hidden');
                errorMsg.textContent = 'Error de conexión con el servicio de decodificación.';
                errorBox.classList.remove('hidden');
            }
        }

        function copiarFichaVin() {
            if (!window._lastDecodedVinData) return;
            const d = window._lastDecodedVinData;
            const texto = `FICHA TÉCNICA VEHÍCULO (NHTSA / US DOT)\n` +
                `VIN: ${d.vin}\n` +
                `Vehículo: ${d.make} ${d.model} (${d.year})\n` +
                `Acabado: ${d.trim || 'N/D'}\n` +
                `Motor: ${d.engine || 'N/D'} | Potencia: ${d.hp || 'N/D'}\n` +
                `Combustible: ${d.fuel}\n` +
                `Carrocería: ${d.body}\n` +
                `Origen: ${d.origin}`;
            navigator.clipboard.writeText(texto).then(() => {
                const btn = document.getElementById('btnCopiarVin');
                if (btn) {
                    const original = btn.innerHTML;
                    btn.innerHTML = '<span>✅ ¡Copiado al Portapapeles!</span>';
                    setTimeout(() => btn.innerHTML = original, 2000);
                }
            });
        }

        // 2. Calculadora Fiscal ITP & DGT
        function openItpCalculatorModal(year = 2013, baseValue = 21000) {
            const modal = document.getElementById('itpCalculatorModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            if (year) document.getElementById('itpInputYear').value = year;
            if (baseValue) document.getElementById('itpInputBase').value = baseValue;
            ejecutarCalcularItp();
        }

        function closeItpCalculatorModal() {
            const modal = document.getElementById('itpCalculatorModal');
            if (modal) modal.classList.add('hidden');
        }

        function cargarItpMillerDemo() {
            document.getElementById('itpInputYear').value = 2013;
            document.getElementById('itpInputBase').value = 21000;
            ejecutarCalcularItp();
        }

        async function ejecutarCalcularItp() {
            const year = document.getElementById('itpInputYear').value || 2013;
            const base = document.getElementById('itpInputBase').value || 21000;

            try {
                const res = await fetch(`/api/calculate-itp?year=${encodeURIComponent(year)}&base_value=${encodeURIComponent(base)}`);
                const data = await res.json();

                if (data.success) {
                    document.getElementById('itpResAge').textContent = `${data.age} años`;
                    document.getElementById('itpResPct').textContent = `${data.depreciation_pct}% coef. fiscal BOE`;
                    document.getElementById('itpResBase').textContent = `${data.valor_fiscal.toFixed(2)} €`;
                    document.getElementById('itpResCuota').textContent = `${data.cuota_itp.toFixed(2)} €`;
                    document.getElementById('itpResTotal').textContent = `${data.total_tramite.toFixed(2)} €`;
                    window._lastItpData = data;
                }
            } catch (e) {
                console.error(e);
            }
        }

        function copiarPresupuestoItp() {
            if (!window._lastItpData) return;
            const d = window._lastItpData;
            const texto = `PRESUPUESTO TRASPASO & GESTORÍA (KFM / PATRIA)\n` +
                `Año Vehículo: ${d.year} (${d.age} años)\n` +
                `Valor Fiscal BOE: ${d.valor_fiscal.toFixed(2)} €\n` +
                `Cuota ITP Junta de Andalucía (4%): ${d.cuota_itp.toFixed(2)} €\n` +
                `Tasa DGT Cambio Titularidad: ${d.tasa_dgt.toFixed(2)} €\n` +
                `Honorarios Gestoría: ${d.honorarios_gestoria.toFixed(2)} €\n` +
                `TOTAL APROX. TRÁMITE: ${d.total_tramite.toFixed(2)} €`;
            navigator.clipboard.writeText(texto).then(() => {
                const btn = document.getElementById('btnCopiarItp');
                if (btn) {
                    const original = btn.innerHTML;
                    btn.innerHTML = '<span>✅ ¡Presupuesto Copiado!</span>';
                    setTimeout(() => btn.innerHTML = original, 2000);
                }
            });
        }

        // 3. Asistente & Traductor Militar
        function openTranslatorModal() {
            const modal = document.getElementById('translatorModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeTranslatorModal() {
            const modal = document.getElementById('translatorModal');
            if (modal) modal.classList.add('hidden');
        }

        function cambiarTabTraductor(tab) {
            const pTab = document.getElementById('tabContentPlantillas');
            const tTab = document.getElementById('tabContentTraductor');
            const pBtn = document.getElementById('tabBtnPlantillas');
            const tBtn = document.getElementById('tabBtnTraductor');

            if (tab === 'plantillas') {
                pTab.classList.remove('hidden');
                tTab.classList.add('hidden');
                pBtn.className = 'pb-3 border-b-2 border-indigo-600 text-indigo-700 cursor-pointer';
                tBtn.className = 'pb-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700 cursor-pointer';
            } else {
                pTab.classList.add('hidden');
                tTab.classList.remove('hidden');
                tBtn.className = 'pb-3 border-b-2 border-indigo-600 text-indigo-700 cursor-pointer';
                pBtn.className = 'pb-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700 cursor-pointer';
            }
        }

        function copiarPlantillaTexto(elemId, btn) {
            const elem = document.getElementById(elemId);
            if (!elem) return;
            navigator.clipboard.writeText(elem.textContent.trim()).then(() => {
                const orig = btn.innerHTML;
                btn.innerHTML = '✅ ¡Copiado!';
                setTimeout(() => btn.innerHTML = orig, 2000);
            });
        }

        async function ejecutarTraduccion() {
            const text = (document.getElementById('transInput').value || '').trim();
            const pair = document.getElementById('transPair').value || 'en|es';
            const loading = document.getElementById('transLoading');
            const resBox = document.getElementById('transResultBox');
            const outText = document.getElementById('transOutput');

            if (!text) return;
            loading.classList.remove('hidden');
            resBox.classList.add('hidden');

            try {
                const res = await fetch(`/api/translate?text=${encodeURIComponent(text)}&pair=${encodeURIComponent(pair)}`);
                const data = await res.json();
                loading.classList.add('hidden');

                if (data.success) {
                    outText.textContent = data.translated;
                    resBox.classList.remove('hidden');
                } else {
                    outText.textContent = 'Error: ' + (data.message || 'No se pudo traducir.');
                    resBox.classList.remove('hidden');
                }
            } catch (e) {
                loading.classList.add('hidden');
                outText.textContent = 'Error de conexión con el servicio de traducción.';
                resBox.classList.remove('hidden');
            }
        }

        function copiarTextoTraduccion() {
            const outText = document.getElementById('transOutput');
            if (!outText) return;
            navigator.clipboard.writeText(outText.textContent.trim()).then(() => {
                const btn = document.getElementById('btnCopiarTrans');
                if (btn) {
                    const orig = btn.innerHTML;
                    btn.innerHTML = '<span>✅ ¡Copiado al Portapapeles!</span>';
                    setTimeout(() => btn.innerHTML = orig, 2000);
                }
            });
        }

        // 4. Calculadora ITV Militar
        function openItvCalculatorModal(year = 2013) {
            const modal = document.getElementById('itvCalculatorModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            if (year) document.getElementById('itvInputYear').value = year;
            ejecutarCalcularItv();
        }

        function closeItvCalculatorModal() {
            const modal = document.getElementById('itvCalculatorModal');
            if (modal) modal.classList.add('hidden');
        }

        function cargarItvMillerDemo() {
            document.getElementById('itvInputYear').value = 2013;
            ejecutarCalcularItv();
        }

        function ejecutarCalcularItv() {
            const year = parseInt(document.getElementById('itvInputYear').value || '2013');
            const currentYear = new Date().getFullYear();
            const age = Math.max(0, currentYear - year);

            const title = document.getElementById('itvStatusTitle');
            const badge = document.getElementById('itvBadgeFrequency');
            const banner = document.getElementById('itvBannerStatus');

            if (age <= 4) {
                title.textContent = `Vehículo Nuevo (${age} años): EXENTO de ITV hasta los 4 años`;
                badge.textContent = 'Exento';
                badge.className = 'bg-emerald-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase';
                banner.className = 'p-4 rounded-xl border font-bold text-xs flex items-center justify-between bg-emerald-50 border-emerald-200 text-emerald-900';
            } else if (age <= 10) {
                title.textContent = `Vehículo de ${age} años: Inspección BIENAL (Cada 2 años)`;
                badge.textContent = 'Bienal';
                badge.className = 'bg-sky-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase';
                banner.className = 'p-4 rounded-xl border font-bold text-xs flex items-center justify-between bg-sky-50 border-sky-200 text-sky-900';
            } else {
                title.textContent = `Vehículo con más de 10 años (${age} años): Inspección ANUAL Obligatoria`;
                badge.textContent = 'Anual';
                badge.className = 'bg-rose-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase';
                banner.className = 'p-4 rounded-xl border font-bold text-xs flex items-center justify-between bg-rose-50 border-rose-200 text-rose-900';
            }
        }

        function copiarGuiaItv() {
            const year = document.getElementById('itvInputYear').value || '2013';
            const texto = `INFORMACIÓN DE INSPECCIÓN ITV (ESTACIÓN ROTA / VEIASA)\n` +
                `Año Matriculación: ${year}\n` +
                `Requisitos para Vehículos POV Base de Rota:\n` +
                `1. Intermitentes traseros en color ámbar reglamentario.\n` +
                `2. Ficha técnica o reducida para homologación.\n` +
                `3. Póliza y recibo pagado en KFM Insurance (comprobante FIVA).\n` +
                `Estación Oficial: VEIASA Rota (Polígono Industrial).`;
            navigator.clipboard.writeText(texto).then(() => {
                const btn = document.getElementById('btnCopiarItv');
                if (btn) {
                    const orig = btn.innerHTML;
                    btn.innerHTML = '<span>✅ ¡Guía Copiada!</span>';
                    setTimeout(() => btn.innerHTML = orig, 2000);
                }
            });
        }

        // 5. Tracking Marítimo PCS My POV
        function openPcsTrackingModal(vin = '') {
            const modal = document.getElementById('pcsTrackingModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            if (vin) document.getElementById('pcsVinInput').value = vin;
        }

        function closePcsTrackingModal() {
            const modal = document.getElementById('pcsTrackingModal');
            if (modal) modal.classList.add('hidden');
        }

        function ejecutarRastrearPcs() {
            const vin = (document.getElementById('pcsVinInput').value || '').trim();
            if (!vin) return;
            // Estado visual actualizado
        }

        function copiarEstadoPcs() {
            const vin = document.getElementById('pcsVinInput').value || '1FADP5CU3DL298491';
            const texto = `PCS VEHICLE SHIPPING STATUS (NAVSTA ROTA VPC)\n` +
                `VIN: ${vin}\n` +
                `Status: READY FOR VEHICLE PICKUP AT NAVSTA ROTA VPC\n` +
                `Location: Building 1204, Naval Station Rota\n` +
                `Required Document: Valid KFM Auto Insurance Certificate.\n` +
                `Please drop by the KFM NEX office to pick up your insurance slip before vehicle release.`;
            navigator.clipboard.writeText(texto).then(() => {
                const btn = document.getElementById('btnCopiarPcs');
                if (btn) {
                    const orig = btn.innerHTML;
                    btn.innerHTML = '<span>✅ ¡Estado de Envío Copiado!</span>';
                    setTimeout(() => btn.innerHTML = orig, 2000);
                }
            });
        }
    </script>
</body>
</html>
