<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{

    // Get all projects
    public function index()
    {
        $projects = Project::all();
        return response()->json($projects);
    }

    // Student submits project with a repository URL
    public function submitProject(Request $request)
    {

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'submited_at' => 'required|date|after:today',
            'project_url' => 'required|url',
        ]);

        // Verify user is enrolled in the course
        $this->verifyEnrollment($request->course_id);


        $project = Project::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'submited_at' => $request->submited_at,
            'project_url' => $request->project_url,
            'mark_obtained' => null, // Default null value
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Project submitted successfully!',
            'project' => $project
        ]);
    }

    // Verify if the authenticated user is enrolled in the course
    private function verifyEnrollment($courseId)
    {
        
            $user = Auth::user();
        
            $isEnrolled = Enrollment::where('user_id', $user->id)
                                    ->where('course_id', $courseId)
                                    ->exists();
        
            if (!$isEnrolled) {
                abort(403, 'You are not enrolled in this course.');
            }
        
    }
    
    // Teacher evaluates the project
    public function evaluate(Request $request, $projectId)
    {
        $request->validate([
            'mark_obtained' => 'required|integer|min:0|max:100',
        ]);

        $project = Project::findOrFail($projectId);
        $project->mark_obtained = $request->mark_obtained;
        $project->status = $request->mark_obtained >= 50 ? 'approved' : 'rejected';
        $project->save();

        return response()->json([
            'message' => 'Project evaluated successfully.',
            'status' => $project->status,
            'mark_obtained' => $project->mark_obtained
        ]);
    }


    
    // Update an existing project
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
    
        $request->validate([
            'course_id' => 'sometimes|exists:courses,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'submited_at' => 'sometimes|date|after:today',
            'project_url' => 'sometimes|url',
        ]);
    
        $project->update([
            'course_id' => $request->course_id ?? $project->course_id,
            'title' => $request->title ?? $project->title,
            'description' => $request->description ?? $project->description,
            'submited_at' => $request->submited_at ?? $project->submited_at,
            'project_url' => $request->project_url ?? $project->project_url,
        ]);
    
        return response()->json([
            'message' => 'Project updated successfully!',
            'project' => $project
        ]);
    }

     // Delete a course
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}

   
    


//     public function approve(Project $project)
//     {
//         // Verify project exists and is accessible
//         if (!$project->exists) {
//             abort(404, 'Project not found');
//         }

//         // Verify user has permission to approve
//         if (!auth()->user()->can('approve', $project)) {
//             abort(403, 'Unauthorized action');
//         }

//         // Rest of your approval logic...
//     }
// }


 


