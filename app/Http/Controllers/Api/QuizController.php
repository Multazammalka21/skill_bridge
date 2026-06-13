<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizResult;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Submit a quiz answer.
     */
    public function submitAnswer(Request $request)
    {
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'lesson_id' => 'required|exists:lessons,id',
            'quiz_question_id' => 'required|exists:quiz_questions,id',
            'jawaban_anak' => 'required|string',
            'benar' => 'sometimes|boolean',
            'skor' => 'sometimes|integer|min:0|max:100',
            'percobaan' => 'required|integer|min:1',
        ]);

        $validated['benar'] = $validated['benar'] ?? false;
        $validated['skor'] = $validated['skor'] ?? 0;

        $result = QuizResult::create($validated);

        return response()->json([
            'message' => 'Jawaban berhasil disimpan.',
            'result' => $result,
        ], 201);
    }

    /**
     * Get quiz results for a specific child and lesson.
     */
    public function results(Request $request)
    {
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        $results = QuizResult::where('child_id', $validated['child_id'])
            ->where('lesson_id', $validated['lesson_id'])
            ->with('quizQuestion')
            ->get();

        return response()->json([
            'results' => $results,
        ]);
    }
}
