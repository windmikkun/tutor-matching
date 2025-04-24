<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employer;

class EmployerFactory extends Factory
{
    protected $model = Employer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'contact_person' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'description' => $this->faker->realText(80),
            // user_idはテストごとに指定することを想定
        ];
    }
}
