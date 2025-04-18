<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualJob extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_id';
    protected $table = 'individual_jobs';

    protected $fillable = [
        'employer_id',
        'title',
        'description',
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id', 'id');
    }

    public function individualContracts()
    {
        return $this->hasMany(IndividualContract::class, 'job_id', 'job_id');
    }
}
