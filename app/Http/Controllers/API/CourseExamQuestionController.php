<?php
namespace App\Http\Controllers\API;

use App\Models\{Course, Exam, Question, Option};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class CourseExamQuestionController extends Controller
{
    public function storeQuestionsWithOptions(Request $request, $course_id, $exam_id)
    {
        // Verify course and exam exist and are related
        $course = Course::findOrFail($course_id);
        $exam = Exam::where('id', $exam_id)
                  ->where('course_id', $course_id)
                  ->firstOrFail();

        $validated = $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string|max:1000',
            'questions.*.type' => 'sometimes|string|in:multichoice,short_answer',
            'questions.*.options' => 'required_if:questions.*.type,multichoice|array|min:2',
            'questions.*.options.*.text' => 'required_if:questions.*.type,multichoice|string|max:255',
            'questions.*.options.*.is_correct' => 'required_if:questions.*.type,multichoice|boolean',
            'questions.*.marks' => 'sometimes|integer|min:1'
        ]);

        // Pass $course to the transaction closure using use()
        return DB::transaction(function () use ($course, $exam, $request) {
            $createdQuestions = [];
            
            foreach ($request->questions as $questionData) {
                $questionType = $questionData['type'] ?? 'multichoice';
                
                $question = Question::create([
                    'exam_id' => $exam->id,
                    'question_text' => $questionData['text'],
                    'type' => $questionType,
                    'marks' => $questionData['marks'] ?? 1,
                ]);

                $labels = ['a', 'b', 'c', 'd']; // Option labels
                $options = [];
                
                if ($questionType === 'multichoice') {
                    foreach ($questionData['options'] as $index =>$optionData) {
                        $options[] = Option::create([
                            'question_id' => $question->id,
                            'option_label' => $labels[$index], // Assign A, B, C, D
                            'option_text' => $optionData['text'],
                            'is_correct' => $optionData['is_correct'] ?? false
                        ]);
                    }
                }

                $createdQuestions[] = [
                    'question' => $question,
                    'options' => $options
                ];
            }

            return response()->json([
                'message' => 'Questions added successfully',
                'course' => [
                    'id' => $course->id,
                    'name' => $course->name
                ],
                'exam' => [
                    'id' => $exam->id,
                    'title' => $exam->title
                ],
                'questions_added' => $createdQuestions
            ], 201);
        });
    }
}