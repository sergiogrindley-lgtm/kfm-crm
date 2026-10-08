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
                        
                        <div id="herramientasDropdown" class="hidden absolute left-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200 py-2 z-50 text-xs">
                            <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                <span>Utilidades de Oficina NEX</span>
                                <span class="bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded text-[9px]">Oficial</span>
                            </div>
                            
                            <button type="button" onclick="openVinDecoderModal()" class="w-full text-left flex items-start space-x-3 px-4 py-2.5 bg-gradient-to-r from-cyan-50 to-emerald-50 hover:from-cyan-100 hover:to-emerald-100 transition text-slate-800 border-b border-slate-200 cursor-pointer">
                                <span class="text-base p-1.5 rounded-lg bg-[#0c3547] text-cyan-300">⚡</span>
                                <div>
                                    <strong class="block text-[#0c3547] font-bold text-xs flex items-center gap-1.5">
                                        <span>Decodificador VIN Integrado</span>
                                        <span class="text-[9px] bg-emerald-600 text-white px-1.5 py-0.5 rounded font-bold uppercase">En vivo</span>
                                    </strong>
                                    <span class="text-[11px] text-slate-600">Decodifica bastidores americanos en 0.2s</span>
                                </div>
                            </button>

                            <a href="https://seisenlinea.com/informe-numero-de-bastidor-coche/" target="_blank" class="flex items-start space-x-3 px-4 py-2.5 hover:bg-slate-50 transition text-slate-800">
                                <span class="text-base p-1.5 rounded-lg bg-cyan-50 text-cyan-700">🔍</span>
                                <div>
                                    <strong class="block text-slate-900 text-xs">Detective Bastidor (SeisEnLínea)</strong>
                                    <span class="text-[11px] text-slate-500">Informe y verificación de bastidor / VIN</span>
                                </div>
                            </a>

                            <a href="https://www.pcsmypov.com/" target="_blank" class="flex items-start space-x-3 px-4 py-2.5 hover:bg-slate-50 transition text-slate-800">
                                <span class="text-base p-1.5 rounded-lg bg-sky-50 text-sky-700">🚢</span>
                                <div>
                                    <strong class="block text-slate-900 text-xs">Find My POV (PCS My POV)</strong>
                                    <span class="text-[11px] text-slate-500">Rastreo oficial de coches enviados a Rota</span>
                                </div>
                            </a>

                            <a href="https://www.juntadeandalucia.es/economiayhacienda/apl/surweb/modelos/modelo620/620.jsp" target="_blank" class="flex items-start space-x-3 px-4 py-2.5 hover:bg-slate-50 transition text-slate-800">
                                <span class="text-base p-1.5 rounded-lg bg-amber-50 text-amber-700">🏛️</span>
                                <div>
                                    <strong class="block text-slate-900 text-xs">Valoración Junta Andalucía (ITP)</strong>
                                    <span class="text-[11px] text-slate-500">Modelo 620 impuestos vehículos usados</span>
                                </div>
                            </a>

                            <a href="https://www.itv.com.es/" target="_blank" class="flex items-start space-x-3 px-4 py-2.5 hover:bg-slate-50 transition text-slate-800">
                                <span class="text-base p-1.5 rounded-lg bg-emerald-50 text-emerald-700">🔧</span>
                                <div>
                                    <strong class="block text-slate-900 text-xs">Cita Previa ITV (VEIASA)</strong>
                                    <span class="text-[11px] text-slate-500">Inspección técnica estaciones Cádiz / Rota</span>
                                </div>
                            </a>

                            <a href="https://www.deepl.com/translator" target="_blank" class="flex items-start space-x-3 px-4 py-2.5 hover:bg-slate-50 transition text-slate-800">
                                <span class="text-base p-1.5 rounded-lg bg-indigo-50 text-indigo-700">🌐</span>
                                <div>
                                    <strong class="block text-slate-900 text-xs">DeepL Traductor Militar</strong>
                                    <span class="text-[11px] text-slate-500">Traducción directa de términos US/ES</span>
                                </div>
                            </a>
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

        // Lógica del Decodificador de VIN integrado
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
    </script>
</body>
</html>
