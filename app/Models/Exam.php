<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'total_marks',
        'duration',
        'passing_marks',
        'start_time',
        'end_time',
        'is_published'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('score', 'passed', 'submited_at');
    }

    public function examResults()
    {
        return $this->hasMany(Exam_result::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

}
