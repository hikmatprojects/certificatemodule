<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function examResults()
    {
        return $this->hasMany(Exam_Result::class);
    }



    // Define the relationship with Exam through ExamResult
   

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    
    public function enrollements()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Define the relationship with Exam through ExamResult
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'exam_results')
                    ->withPivot('score', 'passed', 'submited_at');
    }

   


    // Define the relationship with Exam through ExamResult
    public function exams()
    {
        return $this->belongsToMany(Exam::class)
                    ->withPivot('score', 'passed', 'submited_at');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
