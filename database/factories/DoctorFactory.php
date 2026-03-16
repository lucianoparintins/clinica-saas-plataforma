<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'crm' => $this->faker->unique()->bothify('CRM/?? ######'),
            'specialty' => $this->faker->randomElement([
                'Cardiologia', 'Pediatria', 'Dermatologia', 'Ortopedia', 'Ginecologia', 'Neurologia', 'Oftalmologia', 'Psiquiatria'
            ]),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
        ];
    }
}
