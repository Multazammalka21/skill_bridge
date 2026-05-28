<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Illuminate\Http\Request;

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
            'lesson_id'     => 'required|exists:lessons,id',
            'pertanyaan'    => 'required|string|max:500',
            'jawaban_benar' => 'required|string|max:255',
            'pilihan'       => 'required|array|min:2',
            'pilihan.*'     => 'required|string|max:255',
            'tipe'          => 'required|string|in:voice,image',
        ]);

        $this->quizRepo->create($validated);

        return redirect()->route('admin.quiz.index')
            ->with('success', 'Soal quiz berhasil ditambahkan.');
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
        $validated = $request->validate([
            'lesson_id'     => 'required|exists:lessons,id',
            'pertanyaan'    => 'required|string|max:500',
            'jawaban_benar' => 'required|string|max:255',
            'pilihan'       => 'required|array|min:2',
            'pilihan.*'     => 'required|string|max:255',
            'tipe'          => 'required|string|in:voice,image',
        ]);

        $this->quizRepo->update($id, $validated);

        return redirect()->route('admin.quiz.index')
            ->with('success', 'Soal quiz berhasil diperbarui.');
    }

    /**
     * Delete a quiz question.
     */
    public function destroy(int $id)
    {
        $this->quizRepo->delete($id);

        return redirect()->route('admin.quiz.index')
            ->with('success', 'Soal quiz berhasil dihapus.');
    }
}
