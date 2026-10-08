<?php

use App\Models\Client;
use App\Models\Policy;
use App\Models\Sede;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $nex = Sede::where('slug', 'nex')->first();
        if (!$nex) {
            $nex = Sede::create([
                'slug' => 'nex',
                'name' => 'Oficina NEX (Base Naval)',
                'address' => 'NEX (Navy Exchange) - Edificio Comercial NAVSTA Rota',
                'phone' => '+34 956 81 16 16',
                'color' => '#00739c',
            ]);
        }

        if (!Client::where('apellido', 'MILLER')->exists()) {
            $client = Client::create([
                'doc_identidad' => '549218471',
                'nombre' => 'DAVID ALEXANDER',
                'apellido' => 'MILLER',
                'nombre_familiar' => 'Emily Sarah Johnson (Cesionaria)',
                'agente' => 'Chari',
                'dob' => '14/06/1988',
                'telefono_movil' => '671-234567',
                'telefono_fijo' => '',
                'email_trabajo' => 'david.miller@eu.navy.mil',
                'email_personal' => 'david.miller.usn@gmail.com',
                'direccion_local' => 'C/ SAN JUAN DE PUERTO RICO 12 – 11520 ROTA',
                'direccion_base' => 'LG PSC 819 BOX 4120 - 11530 ROTA NAVAL',
                'sede_id' => $nex->id,
                'observaciones' => 'Caso demostración: Transferencia POV / Cesión de póliza en Gestoría Bahía & Naval (Oficina NEX - Chari).',
            ]);

            Policy::create([
                'client_id' => $client->id,
                'sede_id' => $nex->id,
                'agente' => 'Chari',
                'numero_poliza' => '1842910',
                'codigo_aseguradora' => '04',
                'aseguradora' => 'Patria Hispana',
                'ramo' => 'Vehiculo',
                'marca' => 'FORD',
                'modelo' => 'FOCUS TITANIUM 1.5 ECOBOOST 5P',
                'matricula' => '5931LKP',
                'vin' => '1FADP5CU3DL298491',
                'prima' => '642,50 €',
                'tipo_facturacion' => 'Annual',
                'balance' => 'Paid Full',
                'liquidacion' => 'Liquidado',
                'fecha_pago' => '15/11/2025',
                'fecha_vencimiento' => '15/11/2026',
                'estado' => 'Activo',
                'observaciones' => 'Vehículo transferido a Emily Sarah Johnson.',
            ]);
        }
    }

    public function down(): void
    {
        $client = Client::where('apellido', 'MILLER')->first();
        if ($client) {
            $client->policies()->delete();
            $client->delete();
        }
    }
};
