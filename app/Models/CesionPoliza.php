<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CesionPoliza extends Model
{
    protected $table = 'cesiones_polizas';

    protected $fillable = [
        'client_id',
        'policy_id',
        'sede_id',
        'agente',
        'numero_poliza',
        'cartera',
        'cedente_nombre',
        'cedente_dni',
        'cedente_telefono',
        'cesionario_nombre',
        'cesionario_ssn',
        'cesionario_dob',
        'cesionario_direccion',
        'cesionario_telefono',
        'cesionario_email',
        'matricula',
        'marca',
        'modelo',
        'vin',
        'tiene_suplemento',
        'suplemento_recibo',
        'suplemento_cantidad',
        'fecha_cesion',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'tiene_suplemento' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->estado) {
            'Activa' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'Pendiente de firma' => 'bg-amber-100 text-amber-800 border-amber-300',
            'Finalizada' => 'bg-slate-100 text-slate-700 border-slate-300',
            'Anulada' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-700 border-slate-300',
        };
    }
}
