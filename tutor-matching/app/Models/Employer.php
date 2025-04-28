<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    // 講師によってブックマークされている
    public function bookmarkedByTeachers()
    {
        return $this->morphMany(\App\Models\Bookmark::class, 'bookmarkable');
    }

    public function scoutRequests()
    {
        return $this->hasMany(\App\Models\ScoutRequest::class, 'employer_id', 'id');
    }

    use HasFactory;

    // 主キーはデフォルト(id)を利用

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'contact_person',
        'phone',
        'address',
        'nearest_station',
        'recruiting_subject',
        'description',
        'lesson_type', // 個別/集団
        'student_count', // 生徒数
        'student_demographics', // 生徒層
        'hourly_rate', // 時給
        'profile_image', // プロフィール画像（単体）
        'env_img', // 教室等の画像（複数）
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function corporateJobs()
    {
        return $this->hasMany(CorporateJob::class, 'employer_id', 'id');
    }

    public function individualJobs()
    {
        return $this->hasMany(IndividualJob::class, 'employer_id', 'id');
    }


    public function individualContracts()
    {
        return $this->hasMany(IndividualContract::class, 'employer_id', 'id');
    }
}
