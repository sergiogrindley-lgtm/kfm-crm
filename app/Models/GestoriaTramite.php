<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GestoriaTramite extends Model
{
    protected $table = 'gestoria_tramites';

    protected $guarded = [];

    protected $casts = [
        'tiene_cesion' => 'boolean',
        'documentos_checklist' => 'array',
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
            'DOCUMENTOS' => 'bg-amber-100 text-amber-800 border-amber-300',
            'OUT_GESTORIA' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'BACK_AVISADO' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            'LIQUIDADO' => 'bg-blue-100 text-blue-800 border-blue-300',
            'RECOGIDO' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->estado) {
            'DOCUMENTOS' => '1. Documentación Recibida',
            'OUT_GESTORIA' => '2. Enviado a Gestoría',
            'BACK_AVISADO' => '3. Devuelto / Cliente Avisado',
            'LIQUIDADO' => '4. Liquidado / Pagado',
            'RECOGIDO' => '5. Recogido por Cliente',
            default => $this->estado,
        };
    }
}
