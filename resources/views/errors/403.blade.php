<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Restringido — KFM Insurance</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        body { font-family: 'Inter', sans-serif; background-color: #071f2b; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-[#071f2b] via-[#0c3547] to-[#145a78] text-white">

    <div class="max-w-xl w-full bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 md:p-10 shadow-2xl text-center relative overflow-hidden">
        <!-- Background Shield Decorative -->
        <div class="absolute -top-16 -right-16 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Logo -->
        <div class="inline-flex items-center justify-center bg-white p-3 rounded-xl shadow-lg mb-6">
            <img src="{{ asset('img/logo.png') }}" alt="KFM Insurance" class="h-12 w-auto object-contain">
        </div>

        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 border border-amber-400/40 text-amber-300 text-xs font-semibold uppercase tracking-wider mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Perímetro de Seguridad Activo
        </div>

        <h1 class="text-2xl md:text-3xl font-bold font-display text-white mb-3">
            Acceso Exclusivo Red Corporativa
        </h1>

        <p class="text-slate-300 text-sm md:text-base leading-relaxed mb-6">
            Por estrictas medidas de seguridad y cumplimiento normativo para la custodia de expedientes de personal militar <strong>US Navy / SOFA</strong>, este sistema solo es accesible desde los puestos autorizados en las oficinas de KFM.
        </p>

        <!-- Authorized Sedes Info -->
        <div class="grid grid-cols-2 gap-2 text-xs mb-6">
            <div class="bg-white/5 border border-white/10 rounded-lg p-2.5">
                <span class="block font-semibold text-cyan-300">Oficina Central</span>
                <span class="text-slate-400">Plaza Triunfo (Rota)</span>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-lg p-2.5">
                <span class="block font-semibold text-cyan-300">Oficina NEX</span>
                <span class="text-slate-400">Base Naval de Rota</span>
            </div>
        </div>

        <!-- IP Card -->
        <div class="bg-slate-900/80 border border-slate-700/80 rounded-xl p-4 mb-6">
            <p class="text-xs uppercase tracking-wider text-slate-400 font-medium mb-1">
                Tu dirección IP pública detectada
            </p>
            <div class="text-lg md:text-xl font-mono font-bold text-amber-400 select-all tracking-wide">
                {{ $clientIp ?? request()->ip() }}
            </div>
            <p class="text-[11px] text-slate-400 mt-2">
                Si estás en una de las oficinas autorizadas y ves este aviso, facilita esta IP al soporte técnico para verificar si tu proveedor de internet ha asignado una nueva dirección.
            </p>
        </div>

        <!-- Admin Bypass Form (Accordion) -->
        <div class="pt-2 border-t border-white/10 text-xs">
            <details class="text-left group cursor-pointer">
                <summary class="text-slate-400 hover:text-slate-200 transition-colors text-center list-none flex items-center justify-center gap-1.5 py-1">
                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <span>Acceso de Emergencia para Administrador</span>
                </summary>
                <form action="{{ route('home') }}" method="GET" class="mt-3 flex gap-2">
                    <input type="password" name="bypass" placeholder="Clave de seguridad..." required
                        class="flex-1 bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-cyan-400">
                    <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white font-semibold px-4 py-2 rounded-lg text-xs transition-colors">
                        Desbloquear
                    </button>
                </form>
            </details>
        </div>

    </div>

</body>
</html>
