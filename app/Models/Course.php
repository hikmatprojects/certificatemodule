<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
       
    ];


    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Define the relationship with Exam through ExamResult
    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('status','enrollement_at');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }


}
