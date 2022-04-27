<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Polyclinic extends Model
{
    use HasFactory;

    public function laboratories(): BelongsToMany
    {
        return $this->belongsToMany(Laboratory::class, 'polyclinic_laboratories');
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function operations(): MorphMany
    {
        return $this->morphMany('App\Operation', 'operable');
    }
}
