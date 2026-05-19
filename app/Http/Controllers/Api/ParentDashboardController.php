<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\LessonCompletion;
use App\Models\QuizResult;
use App\Models\StudySession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentDashboardController extends Controller
{
    /**
     * Get overview data for all children of the authenticated parent.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $children = $user->children()->with([
            'lessonCompletions',
            'quizResults',
            'studySessions',
        ])->get();

        $data = $children->map(function (Child $child) {
            return [
                'id' => $child->id,
                'nama_panggilan' => $child->nama_panggilan ?? 'Anak',
                'jenis_disabilitas' => $child->jenis_disabilitas,
                'total_lesson_selesai' => $child->lessonCompletions->count(),
                'rata_rata_skor' => round($child->quizResults->avg('skor') ?? 0, 1),
                'total_waktu_belajar_menit' => round($child->studySessions->sum('durasi_detik') / 60, 1),
            ];
        });

        return response()->json(['children' => $data]);
    }

    /**
     * Get detailed progress data for a specific child.
     * Returns data for Chart.js: lessons completed over time, quiz scores, study time per day.
     */
    public function childProgress(Request $request, Child $child)
    {
        $user = $request->user();

        // Ensure the child belongs to this parent
        if ($child->user_id !== $user->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $days = $request->input('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        // 1. Lessons completed per day
        $lessonsPerDay = LessonCompletion::where('child_id', $child->id)
            ->where('completed_at', '>=', $startDate)
            ->select(DB::raw('DATE(completed_at) as tanggal'), DB::raw('COUNT(*) as jumlah'))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->pluck('jumlah', 'tanggal')
            ->toArray();

        // 2. Average quiz score per day
        $quizScoresPerDay = QuizResult::where('child_id', $child->id)
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as tanggal'), DB::raw('AVG(skor) as rata_skor'))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->mapWithKeys(fn($row) => [$row->tanggal => round($row->rata_skor, 1)])
            ->toArray();

        // 3. Study time per day (in minutes)
        $studyTimePerDay = StudySession::where('child_id', $child->id)
            ->where('started_at', '>=', $startDate)
            ->select(DB::raw('DATE(started_at) as tanggal'), DB::raw('SUM(durasi_detik) as total_detik'))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->mapWithKeys(fn($row) => [$row->tanggal => round($row->total_detik / 60, 1)])
            ->toArray();

        // Fill in missing dates with zeros
        $labels = [];
        $lessonsData = [];
        $scoresData = [];
        $studyTimeData = [];

        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::now()->subDays($days - 1 - $i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('d M');
            $lessonsData[] = $lessonsPerDay[$date] ?? 0;
            $scoresData[] = $quizScoresPerDay[$date] ?? 0;
            $studyTimeData[] = $studyTimePerDay[$date] ?? 0;
        }

        // Summary stats
        $totalLessons = LessonCompletion::where('child_id', $child->id)->count();
        $avgScore = round(QuizResult::where('child_id', $child->id)->avg('skor') ?? 0, 1);
        $totalStudyMinutes = round(StudySession::where('child_id', $child->id)->sum('durasi_detik') / 60, 1);

        return response()->json([
            'child' => [
                'id' => $child->id,
                'nama_panggilan' => $child->nama_panggilan ?? 'Anak',
                'jenis_disabilitas' => $child->jenis_disabilitas,
            ],
            'summary' => [
                'total_lesson_selesai' => $totalLessons,
                'rata_rata_skor' => $avgScore,
                'total_waktu_belajar_menit' => $totalStudyMinutes,
            ],
            'charts' => [
                'labels' => $labels,
                'lessons_completed' => $lessonsData,
                'quiz_scores' => $scoresData,
                'study_time_minutes' => $studyTimeData,
            ],
        ]);
    }
}
