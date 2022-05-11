<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentRoom extends Model
{
    use HasFactory;

    protected $appends = [
        'isFreeRoom',
    ];

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function getOccupiedBedsAttribute() {
        return HospitalAppointment::where('department_room_id', $this->id)->where('status', 'process')->count();
    }

    public function isFreeRoom() {
        return !$this->getOccupiedBedsAttribute();
    }
}
