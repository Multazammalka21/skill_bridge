<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Lesson;
use Illuminate\Http\Request;

class ParentDashboardWebController extends Controller
{
    /**
     * Show the parent dashboard page with Chart.js charts.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $children = $user->children()->with([
            'lessonCompletions.lesson',
            'quizResults.quizQuestion',
            'quizResults.lesson',
            'studySessions',
            'badges',
        ])->get();

        foreach ($children as $child) {
            $age = $child->tanggal_lahir->age;
            $ageCategory = $age >= 8 ? '8-10' : '5-7';
            $tipeDunia = $child->isAudioWorld() ? 'audio' : 'visual';

            // Get completed lesson IDs
            $completedLessonIds = $child->lessonCompletions->pluck('lesson_id')->toArray();

            // Fetch top 3 recommended lessons that are not completed yet
            $child->rekomendasi = Lesson::where('tipe_dunia', $tipeDunia)
                ->where('kategori_usia', $ageCategory)
                ->whereNotIn('id', $completedLessonIds)
                ->limit(3)
                ->get();

            // Fetch last 5 quiz results
            $child->recentQuizzes = $child->quizResults()
                ->latest()
                ->limit(5)
                ->with(['lesson', 'quizQuestion'])
                ->get();

            // ── Progress per kategori (DINAMIS) ──────────────────────────
            // Hitung persentase lesson selesai per kategori untuk tipe_dunia & usia anak
            $categories = Category::active()->ordered()->get();

            $child->progressKategori = $categories->map(function (Category $cat) use ($tipeDunia, $ageCategory, $completedLessonIds) {
                // Total lessons aktif di kategori ini yang sesuai profil anak
                $totalLessons = Lesson::where('category_id', $cat->id)
                    ->where('tipe_dunia', $tipeDunia)
                    ->where('kategori_usia', $ageCategory)
                    ->where('aktif', true)
                    ->count();

                if ($totalLessons === 0) {
                    return null; // Sembunyikan kategori tanpa materi untuk anak ini
                }

                // Lessons dalam kategori ini yang sudah diselesaikan anak
                $selesai = Lesson::where('category_id', $cat->id)
                    ->where('tipe_dunia', $tipeDunia)
                    ->where('kategori_usia', $ageCategory)
                    ->where('aktif', true)
                    ->whereIn('id', $completedLessonIds)
                    ->count();

                return [
                    'nama'   => $cat->nama,
                    'persen' => $totalLessons > 0 ? round(($selesai / $totalLessons) * 100) : 0,
                ];
            })->filter()->values(); // Hapus kategori null (tidak punya materi untuk anak ini)
        }

        return view('parent.dashboard', compact('user', 'children'));
    }
}
