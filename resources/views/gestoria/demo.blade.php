<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo expediente — Gestoría Sánchez Nieva (Simulador KFM)</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8fafc;
        }
        .gestoria-header {
            background-color: #9b1c31; /* Burgundy of Gestoría Sánchez Nieva */
        }
        .gestoria-active-menu {
            background-color: #f1f5f9;
            color: #9b1c31;
            font-weight: 600;
        }
        .gestoria-btn-guardar {
            background-color: #9b1c31;
        }
        .gestoria-btn-guardar:hover {
            background-color: #7f1627;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800 antialiased">

    <!-- DEMO BANNER: Explicación para Kerry y Coral -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 text-white px-4 py-2.5 text-xs font-semibold shadow-md flex flex-wrap items-center justify-between gap-2">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-white animate-ping"></span>
            <span class="bg-white/20 px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider">DEMO EN VIVO · AUTOMATIZACIÓN KFM CRM</span>
            <span>⚡ Este formulario se ha rellenado en <strong>1.1 segundos</strong> leyendo la ficha del cliente en KFM. <strong>0 campos tecleados a mano por Chari.</strong></span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('clients.show', $client) }}" class="bg-white text-slate-800 hover:bg-slate-100 font-bold px-3 py-1 rounded-lg text-xs transition shadow-sm">
                ← Volver al CRM KFM
            </a>
            <a href="{{ route('clients.cesion', ['client' => $client->id, 'policy' => $policy->id]) }}" target="_blank" class="bg-emerald-950/60 hover:bg-emerald-900 text-white font-bold px-3 py-1 rounded-lg text-xs transition border border-emerald-400/40">
                🖨️ Ver Hoja de Cesión para Firmar
            </a>
        </div>
    </div>

    <!-- Gestoría Header (Calcado al de Sánchez Nieva) -->
    <header class="gestoria-header text-white h-14 flex items-center justify-between px-4 sm:px-6 shadow-md sticky top-0 z-40">
        <div class="flex items-center space-x-4">
            <button class="text-white hover:text-slate-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-white/20 border border-white/30 flex items-center justify-center font-bold text-sm tracking-wider">
                    GA
                </div>
                <span class="font-bold text-base tracking-tight hidden sm:inline">Gestoría Sánchez Nieva</span>
            </div>
        </div>

        <div class="flex items-center space-x-4 text-xs font-semibold">
            <!-- GEICO Badge dropdown -->
            <div class="bg-white/15 hover:bg-white/25 px-2.5 py-1 rounded-md border border-white/20 flex items-center space-x-1.5 cursor-pointer">
                <span class="font-black text-amber-300">G</span>
                <span>GEICO</span>
                <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>

            <!-- User Info -->
            <div class="flex items-center space-x-2">
                <div class="w-7 h-7 rounded-full bg-white text-[#9b1c31] font-bold text-xs flex items-center justify-center">
                    CH
                </div>
                <span class="hidden md:inline">CHARI L - NEX</span>
            </div>
        </div>
    </header>

    <!-- Main Body Layout with Sidebar -->
    <div class="flex flex-1">

        <!-- Left Sidebar (Gestoría Navigation) -->
        <aside class="w-56 bg-white border-r border-slate-200 hidden lg:flex flex-col py-4 px-2 space-y-1 text-xs text-slate-600">
            <a href="#" class="gestoria-active-menu flex items-center space-x-3 px-3 py-2.5 rounded-lg">
                <svg class="w-4 h-4 text-[#9b1c31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Expedientes</span>
            </a>
            <a href="#" class="hover:bg-slate-50 flex items-center space-x-3 px-3 py-2.5 rounded-lg transition">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Clientes</span>
            </a>
            <a href="#" class="hover:bg-slate-50 flex items-center space-x-3 px-3 py-2.5 rounded-lg transition">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                <span>Vehículos</span>
            </a>
            <a href="#" class="hover:bg-slate-50 flex items-center space-x-3 px-3 py-2.5 rounded-lg transition">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Trabajadores</span>
            </a>
            <a href="#" class="hover:bg-slate-50 flex items-center space-x-3 px-3 py-2.5 rounded-lg transition">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>Cláusulas del contrato</span>
            </a>
        </aside>

        <!-- Main Form Area -->
        <main class="flex-1 p-6 md:p-8 max-w-5xl">
            <h1 class="text-xl md:text-2xl font-bold text-slate-900 mb-6 flex items-center justify-between">
                <span>Nuevo expediente</span>
                <span class="text-xs font-normal text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Autocompletado con KFM CRM
                </span>
            </h1>

            <div class="bg-white rounded-xl border border-slate-200/90 shadow-sm p-6 space-y-6 text-xs">

                <!-- 1. Datos Generales -->
                <div class="space-y-3 pb-6 border-b border-slate-100">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Datos generales</h2>
                    <div>
                        <label class="block text-slate-600 font-medium mb-1">Trabajador *</label>
                        <select class="w-full md:w-80 border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 text-slate-800 focus:outline-none focus:border-[#9b1c31]">
                            <option selected>CHARI L - NEX</option>
                            <option>CORAL - PUEBLO</option>
                            <option>KERRY - CENTRAL</option>
                            <option>CONCHI - PUEBLO</option>
                        </select>
                    </div>
                </div>

                <!-- 2. Vehículo (Autocompletado) -->
                <div class="space-y-3 pb-6 border-b border-slate-100">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center justify-between">
                        <span>Vehículo</span>
                        <span class="text-[11px] text-emerald-600 font-semibold">✓ Datos importados de KFM</span>
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Matrícula *</label>
                            <input type="text" value="{{ $policy->matricula ?: '5931LKP' }}" readonly
                                class="w-full border border-emerald-300 bg-emerald-50/30 rounded-lg px-3 py-2 font-mono font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Bastidor (Últimos 4 o VIN)</label>
                            <input type="text" value="{{ substr($policy->vin ?: '8491', -4) }}" readonly
                                class="w-full border border-emerald-300 bg-emerald-50/30 rounded-lg px-3 py-2 font-mono font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Marca *</label>
                            <input type="text" value="{{ $policy->marca ?: 'FORD' }}" readonly
                                class="w-full border border-emerald-300 bg-emerald-50/30 rounded-lg px-3 py-2 font-bold text-slate-900">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Modelo *</label>
                            <input type="text" value="{{ $policy->modelo ?: 'FOCUS TITANIUM' }}" readonly
                                class="w-full border border-emerald-300 bg-emerald-50/30 rounded-lg px-3 py-2 font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Fecha de matriculación</label>
                            <input type="text" value="15/05/2018" readonly
                                class="w-full border border-slate-300 bg-slate-50 rounded-lg px-3 py-2 text-slate-800">
                        </div>
                    </div>
                </div>

                <!-- 3. Vendedor (El Cliente de KFM que cede) -->
                <div class="space-y-3 pb-6 border-b border-slate-100">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center justify-between">
                        <span>Vendedor (Titular Saliente KFM)</span>
                        <span class="text-[11px] text-emerald-600 font-semibold">✓ Datos importados de KFM</span>
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">NIF / NIE / CIF *</label>
                            <input type="text" value="{{ $client->doc_identidad ?: 'Y8492015B' }}" readonly
                                class="w-full border border-emerald-300 bg-emerald-50/30 rounded-lg px-3 py-2 font-mono font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Nombre *</label>
                            <input type="text" value="{{ $client->nombre ?: 'DAVID ALEXANDER' }}" readonly
                                class="w-full border border-emerald-300 bg-emerald-50/30 rounded-lg px-3 py-2 font-bold text-slate-900 uppercase">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Primer apellido *</label>
                            <input type="text" value="{{ $client->apellido ?: 'MILLER' }}" readonly
                                class="w-full border border-emerald-300 bg-emerald-50/30 rounded-lg px-3 py-2 font-bold text-slate-900 uppercase">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Sexo *</label>
                            <input type="text" value="Hombre" readonly class="w-full border border-slate-300 bg-slate-50 rounded-lg px-3 py-2 text-slate-800">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Fecha de nacimiento *</label>
                            <input type="text" value="{{ $client->dob ?: '24/08/1993' }}" readonly class="w-full border border-slate-300 bg-slate-50 rounded-lg px-3 py-2 text-slate-800">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Tipo de vía *</label>
                            <input type="text" value="LU - LUGAR" readonly class="w-full border border-slate-300 bg-slate-50 rounded-lg px-3 py-2 text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-slate-600 font-medium mb-1">Nombre de vía *</label>
                            <input type="text" value="BASE NAVAL DE ROTA" readonly class="w-full border border-slate-300 bg-slate-50 rounded-lg px-3 py-2 text-slate-800">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Número</label>
                            <input type="text" value="999" readonly class="w-full border border-slate-300 bg-slate-50 rounded-lg px-3 py-2 text-slate-800">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Código postal *</label>
                            <input type="text" value="11520" readonly class="w-full border border-slate-300 bg-slate-50 rounded-lg px-3 py-2 text-slate-800">
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-600 font-medium mb-1">Nº de póliza (KFM Insurance)</label>
                        <input type="text" value="{{ $policy->numero_poliza ?: '1842910' }}" readonly
                            class="w-full md:w-80 border border-emerald-300 bg-emerald-50/30 rounded-lg px-3 py-2 font-mono font-bold text-slate-900">
                    </div>
                </div>

                <!-- 4. Comprador (El Cesionario) -->
                <div class="space-y-3 pb-6 border-b border-slate-100">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center justify-between">
                        <span>Comprador (Nuevo Cesionario)</span>
                        <span class="text-[11px] text-emerald-600 font-semibold">✓ Datos importados de KFM</span>
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">NIF / NIE / CIF *</label>
                            <input type="text" value="{{ $buyer['doc_identidad'] }}" readonly
                                class="w-full border border-emerald-300 bg-emerald-50/30 rounded-lg px-3 py-2 font-mono font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Nombre *</label>
                            <input type="text" value="{{ $buyer['nombre'] }}" readonly
                                class="w-full border border-emerald-300 bg-emerald-50/30 rounded-lg px-3 py-2 font-bold text-slate-900 uppercase">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Primer apellido *</label>
                            <input type="text" value="{{ $buyer['apellido'] }}" readonly
                                class="w-full border border-emerald-300 bg-emerald-50/30 rounded-lg px-3 py-2 font-bold text-slate-900 uppercase">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Sexo *</label>
                            <input type="text" value="{{ $buyer['sexo'] }}" readonly class="w-full border border-slate-300 bg-slate-50 rounded-lg px-3 py-2 text-slate-800">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Fecha de nacimiento *</label>
                            <input type="text" value="{{ $buyer['dob'] }}" readonly class="w-full border border-slate-300 bg-slate-50 rounded-lg px-3 py-2 text-slate-800">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Código postal *</label>
                            <input type="text" value="{{ $buyer['cp'] }}" readonly class="w-full border border-slate-300 bg-slate-50 rounded-lg px-3 py-2 text-slate-800">
                        </div>
                    </div>
                </div>

                <!-- 5. Datos del contrato -->
                <div class="space-y-3 pb-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Datos del contrato</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Lugar *</label>
                            <input type="text" value="ROTA" readonly class="w-full border border-slate-300 bg-slate-50 rounded-lg px-3 py-2 text-slate-800">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Fecha *</label>
                            <input type="text" value="{{ date('d/m/Y') }}" readonly class="w-full border border-slate-300 bg-slate-50 rounded-lg px-3 py-2 text-slate-800">
                        </div>
                        <div>
                            <label class="block text-slate-600 font-medium mb-1">Precio (€) *</label>
                            <input type="text" value="{{ $buyer['precio'] }}" readonly class="w-full border border-slate-300 bg-slate-50 rounded-lg px-3 py-2 font-bold text-slate-900">
                        </div>
                    </div>
                </div>

                <!-- Form Bottom Actions -->
                <div class="pt-6 border-t border-slate-200 flex flex-wrap items-center justify-between gap-3">
                    <div class="text-xs text-slate-500 italic">
                        Demostración de sincronización instantánea para KFM Insurance & Gestoría Sánchez Nieva
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('clients.show', $client) }}" class="px-4 py-2 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition">
                            Cancelar
                        </a>
                        <button type="button" onclick="alert('✓ Expediente simulado guardado con éxito en la plataforma de Sánchez Nieva.')"
                            class="gestoria-btn-guardar text-white font-bold px-6 py-2 rounded-lg shadow-sm transition">
                            Guardar Expediente
                        </button>
                    </div>
                </div>

            </div>
        </main>

    </div>

</body>
</html>
