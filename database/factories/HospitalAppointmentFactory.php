<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\DepartmentRoom;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Polyclinic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HospitalAppointment>
 */
class HospitalAppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
//        $patient_id = Patient::all()->random()->id;
//        // $hospital_id = Hospital::all()->random()->id;
//        $doctorHospitals = DB::table('doctor_hospitals')->inRandomOrder()->first();
//        $hospital_id = $doctorHospitals->hospital_id;
//        $doctor_id = $doctorHospitals->doctor_id;
//        $department_id = Department::all()->where('hospital_id', $hospital_id)->random()->id;
        $department_room = DepartmentRoom::all()->random();
        $hospital_id = Department::find($department_room->department_id)->hospital_id;
        $doctor_id = DB::table('doctor_hospitals')->where('hospital_id', $hospital_id)->inRandomOrder()->first()->doctor_id;

        $status = $this->faker->randomElement(['process', 'released']);
        return [
            'patient_id' => Patient::all()->random()->id,
            'hospital_id' => $hospital_id,
            'department_id' => $department_room->department_id,
            'department_room_id' => $department_room->id,
            'doctor_id' => $doctor_id,
            'description' => $this->faker->randomElement(['Экстренная госпитализация', 'Ранение ноги', 'Открытое кровотечение', 'Перелом', 'Обострения хронических болезней', 'Плановая госпитализация']),
            'status' => $status,
            'condition' => $this->faker->randomElement(['satisfactory', 'critical']),
            'temperature' => $this->faker->randomFloat(1, 36.4, 38),
            'date_in' => $this->faker->date(),
            'date_out' => $status == 'released' ? $this->faker->date() : null,
        ];
    }
}
