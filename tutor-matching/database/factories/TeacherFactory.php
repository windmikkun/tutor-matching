<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Teacher;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'subject' => $this->faker->word(),
            'grade_level' => $this->faker->randomElement(['小学校','中学校','高校','大学','その他']),
            'bio' => $this->faker->randomElement([
                '生徒一人ひとりに寄り添った指導を心がけています。趣味は読書とジョギングです。',
                '分かりやすく丁寧な授業を提供します。理系科目が得意です！',
                '海外留学経験あり。英語と数学の指導に自信があります。',
                '明るく楽しい授業を目指しています。よろしくお願いします！',
                '教育現場で10年以上の経験があります。学習の悩みもご相談ください。'
            ]),
            // user_idはテストごとに指定することを想定
        ];
    }
}
