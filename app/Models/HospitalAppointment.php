<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalAppointment extends Model
{
    use HasFactory;

    public function patient() {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function doctor() {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    public function hospital() {
        return $this->belongsTo(Hospital::class, 'hospital_id', 'id');
    }

    public function department() {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function department_room() {
        return $this->belongsTo(DepartmentRoom::class, 'department_room_id', 'id');
    }
}
