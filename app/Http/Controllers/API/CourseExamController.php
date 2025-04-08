<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\{Course, Exam};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class CourseExamController extends Controller
{
    // Create exam for course
    public function store(Request $request, $course_id)
    {
        $course = Course::findOrFail($course_id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1', // in minutes
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:1|lte:total_marks',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'is_published' => 'sometimes|boolean'
        ]);

        $exam = DB::transaction(function () use ($course, $validated) {
            return $course->exams()->create($validated);
        });

        return response()->json([
            'message' => 'Exam created successfully',
            'exam' => $exam
        ], 201);
    }

    // Get all exams for course
    public function index($course_id)
    {
        $exams = Exam::where('course_id', $course_id)
                   ->withCount('questions')
                   ->orderBy('start_time')
                   ->get();

        return response()->json([
            'exams' => $exams
        ]);
    }

    // Get specific exam
    public function show($course_id, $exam_id)
    {
        $exam = Exam::where('course_id', $course_id)
                  ->with(['questions' => function($query) {
                      $query->with('options');
                  }])
                  ->findOrFail($exam_id);

        return response()->json([
            'exam' => $exam
        ]);
    }

    // Update exam
    public function update(Request $request, $course_id, $exam_id)
    {
        $exam = Exam::where('course_id', $course_id)
                  ->findOrFail($exam_id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'sometimes|integer|min:1',
            'total_marks' => 'sometimes|integer|min:1',
            'passing_marks' => 'sometimes|integer|min:1|lte:total_marks',
            'start_time' => 'sometimes|date|after:now',
            'end_time' => 'sometimes|date|after:start_time',
            'is_published' => 'sometimes|boolean'
        ]);

        $exam->update($validated);

        return response()->json([
            'message' => 'Exam updated successfully',
            'exam' => $exam
        ]);
    }

    // Delete exam
    public function destroy($course_id, $exam_id)
    {
        $exam = Exam::where('course_id', $course_id)
                  ->findOrFail($exam_id);

        DB::transaction(function () use ($exam) {
            // Delete related questions and options first
            $exam->questions()->each(function($question) {
                $question->options()->delete();
            });
            $exam->questions()->delete();
            
            // Then delete the exam
            $exam->delete();
        });

        return response()->json([
            'message' => 'Exam deleted successfully'
        ]);
    }

    // Publish/unpublish exam
    public function publish($course_id, $exam_id)
    {
        $exam = Exam::where('course_id', $course_id)
                  ->findOrFail($exam_id);

        $exam->update(['is_published' => !$exam->is_published]);

        return response()->json([
            'message' => $exam->is_published ? 'Exam published' : 'Exam unpublished',
            'exam' => $exam
        ]);
    }

   
}

