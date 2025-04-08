<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CertificateController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EnrollmentController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\ExamController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\AnswerController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\CourseExamQuestionController;
use App\Http\Controllers\API\CourseExamController;
use App\Http\Controllers\API\AdminController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes (Require authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Enrollment Route
    Route::post('/enrollments/', [EnrollmentController::class, 'enroll']); // Enroll in a course by student
    Route::get('/enrollments/', [EnrollmentController::class, 'index']); // Get all enrollments (admin)
    Route::get('/enrollments/my', [EnrollmentController::class, 'myEnrollments']); // Get enrollments for the authenticated user
    Route::get('/enrollments/{id}', [EnrollmentController::class, 'show']); // Get a specific enrollment
    Route::put('/enrollments/{id}', [EnrollmentController::class, 'update']); // Update enrollment status used by teacher
    Route::delete('/enrollments/{id}', [EnrollmentController::class, 'destroy']); // Delete an enrollment
    Route::get('/courses/{courseId}/enrollments', [EnrollmentController::class, 'courseEnrollments']); // Get enrollments for a specific course

    // Course Routes
    Route::get('/courses', [CourseController::class, 'index']); // Get all Courses
    Route::get('/courses/{id}', [CourseController::class, 'show']); // Get a specific Course
    Route::post('/courses', [CourseController::class, 'store']); // Create a new Course
    Route::put('/courses/{id}', [CourseController::class, 'update']); // Update a Course
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']); // Delete a Course

    // Create exam for course
    Route::post('/courses/{course_id}/exams/', [CourseExamController::class, 'store']);
    Route::get('/courses/{course_id}/exams/', [CourseExamController::class, 'index']);
    Route::get('/courses/{course_id}/exams/{exam_id}', [CourseExamController::class, 'show']);
    Route::put('/courses/{course_id}/exams/{exam_id}', [CourseExamController::class, 'update']);
    Route::delete('/courses/{course_id}/exams/{exam_id}', [CourseExamController::class, 'destroy']);
    Route::post('/courses/{course_id}/exams/{exam_id}/publish', [CourseExamController::class, 'publish']);


    // Question Routes
    Route::get('/questions/{exam_id}', [QuestionController::class, 'index']);
    Route::post('/courses/{course_id}/exams/{exam_id}/questions', [CourseExamQuestionController::class, 'storeQuestionsWithOptions']);//teacher store questions with their options in a specific course

    // Answer Routes
    Route::get('/get_answers/{exam_id}', [AnswerController::class, 'getAnswers']);
    Route::post('/exams/{exam}/submit-answers', [AnswerController::class, 'submitAnswers']);// student submit their answers on a questions


    // Role Route
    Route::get('/roles/', [RoleController::class, 'index']); // Get all roles
    Route::post('/roles/', [RoleController::class, 'store']); // Create a new role
    Route::get('/roles/{id}', [RoleController::class, 'show']); // Get a specific role
    Route::put('/roles/{id}', [RoleController::class, 'update']); // Update a role
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']); // Delete a role


    // Project Routes
    Route::post('/projects/submit', [ProjectController::class, 'submitProject']); // Student submits project
    Route::post('/projects/{id}/evaluate', [ProjectController::class, 'evaluate']); // Teacher evaluates project
    Route::get('/projects', [ProjectController::class, 'index']); // Get all Projects
    Route::put('/projects/{id}', [ProjectController::class, 'update']); // Update a Project
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']); // Delete a Project


   // Certificate Routes
   Route::post('/certificates/issue', [CertificateController::class, 'issueCertificate']);
   
   //for admin to allow student to reissue certificate more than one 
   Route::post('/certificate/reissue', [AdminController::class, 'reissueCertificate']);


});
