<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    // 主キーはデフォルト(id)を利用

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'profile_image',
        'phone',
        'address',
        'nearest_station',
        'university',
        'faculty',
        'graduation_year',
        'teaching_experience',
        'subject',
        'grade_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scoutRequests()
    {
        return $this->hasMany(ScoutRequest::class, 'teacher_id', 'id');
    }

    public function individualContracts()
    {
        return $this->hasMany(IndividualContract::class, 'teacher_id', 'id');
    }

    // 雇用者によってブックマークされている
    public function bookmarkedByEmployers()
    {
        return $this->morphMany(\App\Models\Bookmark::class, 'bookmarkable');
    }
}
