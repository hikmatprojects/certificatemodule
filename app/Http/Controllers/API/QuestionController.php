<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index($exam_id)
    {
        $questions = Question::where('exam_id', $exam_id)->with('options')->get();
        return response()->json($questions);
    }

  
}
