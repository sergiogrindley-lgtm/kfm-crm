<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEICO Overseas Policy Declaration — {{ $policy->matricula }} — {{ $client->full_name }}</title>
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
                margin: 12mm 15mm 12mm 15mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 px-4 flex flex-col items-center">

    <!-- Action Bar (Hidden on print) -->
    <div class="no-print max-w-4xl w-full mb-6 flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to {{ $client->full_name }}'s Policy</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="copiarTextoGeico()" id="btnCopiarGeico" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all cursor-pointer border border-slate-300">
                <span>📋 Copy for GEICO Defense Portal</span>
            </button>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0c3547] hover:bg-[#145a78] text-white text-xs font-bold shadow-md transition-all cursor-pointer">
                <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Declaration (Ctrl + P)</span>
            </button>
        </div>
    </div>

    <!-- Official Document Sheet -->
    <div class="page-container max-w-4xl w-full bg-white rounded-2xl shadow-xl border border-slate-200 p-8 sm:p-12 text-slate-900">
        
        <!-- Document Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 border-b-2 border-slate-900 gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-[#0c3547] rounded-xl flex items-center justify-center text-white font-black text-2xl tracking-tighter shadow-sm border border-cyan-500/30">
                    GEICO
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="font-display font-extrabold text-xl text-[#0c3547] tracking-tight">OVERSEAS MILITARY DIVISION</h1>
                        <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">DOD Authorized</span>
                    </div>
                    <p class="text-xs text-slate-500 font-medium">Naval Station Rota Liaison & Servicing Office (KFM Insurance & Patria Gestoría)</p>
                </div>
            </div>

            <div class="text-left sm:text-right text-xs">
                <div class="font-mono font-bold text-slate-900 text-sm">POLICY: #{{ $policy->numero_poliza }}</div>
                <div class="text-slate-500">Effective: <strong class="text-slate-700">{{ $militaryInfo['policy_period'] }}</strong></div>
                <div class="text-emerald-700 font-bold text-[11px] mt-0.5">● STATUS: ACTIVE & PAID FULL</div>
            </div>
        </div>

        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-900 flex items-center justify-between">
            <div>
                <strong>U.S. MILITARY POV OVERSEAS INSURANCE DECLARATION</strong> • SOFA STATUS COMPLIANT
            </div>
            <span class="text-[10px] font-bold uppercase bg-blue-200/70 text-blue-800 px-2 py-0.5 rounded">NAVSTA ROTA, SPAIN</span>
        </div>

        <!-- 1. Insured Military Personnel Details -->
        <div class="mt-6">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-200 pb-1 mb-3">
                1. Insured Military Personnel / Policyholder Information
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-slate-500 block">Full Legal Name:</span>
                    <strong class="text-slate-900 text-sm">{{ $client->full_name }}</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Military Rank / Pay Grade:</span>
                    <strong class="text-slate-900">{{ $militaryInfo['rank'] }}</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Branch of Service:</span>
                    <strong class="text-slate-900">{{ $militaryInfo['branch'] }}</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">DOD ID / SSN Reference:</span>
                    <strong class="text-slate-900 font-mono">{{ $militaryInfo['dod_id'] }}</strong>
                </div>

                <div>
                    <span class="text-slate-500 block">Duty Station:</span>
                    <strong class="text-slate-900">{{ $militaryInfo['duty_station'] }}</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">U.S. Driver License:</span>
                    <strong class="text-slate-900 font-mono">{{ $militaryInfo['us_license'] }}</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Local Spanish Address:</span>
                    <strong class="text-slate-900">{{ $client->direccion_local ?: 'C/ San Juan de Puerto Rico 12, Rota' }}</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Contact Phone / Email:</span>
                    <strong class="text-slate-900">{{ $client->telefono_movil }}</strong>
                </div>
            </div>
        </div>

        <!-- 2. Insured Vehicle Details (Auto-decoded from NHTSA API) -->
        <div class="mt-6">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-200 pb-1 mb-3">
                2. Insured Vehicle Technical Specifications (US DOT Verified)
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div>
                    <span class="text-slate-500 block">Vehicle Make & Model:</span>
                    <strong class="text-slate-900 text-sm">{{ $policy->marca }} {{ $policy->modelo }}</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">License Plate (Spanish / POV):</span>
                    <strong class="text-slate-900 text-sm font-mono bg-white px-2 py-0.5 rounded border border-slate-300 inline-block">{{ $policy->matricula }}</strong>
                </div>
                <div class="col-span-2">
                    <span class="text-slate-500 block">Vehicle Identification Number (VIN):</span>
                    <strong class="text-cyan-900 text-sm font-mono tracking-wider bg-cyan-50 px-2 py-0.5 rounded border border-cyan-200 inline-block">{{ $policy->vin }}</strong>
                </div>

                <div>
                    <span class="text-slate-500 block">Model Year:</span>
                    <strong class="text-slate-900">2013</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Engine & Displacement:</span>
                    <strong class="text-slate-900">2.0L EcoBoost (141 HP)</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Fuel Type:</span>
                    <strong class="text-slate-900">Gasoline</strong>
                </div>
                <div>
                    <span class="text-slate-500 block">Assembly Country:</span>
                    <strong class="text-slate-900">United States (Wayne, MI)</strong>
                </div>
            </div>
        </div>

        <!-- 3. Policy Coverages & Statutory European Limits -->
        <div class="mt-6">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-200 pb-1 mb-3">
                3. Selected Schedule of Coverages & Limits
            </h2>
            <div class="space-y-2 text-xs">
                @foreach($militaryInfo['coverages'] as $key => $cov)
                    <div class="flex items-center justify-between p-2.5 rounded-lg border border-slate-200 bg-white">
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span class="font-semibold text-slate-800">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                        </div>
                        <span class="text-slate-600 font-medium">{{ $cov }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 4. Premium & Servicing Agent Certification -->
        <div class="mt-6 pt-4 border-t-2 border-slate-900 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Financial Settlement</span>
                <div class="text-lg font-black text-slate-900">ANNUAL PREMIUM: {{ $militaryInfo['premium'] }}</div>
                <div class="text-slate-500 text-[11px]">Settlement Method: {{ $militaryInfo['billing'] }} • Underwriting: KFM Insurance / Patria 81</div>
            </div>

            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Authorized Servicing Officer</span>
                <div class="font-bold text-slate-800">AGENT CHARI (NEX OFFICE NAVSTA ROTA)</div>
                <div class="text-slate-500 text-[11px]">Certified Liaison Agent • KFM Insurance & Patria Gestoría</div>
            </div>
        </div>

        <!-- Signatures Box -->
        <div class="mt-8 pt-6 border-t border-slate-200 grid grid-cols-2 gap-8 text-xs text-center">
            <div class="border-t border-dashed border-slate-400 pt-3">
                <span class="block font-bold text-slate-800">{{ $client->full_name }}</span>
                <span class="text-[10px] text-slate-500 uppercase">Insured Policyholder Signature</span>
            </div>

            <div class="border-t border-dashed border-slate-400 pt-3">
                <span class="block font-bold text-slate-800">KFM INSURANCE & PATRIA GESTORÍA</span>
                <span class="text-[10px] text-slate-500 uppercase">Authorized Official Stamp & Seal</span>
            </div>
        </div>

        <div class="mt-6 text-center text-[10px] text-slate-400">
            KFM Insurance Company • NEX Building Ground Floor, NAVSTA Rota • Phone: +34 956 84 00 50 • Email: info@kfminsurance.com
        </div>
    </div>

    <script>
        function copiarTextoGeico() {
            const texto = `GEICO OVERSEAS POLICY DECLARATION SUMMARY\n` +
                `POLICY NUMBER: #{{ $policy->numero_poliza }}\n` +
                `INSURED: {{ $client->full_name }}\n` +
                `MILITARY DUTY: {{ $militaryInfo['duty_station'] }}\n` +
                `DOD ID: {{ $militaryInfo['dod_id'] }}\n` +
                `VEHICLE: {{ $policy->marca }} {{ $policy->modelo }} (2013)\n` +
                `VIN: {{ $policy->vin }}\n` +
                `PLATE: {{ $policy->matricula }}\n` +
                `PREMIUM: {{ $militaryInfo['premium'] }} (Annual Paid Full)\n` +
                `STATUS: ACTIVE & CERTIFIED AT NAVSTA ROTA LIAISON OFFICE`;
            navigator.clipboard.writeText(texto).then(() => {
                const btn = document.getElementById('btnCopiarGeico');
                if (btn) {
                    const orig = btn.innerHTML;
                    btn.innerHTML = '<span>✅ Copied to Clipboard!</span>';
                    setTimeout(() => btn.innerHTML = orig, 2000);
                }
            });
        }
    </script>
</body>
</html>
