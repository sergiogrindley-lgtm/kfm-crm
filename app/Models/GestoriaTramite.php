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
            'PREPARADO' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'PENDIENTE_DOC', 'DOCUMENTOS' => 'bg-amber-100 text-amber-800 border-amber-300',
            'ENVIADO_GESTORIA', 'OUT_GESTORIA' => 'bg-purple-100 text-purple-800 border-purple-300',
            'PERMISO_RECIBIDO', 'BACK_AVISADO', 'RECOGIDO', 'LIQUIDADO' => 'bg-white text-slate-800 border-slate-300 shadow-xs',
            'CANCELADO' => 'bg-red-100 text-red-800 border-red-300',
            'CESION_POLIZA' => 'bg-slate-200 text-slate-700 border-slate-400',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->estado) {
            'PREPARADO' => 'Preparado',
            'PENDIENTE_DOC', 'DOCUMENTOS' => 'Pendiente de documentación',
            'ENVIADO_GESTORIA', 'OUT_GESTORIA' => 'Enviado a gestoría',
            'PERMISO_RECIBIDO' => 'Permiso de circulación recibido',
            'CANCELADO' => 'Cancelado',
            'CESION_POLIZA' => 'Cesión de póliza',
            'BACK_AVISADO' => 'Permiso de circulación recibido',
            'LIQUIDADO' => 'Permiso de circulación recibido',
            'RECOGIDO' => 'Permiso de circulación recibido',
            default => $this->estado,
        };
    }
}
