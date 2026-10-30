import urllib.request
import urllib.parse
import json
import ssl
import time

ctx = ssl.create_default_context()

BASE_URL = "https://app.kfminsurance.com"

endpoints = [
    # Ruta, Método, Data, Esperado, Descripción
    ("/", "GET", None, 200, "Buscador Principal / Dashboard (Home)"),
    ("/clients/create", "GET", None, 200, "Formulario Nuevo Cliente"),
    ("/clients/1", "GET", None, 200, "Ficha de Cliente (#1 Bolton)"),
    ("/clients/1/edit", "GET", None, 200, "Vista Edición de Cliente (#1)"),
    ("/clients/24", "GET", None, 200, "Ficha de Cliente (#24 Conchi - Pruebas)"),
    ("/clients/1/geico/1", "GET", None, 200, "Documento GEICO Military Overseas"),
    ("/clients/1/mandato/1", "GET", None, 200, "Documento Mandato Colegiado DGT"),
    ("/clients/1/carta-verde/1", "GET", None, 200, "Documento Carta Verde (CIS Green Card)"),
    ("/clients/1/cesion/1", "GET", None, 200, "Documento Hoja de Cesión Oficial"),
    ("/gestoria-demo", "GET", None, 200, "Simulador Gestoría Bahía & Naval"),
    ("/operations/gestoria", "GET", None, 200, "Módulo Gestoría DGT & Transferencias"),
    ("/operations/extornos", "GET", None, 200, "Módulo Extornos PCS"),
    ("/operations/sin-matricula", "GET", None, 200, "Módulo Placas Provisionales / Sin Matrícula"),
    ("/operations/embarque", "GET", None, 200, "Módulo Pólizas de Embarque POV"),
    ("/operations/siniestros", "GET", None, 200, "Módulo Siniestros & Multas ARAG"),
    ("/operations/pagos", "GET", None, 200, "Módulo Pagos Fraccionados"),
    ("/api/decode-vin?vin=1FADP5CU3DL298491", "GET", None, 200, "API VIN Decoder (NHTSA)"),
    ("/api/calculate-itp?ano=2018&valor_fiscal=15000", "GET", None, 200, "API Calculadora Tasas e ITP"),
    ("/api/calculate-pcs-refund", "POST", {"prima": "642.50", "dias_consumidos": "142"}, 200, "API Cálculo Reembolso Prima PCS"),
    ("/api/translate", "POST", {"text": "Motor vehicle policy", "target": "es"}, 200, "API Traductor Bilingüe"),
    ("/api/check-duplicate-client?ssn=25252525", "GET", None, 200, "API Detección Duplicados en Tiempo Real"),
    ("/ip-check", "GET", None, 200, "Endpoint Diagnóstico IP & Cabeceras"),
]

print("========================================================================")
print("🌐 AUDITORÍA EN VIVO DE PRODUCCIÓN (https://app.kfminsurance.com)")
print("========================================================================")

total = len(endpoints)
passed = 0
failed = []

for path, method, data, expectedCode, desc in endpoints:
    url = BASE_URL + path
    headers = {"User-Agent": "KFM-Audit-Bot/2.0"}
    reqData = None
    if method == "POST" and data:
        encoded = urllib.parse.urlencode(data).encode("utf-8")
        reqData = encoded
        headers["Content-Type"] = "application/x-www-form-urlencoded"

    req = urllib.request.Request(url, data=reqData, headers=headers, method=method)
    start = time.time()
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
            code = resp.getcode()
            elapsed = round((time.time() - start) * 1000, 1)
            content = resp.read().decode("utf-8", errors="ignore")
            
            # Validaciones semánticas específicas
            isSemanticOk = True
            extraInfo = ""
            if "api/calculate-pcs-refund" in path:
                parsed = json.loads(content)
                if parsed.get("reembolso_estimado", 0) <= 0:
                    isSemanticOk = False
                    extraInfo = "Reembolso calculado <= 0"
                else:
                    extraInfo = f"Reembolso: {parsed.get('formato_moneda')}"
            elif "api/decode-vin" in path:
                parsed = json.loads(content)
                extraInfo = f"Marca: {parsed.get('make', 'N/A')}"
            elif "api/calculate-itp" in path:
                parsed = json.loads(content)
                extraInfo = f"Cuota ITP: {parsed.get('cuota_itp', 'N/A')} €"

            if code == expectedCode and isSemanticOk:
                passed += 1
                print(f"  [PASS] ({code}) {desc} [{elapsed}ms] {extraInfo}")
            else:
                failed.append(f"{desc}: HTTP {code} - {extraInfo}")
                print(f"  [FAIL] ({code}) {desc} [{elapsed}ms] - {extraInfo}")
    except Exception as e:
        elapsed = round((time.time() - start) * 1000, 1)
        failed.append(f"{desc}: Excepción {str(e)}")
        print(f"  [FAIL] {desc} [{elapsed}ms] - Error: {e}")

print("========================================================================")
print(f"📊 RESUMEN EN PRODUCCIÓN: {passed}/{total} endpoints OK ({round((passed/total)*100, 1)}%)")
print("========================================================================")

if failed:
    print("\n⚠️ ENDPOINTS CON INCIDENCIAS:")
    for f in failed:
        print(f" - {f}")
    exit(1)
else:
    print("\n✨ ¡TODOS LOS ENDPOINTS DE PRODUCCIÓN RESPONDEN 200 OK Y SIN ERRORES! ✨")
    exit(0)
