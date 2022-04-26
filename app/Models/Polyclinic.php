<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polyclinic extends Model
{
    use HasFactory;

    public function laboratories() {
        return $this->belongsToMany(Laboratory::class, 'polyclinic_laboratories');
    }

    public function hospital() {
        return $this->belongsTo(Hospital::class);
    }

    public function operations() {
        return $this->morphMany('App\Operation', 'operatable');
    }
}
