<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employer;

class EmployerSeeder extends Seeder
{
    public function run(): void
    {
        // 固定の求人側データを数件作成
        $sampleEmployers = [
    [
        'first_name' => '未来教育塾', // 法人名をfirst_nameに
        'last_name' => '',
        'contact_person' => '佐々木 一郎',
        'phone' => '03-1234-5678',
        'address' => '東京都千代田区丸の内1-1-1',
        'description' => '生徒一人ひとりの可能性を伸ばすことを目指す学習塾です。最新のICT教材も導入しています。',
        'email' => 'employer1@example.com',
    ],
    [
        'first_name' => 'グローバル進学館',
        'last_name' => '',
        'contact_person' => '田中 美咲',
        'phone' => '06-9876-5432',
        'address' => '大阪府大阪市北区梅田2-2-2',
        'description' => '海外進学や英語教育に強みを持つ進学塾。多様なバックグラウンドの講師が在籍。',
        'email' => 'employer2@example.com',
    ],
    [
        'first_name' => '個別指導学院エース',
        'last_name' => '',
        'contact_person' => '山本 剛',
        'phone' => '052-222-3333',
        'address' => '愛知県名古屋市中区栄3-3-3',
        'description' => '小・中・高すべての学年に対応した個別指導塾です。アットホームな雰囲気が自慢。',
        'email' => 'employer3@example.com',
    ],
];
        foreach ($sampleEmployers as $data) {
            $user = \App\Models\User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'user_type' => 'corporate_employer',
                    'first_name' => $data['contact_person'],
                    'last_name' => '',
                ]
            );
            \App\Models\Employer::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'contact_person' => $data['contact_person'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'description' => $data['description'],
                ]
            );
        }
        // 残りはfakerで
        \App\Models\User::factory()->count(7)->create()->each(function ($user) {
            if (!$user->employer) {
                \App\Models\Employer::factory()->create([
                    'user_id' => $user->id,
                ]);
            }
        });
    }
}
