<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $table = 'exam_results'; // Explicitly set table name

    protected $fillable = [
        'user_id', 
        'exam_id',
        'score',
        'status',
        'submited_at',
          
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


