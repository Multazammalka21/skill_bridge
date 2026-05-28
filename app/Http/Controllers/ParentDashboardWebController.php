<?php

namespace App\Http\Controllers;

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
        }

        return view('parent.dashboard', compact('user', 'children'));
    }
}
