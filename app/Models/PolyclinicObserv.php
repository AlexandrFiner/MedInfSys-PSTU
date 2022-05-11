<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolyclinicObserv extends Model
{
    use HasFactory;

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function profile() {
        return $this->belongsTo(ProfileDoctors::class, 'profile_doctors_id', 'id');
    }

    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }

    public function polyclinic() {
        return $this->belongsTo(Polyclinic::class);
    }
}
