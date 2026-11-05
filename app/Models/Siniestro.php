<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

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
            'Parte enviado' => 'bg-sky-100 text-sky-800 border-sky-300',
            'Declaracion de Inexistencia' => 'bg-purple-100 text-purple-800 border-purple-300',
            'Peritacion', 'En Peritación' => 'bg-amber-100 text-amber-800 border-amber-300',
            'Cerrado' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }

    /**
     * Días transcurridos desde que se avisó al cliente.
     */
    public function getDiasDesdeAvisoAttribute(): ?int
    {
        if (empty($this->fecha_cliente_avisado)) {
            return null;
        }

        $str = trim($this->fecha_cliente_avisado);
        $dt = null;

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $str, $m)) {
            $dt = Carbon::createFromDate((int)$m[3], (int)$m[2], (int)$m[1])->startOfDay();
        } else {
            try {
                $dt = Carbon::parse($str)->startOfDay();
            } catch (\Throwable $e) {
                return null;
            }
        }

        if (!$dt) {
            return null;
        }

        return (int) $dt->diffInDays(Carbon::now()->startOfDay(), false);
    }

    /**
     * Regla de negocio solicitada por Conchi:
     * Si tenemos reclamación recibida, cliente avisado y el estado es 'Abierto',
     * si han pasado 5 o más días desde la fecha de aviso al cliente, activar aviso de reaviso.
     */
    public function getRequiereReavisoAttribute(): bool
    {
        if ($this->tipo !== 'Siniestro') {
            return false;
        }

        if ($this->estado_siniestro !== 'Abierto') {
            return false;
        }

        if (empty($this->fecha_reclamacion_recibida) || empty($this->fecha_cliente_avisado)) {
            return false;
        }

        $dias = $this->dias_desde_aviso;
        return $dias !== null && $dias >= 5;
    }
}
