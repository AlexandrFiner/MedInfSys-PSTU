<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    public function polyclinics() {
        // return $this->belongsToMany(Polyclinic::class, 'polyclinic_id', 'id');

        return $this->belongsToMany(Polyclinic::class, 'hospital_polyclinics');
    }
}
