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
     * Палаты
     */
    public function rooms() {
        return $this->hasMany(DepartmentRoom::class);
    }

    /*
     * Количество палат
     */
    public function getRoomsCountAttribute() {
        return $this->rooms()->count();
    }

    /*
     * Общее количество коек
     */
    public function getBedsCountAttribute() {
        return $this->rooms()->sum('beds');
    }

    /*
     * Занятые койки
     */
    public function getOccupiedBedsAttribute() {
        return HospitalAppointment::where('department_id', $this->id)->where('status', 'process')->count();
    }

    /*
     * Свободные койки
     */
    public function getFreeBedsAttribute() {
        return $this->getBedsCountAttribute() - $this->getOccupiedBedsAttribute();
    }

    /*
     * Свободные комнаты
     */
    public function getFreeRoomsAttribute() {
        $collection = DepartmentRoom::where('department_id', $this->id)->get();
        $filtered = $collection->filter(function ($model) {
            return $model->isFreeRoom() == true;
        });

        return $filtered->count();
    }
}
