<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siniestro extends Model
{
    protected $table = 'siniestros';

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
        if ($this->tipo === 'Multa') {
            return match ($this->estado_multa) {
                'En Trámite ARAG' => 'bg-amber-100 text-amber-800 border-amber-300',
                'Estimada' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'Pagada' => 'bg-blue-100 text-blue-800 border-blue-300',
                'Archivada' => 'bg-slate-100 text-slate-800 border-slate-300',
                default => 'bg-slate-100 text-slate-800 border-slate-300',
            };
        }

        return match ($this->estado_siniestro) {
            'Abierto' => 'bg-rose-100 text-rose-800 border-rose-300',
            'En Peritación' => 'bg-amber-100 text-amber-800 border-amber-300',
            'Indemnizado' => 'bg-blue-100 text-blue-800 border-blue-300',
            'Cerrado' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}
