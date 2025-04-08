<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CertificateIssued extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $courseName;
    public $certificatePath;
    public $recommendationPath;

    public function __construct($user,$courseName , $certificatePath, $recommendationPath)
    {
        $this->user = $user;
        $this->courseName = $courseName;
        $this->certificatePath = $certificatePath;
        $this->recommendationPath = $recommendationPath;
    }

    public function build()
    {
        return $this->subject('Your Certificate')
                    ->view('email.certificate_issued') // Use a dedicated email view, not the PDF template
                    ->with([
                        'user' => $this->user,
                        'courseName' => $this->courseName,
                    ])
                    ->attach(Storage::path($this->certificatePath), [
                        'as' => 'Certificate.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->attach(Storage::path($this->recommendationPath), [
                        'as' => 'Recommendation_Letter.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
    
}
