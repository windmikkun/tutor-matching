<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualContract extends Model
{
    use HasFactory;

    protected $primaryKey = 'contract_id';

    protected $fillable = [
        'teacher_id',
        'employer_id',
        'job_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id', 'id');
    }

    public function individualJob()
    {
        return $this->belongsTo(IndividualJob::class, 'job_id', 'id');
    }
}
