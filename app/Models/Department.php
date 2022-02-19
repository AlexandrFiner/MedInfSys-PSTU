<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public function hospital() {
        return $this->belongsTo(Hospital::class);
    }

    /*
     * Занятые койки
     */
    public function getOccupiedRoomsAttribute() {
        return HospitalAppointment::where('department_id', $this->id)->where('status', 'process')->count();
    }

    /*
     * Свободные койки
     */
    public function getFreeRoomsAttribute() {
        return $this->rooms * $this->beds - $this->occupied_rooms;
    }
}
