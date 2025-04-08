<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Course;
use App\Models\Certificate;
use App\Models\Project;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use App\Mail\CertificateIssued;

class CertificateController extends Controller
{
    public function issueCertificate(Request $request)
    {
        $userId = auth()->id();
        $courseId = $request->course_id;
    
        // Check if the user has already received a certificate
        if (Certificate::where('user_id', $userId)->where('course_id', $courseId)->exists()) {
            return response()->json(['message' => 'Certificate already issued.'], 400);
        }
    
        // Check if the project and exam are passed
        $project = Project::where('user_id', $userId)
                          ->where('course_id', $courseId)
                          ->where('status', 'approved')
                          ->first();
        $exam = ExamResult::where('user_id', $userId)->where('status', 'passed')->exists();
    
        if (!$project || !$exam) {
            return response()->json(['message' => 'User has not passed the required criteria.'], 400);
        }
    
        $user = auth()->user();
        $course = Course::findOrFail($courseId);
    
        // ✅ Generate Unique Certificate Number
        $certificateNumber = 'CERT-' . strtoupper(uniqid());

        
    
        // ✅ Generate Certificate PDF . // Comact used to Pass data to the view
        $certificatePath = "certificates/{$user->id}_{$course->id}.pdf";
        $certificatePdf = Pdf::loadView('pdf.certificate', compact('user', 'course', 'certificateNumber'))->output();
        Storage::put($certificatePath, $certificatePdf);
    
        // ✅ Generate Recommendation Letter PDF
        $recommendationPath = "recommendations/{$user->id}_{$course->id}.pdf";
        $recommendationPdf = Pdf::loadView('pdf.recommendation', compact('user', 'course', 'certificateNumber'))->output();
        Storage::put($recommendationPath, $recommendationPdf);
        // if (Storage::exists($recommendationPath)) {
        //     // Send the file to the browser for download
        //    // return Storage::download($recommendationPath);
        // } else {
        //     // Return a 404 if the file doesn't exist
        //     abort(404, 'Certificate not found');
        // $certificate = Storage::get($recommendationPath);

        //return response()->json([$recommendationPath]);
        
        // ✅ Create Certificate Record After PDFs are Generated
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_number' => $certificateNumber,
            'certificate_url' => Storage::url($certificatePath),
            'recommendation_letter_url' => Storage::url($recommendationPath),
            'status' => 'issued',
        ]);
     

        // ✅ Send Email with Certificate & Recommendation Letter
        Mail::to($user->email)->queue(new CertificateIssued($user, $certificatePath, $recommendationPath, $course->name));
    
        return response()->json(['message' => 'Certificate issued successfully!'], 200);
        //return view('pdf.certificate', compact('user', 'course', 'certificateNumber'));

            
    // Return PDF as a response to download or view immediately
    //return $pdf->stream('certificate.pdf');  // To open directly in the browser
    
    // ✅ Return the certificate file directly to be downloaded in Postman
    //return response()->file(storage_path("app/{$certificatePath}"));
   }
    
}    