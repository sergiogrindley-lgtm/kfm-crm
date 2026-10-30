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

    @if (isset($errors) && $errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg text-sm text-rose-800">
            <p class="font-bold mb-1">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error_duplicado'))
        @php $dup = session('error_duplicado'); @endphp
        <div class="bg-amber-50 border-2 border-amber-400 p-5 rounded-2xl shadow-sm text-sm text-amber-950 space-y-3">
            <div class="flex items-start space-x-3">
                <span class="text-2xl flex-shrink-0">⚠️</span>
                <div class="flex-1">
                    <h3 class="font-bold text-base text-amber-900">¡Atención! Posible cliente duplicado en el sistema</h3>
                    <p class="text-xs text-amber-800 mt-0.5">
                        Se ha detectado una coincidencia exacta por <strong>{{ $dup['coincidencia'] ?? 'documento / nombre' }}</strong> con un cliente ya existente en la base de datos:
                    </p>
                    <div class="mt-2 p-3 bg-white/90 rounded-xl border border-amber-200 flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <strong class="font-bold text-slate-900 text-sm">{{ $dup['nombre_completo'] ?? '' }}</strong>
                            <span class="text-xs text-slate-500 font-mono ml-2">({{ $dup['doc'] ?? '' }})</span>
                        </div>
                        <a href="{{ $dup['url'] ?? route('clients.show', $dup['cliente_id'] ?? 1) }}" target="_blank" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1 shadow-sm">
                            <span>Ver Ficha Existente (#{{ $dup['cliente_id'] ?? '' }})</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <span class="text-xs text-amber-800">Si estás seguro de que es una persona distinta o deseas crear la ficha igualmente:</span>
                        <button type="submit" name="forzar_duplicado" value="1" form="nuevoClienteForm" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold transition cursor-pointer">
                            Confirmar y Crear Duplicado
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form id="nuevoClienteForm" method="POST" action="{{ route('clients.store') }}" class="space-y-6">
        @csrf

        <!-- Sede y Agente por defecto KFM -->
        <input type="hidden" name="sede_id" value="{{ old('sede_id', 2) }}">
        <input type="hidden" name="agente" value="{{ old('agente', 'Kerry') }}">

        <!-- 1. Datos Personales -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center space-x-2">
                <span>👤 1. Datos Personales</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nombre <span class="text-rose-500">*</span></label>
                    <input type="text" id="inputNombre" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: JONATHAN" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Apellidos <span class="text-rose-500">*</span></label>
                    <input type="text" id="inputApellido" name="apellido" value="{{ old('apellido') }}" required placeholder="Ej: NEGRON" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">DNI / NIE / CIF</label>
                    <input type="text" id="inputDoc" name="doc_identidad" value="{{ old('doc_identidad') }}" placeholder="Ej: 48991448R o Y1979608N" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm uppercase focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">SSN (Social Security)</label>
                    <input type="text" id="inputSsn" name="ssn" value="{{ old('ssn') }}" placeholder="Ej: 582-81-1386" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
            </div>

            <!-- Banner Alerta de Duplicado en Tiempo Real -->
            <div id="liveDuplicateContainer" class="hidden transition-all duration-300">
                <div class="bg-amber-50 border-2 border-amber-400 rounded-2xl p-4 sm:p-5 shadow-sm space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-lg shadow-sm flex-shrink-0">
                                ⚠️
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-amber-950 font-display">
                                    ¡Esta persona ya es cliente en el CRM!
                                </h3>
                                <p id="liveDuplicateReason" class="text-xs text-amber-800 font-medium mt-0.5"></p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-200 text-amber-900 border border-amber-300 flex-shrink-0">
                            Cliente Existente
                        </span>
                    </div>

                    <div id="liveDuplicateList" class="space-y-2 pt-1">
                        <!-- Clientes existentes inyectados dinámicamente -->
                    </div>

                    <div class="text-[11px] text-amber-900 bg-amber-100/70 p-2.5 rounded-xl border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <span>💡 <strong>Consejo para no duplicar:</strong> Pulsa en <em>"Abrir Ficha Existente"</em> para añadirle la nueva póliza o modificar sus datos en su expediente ya creado.</span>
                        <button type="button" onclick="cerrarLiveDuplicate()" class="text-xs font-semibold text-amber-800 hover:text-amber-950 underline self-end sm:self-auto cursor-pointer">
                            Ocultar aviso y continuar igualmente
                        </button>
                    </div>
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

        <!-- 2. Contratación & Facturación (Opcional Inicial) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <label class="flex items-start sm:items-center space-x-3 cursor-pointer select-none">
                    <input type="checkbox" id="checkCrearPoliza" name="crear_poliza" value="1" onchange="toggleSeccionPoliza(this.checked)" {{ old('crear_poliza') ? 'checked' : '' }} 
                           class="w-5 h-5 mt-0.5 sm:mt-0 text-kfm-primary rounded border-slate-300 focus:ring-kfm-primary cursor-pointer">
                    <div>
                        <span class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center space-x-2">
                            <span>🛡️ 2. Añadir Póliza / Contratación Inicial Ahora (Opcional)</span>
                        </span>
                        <span class="text-xs text-slate-500 font-normal">Marca aquí sólo si el cliente va a contratar una póliza ahora. Si sólo estás dando de alta al cliente, déjalo desmarcado.</span>
                    </div>
                </label>
                <span id="badgePolizaEstado" class="text-xs font-semibold px-2.5 py-1 rounded-full {{ old('crear_poliza') ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                    {{ old('crear_poliza') ? 'Póliza Activa' : 'Sin póliza inicial' }}
                </span>
            </div>

            <div id="contenedorPolizaCampos" class="{{ old('crear_poliza') ? '' : 'hidden' }} space-y-5">

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
                    <select name="codigo_aseguradora" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                        <option value="81" selected>Patria 81</option>
                        <option value="87">GEICO 87</option>
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Categoría del Vehículo</label>
                            <select name="categoria_vehiculo" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold text-slate-800 focus:ring-1 focus:ring-kfm-primary">
                                <option value="Turismo" selected>Turismo</option>
                                <option value="Furgoneta">Furgoneta</option>
                                <option value="Camiones">Camiones</option>
                                <option value="Autocaravana">Autocaravana</option>
                                <option value="Microcar">Microcar</option>
                                <option value="Motocicleta">Motocicleta</option>
                                <option value="Ciclomotores">Ciclomotores</option>
                            </select>
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
                                <option value="">-- Seleccionar --</option>
                                <option value="PISO MEDIO">Piso (Planta Intermedia)</option>
                                <option value="PISO BAJO">Piso (Bajo / Con Jardín)</option>
                                <option value="ATICO">Ático</option>
                                <option value="UNIF ADOSADA">Unifamiliar Adosada</option>
                                <option value="UNIF NO ADOSADA">Chalet / Vivienda Aislada</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Categoría (Régimen)</label>
                            <select name="categoria_hogar" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold text-slate-800 focus:ring-1 focus:ring-kfm-primary">
                                <option value="Owner" selected>Owner</option>
                                <option value="Renter">Renter</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Capital Continente (€)</label>
                            <input type="text" name="capital_continente" placeholder="Ej: 120.000 €" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Capital Contenido (€)</label>
                            <input type="text" name="capital_contenido" placeholder="Ej: 30.000 €" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                        </div>
                    </div>
                </div>

                <!-- 3. BLOQUE RESPONSABILIDAD CIVIL (RC) -->
                <div id="bloque-RC" class="space-y-4 hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Categoría (Modalidad de RC)</label>
                            <select name="categoria_rc" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold text-slate-800 focus:ring-1 focus:ring-kfm-primary">
                                <option value="Animales de compañía/perros" selected>Animales de compañía/perros</option>
                                <option value="Profesional/autónomo">Profesional/autónomo</option>
                                <option value="Otros">Otros</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Detalle del Riesgo (Mascota / Actividad / Microchip)</label>
                            <input type="text" name="detalles_cobertura" placeholder="Ej: Perro Pastor Alemán • Nº Microchip 94100002891 • Cobertura en Base y Territorio Español" 
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs focus:ring-1 focus:ring-kfm-primary">
                        </div>
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
                
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nº de Recibo</label>
                        <input type="text" name="recibo_numero" value="{{ old('recibo_numero') }}" placeholder="Ej: 2026-0814" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Prima (€)</label>
                        <input type="text" name="prima" value="{{ old('prima') }}" placeholder="Ej: 406,05 €" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-emerald-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tipo de Pago</label>
                        <select name="tipo_facturacion" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                            <option value="Anual" selected>Anual</option>
                            <option value="Semianual">Semianual</option>
                            <option value="Suplemento">Suplemento</option>
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

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-2">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Fecha de Pago</label>
                        <input type="text" name="fecha_pago" value="{{ old('fecha_pago') }}" placeholder="DD/MM/AAAA" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Fecha de Vencimiento</label>
                        <input type="text" name="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}" placeholder="DD/MM/AAAA" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Liquidación Cía.</label>
                        <select name="liquidacion" id="liquidacionSelect" onchange="toggleLiquidacionFecha(this.value)" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold">
                            <option value="Pendiente" {{ old('liquidacion') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="Liquidado" {{ old('liquidacion') == 'Liquidado' ? 'selected' : '' }}>Liquidado</option>
                        </select>
                    </div>
                    <div id="fechaLiquidacionCol" class="{{ old('liquidacion') == 'Liquidado' ? '' : 'hidden' }}">
                        <label class="block text-xs font-semibold text-emerald-700 mb-1">Fecha de Liquidación</label>
                        <input type="text" name="fecha_liquidacion" id="fechaLiquidacionInput" value="{{ old('fecha_liquidacion') }}" placeholder="DD/MM/AAAA" 
                               class="w-full px-3 py-2 bg-emerald-50 border border-emerald-300 rounded-xl text-xs font-medium text-emerald-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            </div>
            </div> <!-- /contenedorPolizaCampos -->
        </div>

        <!-- 3. Observaciones -->
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
    function toggleSeccionPoliza(activada) {
        const contenedor = document.getElementById('contenedorPolizaCampos');
        const badge = document.getElementById('badgePolizaEstado');
        if (activada) {
            contenedor.classList.remove('hidden');
            badge.className = 'text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800';
            badge.textContent = 'Póliza Activa';
        } else {
            contenedor.classList.add('hidden');
            badge.className = 'text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600';
            badge.textContent = 'Sin póliza inicial';
        }
    }

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

    function toggleLiquidacionFecha(val) {
        const col = document.getElementById('fechaLiquidacionCol');
        if (!col) return;
        if (val === 'Liquidado') {
            col.classList.remove('hidden');
            const input = document.getElementById('fechaLiquidacionInput');
            if (input && !input.value) {
                const today = new Date();
                const d = String(today.getDate()).padStart(2, '0');
                const m = String(today.getMonth() + 1).padStart(2, '0');
                const y = today.getFullYear();
                input.value = `${d}/${m}/${y}`;
            }
        } else {
            col.classList.add('hidden');
        }
    }

    // ========================================================
    // DETECCIÓN INTERACTIVA DE DUPLICADOS EN TIEMPO REAL
    // ========================================================
    let checkTimeout = null;
    let lastQuery = '';

    function setupLiveDuplicateCheck() {
        const inputs = ['inputNombre', 'inputApellido', 'inputDoc', 'inputSsn'];
        inputs.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('input', () => {
                clearTimeout(checkTimeout);
                checkTimeout = setTimeout(checkDuplicateLive, 350);
            });
        });

        // Comprobación inicial por si hay valores recuperados (old)
        checkDuplicateLive();
    }

    function checkDuplicateLive() {
        const nombre = document.getElementById('inputNombre')?.value.trim() || '';
        const apellido = document.getElementById('inputApellido')?.value.trim() || '';
        const doc = document.getElementById('inputDoc')?.value.trim() || '';
        const ssn = document.getElementById('inputSsn')?.value.trim() || '';

        // Si todos están vacíos o muy cortos, ocultamos
        if (!nombre && !apellido && !doc && !ssn) {
            document.getElementById('liveDuplicateContainer')?.classList.add('hidden');
            return;
        }

        const currentQuery = `${nombre}|${apellido}|${doc}|${ssn}`;
        if (currentQuery === lastQuery) return;
        lastQuery = currentQuery;

        const params = new URLSearchParams({
            nombre: nombre,
            apellido: apellido,
            doc_identidad: doc,
            ssn: ssn
        });

        fetch(`/api/check-duplicate-client?${params.toString()}`)
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('liveDuplicateContainer');
                const reasonEl = document.getElementById('liveDuplicateReason');
                const listEl = document.getElementById('liveDuplicateList');
                if (!container || !reasonEl || !listEl) return;

                if (data.exists && data.clients && data.clients.length > 0) {
                    reasonEl.textContent = data.reason;
                    listEl.innerHTML = '';

                    data.clients.forEach(c => {
                        const card = document.createElement('div');
                        card.className = 'p-3.5 bg-white rounded-xl border border-amber-300 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs';
                        card.innerHTML = `
                            <div>
                                <div class="flex items-center space-x-2">
                                    <strong class="text-sm font-bold text-slate-900 font-display">${c.nombre_completo}</strong>
                                    <span class="text-xs text-slate-500 font-mono">ID: #${c.id}</span>
                                </div>
                                <div class="text-xs text-slate-600 mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                                    ${c.doc_identidad && c.doc_identidad !== 'Sin DNI' ? `<span>DNI/NIE: <strong>${c.doc_identidad}</strong></span>` : ''}
                                    ${c.ssn ? `<span>SSN: <strong class="text-blue-700">${c.ssn}</strong></span>` : ''}
                                    ${c.telefono ? `<span>Tel: <strong>${c.telefono}</strong></span>` : ''}
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase ${c.policies_count > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'}">
                                        ${c.policies_count} Póliza(s)
                                    </span>
                                </div>
                            </div>
                            <a href="${c.url}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex-shrink-0 gap-1.5 cursor-pointer">
                                <span>Abrir Ficha Existente (#${c.id})</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        `;
                        listEl.appendChild(card);
                    });

                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            })
            .catch(err => {
                console.error('Error comprobando duplicados:', err);
            });
    }

    function cerrarLiveDuplicate() {
        document.getElementById('liveDuplicateContainer')?.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', setupLiveDuplicateCheck);
</script>
@endsection
