<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Certificate;

use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function reissueCertificate(Request $request)
    {
        $request->validate(['user_id' => 'required', 'course_id' => 'required']);
    
        $certificate = Certificate::where('user_id', $request->user_id)
                                  ->where('course_id', $request->course_id)
                                  ->first();
    
        if (!$certificate) {
            return response()->json(['message' => 'Certificate not found.'], 404);
        }
    
        $certificate->delete();
    
        return response()->json(['message' => 'Certificate reissued successfully.']);
    }
}