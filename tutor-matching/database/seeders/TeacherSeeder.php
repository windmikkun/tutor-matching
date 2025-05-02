<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        // 固定の講師データを数件作成
        $sampleTeachers = [
            [
                'first_name' => '太郎',
                'last_name' => '山田',
                'subject' => '数学',
                'grade_level' => '高校',
                'bio' => '生徒一人ひとりの理解度に合わせて、丁寧に指導します。趣味は登山と読書です。',
                'email' => 'teacher1@example.com',
                'education' => '東京大学 理学部 卒業',
                'current_school' => '東京大学大学院',
                'trial_lesson' => 'https://www.youtube.com/watch?v=xxxxxxx',
                'self_appeal' => '生徒のやる気を引き出す授業が得意です。',
                'estimated_hourly_rate' => 3000,
                'profile_image' => 'sample/teacher1.jpg',
            ],
            [
                'first_name' => '花子',
                'last_name' => '佐藤',
                'subject' => '英語',
                'grade_level' => '中学校',
                'bio' => '海外経験を活かした英語指導が得意です。楽しく学びましょう！',
                'email' => 'teacher2@example.com',
                'education' => '早稲田大学 教育学部 卒業',
                'current_school' => '早稲田大学大学院',
                'trial_lesson' => 'https://www.youtube.com/watch?v=yyyyyyy',
                'self_appeal' => '英語を楽しく学べる工夫をしています。',
                'estimated_hourly_rate' => 2500,
                'profile_image' => 'sample/teacher2.jpg',
            ],
            [
                'first_name' => '健',
                'last_name' => '鈴木',
                'subject' => '理科',
                'grade_level' => '小学校',
                'bio' => '理科の楽しさを伝えることがモットーです。実験が大好きです。',
                'email' => 'teacher3@example.com',
                'education' => '大阪大学 理学部 卒業',
                'current_school' => '大阪大学大学院',
                'trial_lesson' => 'https://www.youtube.com/watch?v=zzzzzzz',
                'self_appeal' => '実験を通じて理科の面白さを伝えます。',
                'estimated_hourly_rate' => 2000,
                'profile_image' => 'sample/teacher3.jpg',
            ],
        ];
        foreach ($sampleTeachers as $data) {
            $user = \App\Models\User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'user_type' => 'teacher',
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                ]
            );
            \App\Models\Teacher::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'subject' => $data['subject'],
                    'grade_level' => $data['grade_level'],
                    'bio' => $data['bio'],
                    'education' => $data['education'],
                    'current_school' => $data['current_school'],
                    'trial_lesson' => $data['trial_lesson'],
                    'self_appeal' => $data['self_appeal'],
                    'estimated_hourly_rate' => $data['estimated_hourly_rate'],
                    'profile_image' => $data['profile_image'],
                ]
            );
        }
        // 残りはfakerで
        \App\Models\User::factory()->count(7)->create(['user_type' => 'teacher'])->each(function ($user) {
            \App\Models\Teacher::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
