<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Polyclinic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operation>
 */
class OperationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $organization_type = rand(0, 1) ? Hospital::class : Polyclinic::class;
        $organization_id = $organization_type == Hospital::class ? Hospital::all()->random()->id : Polyclinic::all()->random()->id;

        return [
            'purpose' => 'Плановое лечение',
            'doctor_id' => Doctor::all()->random()->id,
            'patient_id' => Patient::all()->random()->id,
            'organization_type' => $organization_type,
            'organization_id' => $organization_id,
            'date' => $this->faker->date()
        ];
    }
}
