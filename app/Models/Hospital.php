<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Hospital extends Model
{
    use HasFactory;

    public function polyclinics(): HasMany
    {
        return $this->hasMany(Polyclinic::class);
    }

    public function operations(): MorphMany
    {
        return $this->morphMany('App\Operation', 'operable');
    }
}
