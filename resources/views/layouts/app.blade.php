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
    </script>
</body>
</html>
