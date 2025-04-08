<?php
namespace App\Http\Controllers\API;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EnrollmentController extends Controller
{
    // Enroll in a course
    public function enroll(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $userId = auth()->id();
        $existingEnrollment = Enrollment::where('user_id', $userId)->where('course_id', $request->course_id)->first();

        if ($existingEnrollment) {
            return response()->json(['message' => 'Already enrolled in this course.'], 400);
        }

        $enrollment = Enrollment::create([
            'user_id' => $userId,
            'course_id' => $request->course_id,
            'status' => 'pending',
            'enrollment_at' => now(),
        ]);

        return response()->json(['message' => 'Enrollment successful', 'enrollment' => $enrollment], 201);
    }

    // Get all enrollments (for admin)
    public function index()
    {
        $enrollments = Enrollment::with(['user', 'course'])->get();
        return response()->json(['enrollments' => $enrollments]);
    }

    // Get enrollments for the authenticated user
    public function myEnrollments()
    {
        $userId = auth()->id();
        $enrollments = Enrollment::with('course')->where('user_id', $userId)->get();
        return response()->json(['enrollments' => $enrollments]);
    }

    // Get a specific enrollment
    public function show($id)
    {
        $enrollment = Enrollment::with(['user', 'course'])->find($id);
        if (!$enrollment) {
            return response()->json(['message' => 'Enrollment not found'], 404);
        }
        return response()->json(['enrollment' => $enrollment]);
    }

    // Update enrollment status
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $enrollment = Enrollment::find($id);
        if (!$enrollment) {
            return response()->json(['message' => 'Enrollment not found'], 404);
        }

        $enrollment->update([
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Enrollment status updated', 'enrollment' => $enrollment]);
    }

    // Delete an enrollment
    public function destroy($id)
    {
        $enrollment = Enrollment::find($id);
        if (!$enrollment) {
            return response()->json(['message' => 'Enrollment not found'], 404);
        }

        $enrollment->delete();
        return response()->json(['message' => 'Enrollment deleted successfully']);
    }

    // Get enrollments for a specific course
    public function courseEnrollments($courseId)
    {
        $enrollments = Enrollment::with('user')->where('course_id', $courseId)->get();
        return response()->json(['enrollments' => $enrollments]);
    }
}