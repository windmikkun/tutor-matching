<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateJob extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_id';
    protected $table = 'corporate_jobs';

    protected $fillable = [
        'employer_id',
        'title',
        'description',
        'subjects',
        'target_grades',
        'location',
        'employment_type',
        'salary_type',
        'salary_min',
        'salary_max',
        'benefits',
        'requirements',
        'application_deadline',
        'start_date',
        'status',
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id', 'employer_id');
    }
}
