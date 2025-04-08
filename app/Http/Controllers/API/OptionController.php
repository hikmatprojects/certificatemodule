<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_text' => 'required|string',
            'is_correct' => 'required|boolean'
        ]);

        $option = Option::create([
            'question_id' => $request->question_id,
            'option_text' => $request->option_text,
            'is_correct' => $request->is_correct
        ]);

        return response()->json($option, 201);
    }
}
