<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employer;

class EmployerSeeder extends Seeder
{
    public function run(): void
    {
        // まず employer@gmail.com で1件必ず作成
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'employer@gmail.com'],
            [
                'password' => bcrypt('password'),
                // 必要なら他の初期値も追加
            ]
        );
        \App\Models\Employer::firstOrCreate(
            ['user_id' => $user->id],
            \Database\Factories\EmployerFactory::new()->definition()
        );
        // 残りはfakerで
        \App\Models\User::factory()->count(9)->create()->each(function ($user) {
            if (!$user->employer) {
                \App\Models\Employer::factory()->create([
                    'user_id' => $user->id,
                ]);
            }
        });
    }
}
