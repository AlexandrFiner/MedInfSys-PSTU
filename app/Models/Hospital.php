<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    public function polyclinics() {
        return $this->hasMany(Polyclinic::class);
    }

    public function operations() {
        return $this->morphMany('App\Operation', 'operatable');
    }
}
