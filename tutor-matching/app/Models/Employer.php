<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;

    protected $primaryKey = 'employer_id';

    protected $fillable = [
        'user_id',
        'name',
        'contact_person',
        'phone',
        'address',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function corporateJobs()
    {
        return $this->hasMany(CorporateJob::class, 'employer_id', 'employer_id');
    }

    public function individualJobs()
    {
        return $this->hasMany(IndividualJob::class, 'employer_id', 'employer_id');
    }

    public function scoutRequests()
    {
        return $this->hasMany(ScoutRequest::class, 'employer_id', 'employer_id');
    }

    public function individualContracts()
    {
        return $this->hasMany(IndividualContract::class, 'employer_id', 'employer_id');
    }
}
