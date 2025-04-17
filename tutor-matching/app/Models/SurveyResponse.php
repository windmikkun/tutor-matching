<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $primaryKey = 'response_id';

    protected $fillable = [
        'survey_id',
        'user_id',
        'answer',
        'submitted_at',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id', 'survey_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
