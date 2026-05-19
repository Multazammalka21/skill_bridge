<?php

namespace App\Http\Controllers;

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
            'lessonCompletions',
            'quizResults',
            'studySessions',
        ])->get();

        return view('parent.dashboard', compact('user', 'children'));
    }
}
