<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'sede_id',
        'agente',
        'doc_identidad',
        'nombre',
        'apellido',
        'nombre_familiar',
        'dob',
        'telefono_movil',
        'telefono_fijo',
        'email_personal',
        'email_trabajo',
        'direccion_local',
        'direccion_base',
        'observaciones',
    ];

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }
}
