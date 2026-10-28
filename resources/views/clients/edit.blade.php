@extends('layouts.app')

@section('title', 'Editar ' . $client->full_name . ' — KFM CRM')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header / Volver -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('clients.show', $client) }}" class="text-xs text-kfm-primary hover:underline font-semibold flex items-center space-x-1 mb-1">
                <span>← Volver a la Ficha de {{ $client->full_name }}</span>
            </a>
            <h1 class="text-2xl font-bold font-display text-slate-900">Editar Ficha de Cliente</h1>
            <p class="text-sm text-slate-500">Modifica los datos personales, de contacto o direcciones del expediente #{{ $client->id }}.</p>
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

    <form method="POST" action="{{ route('clients.update', $client) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Datos de Gestión KFM -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center space-x-2">
                <span>🏢 Asignación KFM</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Sede de KFM <span class="text-rose-500">*</span></label>
                    <select name="sede_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                        @foreach($sedes as $s)
                            <option value="{{ $s->id }}" {{ old('sede_id', $client->sede_id) == $s->id ? 'selected' : '' }}>
                                {{ $s->nombre }} ({{ $s->slug }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Agente Responsable</label>
                    <select name="agente" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                        @foreach($agentes as $ag)
                            <option value="{{ $ag }}" {{ old('agente', $client->agente) == $ag ? 'selected' : '' }}>
                                {{ $ag }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 1. Datos Personales -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center space-x-2">
                <span>👤 1. Datos Personales</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nombre <span class="text-rose-500">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre', $client->nombre) }}" required placeholder="Ej: JONATHAN" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm uppercase focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Apellidos <span class="text-rose-500">*</span></label>
                    <input type="text" name="apellido" value="{{ old('apellido', $client->apellido) }}" required placeholder="Ej: NEGRON" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm uppercase focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">DNI / NIE / CIF</label>
                    <input type="text" name="doc_identidad" value="{{ old('doc_identidad', $client->doc_identidad) }}" placeholder="Ej: 48991448R o Y1979608N" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm uppercase font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">SSN (Social Security)</label>
                    <input type="text" name="ssn" value="{{ old('ssn', $client->ssn) }}" placeholder="Ej: 582-81-1386" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
            </div>

            <!-- Fila Nombre Familiar & Fecha Nacimiento -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-purple-800 mb-1 flex items-center space-x-1">
                        <span>👥 Nombre Familiar / Cónyuge</span>
                        <span class="text-[10px] text-slate-400 font-normal">(Esposa, marido o familiar autorizado)</span>
                    </label>
                    <input type="text" name="nombre_familiar" value="{{ old('nombre_familiar', $client->nombre_familiar) }}" placeholder="Ej: Mary Ferguson (Esposa)" 
                           class="w-full px-3.5 py-2.5 bg-purple-50/50 border border-purple-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Fecha de Nacimiento (DOB)</label>
                    <input type="text" name="dob" value="{{ old('dob', $client->dob) }}" placeholder="DD/MM/AAAA (ej: 28/09/79)" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Número 1 (Móvil)</label>
                    <input type="text" name="telefono_movil" value="{{ old('telefono_movil', $client->telefono_movil) }}" placeholder="Ej: 628-561129" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Número 2 (Fijo / Trabajo)</label>
                    <input type="text" name="telefono_fijo" value="{{ old('telefono_fijo', $client->telefono_fijo) }}" placeholder="Ej: 956-824185" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Personal</label>
                    <input type="email" name="email_personal" value="{{ old('email_personal', $client->email_personal) }}" placeholder="ejemplo@gmail.com" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Trabajo (Militar / Oficial)</label>
                    <input type="text" name="email_trabajo" value="{{ old('email_trabajo', $client->email_trabajo) }}" placeholder="ejemplo@eu.navy.mil o @dodea.edu" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Dirección Base (NEX / PSC Box / Buque USS)</label>
                    <input type="text" name="direccion_base" value="{{ old('direccion_base', $client->direccion_base) }}" placeholder="Ej: LG PSC 819 BOX 7596 – 11530 ROTA" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Dirección Local (España)</label>
                    <input type="text" name="direccion_local" value="{{ old('direccion_local', $client->direccion_local) }}" placeholder="Ej: C/ Triunfo 7, Rota" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">
                </div>
            </div>
        </div>

        <!-- 2. Observaciones -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-2">
            <label class="block text-xs font-semibold text-slate-700 mb-1">Observaciones / Notas Internas</label>
            <textarea name="observaciones" rows="3" placeholder="Anotaciones de gestión, detalles del cliente o requerimientos de base..." 
                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-kfm-primary">{{ old('observaciones', $client->observaciones) }}</textarea>
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('clients.show', $client) }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-kfm-primary hover:bg-kfm-cerulean text-white font-semibold text-sm shadow-md transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Guardar Cambios</span>
            </button>
        </div>

    </form>
</div>
@endsection
