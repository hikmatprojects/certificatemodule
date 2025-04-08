<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Get all courses
    public function index()
    {
        $courses = Course::all();
        return response()->json($courses);
    }

    // Get a specific course by ID
    public function show($id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course);
    }

    // Create a new course
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            //'price' => 'required|numeric',
        ]);

        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
            //'price' => $request->price,
        ]);

        return response()->json($course, 201); // 201: Created
    }

    // Update an existing course
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            //'price' => 'sometimes|numeric',
        ]);

        $course->update([
            'name' => $request->name ?? $course->name,
            'description' => $request->description ?? $course->description,
            //'price' => $request->price ?? $course->price,
        ]);

        return response()->json($course);
    }

    // Delete a course
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(['message' => 'Course deleted successfully']);
    }
}
