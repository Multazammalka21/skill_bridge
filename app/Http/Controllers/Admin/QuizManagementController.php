<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\Child;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizManagementController extends Controller
{
    protected QuizRepositoryInterface $quizRepo;

    public function __construct(QuizRepositoryInterface $quizRepo)
    {
        $this->quizRepo = $quizRepo;
    }

    /**
     * Display paginated quiz questions with filters.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['lesson_id', 'tipe_dunia', 'kategori_usia', 'tipe']);

        $quizzes = $this->quizRepo->getPaginated(15, $filters);
        $lessons = Lesson::orderBy('judul')->get();

        return view('admin.quiz.index', compact('quizzes', 'lessons', 'filters'));
    }

    /**
     * Show form to create a new quiz question.
     */
    public function create()
    {
        $lessons = Lesson::orderBy('judul')->get();
        return view('admin.quiz.create', compact('lessons'));
    }

    /**
     * Store a new quiz question.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id'       => 'required|exists:lessons,id',
            'pertanyaan'      => 'required|string|max:500',
            'jawaban_benar'   => 'required|string|max:255',
            'pilihan'         => 'required|array|min:2',
            'pilihan.*'       => 'required|string|max:255',
            'tipe'            => 'required|string|in:voice,image',
            'gambar_file'     => 'nullable|image|max:2048',
            'audio_file'      => 'nullable|mimes:mp3,wav,ogg,m4a,aac|max:5120',
            'animasi_url'     => 'nullable|string|max:255',
            'efek_suara_url'  => 'nullable|string|max:255',
            'poin'            => 'required|integer|min:1',
        ]);

        // Handle file uploads
        if ($request->hasFile('gambar_file')) {
            $path = $request->file('gambar_file')->store('quiz', 'public');
            $validated['gambar'] = '/storage/' . $path;
        }

        if ($request->hasFile('audio_file')) {
            $path = $request->file('audio_file')->store('quiz', 'public');
            $validated['audio_url'] = '/storage/' . $path;
        }

        $this->quizRepo->create($validated);

        return redirect()->route('admin.quiz.index')
            ->with('success', 'Soal kuis berhasil ditambahkan.');
    }

    /**
     * Show form to edit a quiz question.
     */
    public function edit(int $id)
    {
        $quiz = $this->quizRepo->findById($id);
        $lessons = Lesson::orderBy('judul')->get();

        return view('admin.quiz.edit', compact('quiz', 'lessons'));
    }

    /**
     * Update a quiz question.
     */
    public function update(Request $request, int $id)
    {
        $quiz = $this->quizRepo->findById($id);

        $validated = $request->validate([
            'lesson_id'       => 'required|exists:lessons,id',
            'pertanyaan'      => 'required|string|max:500',
            'jawaban_benar'   => 'required|string|max:255',
            'pilihan'         => 'required|array|min:2',
            'pilihan.*'       => 'required|string|max:255',
            'tipe'            => 'required|string|in:voice,image',
            'gambar_file'     => 'nullable|image|max:2048',
            'audio_file'      => 'nullable|mimes:mp3,wav,ogg,m4a,aac|max:5120',
            'animasi_url'     => 'nullable|string|max:255',
            'efek_suara_url'  => 'nullable|string|max:255',
            'poin'            => 'required|integer|min:1',
        ]);

        // Keep old values if no new files are uploaded
        $validated['gambar'] = $quiz->gambar;
        $validated['audio_url'] = $quiz->audio_url;

        // Handle file uploads
        if ($request->hasFile('gambar_file')) {
            // Delete old file
            if ($quiz->gambar) {
                Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $quiz->gambar), '/'));
            }
            $path = $request->file('gambar_file')->store('quiz', 'public');
            $validated['gambar'] = '/storage/' . $path;
        }

        if ($request->hasFile('audio_file')) {
            // Delete old file
            if ($quiz->audio_url) {
                Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $quiz->audio_url), '/'));
            }
            $path = $request->file('audio_file')->store('quiz', 'public');
            $validated['audio_url'] = '/storage/' . $path;
        }

        $this->quizRepo->update($id, $validated);

        return redirect()->route('admin.quiz.index')
            ->with('success', 'Soal kuis berhasil diperbarui.');
    }

    /**
     * Delete a quiz question.
     */
    public function destroy(int $id)
    {
        $quiz = $this->quizRepo->findById($id);

        if ($quiz->gambar) {
            Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $quiz->gambar), '/'));
        }
        if ($quiz->audio_url) {
            Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $quiz->audio_url), '/'));
        }

        $this->quizRepo->delete($id);

        return redirect()->route('admin.quiz.index')
            ->with('success', 'Soal kuis berhasil dihapus.');
    }

    /**
     * Display quiz monitoring dashboard for admin.
     */
    public function monitoring()
    {
        $totalChildren = Child::count();
        $totalQuizAttempts = QuizResult::count();
        
        $correctAnswers = QuizResult::where('benar', 1)->count();
        $incorrectAnswers = QuizResult::where('benar', 0)->count();
        $successRate = $totalQuizAttempts > 0 ? round(($correctAnswers / $totalQuizAttempts) * 100, 1) : 0;

        // Breakdown of stars
        $starBreakdown = [
            '3' => QuizResult::where('bintang', 3)->count(),
            '2' => QuizResult::where('bintang', 2)->count(),
            '1' => QuizResult::where('bintang', 1)->count(),
            '0' => QuizResult::where('bintang', 0)->count(),
        ];

        // Top 5 hardest quiz questions
        $hardestQuestions = QuizResult::select('quiz_question_id')
            ->selectRaw('COUNT(*) as total_attempts')
            ->selectRaw('SUM(CASE WHEN benar = 0 THEN 1 ELSE 0 END) as wrong_attempts')
            ->groupBy('quiz_question_id')
            ->orderBy('wrong_attempts', 'desc')
            ->limit(5)
            ->with('quizQuestion.lesson')
            ->get();

        // Recent quiz activities
        $recentActivities = QuizResult::latest()
            ->limit(10)
            ->with(['child', 'lesson', 'quizQuestion'])
            ->get();

        return view('admin.quiz.monitoring', compact(
            'totalChildren',
            'totalQuizAttempts',
            'successRate',
            'correctAnswers',
            'incorrectAnswers',
            'starBreakdown',
            'hardestQuestions',
            'recentActivities'
        ));
    }
}
