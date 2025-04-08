<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Certificate;
use App\Mail\CertificateMail;


class NotificationController extends Controller
{
    public function sendCertificateEmail(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = auth()->user();
        $certificate = Certificate::where('user_id', $user->id)->where('course_id', $request->course_id)->first();

        if (!$certificate) {
            return response()->json(['message' => 'Certificate not found.'], 404);
        }

        Mail::to($user->email)->send(new CertificateMail($certificate));

        return response()->json(['message' => 'Certificate email sent successfully']);
    }
}


