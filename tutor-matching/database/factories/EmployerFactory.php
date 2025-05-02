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
            'first_name' => $this->faker->company(),
            'last_name' => '',
            'contact_person' => $this->faker->name(),
                        'description' => $this->faker->realText(80),
            // user_idはテストごとに指定することを想定
        ];
    }
}
