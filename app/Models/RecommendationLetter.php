<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RecommendationLetter extends Model
{
    protected $fillable = [
        'certificate_id',
        'letter_number',
        'content',
        'issue_date'
    ];

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }

    public function user()
    {
        return $this->through('certificate')->has('user');
    }
}