<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\StudySession;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * List lessons (lazy load: no media fields, only metadata).
     */
    public function index(Request $request)
    {
        $tipeDunia = $request->input('tipe_dunia');

        $query = Lesson::active()->listing()->orderBy('urutan');

        if ($tipeDunia) {
            $query->forWorld($tipeDunia);
        }

        return response()->json([
            'lessons' => $query->get(),
        ]);
    }

    /**
     * Show a single lesson WITH all media assets (lazy loaded on demand).
     * This is the endpoint called when a child enters a lesson session.
     */
    public function show(Lesson $lesson)
    {
        $lesson->load('quizQuestions');

        return response()->json([
            'lesson' => $lesson,
        ]);
    }

    /**
     * Mark a lesson as completed by a child.
     */
    public function complete(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
        ]);

        LessonCompletion::firstOrCreate(
            [
                'child_id' => $validated['child_id'],
                'lesson_id' => $lesson->id,
            ],
            [
                'completed_at' => Carbon::now(),
            ]
        );

        return response()->json([
            'message' => 'Lesson berhasil diselesaikan.',
        ]);
    }

    /**
     * Start a study session (called when child enters a lesson).
     */
    public function startSession(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
        ]);

        $session = StudySession::create([
            'child_id' => $validated['child_id'],
            'lesson_id' => $lesson->id,
            'started_at' => Carbon::now(),
            'durasi_detik' => 0,
        ]);

        return response()->json([
            'message' => 'Sesi belajar dimulai.',
            'session_id' => $session->id,
        ]);
    }

    /**
     * End a study session (called when child leaves a lesson).
     */
    public function endSession(Request $request, StudySession $session)
    {
        $now = Carbon::now();
        $session->update([
            'ended_at' => $now,
            'durasi_detik' => $now->diffInSeconds($session->started_at),
        ]);

        return response()->json([
            'message' => 'Sesi belajar selesai.',
            'durasi_detik' => $session->durasi_detik,
        ]);
    }
}
