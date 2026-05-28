<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Lesson;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard with summary statistics.
     */
    public function index(Request $request)
    {
        $stats = [
            'total_users'     => User::count(),
            'total_parents'   => User::where('role', 'parent')->count(),
            'total_children'  => Child::count(),
            'total_tunanetra' => Child::where('jenis_disabilitas', 'tunanetra')->count(),
            'total_tunarungu' => Child::where('jenis_disabilitas', 'tunarungu')->count(),
            'total_lessons'   => Lesson::count(),
            'total_quizzes'   => QuizQuestion::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
