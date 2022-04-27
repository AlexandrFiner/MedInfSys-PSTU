<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $appends = [
        'experience',
        'operationsCount'
    ];

    public function profile() {
        return $this->belongsTo(ProfileDoctors::class, 'profile_doctors_id', 'id');
    }

    public function hospitals() {
        return $this->belongsToMany(Hospital::class, 'doctor_hospitals');
    }

    public function polyclinics() {
        return $this->belongsToMany(Polyclinic::class, 'doctor_polyclinics');
    }

    public function operations() {
        return $this->belongsToMany(Operation::class, 'operations');
    }

    public function getExperienceAttribute() {
        return date_create()->diff(new \DateTime($this->date_started_working))->y;
    }

    public function getOperationsCountAttribute() {
        /* костыль */
        return Operation::query()->where('doctor_id', '=', $this->id)->count('*');
    }
}
