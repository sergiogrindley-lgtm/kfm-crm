@extends('layouts.app')

@section('title', 'Nuevo Cliente — KFM CRM')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header / Volver -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('home') }}" class="text-xs text-kfm-primary hover:underline font-semibold flex items-center space-x-1 mb-1">
                <span>← Volver al Buscador de Clientes</span>
            </a>
            <h1 class="text-2xl font-bold font-display text-slate-900">Introducir Nuevo Cliente</h1>
            <p class="text-sm text-slate-500">Formulario configurado según las especificaciones operativas de KFM.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg text-sm text-rose-800">
            <p class="font-bold mb-1">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('clients.store') }}" class="space-y-6">
        @csrf

        <!-- 1. Oficina y Agente Tramitadora -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center space-x-2">
                <span>🏢 1. Oficina y Agente</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Oficina Asignada <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($sedes as $sede)
                            <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-slate-50 transition {{ old('sede_id', 2) == $sede->id ? 'border-kfm-primary bg-sky-50/50 ring-2 ring-kfm-primary/20' : 'border-slate-200' }}">
                                <input type="radio" name="sede_id" value="{{ $sede->id }}" {{ old('sede_id', 2) == $sede->id ? 'checked' : '' }} class="h-4 w-4 text-kfm-primary focus:ring-kfm-primary border-slate-300">
                                <div class="ml-2.5">
                                    <span class="block text-xs font-bold text-slate-900">{{ $sede->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Agente Tramitador/a <span class="text-rose-500">*</span></label>
                    <select name="agente" class="w-full px-3.5 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                        @foreach($agentes as $ag)
                            <option value="{{ $ag }}" {{ old('agente', 'Kerry') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                        @endforeach
                    </select>
                    <span class="text-[11px] text-slate-400 mt-1 block">Equipo activo KFM Rota</span>
                </div>
            </div>
        </div>

        <!-- 2. Datos Personales -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center space-x-2">
                <span>👤 2. Datos Personales</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nombre <span class="text-rose-500">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: JONATHAN" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Apellidos <span class="text-rose-500">*</span></label>
                    <input type="text" name="apellido" value="{{ old('apellido') }}" required placeholder="Ej: NEGRON" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Doc. Identificación (DNI/NIE/SSN)</label>
                    <input type="text" name="doc_identidad" value="{{ old('doc_identidad') }}" placeholder="Ej: 582811386 o Y1979608N" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm uppercase focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
            </div>

            <!-- Fila Nombre Familiar & Fecha Nacimiento -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-purple-800 mb-1 flex items-center space-x-1">
                        <span>👥 Nombre Familiar / Cónyuge</span>
                        <span class="text-[10px] text-slate-400 font-normal">(Esposa, marido o familiar autorizado)</span>
                    </label>
                    <input type="text" name="nombre_familiar" value="{{ old('nombre_familiar') }}" placeholder="Ej: Mary Ferguson (Esposa)" 
                           class="w-full px-3.5 py-2.5 bg-purple-50/50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Fecha de Nacimiento</label>
                    <input type="text" name="dob" value="{{ old('dob') }}" placeholder="DD/MM/AAAA (ej: 28/09/79)" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Número 1 (Móvil)</label>
                    <input type="text" name="telefono_movil" value="{{ old('telefono_movil') }}" placeholder="Ej: 628-561129" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Número 2 (Fijo / Trabajo)</label>
                    <input type="text" name="telefono_fijo" value="{{ old('telefono_fijo') }}" placeholder="Ej: 956-824185" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Personal</label>
                    <input type="email" name="email_personal" value="{{ old('email_personal') }}" placeholder="ejemplo@gmail.com" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Trabajo (Militar / Oficial)</label>
                    <input type="text" name="email_trabajo" value="{{ old('email_trabajo') }}" placeholder="ejemplo@eu.navy.mil o @dodea.edu" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Dirección Base (NEX / PSC Box / Buque USS)</label>
                    <input type="text" name="direccion_base" value="{{ old('direccion_base') }}" placeholder="Ej: LG PSC 819 BOX 7596 – 11530 ROTA" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Dirección Local (España)</label>
                    <input type="text" name="direccion_local" value="{{ old('direccion_local') }}" placeholder="Ej: C/ Triunfo 7, Rota" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
            </div>
        </div>

        <!-- 3. Contratación & Facturación (Opcional Inicial) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center space-x-2">
                    <span>🛡️ 3. Contratación y Facturación</span>
                </h2>
                <span class="text-xs text-slate-400">Puedes dejarlo en blanco o darlo de alta ahora</span>
            </div>

            <!-- Selector de Ramo & Aseguradora -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Contratación (Ramo)</label>
                    <select name="ramo" id="ramoSelector" onchange="cambiarRamo(this.value)" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-kfm-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                        <option value="Vehiculo" selected>🚗 Vehículo (Coche, Moto, POV)</option>
                        <option value="Hogar">🏠 Hogar / Inquilino (Renters)</option>
                        <option value="RC">⚖️ Responsabilidad Civil (RC)</option>
                        <option value="Empresa">🏢 Empresa / Comercio</option>
                        <option value="Otros">🛡️ Otros Seguros (Salud, Embarcación)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Aseguradora (Código)</label>
                    <select name="codigo_aseguradora" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                        <option value="81">81 — Patria Hispana</option>
                        <option value="87">87 — Entidad 87 (Liberty)</option>
                        <option value="Allianz">Allianz Seguros</option>
                        <option value="Reale">Reale Seguros</option>
                        <option value="Mapfre">Mapfre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Número de Póliza</label>
                    <input type="text" name="numero_poliza" value="{{ old('numero_poliza') }}" placeholder="Ej: 1661462" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
            </div>

            <!-- Pestañas Visuales de Selección Rápida de Ramo -->
            <div class="flex items-center space-x-2 border-b border-slate-200 pb-2 text-xs overflow-x-auto">
                <button type="button" onclick="setRamo('Vehiculo')" id="tab-Vehiculo" class="px-3 py-1.5 rounded-lg font-bold transition flex items-center space-x-1.5 bg-kfm-primary text-white shadow-sm">
                    <span>🚗 Vehículo</span>
                </button>
                <button type="button" onclick="setRamo('Hogar')" id="tab-Hogar" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200">
                    <span>🏠 Hogar / Inquilino</span>
                </button>
                <button type="button" onclick="setRamo('RC')" id="tab-RC" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200">
                    <span>⚖️ Responsabilidad Civil</span>
                </button>
                <button type="button" onclick="setRamo('Empresa')" id="tab-Empresa" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200">
                    <span>🏢 Empresa</span>
                </button>
                <button type="button" onclick="setRamo('Otros')" id="tab-Otros" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200">
                    <span>🛡️ Otros Seguros</span>
                </button>
            </div>

            <!-- ========================================== -->
            <!-- DETALLES DEL BIEN ASEGURADO (DINÁMICO) -->
            <!-- ========================================== -->
            <div class="bg-slate-50 rounded-2xl border border-slate-200/90 p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <span id="ramoHeaderTitle" class="text-xs font-bold uppercase tracking-wider text-kfm-navy flex items-center space-x-2">
                        <span>🚗 Detalles del Vehículo Asegurado</span>
                    </span>
                    <span class="text-[11px] text-slate-400">Campos adaptados al ramo</span>
                </div>

                <!-- 1. BLOQUE VEHÍCULO -->
                <div id="bloque-Vehiculo" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Marca</label>
                            <input type="text" name="marca" placeholder="Ej: HONDA / BMW" 
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase focus:ring-1 focus:ring-kfm-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Modelo</label>
                            <input type="text" name="modelo" placeholder="Ej: CIVIC 1.5 / CR-V" 
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase focus:ring-1 focus:ring-kfm-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Matrícula (Española / TA / US)</label>
                            <input type="text" name="matricula" placeholder="Ej: 0680 JCL" 
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono uppercase focus:ring-1 focus:ring-kfm-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">VIN (Bastidor Oficial)</label>
                            <input type="text" name="vin" placeholder="17 caracteres alfanuméricos" 
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono uppercase focus:ring-1 focus:ring-kfm-primary">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Categoría / Uso del Vehículo</label>
                            <select class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                <option>POV Militar (NAVSTA Base Rota)</option>
                                <option>Turismo Particular (Residente)</option>
                                <option>Motocicleta / Scooter</option>
                                <option>Vehículo Importado EE.UU.</option>
                                <option>Vehículo Comercial / Furgoneta</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Carta Verde (Green Card)</label>
                            <select class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                <option>Emitida en el acto (Requerida en base)</option>
                                <option>Pendiente de emisión</option>
                                <option>No requerida (Circulación nacional)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Combustible / Año</label>
                            <input type="text" placeholder="Ej: Gasolina • 2021" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                        </div>
                    </div>
                </div>

                <!-- 2. BLOQUE HOGAR / INQUILINO -->
                <div id="bloque-Hogar" class="space-y-4 hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Dirección de la Vivienda Asegurada</label>
                            <input type="text" name="direccion_riesgo" placeholder="Ej: C/ Corbeta 13, Urb. Vistahermosa, El Puerto o Rota Naval Housing" 
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs focus:ring-1 focus:ring-kfm-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tipo de Vivienda</label>
                            <select name="tipo_vivienda" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                <option value="PISO MEDIO">Piso (Planta Intermedia)</option>
                                <option value="PISO BAJO">Piso (Bajo / Con Jardín)</option>
                                <option value="ATICO">Ático</option>
                                <option value="UNIF ADOSADA" selected>Unifamiliar Adosada</option>
                                <option value="UNIF NO ADOSADA">Chalet / Vivienda Aislada</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-1">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Régimen del Militar/Cliente</label>
                            <select class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                <option>Inquilino (Renters SOFA Base)</option>
                                <option>Propietario Residente</option>
                                <option>Arrendador (Propietario alquila)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Capital Continente (€)</label>
                            <input type="text" placeholder="Ej: 120.000 €" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Capital Contenido (€)</label>
                            <input type="text" placeholder="Ej: 30.000 €" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">RC Inquilino / Locativa (€)</label>
                            <input type="text" placeholder="Ej: 300.000 €" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                        </div>
                    </div>
                </div>

                <!-- 3. BLOQUE RESPONSABILIDAD CIVIL (RC) -->
                <div id="bloque-RC" class="space-y-4 hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Modalidad de RC</label>
                            <select class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                <option>RC Animales de Compañía / Perros (PPP)</option>
                                <option>RC Familiar y Vida Privada</option>
                                <option>RC Cazador y Armas</option>
                                <option>RC Profesional / Autónomo</option>
                                <option>RC Embarcación de Recreo</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Límite de Cobertura (€)</label>
                            <select class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                <option>150.000 € (Básica)</option>
                                <option selected>300.000 € (Estándar)</option>
                                <option>600.000 € (Ampliada)</option>
                                <option>1.200.000 € (Total)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Franquicia</label>
                            <select class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                <option>Sin Franquicia (0 €)</option>
                                <option>150 €</option>
                                <option>300 €</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Detalle del Riesgo (Mascota / Actividad / Microchip)</label>
                        <input type="text" name="detalles_cobertura" placeholder="Ej: Perro Pastor Alemán • Nº Microchip 94100002891 • Cobertura en Base y Territorio Español" 
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs focus:ring-1 focus:ring-kfm-primary">
                    </div>
                </div>

                <!-- 4. BLOQUE EMPRESA / COMERCIO -->
                <div id="bloque-Empresa" class="space-y-4 hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nombre Comercial / Razón Social</label>
                            <input type="text" placeholder="Ej: Rota Naval Services S.L." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">CIF / NIF Empresa</label>
                            <input type="text" placeholder="Ej: B-11223344" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs uppercase font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Actividad / Epígrafe</label>
                            <input type="text" placeholder="Ej: Hostelería, Taller naval, Comercio" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Dirección del Local o Nave</label>
                            <input type="text" placeholder="Ej: Polígono Industrial La Marina, Rota" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Capital Continente / Local (€)</label>
                            <input type="text" placeholder="Ej: 200.000 €" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Existencias y Mobiliario (€)</label>
                            <input type="text" placeholder="Ej: 60.000 €" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                        </div>
                    </div>
                </div>

                <!-- 5. BLOQUE OTROS SEGUROS -->
                <div id="bloque-Otros" class="space-y-4 hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tipo de Seguro Especial</label>
                            <select class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                                <option>Salud Privada Bilingüe (Cobertura Internacional)</option>
                                <option>Embarcación de Recreo / Moto de Agua</option>
                                <option>Vida y Protección Familiar</option>
                                <option>Accidentes Personales</option>
                                <option>Decesos y Repatriación Militar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Compañía Especializada</label>
                            <input type="text" placeholder="Ej: Sanitas / Adeslas / DKV" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nº Asegurados / Beneficiarios</label>
                            <input type="text" placeholder="Ej: Titular + 2 beneficiarios" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Descripción del Riesgo y Cláusulas</label>
                        <input type="text" placeholder="Ej: Cobertura médica completa sin copago + repatriación aérea a EE.UU." 
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                    </div>
                </div>

            </div>

            <!-- Facturación -->
            <div class="pt-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-2">Información de Facturación</span>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Prima (€)</label>
                        <input type="text" name="prima" placeholder="Ej: 406,05 €" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-emerald-700">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Tipo de Pago</label>
                        <select name="tipo_facturacion" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                            <option value="Annual">Annual (Anual)</option>
                            <option value="Semi-annual">Semi-annual (Semestral)</option>
                            <option value="Suplemento">Suplemento</option>
                            <option value="Prima aplicada">Prima aplicada</option>
                            <option value="Cedido">Cedido</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Balance</label>
                        <select name="balance" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold">
                            <option value="Paid Full">Paid Full (Pagado Completo)</option>
                            <option value="Partial">Partial (Pago Parcial)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Fecha de Pago</label>
                        <input type="text" name="fecha_pago" placeholder="DD/MM/AAAA" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Fecha de Vencimiento</label>
                        <input type="text" name="fecha_vencimiento" placeholder="DD/MM/AAAA" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Liquidación Cía.</label>
                        <select name="liquidacion" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                            <option value="Liquidado">Liquidado</option>
                            <option value="Pendiente">Pendiente</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Observaciones -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-2">
            <label class="block text-xs font-semibold text-slate-700 mb-1">Observaciones / Notas Internas</label>
            <textarea name="observaciones" rows="3" placeholder="Anotaciones de gestión, detalles del cliente o requerimientos de base..." 
                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">{{ old('observaciones') }}</textarea>
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-kfm-primary hover:bg-kfm-cerulean text-white font-semibold text-sm shadow-md transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Guardar Cliente en CRM</span>
            </button>
        </div>

    </form>
</div>

<!-- JavaScript para alternar visualmente entre los ramos -->
<script>
    const ramos = ['Vehiculo', 'Hogar', 'RC', 'Empresa', 'Otros'];
    const titulos = {
        'Vehiculo': '🚗 Detalles del Vehículo Asegurado (Auto / Moto / POV)',
        'Hogar': '🏠 Detalles del Inmueble Asegurado (Hogar / Inquilino Renters)',
        'RC': '⚖️ Detalles de Responsabilidad Civil (Mascotas / Profesional / Armas)',
        'Empresa': '🏢 Detalles de la Empresa o Comercio',
        'Otros': '🛡️ Detalles de Cobertura Especial (Salud / Embarcación / Vida)'
    };

    function setRamo(ramoSeleccionado) {
        document.getElementById('ramoSelector').value = ramoSeleccionado;
        cambiarRamo(ramoSeleccionado);
    }

    function cambiarRamo(ramo) {
        // Actualizar título
        document.getElementById('ramoHeaderTitle').innerHTML = `<span>${titulos[ramo] || 'Detalles del Bien Asegurado'}</span>`;

        // Ocultar todos los bloques y mostrar el seleccionado
        ramos.forEach(r => {
            const bloque = document.getElementById('bloque-' + r);
            const tab = document.getElementById('tab-' + r);
            if (bloque) {
                if (r === ramo) {
                    bloque.classList.remove('hidden');
                } else {
                    bloque.classList.add('hidden');
                }
            }
            if (tab) {
                if (r === ramo) {
                    tab.className = 'px-3 py-1.5 rounded-lg font-bold transition flex items-center space-x-1.5 bg-kfm-primary text-white shadow-sm';
                } else {
                    tab.className = 'px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200';
                }
            }
        });
    }
</script>
@endsection
