<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Policy extends Model
{
    protected $fillable = [
        'client_id',
        'sede_id',
        'agente',
        'numero_poliza',
        'codigo_aseguradora',
        'aseguradora',
        'ramo',
        'marca',
        'modelo',
        'matricula',
        'vin',
        'tipo_vivienda',
        'direccion_riesgo',
        'detalles_cobertura',
        'capital',
        'prima',
        'tipo_facturacion',
        'balance',
        'liquidacion',
        'fecha_pago',
        'fecha_vencimiento',
        'estado',
        'observaciones',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }
}
