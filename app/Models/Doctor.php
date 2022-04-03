<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    public function profile() {
        return $this->belongsTo(ProfileDoctors::class, 'profile_doctors_id', 'id');
    }

    public function hospitals() {
        return $this->belongsToMany(Hospital::class, 'doctor_hospitals');
    }

    public function polyclinics() {
        return $this->belongsToMany(Polyclinic::class, 'doctor_polyclinics');
    }
}
