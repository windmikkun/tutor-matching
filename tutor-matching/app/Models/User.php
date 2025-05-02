<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    // 自分がブックマークした求人や講師
    public function bookmarks()
    {
        return $this->hasMany(\App\Models\Bookmark::class, 'user_id', 'id');
    }

    // 雇用者にブックマークされている
    public function bookmarkedByEmployers()
    {
        return $this->morphMany(\App\Models\Bookmark::class, 'bookmarkable');
    }

    // ユーザー種別判定メソッド
    public function isTeacher() {
        return $this->user_type === 'teacher';
    }
    public function isEmployer() {
        return in_array($this->user_type, ['employer', 'corporate_employer']);
    }


    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'user_type',
        'phone',
        'postal_code',
        'prefecture', // 追加
        'address1',   // 追加
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    /**
     * Get the teacher profile associated with the user.
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    /**
     * Get the employer profile associated with the user.
     */
    public function employer()
    {
        return $this->hasOne(Employer::class, 'user_id');
    }
}
