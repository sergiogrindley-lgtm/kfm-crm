@php
    $rawDireccion = trim($tramite->seller_direccion ?: 'IU BASE NAVAL DE ROTA 99');
    $calle = $rawDireccion;
    $numero = '99';
    if (preg_match('/^(.*?)(?:[,\s]+(?:n[ºo°]?|núm(?:ero)?\.?)?\s*(\d+[A-Za-z\/\-]*))$/i', $rawDireccion, $m)) {
        $calle = trim($m[1]);
        $numero = trim($m[2]);
    }
    $municipio = $tramite->seller_municipio ?: 'ROTA (CÁDIZ)';
    $cp = $tramite->seller_cp ?: '11520';
    $nombre = $tramite->seller_nombre ?: 'SIN ESPECIFICAR';
    $dni = $tramite->seller_dni ?: ($tramite->seller_fa ?: '');
    $asunto = 'SOLICITUD DE TRANSMISIÓN DE VEHÍCULO (' . ($tramite->matricula ?: 'SIN MATRÍCULA') . ')';
    $fechaTexto = 'En ROTA a ' . $dia . ' de ' . $mesNombre . ' de ' . $ano;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandato Oficial DGT — Vendedor — {{ $tramite->matricula }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        html, body {
            width: 210mm;
            min-height: 297mm;
            margin: 0;
            padding: 0;
            background: #0f172a;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .no-print {
            max-width: 210mm;
            margin: 16px auto;
            padding: 12px 20px;
            background: #1e293b;
            color: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: system-ui, -apple-system, sans-serif;
            box-shadow: 0 4px 16px rgba(0,0,0,0.3);
        }
        .no-print .title {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .no-print .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22d3ee;
            display: inline-block;
        }
        .no-print .actions {
            display: flex;
            gap: 10px;
        }
        .no-print button {
            cursor: pointer;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 12px;
            font-weight: 700;
            transition: all 0.2s;
        }
        .btn-print {
            background: #0891b2;
            color: white;
        }
        .btn-print:hover {
            background: #06b6d4;
        }
        .btn-close {
            background: #334155;
            color: #cbd5e1;
        }
        .btn-close:hover {
            background: #475569;
            color: white;
        }

        /* Contenedor exacto A4 */
        .page-wrapper {
            position: relative;
            width: 210mm;
            height: 297mm;
            margin: 0 auto 30px auto;
            background: white;
            box-shadow: 0 6px 30px rgba(0,0,0,0.35);
            overflow: hidden;
        }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .page-wrapper {
                margin: 0 !important;
                box-shadow: none !important;
                width: 210mm !important;
                height: 297mm !important;
                page-break-after: avoid !important;
                page-break-before: avoid !important;
            }
        }

        /* Imagen del documento oficial (Consejo General de Gestores) en 300 DPI */
        .bg-template {
            position: absolute;
            top: 0;
            left: 0;
            width: 210mm;
            height: 297mm;
            z-index: 1;
            pointer-events: none;
        }

        /* Capa de campos rellenados directamente sobre los huecos */
        .fields-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 210mm;
            height: 297mm;
            z-index: 2;
        }

        .field {
            position: absolute;
            background: #ffffff;
            color: #000000;
            font-family: Arial, "Arial Narrow", Helvetica, sans-serif;
            font-size: 7.9pt;
            font-weight: bold;
            line-height: 1;
            white-space: nowrap;
            display: flex;
            align-items: center;
            padding: 0.5px 3px 0.5px 1px;
        }
    </style>
</head>
<body>

    <!-- Barra de acciones (No se imprime) -->
    <div class="no-print">
        <div class="title">
            <span class="dot"></span>
            <span>MANDATO OFICIAL DGT — VENDEDOR — {{ $tramite->matricula }}</span>
        </div>
        <div class="actions">
            <button onclick="window.print()" class="btn-print">🖨️ Imprimir Mandato Vendedor</button>
            <button onclick="window.close()" class="btn-close">Cerrar</button>
        </div>
    </div>

    <!-- Hoja A4 Oficial Exacta -->
    <div class="page-wrapper">
        <!-- Plantilla Oficial DGT / Consejo General de Gestores Administrativos -->
        <img class="bg-template" src="{{ asset('img/mandato_oficial_base.png') }}" alt="Plantilla Oficial Mandato DGT">

        <!-- Capa de datos rellenados sobre los huecos exactos del original -->
        <div class="fields-layer">
            <!-- 1. MANDANTE (VENDEDOR) -->
            <!-- D./ña. [NOMBRE] -->
            <div class="field" style="top: 32.2mm; left: 27.5mm; width: 95mm;">{{ strtoupper($nombre) }}</div>
            <!-- con DNI [DNI] -->
            <div class="field" style="top: 32.2mm; left: 147.0mm; width: 42mm;">{{ strtoupper($dni) }}</div>

            <!-- 2. DOMICILIO A EFECTOS DE NOTIFICACIONES -->
            <!-- notificaciones en [MUNICIPIO] -->
            <div class="field" style="top: 47.0mm; left: 103.0mm; width: 60mm;">{{ strtoupper($municipio) }}</div>
            <!-- calle [CALLE] -->
            <div class="field" style="top: 50.8mm; left: 20.2mm; width: 68mm;">{{ strtoupper($calle) }}</div>
            <!-- nº [NUMERO] -->
            @if($numero)
            <div class="field" style="top: 50.8mm; left: 93.5mm; width: 14mm;">{{ $numero }}</div>
            @endif
            <!-- C.P. [CP] -->
            <div class="field" style="top: 50.8mm; left: 117.0mm; width: 30mm;">{{ $cp }}</div>

            <!-- 3. MANDATARIO (GESTOR ADMINISTRATIVO OFICIAL) -->
            <!-- D./ña [GESTOR] -->
            <div class="field" style="top: 61.8mm; left: 67.0mm; width: 62mm;">FERNANDO SANCHEZ DE NIEVA BALLESTA</div>
            <!-- con DNI [DNI GESTOR] -->
            <div class="field" style="top: 61.8mm; left: 144.0mm; width: 28mm;">75796515S</div>
            <!-- número de colegiado [COLEGIADO] -->
            <div class="field" style="top: 65.6mm; left: 31.5mm; width: 18mm;">1271</div>
            <!-- Colegio Oficial de [COLEGIO] -->
            <div class="field" style="top: 72.8mm; left: 132.0mm; width: 32mm;">SEVILLA</div>
            <!-- con domicilio en [DOMICILIO DESPACHO] -->
            <div class="field" style="top: 76.5mm; left: 125.0mm; width: 45mm;">PTO STA MARIA</div>
            <!-- calle [CALLE DESPACHO] -->
            <div class="field" style="top: 80.2mm; left: 20.2mm; width: 65mm;">CALLE AURORA</div>
            <!-- nº [NUMERO DESPACHO] -->
            <div class="field" style="top: 80.2mm; left: 91.0mm; width: 12mm;">11</div>
            <!-- C.P. [CP DESPACHO] -->
            <div class="field" style="top: 80.2mm; left: 118.0mm; width: 30mm;">11500</div>

            <!-- 4. ASUNTO OFICIAL -->
            <div class="field" style="top: 96.5mm; left: 25.5mm; width: 164mm;">{{ $asunto }}</div>

            <!-- 5. FECHAS Y FIRMAS -->
            <!-- Fecha Mandante -->
            <div class="field" style="top: 228.6mm; left: 58.0mm; width: 95mm;">{{ $fechaTexto }}</div>

            <!-- Fecha Mandatario -->
            <div class="field" style="top: 265.0mm; left: 57.0mm; width: 97mm;">{{ $fechaTexto }}</div>
        </div>
    </div>

</body>
</html>
