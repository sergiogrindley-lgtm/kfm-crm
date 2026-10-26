<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandato Oficial DGT — Comprador — {{ $tramite->matricula }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm 14mm 8mm 14mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
            background-color: #f1f5f9;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        @media print {
            html, body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                height: auto !important;
            }
            .no-print { display: none !important; }
            .print-sheet {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                min-height: auto !important;
                height: auto !important;
                page-break-after: avoid !important;
                page-break-inside: avoid !important;
            }
        }
        .print-sheet {
            width: 210mm;
            max-width: 100%;
            padding: 10mm 15mm;
            margin: 0 auto;
            background: white;
            box-sizing: border-box;
        }
        .field-val {
            font-weight: 600;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            border-bottom: 1px solid #475569;
            padding: 0 2px;
            text-transform: uppercase;
        }
        .field-line {
            color: #64748b;
            letter-spacing: -0.5px;
        }
    </style>
</head>
<body class="py-4 text-[10px]">

    <!-- Action Bar (No Print) -->
    <div class="max-w-[210mm] mx-auto mb-4 flex items-center justify-between no-print bg-slate-900 text-white px-5 py-2.5 rounded-xl shadow-lg">
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
    <div class="print-sheet shadow-xl border border-slate-200 text-black text-[9px]">
        
        <!-- Header Oficial Colegiado (Consejo General) -->
        <div class="text-center mb-2.5">
            <img src="{{ asset('img/logo-consejo-gestores.png') }}" alt="Consejo General de Colegios de Gestores Administrativos de España" class="h-12 w-auto mx-auto object-contain">
        </div>

        <div class="space-y-2 leading-snug text-justify">
            <p>
                D./ña. <span class="field-val">{{ $tramite->buyer_nombre }}</span> con DNI <span class="field-val">{{ $tramite->buyer_dni ?: ($tramite->buyer_fa ?: '___________') }}</span>
                y D./ña. <span class="field-line">_________________________________</span> con DNI <span class="field-line">________________</span>, que declara/declaran tener
                poder suficiente para actuar en su propio nombre y/o en representación de <span class="field-line">________________________________</span> con DNI/CIF nº
                <span class="field-line">____________________</span> y domicilio a efectos de notificaciones en <span class="field-val">ROTA (CADIZ)</span> calle
                <span class="field-val">{{ $tramite->buyer_direccion ?: 'LU BASE NAVAL DE ROTA' }}</span> nº <span class="field-val">999</span> C.P. <span class="field-val">{{ $tramite->buyer_cp ?: '11520' }}</span>, en concepto de <strong>MANDANTE</strong>, dice y otorga:
            </p>

            <p>
                Que por el presente documento confiere, con <strong>carácter específico, MANDATO CON REPRESENTACIÓN</strong> a favor de, el/los Gestor/es Administrativo/s en ejercicio, D./ña <span class="field-val">FERNANDO SANCHEZ DE NIEVA BALLESTA</span>, con DNI <span class="field-val">75796515S</span>, número de
                colegiado <span class="field-val">1271</span>, y D./ña <span class="field-line">_________________________________</span>, con DNI <span class="field-line">_________________</span>, número de
                colegiado <span class="field-line">____________</span> y D./ña <span class="field-line">____________________________________________________________________</span>,
                todos ellos pertenecientes al Colegio Oficial de Gestores Administrativos de <span class="field-val">SEVILLA</span>, y al despacho
                profesional <span class="field-line">_____________________________________</span> con domicilio en <span class="field-val">PTO STA MARIA</span> calle
                <span class="field-val">CALLE AURORA</span> nº <span class="field-val">11</span> C.P. <span class="field-val">11500</span>, en concepto de <strong>MANDATARIO/S</strong>, para su
                actuación ante todos los órganos y entidades de la Administración del Estado, Autonómica, Provincial y Local que resulten competentes, y
                específicamente ante la Dirección General de Tráfico del Ministerio del Interior del Gobierno de España, para que promueva, solicite y realice todos
                los trámites necesarios con el siguiente <strong>ASUNTO</strong>:
            </p>

            <div class="space-y-0.5">
                <p>• <span class="field-val">SOLICITUD DE TRANSMISION DE VEHICULO ({{ $tramite->matricula }})</span></p>
                <p>• <span class="field-line">_________________________________________________________________________________________________________</span></p>
            </div>

            <div class="space-y-1.5 text-[7.5px] leading-tight pt-0.5">
                <p>
                    El presente mandato, que se regirá por los artículos 1709 a 1739 del Código Civil, se confiere al amparo del artículo 5 de la Ley 39/2015, de 1 de
                    octubre, del Procedimiento Administrativo Común de las Administraciones Públicas, y del artículo 1 del Estatuto Orgánico de la Profesión de Gestor
                    Administrativo, aprobado por Decreto 424/1963.
                </p>
                <p>
                    El mandante autoriza al mandatario/s para que nombre/n sustituto/s, en caso de necesidad justificada, a favor de un/os Gestor/es Administrativo/s
                    colegiado/s ejerciente/s. El presente mandato mantendrá su vigencia mientras no sea expresamente revocado por el mandante y comunicado
                    fehacientemente su revocación al mandatario/s, pudiendo ser su revocación parcial sobre alguno de ellos, quedando en todos los términos vigente
                    sobre aquel/aquellos mandatario/s a quién/es no se le haya comunicado la revocación. El mandatario/s a los que se les revoque el presente mandato
                    quedan obligados a devolver el mismo al Mandante en el mismo momento en el que se le comunique la revocación. En caso de fallecimiento,
                    jubilación, o cese de negocio del mandatario/s, o cualquier otra causa que impida la terminación del mandato, el mandante autoriza de forma expresa
                    que el trámite encomendado sea finalizado por el gestor/es administrativo/s que le/s sustituya/n oficialmente.
                </p>
                <p>
                    El mandante declara bajo su responsabilidad de conformidad con el artículo 69 de la Ley 39/2015, de 1 de octubre, del Procedimiento Administrativo
                    Común de las Administraciones Públicas, que cumple con los requisitos establecidos en la normativa vigente para obtener el reconocimiento de un
                    derecho o facultad o para su ejercicio, que dispone de la documentación que así lo acredita, que es auténtica y su contenido enteramente correcto,
                    y que entrega al gestor Administrativo, el cual se responsabiliza de su custodia, se compromete a ponerla a disposición de la Administración cuando
                    le sea requerida, y a mantener el cumplimiento de las anteriores obligaciones durante el período de tiempo inherente al trámite conferido.
                </p>
                <p>
                    El mandante declara, que conoce y consiente que los datos que suministra pueden incorporarse a ficheros automatizados de los que serán
                    responsables el Gestor/es Administrativo/s al que se le otorga el mandato, el Colegio Oficial de Gestores Administrativos citado, y el Consejo General
                    de Colegios de Gestores Administrativos de España, con el único objeto y plazo de posibilitar la prestación de los servicios profesionales objeto del
                    presente mandato y el cumplimiento por estos de las obligaciones derivadas del trámite encomendado. No obstante, lo anterior, el mandatario se
                    reserva el derecho de custodia y conservación de los datos personales recabados con fines de cumplimiento de obligaciones legales exigidas por la
                    normativa tributaria, laboral, civil o mercantil, así como para la atención o emprendimiento de reclamaciones y/o acciones judiciales. El mandante
                    tendrá derecho a la portabilidad de sus datos, a su acceso, rectificación, supresión, limitación, y oposición, dirigiéndose al Consejo General de
                    Colegios de Gestores Administrativos de España en la dirección electrónica <span class="underline">consejo@consejogestores.net</span>, o por correo postal a la calle Mayor nº
                    58, 28013 de Madrid, así como en la dirección postal del Mandatario, donde podrá ampliar la información sobre sus derechos y ejercer los mismos,
                    como a interponer las reclamaciones que estime oportunas ante la Agencia Española de Protección de Datos, o su equivalente en su país de
                    residencia como Autoridad de Control, en los términos previstos en la Ley Orgánica 3/2018, de 5 de diciembre, de Protección de Datos Personales
                    y garantía de los derechos digitales, y el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo de 27 de abril de 2016. Pudiendo ampliar
                    la información sobre el tratamiento de sus datos en la dirección <span class="underline">www.consejogestores.org</span> (política de privacidad).
                </p>
            </div>

            <!-- Firma Mandante -->
            <div class="pt-1 text-center">
                <p class="text-[8.5px]">
                    En <span class="field-val">ROTA</span> a <span class="field-val">{{ $dia }}</span> de <span class="field-val">{{ $mesNombre }}</span> de <span class="field-val">{{ $ano }}</span>
                </p>
                <div class="h-6"></div>
                <p class="font-bold text-[8.5px] uppercase tracking-wider">EL MANDANTE</p>
            </div>

            <!-- Cláusula Aceptación Gestor -->
            <p class="text-[7.5px] leading-tight text-justify pt-0.5">
                El mandatario/s acepta/n el mandato conferido y se obliga a cumplirlo de conformidad con las instrucciones del mandante, y declara/n bajo su
                responsabilidad que los documentos recibidos del mandante han sido verificados en cuanto a la corrección formal de los datos contenidos en los
                mismos.
            </p>

            <!-- Firma Mandatario -->
            <div class="pt-0.5 text-center">
                <p class="text-[8.5px]">
                    En <span class="field-val">ROTA</span> a <span class="field-val">{{ $dia }}</span> de <span class="field-val">{{ $mesNombre }}</span> de <span class="field-val">{{ $ano }}</span>
                </p>
                <div class="h-6"></div>
                <p class="font-bold text-[8.5px] uppercase tracking-wider">EL MANDATARIO/S</p>
            </div>

        </div>

    </div>

</body>
</html>
