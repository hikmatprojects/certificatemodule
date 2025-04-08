<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecommendationLetterMail;


class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'certificate_number',
        'certificate_url', 
        'recommendation_letter_url', 
        'status',
        'issue_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

 

    // protected static function booted()
    // {
    //     static::created(function ($certificate) {
    //         // Generate the recommendation letter automatically
    //         $certificate->generateRecommendationLetter();
    //     });
    // }

    // public function generateRecommendationLetter()
    // {
    //     $student = $this->student;
    //     $course = $this->course;

    //     // Define the recommendation letter data
    //     $data = [
    //         'student_name' => $student->name,
    //         'course_title' => $course->title,
    //         'issue_date' => $this->issue_date,
    //         'tutor_name' => $course->tutor->name
    //     ];

    //     // Generate the PDF recommendation letter
    //     $pdf = Pdf::loadView('pdf.recommendation_letter', $data);

    //     // Save the generated PDF in storage
    //     $fileName = 'recommendations/recommendation_' . $this->id . '.pdf';
    //     Storage::put('public/' . $fileName, $pdf->output());

    //     // Save the file path in the database (optional)
    //     $this->update(['recommendation_letter' => $fileName]);

    //      // Send Email
    //     Mail::to($student->email)->send(new RecommendationLetterMail($student, $filePath));
    // }

}
