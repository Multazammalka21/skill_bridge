<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Child;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\MediaAsset;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard with full analytics.
     */
    public function index()
    {
        // ── Core stats ───────────────────────────────────────────────────
        $stats = [
            'total_users'       => User::count(),
            'total_parents'     => User::where('role', 'parent')->count(),
            'total_children'    => Child::count(),
            'total_tunanetra'   => Child::where('jenis_disabilitas', 'tunanetra')->count(),
            'total_tunarungu'   => Child::where('jenis_disabilitas', 'tunarungu')->count(),
            'total_lessons'     => Lesson::count(),
            'total_active_lessons' => Lesson::where('aktif', true)->count(),
            'total_categories'  => Category::count(),
            'total_quizzes'     => QuizQuestion::count(),
            'total_completions' => LessonCompletion::count(),
            'total_quiz_results'=> QuizResult::count(),
            'total_media'       => class_exists(\App\Models\MediaAsset::class) ? MediaAsset::count() : 0,
        ];

        // ── Quiz analytics ────────────────────────────────────────────
        $correctAnswers   = QuizResult::where('benar', 1)->count();
        $incorrectAnswers = QuizResult::where('benar', 0)->count();
        $successRate      = $stats['total_quiz_results'] > 0
            ? round(($correctAnswers / $stats['total_quiz_results']) * 100, 1)
            : 0;

        // ── 7-day activity chart (lesson completions per day) ─────────
        $last7Days = collect(range(6, 0))->map(fn($d) => Carbon::today()->subDays($d));

        $completionsByDay = LessonCompletion::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::today()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        $quizByDay = QuizResult::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::today()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartLabels     = $last7Days->map(fn($d) => $d->format('d M'))->values()->toArray();
        $chartCompletions = $last7Days->map(fn($d) => $completionsByDay->get($d->toDateString(), 0))->values()->toArray();
        $chartQuizzes    = $last7Days->map(fn($d) => $quizByDay->get($d->toDateString(), 0))->values()->toArray();

        // ── Top 5 popular lessons (by completions) ────────────────────
        $popularLessons = Lesson::withCount('completions')
            ->with('category')
            ->orderByDesc('completions_count')
            ->limit(5)
            ->get();

        // ── Recent activities (latest lesson completions) ──────────────
        $recentActivities = LessonCompletion::with(['child', 'lesson'])
            ->latest()
            ->limit(8)
            ->get();

        // ── Category distribution ─────────────────────────────────────
        $categoryStats = Category::withCount('lessons')
            ->ordered()
            ->get();

        // ── Age distribution ──────────────────────────────────────────
        $lessonsByAge = Lesson::selectRaw('kategori_usia, COUNT(*) as total')
            ->groupBy('kategori_usia')
            ->pluck('total', 'kategori_usia');

        return view('admin.dashboard', compact(
            'stats',
            'successRate',
            'correctAnswers',
            'incorrectAnswers',
            'chartLabels',
            'chartCompletions',
            'chartQuizzes',
            'popularLessons',
            'recentActivities',
            'categoryStats',
            'lessonsByAge'
        ));
    }

    /**
     * Show the statistics page with extended analytics.
     */
    public function statistik()
    {
        // ── Core stats ───────────────────────────────────────────────────
        $stats = [
            'total_users'          => User::count(),
            'total_parents'        => User::where('role', 'parent')->count(),
            'total_children'       => Child::count(),
            'total_tunanetra'      => Child::where('jenis_disabilitas', 'tunanetra')->count(),
            'total_tunarungu'      => Child::where('jenis_disabilitas', 'tunarungu')->count(),
            'total_lessons'        => Lesson::count(),
            'total_active_lessons' => Lesson::where('aktif', true)->count(),
            'total_categories'     => Category::count(),
            'total_quizzes'        => QuizQuestion::count(),
            'total_completions'    => LessonCompletion::count(),
            'total_quiz_results'   => QuizResult::count(),
            'total_media'          => class_exists(\App\Models\MediaAsset::class) ? MediaAsset::count() : 0,
        ];

        // ── Quiz analytics ────────────────────────────────────────────
        $correctAnswers   = QuizResult::where('benar', 1)->count();
        $incorrectAnswers = QuizResult::where('benar', 0)->count();
        $successRate      = $stats['total_quiz_results'] > 0
            ? round(($correctAnswers / $stats['total_quiz_results']) * 100, 1)
            : 0;

        // ── 30-day activity chart ─────────────────────────────────────
        $last30Days = collect(range(29, 0))->map(fn($d) => Carbon::today()->subDays($d));

        $completionsByDay30 = LessonCompletion::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::today()->subDays(29)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        $quizByDay30 = QuizResult::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::today()->subDays(29)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        $chart30Labels      = $last30Days->map(fn($d) => $d->format('d M'))->values()->toArray();
        $chart30Completions = $last30Days->map(fn($d) => $completionsByDay30->get($d->toDateString(), 0))->values()->toArray();
        $chart30Quizzes     = $last30Days->map(fn($d) => $quizByDay30->get($d->toDateString(), 0))->values()->toArray();

        // ── Completions by disability type ────────────────────────────
        $completionsByDisability = LessonCompletion::join('children', 'lesson_completions.child_id', '=', 'children.id')
            ->selectRaw('children.jenis_disabilitas, COUNT(*) as total')
            ->groupBy('children.jenis_disabilitas')
            ->pluck('total', 'jenis_disabilitas');

        // ── Completions by age group ──────────────────────────────────
        $completionsByAge = LessonCompletion::join('lessons', 'lesson_completions.lesson_id', '=', 'lessons.id')
            ->selectRaw('lessons.kategori_usia, COUNT(*) as total')
            ->groupBy('lessons.kategori_usia')
            ->pluck('total', 'kategori_usia');

        // ── Quiz success rate per category ────────────────────────────
        $categoryStats = Category::withCount('lessons')->ordered()->get()->map(function ($cat) {
            $total   = QuizResult::join('quiz_questions', 'quiz_results.quiz_question_id', '=', 'quiz_questions.id')
                ->join('lessons', 'quiz_questions.lesson_id', '=', 'lessons.id')
                ->where('lessons.category_id', $cat->id)->count();
            $correct = QuizResult::join('quiz_questions', 'quiz_results.quiz_question_id', '=', 'quiz_questions.id')
                ->join('lessons', 'quiz_questions.lesson_id', '=', 'lessons.id')
                ->where('lessons.category_id', $cat->id)->where('quiz_results.benar', 1)->count();
            $cat->quiz_total   = $total;
            $cat->quiz_correct = $correct;
            $cat->quiz_rate    = $total > 0 ? round($correct / $total * 100, 1) : 0;
            return $cat;
        });

        // ── Top 5 lessons by completions ──────────────────────────────
        $topLessons = Lesson::withCount('completions')
            ->with('category')
            ->orderByDesc('completions_count')
            ->limit(5)->get();

        // ── Bottom 5 lessons (least engagement, active only) ──────────
        $bottomLessons = Lesson::withCount('completions')
            ->with('category')
            ->where('aktif', true)
            ->orderBy('completions_count')
            ->limit(5)->get();

        // ── Lessons by world type ──────────────────────────────────────
        $lessonsByWorld = Lesson::selectRaw('tipe_dunia, COUNT(*) as total')
            ->groupBy('tipe_dunia')
            ->pluck('total', 'tipe_dunia');

        // ── Age distribution of lessons ───────────────────────────────
        $lessonsByAge = Lesson::selectRaw('kategori_usia, COUNT(*) as total')
            ->groupBy('kategori_usia')
            ->pluck('total', 'kategori_usia');

        return view('admin.statistik', compact(
            'stats',
            'successRate', 'correctAnswers', 'incorrectAnswers',
            'chart30Labels', 'chart30Completions', 'chart30Quizzes',
            'completionsByDisability',
            'completionsByAge',
            'categoryStats',
            'topLessons', 'bottomLessons',
            'lessonsByWorld', 'lessonsByAge'
        ));
    }
}
