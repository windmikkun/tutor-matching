<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ScoutRequest;

class ScoutRequestFactory extends Factory
{
    protected $model = ScoutRequest::class;

    public function definition(): array
    {
        return [
            // employer_id, teacher_id, messageはテストごとに指定することを想定
            'message' => $this->faker->realText(30),
            'status' => 'pending',
        ];
    }
}
