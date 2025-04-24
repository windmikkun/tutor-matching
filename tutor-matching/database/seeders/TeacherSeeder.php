<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::factory()->count(10)->create()->each(function ($user) {
            \App\Models\Teacher::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
