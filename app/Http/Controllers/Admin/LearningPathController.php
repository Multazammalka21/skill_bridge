<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LearningPathController extends Controller
{
    /**
     * Display learning path management page.
     * Shows lessons grouped by category and tipe_dunia, in urutan order.
     */
    public function index(Request $request)
    {
        $selectedCategory  = $request->get('category_id');
        $selectedTipeDunia = $request->get('tipe_dunia', 'audio');
        $selectedUsia      = $request->get('kategori_usia');

        $categories = Category::active()->ordered()->get();

        $query = Lesson::with(['category', 'prerequisite'])
            ->where('tipe_dunia', $selectedTipeDunia)
            ->when($selectedCategory, fn($q) => $q->where('category_id', $selectedCategory))
            ->when($selectedUsia, fn($q) => $q->where('kategori_usia', $selectedUsia))
            ->orderBy('urutan')
            ->orderBy('judul');

        $lessons = $query->get();

        // All lessons for prerequisite pickers
        $allLessons = Lesson::orderBy('judul')->get();

        return view('admin.learning-path.index', compact(
            'categories',
            'lessons',
            'allLessons',
            'selectedCategory',
            'selectedTipeDunia',
            'selectedUsia'
        ));
    }

    /**
     * Save learning path order and prerequisites.
     * Accepts array of {id, urutan, prerequisite_lesson_id}.
     */
    public function update(Request $request)
    {
        $request->validate([
            'lessons'                            => 'required|array',
            'lessons.*.id'                       => 'required|integer|exists:lessons,id',
            'lessons.*.urutan'                   => 'required|integer|min:0',
            'lessons.*.prerequisite_lesson_id'   => 'nullable|integer|exists:lessons,id',
        ]);

        foreach ($request->lessons as $item) {
            Lesson::where('id', $item['id'])->update([
                'urutan'                  => $item['urutan'],
                'prerequisite_lesson_id'  => $item['prerequisite_lesson_id'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Learning path berhasil disimpan.', 'status' => 'ok']);
    }
}
