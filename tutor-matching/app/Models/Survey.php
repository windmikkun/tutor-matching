<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $primaryKey = 'survey_id';

    protected $fillable = [
        'employer_id',
        'title',
        'description',
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id', 'id');
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class, 'survey_id', 'survey_id');
    }
}
