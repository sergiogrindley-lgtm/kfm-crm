<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolizaEmbarque extends Model
{
    protected $table = 'polizas_embarque';

    protected $guarded = [];

    protected $casts = [
        'prima_aplicada_renovacion' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->estado) {
            'En Tránsito' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'Llegado a VPC' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            'Peritado' => 'bg-amber-100 text-amber-800 border-amber-300',
            'Reactivado' => 'bg-blue-100 text-blue-800 border-blue-300',
            'Liquidado' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}
