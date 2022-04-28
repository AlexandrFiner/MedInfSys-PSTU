<?php

namespace Database\Factories;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'hospital_id' => Hospital::all()->random()->id,
            'name' => $this->faker->randomElement(['Главный корпус', 'Хирургический корпус', 'Родильный корпус', 'Неврологический корупс', 'Детское отделение', 'Гинекологическое отделение', 'Терапевтический корпус', 'Лечебный корпус'])
        ];
    }
}
