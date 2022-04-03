<?php

namespace Database\Factories;

use App\Models\Polyclinic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);

        return [
            'name' => $this->faker->name($gender),
            'gender' => $gender,
            'height' => $this->faker->randomFloat(2, 100, 200),
            'weight' => $this->faker->randomFloat(2, 30, 10),
            'birthday' => $this->faker->date('Y-m-d'),
            'polyclinic_id' => Polyclinic::all()->random()->id,
        ];
    }
}
