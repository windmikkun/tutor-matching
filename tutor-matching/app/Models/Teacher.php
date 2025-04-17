<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $primaryKey = 'teacher_id';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'profile_image',
        'phone',
        'address',
        'university',
        'faculty',
        'graduation_year',
        'teaching_experience',
        'subject',
        'grade_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function scoutRequests()
    {
        return $this->hasMany(ScoutRequest::class, 'teacher_id', 'teacher_id');
    }

    public function individualContracts()
    {
        return $this->hasMany(IndividualContract::class, 'teacher_id', 'teacher_id');
    }
}
