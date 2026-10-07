<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sede extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'color',
    ];

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }
}
