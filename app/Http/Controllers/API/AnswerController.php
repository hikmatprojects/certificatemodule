<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\{Answer, Exam, Question, Option, ExamResult};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AnswerController extends Controller
{
   
        public function submitAnswers(Request $request, Exam $exam)  // Using route model binding
        {
            $user = auth()->user();
            
            $validated = $request->validate([
                'answers' => 'required|array|min:1',
                'answers.*.question_id' => [
                    'required',
                    Rule::exists('questions', 'id')->where('exam_id', $exam->id)
                ],
                'answers.*.option_label' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) {
                        $validLabels = ['a', 'b', 'c', 'd'];
                        if (!in_array($value, $validLabels)) {
                            $fail("Invalid option label. Must be a, b, c, or d.");
                        }
                    }
                ]
            ]);

            return DB::transaction(function () use ($user, $exam, $validated) {
                $answers = [];
                $totalScore = 0;

                foreach ($validated['answers'] as $answerData) {
                    $question = Question::where('id', $answerData['question_id'])
                                    ->where('exam_id', $exam->id)
                                    ->firstOrFail();

                    // Convert A, B, C, D to the actual option ID
                    $option = Option::where('question_id', $question->id)
                                ->where('option_label', $answerData['option_label']) // Match by label
                                ->firstOrFail();

                    $answers[] = Answer::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'question_id' => $question->id
                        ],
                        [
                            'option_id' => $option->id
                        ]
                    );

                    // If the selected option is correct, add the question's marks to the score
                    if ($option->is_correct) {
                        $totalScore += $question->marks;
                    }
                }

                // Determine if the student passed
                $passing_marks = $exam->passing_marks ?? 50; // Default to 50 if not set
                $status = $totalScore >= $passing_marks ? 'passed' : 'failed';
    
                // Store the exam result
                ExamResult::updateOrCreate(
                    ['user_id' => $user->id, 'exam_id' => $exam->id],
                    ['score' => $totalScore, 'status' => $status]
                );
                

                return response()->json([
                    'message' => 'Answers submitted successfully',
                    'score' => $totalScore,
                    'total_questions' => $exam->questions()->count(),
                    'answers' => $answers
                ], 201);
            });
        }
    
        public function getAnswers($examId)
        {
            $userId = auth()->id();
    
            $answers = Answer::where('user_id', $userId)
                ->whereHas('question.exam', function ($query) use ($examId) {
                    $query->where('id', $examId);
                })
                ->with('question', 'Option')
                ->get();
    
            return response()->json(['answers' => $answers]);
        }
    }
    

