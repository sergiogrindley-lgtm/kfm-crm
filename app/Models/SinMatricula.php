<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SinMatricula extends Model
{
    protected $table = 'sin_matriculas';

    protected $guarded = [];

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
            'Pendiente' => 'bg-rose-100 text-rose-800 border-rose-300',
            'En Trámite DGT' => 'bg-amber-100 text-amber-800 border-amber-300',
            'Regularizado' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}
