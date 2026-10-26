<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandato DGT Comprador — {{ $tramite->matricula }} — KFM Rota</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 16mm 12mm 16mm;
        }
        body {
            font-family: Arial, 'Helvetica Neue', Helvetica, 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
            background-color: #f8fafc;
            line-height: 1.5;
        }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            body { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .print-sheet {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="p-3 sm:p-6 text-[12px]">

    <!-- Action Bar (No Print) -->
    <div class="max-w-3xl mx-auto mb-4 flex items-center justify-between no-print bg-slate-900 text-white px-5 py-2.5 rounded-xl shadow-lg">
        <div class="flex items-center space-x-3 text-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-cyan-400 animate-pulse"></span>
            <span class="font-bold uppercase tracking-wider text-slate-200">Mandato Oficial DGT — Comprador (Adquirente)</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 font-bold text-xs rounded-lg transition flex items-center gap-1.5 shadow-md cursor-pointer">
                <span>🖨️ Imprimir Mandato Comprador</span>
            </button>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-lg transition cursor-pointer">
                Cerrar
            </button>
        </div>
    </div>

    <!-- Official Document Sheet -->
    <div class="max-w-3xl mx-auto bg-white p-10 sm:p-12 rounded-xl shadow-xl border border-slate-200 print-sheet space-y-4 text-slate-900">
        
        <!-- Header Oficial Colegiado (QA + Escudo) -->
        <div class="text-center pb-2 border-b border-slate-200">
            <img src="{{ asset('img/logo-dgt-gestor.png') }}" alt="Consejo General de Colegios de Gestores Administrativos de España" class="h-14 w-auto mx-auto object-contain">
        </div>

        <div class="space-y-2.5 leading-relaxed text-justify text-[11px] text-slate-800">
            <p>
                D./Dña. <strong class="uppercase text-black">{{ $tramite->buyer_nombre }}</strong> con DNI/NIE 
                <strong class="font-mono text-black">{{ $tramite->buyer_dni ?: ($tramite->buyer_fa ?: 'No aportado') }}</strong>, 
                que declara tener poder suficiente para actuar en su propio nombre y en concepto de <strong>MANDANTE</strong>, 
                con domicilio a efectos de notificaciones en <strong class="uppercase text-black">{{ $tramite->buyer_direccion ?: 'ROTA (CÁDIZ)' }}</strong>, 
                C.P. <strong class="text-black">{{ $tramite->buyer_cp ?: '11520' }}</strong> de ROTA (CÁDIZ), dice y otorga:
            </p>

            <p>
                Que por el presente documento confiere, con carácter específico, <strong>MANDATO DE REPRESENTACIÓN</strong> a favor de, el/los Gestor/es Administrativo/s en ejercicio, 
                D./Dña. <strong class="text-black">FERNANDO SÁNCHEZ DE NIEVA BALLESTA</strong>, con DNI <strong class="font-mono text-black">75796515S</strong>, 
                número de colegiado <strong class="font-mono text-black">1271</strong>, perteneciente al Ilustre Colegio Oficial de Gestores Administrativos de 
                <strong class="text-black">SEVILLA</strong>, y al despacho profesional con domicilio en <strong class="text-black">CALLE AURORA Nº 11, C.P. 11500, EL PUERTO DE SANTA MARÍA (CÁDIZ)</strong>, 
                en concepto de <strong>MANDATARIO/S</strong>, para su actuación ante todos los órganos y entidades de la Administración del Estado, Autonómica, Provincial y Local que resulten competentes, y específicamente ante la Dirección General de Tráfico del Ministerio del Interior del Gobierno de España, para que promueva, solicite y realice todos los trámites necesarios con el siguiente <strong>ASUNTO</strong>:
            </p>

            <div class="py-1.5 px-3 bg-slate-50 border border-slate-300 rounded font-bold text-center text-xs text-black uppercase">
                Transferencia de vehículo matrícula: <span class="font-mono">{{ $tramite->matricula }}</span>
                @if($tramite->vin) — Bastidor: <span class="font-mono">{{ $tramite->vin }}</span>@endif
            </div>

            <div class="space-y-1.5 text-[8.5px] text-slate-600 leading-normal border-t border-b border-slate-200 py-2">
                <p>
                    El presente mandato, que se regirá por los artículos 1709 a 1739 del Código Civil, se confiere al amparo del artículo 5 de la Ley 39/2015, de 1 de octubre, del Procedimiento Administrativo Común de las Administraciones Públicas, y del artículo 1 del Estatuto Orgánico de la Profesión de Gestor Administrativo, aprobado por Decreto 424/1963.
                </p>
                <p>
                    El mandante autoriza al mandatario/s para que nombre/n sustituto/s, en caso de necesidad justificada, a favor de un/os Gestor/es Administrativo/s colegiado/s ejerciente/s. El presente mandato mantendrá su vigencia mientras no sea expresamente revocado por el mandante y comunicado fehacientemente su revocación al mandatario/s, pudiendo ser su revocación parcial sobre alguno de ellos, quedando en todos los términos vigente sobre aquel/aquellos mandatario/s a quién/es no se le haya comunicado la revocación. El mandatario/s a los que se les revoque el presente mandato quedan obligados a devolver el mismo al Mandante en el mismo momento en el que se le comunique la revocación. En caso de fallecimiento, jubilación, o cese de negocio del mandatario/s, o cualquier otra causa que impida la terminación del mandato, el mandante autoriza de forma expresa que el trámite encomendado sea finalizado por el gestor/es administrativo/s que le/s sustituya/n oficialmente.
                </p>
                <p>
                    El mandante declara bajo su responsabilidad de conformidad con el artículo 69 de la Ley 39/2015, de 1 de octubre, del Procedimiento Administrativo Común de las Administraciones Públicas, que cumple con los requisitos establecidos en la normativa vigente para obtener el reconocimiento de un derecho o facultad o para su ejercicio, que dispone de la documentación que así lo acredita, que es auténtica y su contenido enteramente correcto, y que entrega al gestor Administrativo, el cual se responsabiliza de su custodia, se compromete a ponerla a disposición de la Administración cuando le sea requerida, y a mantener el cumplimiento de las anteriores obligaciones durante el período de tiempo inherente al trámite conferido.
                </p>
                <p>
                    El mandante declara, que conoce y consiente que los datos que suministra pueden incorporarse a ficheros automatizados de los que serán responsables el Gestor/es Administrativo/s al que se le otorga el mandato, el Colegio Oficial de Gestores Administrativos citado, y el Consejo General de Colegios de Gestores Administrativos de España, con el único objeto y plazo de posibilitar la prestación de los servicios profesionales objeto del presente mandato y el cumplimiento por estos de las obligaciones derivadas del trámite encomendado. No obstante lo anterior, el mandatario se reserva el derecho de custodia y conservación de los datos personales recabados con fines de cumplimiento de obligaciones legales exigidas por la normativa tributaria, laboral, civil o mercantil, así como para la atención o emprendimiento de reclamaciones y/o acciones judiciales. El mandante tendrá derecho a la portabilidad de sus datos, a su acceso, rectificación, supresión, limitación, y oposición, dirigiéndose al Consejo General de Colegios de Gestores Administrativos de España en la dirección electrónica consejo@consejogestores.net, o por correo postal a la calle Mayor nº 58, 28013 de Madrid, así como en la dirección postal del Mandatario, donde podrá ampliar la información sobre sus derechos y ejercer los mismos, como a interponer las reclamaciones que estime oportunas ante la Agencia Española de Protección de Datos, en los términos previstos en la Ley Orgánica 3/2018, de 5 de diciembre, de Protección de Datos Personales y garantía de los derechos digitales, y el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo de 27 de abril de 2016. Pudiendo ampliar la información sobre el tratamiento de sus datos en la dirección www.consejogestores.org (política de privacidad).
                </p>
                <p class="font-bold text-slate-800">
                    El mandatario/s acepta/n el mandato conferido y se obliga a cumplirlo de conformidad con las instrucciones del mandante, y declara/n bajo su responsabilidad que los documentos recibidos del mandante han sido verificados en cuanto a la corrección formal de los datos contenidos en los mismos.
                </p>
            </div>
        </div>

        <div class="pt-3 border-t border-slate-200 grid grid-cols-2 gap-8 text-center text-xs">
            <div>
                <p class="text-slate-500 mb-1">En Rota, a <strong>{{ $tramite->fecha_tramite ?: date('d/m/Y') }}</strong></p>
                <strong class="block text-black uppercase">EL MANDANTE (COMPRADOR)</strong>
                <p class="text-[11px] text-slate-600 uppercase mt-0.5">{{ $tramite->buyer_nombre }}</p>
                <div class="h-16 border-b border-dashed border-slate-300 mx-4 mt-1"></div>
            </div>

            <div>
                <p class="text-slate-500 mb-1">En El Puerto de Santa María, a <strong>{{ $tramite->fecha_tramite ?: date('d/m/Y') }}</strong></p>
                <strong class="block text-black uppercase">EL MANDATARIO</strong>
                <p class="text-[11px] text-slate-600 uppercase mt-0.5">FERNANDO SÁNCHEZ DE NIEVA BALLESTA (COL. 1271)</p>
                <div class="h-16 border-b border-dashed border-slate-300 mx-4 mt-1"></div>
            </div>
        </div>

        <div class="border-t border-slate-200 pt-2 flex items-center justify-between text-[9px] text-slate-400">
            <span>Colegio Oficial de Gestores Administrativos • Consejo General</span>
            <span>Tramitado a través de KFM Insurance Rota</span>
        </div>

    </div>

</body>
</html>
