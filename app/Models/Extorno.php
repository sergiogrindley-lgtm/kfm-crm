<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Extorno extends Model
{
    protected $table = 'extornos';

    protected $guarded = [];

    protected $casts = [
        'stateside_geico_form' => 'boolean',
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
        return match ($this->status) {
            'Pendiente' => 'bg-amber-100 text-amber-800 border-amber-300',
            'Tramitado' => 'bg-sky-100 text-sky-800 border-sky-300',
            'Reembolsado' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'Cancelado' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}
