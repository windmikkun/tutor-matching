<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoutRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'request_id';

    protected $fillable = [
        'employer_id',
        'teacher_id',
        'message',
        'status',
        'expires_at',
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id', 'employer_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'teacher_id');
    }
}
