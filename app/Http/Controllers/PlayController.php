<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Lesson;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\LessonCompletion;
use App\Models\StudySession;
use App\Services\AIService;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlayController extends Controller
{
    public function __construct(private readonly AIService $ai) {}

    /**
     * Get age category string from child birthdate.
     */
    private function getAgeCategory(Child $child): string
    {
        $age = $child->tanggal_lahir->age;
        return $age >= 8 ? '8-10' : '5-7';
    }

    /**
     * Check if child belongs to authenticated parent.
     */
    private function authorizeChild(Child $child)
    {
        if ($child->user_id !== Auth::id()) {
            abort(403, 'Akses tidak sah untuk profil anak ini.');
        }
    }

    /**
     * Auto-redirect to the appropriate world based on the child's disability.
     */
    public function autoPlay(Child $child)
    {
        $this->authorizeChild($child);

        if ($child->isAudioWorld()) {
            return redirect()->route('play.tunanetra', $child->id);
        }

        return redirect()->route('play.tunarungu', $child->id);
    }

    /**
     * Show mode selection screen for the child.
     */
    public function chooseMode(Child $child)
    {
        $this->authorizeChild($child);
        return view('play.choose-mode', compact('child'));
    }

    /**
     * Verify parent password for Kids Mode unlock.
     */
    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if (\Illuminate\Support\Facades\Hash::check($request->password, Auth::user()->password)) {
            return response()->json([
                'success' => true,
                'message' => 'Kata sandi berhasil diverifikasi.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kata sandi salah, silakan coba lagi!'
        ], 422);
    }

    /**
     * Play view for blind children (Tunanetra).
     */
    public function tunanetra(Child $child)
    {
        $this->authorizeChild($child);
        
        $kategoriUsia = $this->getAgeCategory($child);

        // Get audio lessons for this age category; fallback to ALL audio lessons if none found
        $lessons = Lesson::where('tipe_dunia', 'audio')
            ->where('kategori_usia', $kategoriUsia)
            ->where('aktif', true)
            ->with(['quizQuestions' => function($query) {
                $query->where('tipe', 'voice');
            }])
            ->orderBy('urutan')
            ->get();

        // Fallback: if no age-specific lessons exist, show all active audio lessons
        if ($lessons->isEmpty()) {
            $lessons = Lesson::where('tipe_dunia', 'audio')
                ->where('aktif', true)
                ->with(['quizQuestions' => function($query) {
                    $query->where('tipe', 'voice');
                }])
                ->orderBy('urutan')
                ->get();
        }

        // Start a study session HANYA jika belum ada sesi aktif dalam 30 menit terakhir
        $firstLesson = $lessons->first();
        if ($firstLesson) {
            $recentSession = StudySession::where('child_id', $child->id)
                ->where('lesson_id', $firstLesson->id)
                ->where('started_at', '>=', Carbon::now()->subMinutes(30))
                ->exists();

            if (!$recentSession) {
                StudySession::create([
                    'child_id'     => $child->id,
                    'lesson_id'    => $firstLesson->id,
                    'started_at'   => Carbon::now(),
                    'durasi_detik' => 0,
                ]);
            }
        }

        return view('play.tunanetra', compact('child', 'lessons'));
    }

    /**
     * Play view for deaf children (Tunarungu).
     */
    public function tunarungu(Child $child)
    {
        $this->authorizeChild($child);
        
        $kategoriUsia = $this->getAgeCategory($child);

        // Get visual lessons for this age category; fallback to ALL visual lessons if none found
        $lessons = Lesson::where('tipe_dunia', 'visual')
            ->where('kategori_usia', $kategoriUsia)
            ->where('aktif', true)
            ->with(['quizQuestions' => function($query) {
                $query->where('tipe', 'image');
            }])
            ->orderBy('urutan')
            ->get();

        // Fallback: if no age-specific lessons exist, show all active visual lessons
        if ($lessons->isEmpty()) {
            $lessons = Lesson::where('tipe_dunia', 'visual')
                ->where('aktif', true)
                ->with(['quizQuestions' => function($query) {
                    $query->where('tipe', 'image');
                }])
                ->orderBy('urutan')
                ->get();
        }

        // Start a study session HANYA jika belum ada sesi aktif dalam 30 menit terakhir
        $firstLesson = $lessons->first();
        if ($firstLesson) {
            $recentSession = StudySession::where('child_id', $child->id)
                ->where('lesson_id', $firstLesson->id)
                ->where('started_at', '>=', Carbon::now()->subMinutes(30))
                ->exists();

            if (!$recentSession) {
                StudySession::create([
                    'child_id'     => $child->id,
                    'lesson_id'    => $firstLesson->id,
                    'started_at'   => Carbon::now(),
                    'durasi_detik' => 0,
                ]);
            }
        }

        return view('play.tunarungu', compact('child', 'lessons'));
    }

    /**
     * Submit quiz completion & answer data.
     */
    public function submitResult(Request $request)
    {
        $validated = $request->validate([
            'child_id'         => 'required|exists:children,id',
            'lesson_id'        => 'required|exists:lessons,id',
            'quiz_question_id' => 'required|exists:quiz_questions,id',
            'jawaban_anak'     => 'required|string',
            'benar'            => 'required|boolean',
            'skor'             => 'required|integer|min:0|max:100',
            'percobaan'        => 'required|integer|min:1',
            'waktu_detik'      => 'required|integer|min:0',
        ]);

        $child = Child::findOrFail($validated['child_id']);
        $this->authorizeChild($child);

        // Calculate stars
        $bintang = GamificationService::calculateStars($validated['skor']);
        $validated['bintang'] = $bintang;

        // Save result
        $result = QuizResult::create($validated);

        // Track and log completion if user got it right or perfect score
        if ($validated['benar'] && !LessonCompletion::where('child_id', $child->id)->where('lesson_id', $validated['lesson_id'])->exists()) {
            LessonCompletion::create([
                'child_id' => $child->id,
                'lesson_id' => $validated['lesson_id'],
                'completed_at' => Carbon::now(),
            ]);
        }

        // Find or create active study session for this child and lesson
        $activeSession = StudySession::where('child_id', $child->id)
            ->where('lesson_id', $validated['lesson_id'])
            ->latest()
            ->first();

        if (!$activeSession) {
            $activeSession = StudySession::create([
                'child_id' => $child->id,
                'lesson_id' => $validated['lesson_id'],
                'started_at' => Carbon::now()->subSeconds($validated['waktu_detik']),
                'durasi_detik' => 0,
            ]);
        }

        $activeSession->increment('durasi_detik', $validated['waktu_detik']);
        $activeSession->update(['ended_at' => Carbon::now()]);

        // Award and check badges
        $newBadges = GamificationService::checkAndAwardBadges($child);

        return response()->json([
            'message'    => 'Hasil kuis disimpan successfully!',
            'result'     => $result,
            'bintang'    => $bintang,
            'new_badges' => $newBadges,
        ], 201);
    }

    // =========================================================================
    // Tunanetra AI Pipeline — Web Routes (Session Auth + CSRF)
    // =========================================================================

    /**
     * Text-to-Speech untuk Mode Tunanetra.
     *
     * Menerima teks, mengembalikan file MP3 binary dari Google Cloud TTS.
     * Jika API key belum dikonfigurasi, mengembalikan 204 sehingga
     * frontend fallback ke browser SpeechSynthesis.
     *
     * POST /play/ai/tts
     * Body JSON: { "text": "..." }
     */
    public function tunanetraTts(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $mp3Binary = $this->ai->textToSpeech($validated['text']);

        if ($mp3Binary === null) {
            // Key belum ada → frontend gunakan browser TTS
            return response()->noContent();
        }

        return response($mp3Binary, 200, [
            'Content-Type'        => 'audio/mpeg',
            'Content-Disposition' => 'inline; filename="tts.mp3"',
            'Cache-Control'       => 'no-store',
        ]);
    }

    /**
     * Pipeline Tunanetra: Audio Anak → Whisper → Llama → Evaluasi.
     *
     * Menerima file audio, mentranskripsi dengan Whisper, menilai dengan Llama.
     * Mengembalikan JSON dengan transcript, is_correct, dan feedback.
     *
     * POST /play/ai/evaluate-answer
     * Form-data: audio (file), correct_answer (string), question (string)
     */
    public function tunanetraEvaluate(Request $request)
    {
        $validated = $request->validate([
            'audio'          => 'required|file|mimes:mp3,wav,m4a,webm,ogg|max:25600',
            'correct_answer' => 'required|string|max:500',
            'question'       => 'required|string|max:1000',
            'language'       => 'nullable|string|size:2',
        ]);

        // Simpan audio sementara
        $path     = $validated['audio']->store('temp/audio', 'local');
        $fullPath = Storage::disk('local')->path($path);

        try {
            $transcription = $this->ai->transcribeAudio($fullPath, $validated['language'] ?? 'id');
        } finally {
            Storage::disk('local')->delete($path);
        }

        if (!$transcription['success'] || empty($transcription['transcript'])) {
            return response()->json([
                'message' => 'Gagal mentranskripsi audio. Coba bicara lebih jelas.',
            ], 422);
        }

        $evaluation = $this->ai->evaluateQuizAnswer(
            $transcription['transcript'],
            $validated['correct_answer'],
            $validated['question']
        );

        return response()->json([
            'transcript' => $transcription['transcript'],
            'is_correct' => $evaluation['is_correct'],
            'feedback'   => $evaluation['feedback'],
        ]);
    }
}
