<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeManagementController extends Controller
{
    /**
     * Display a listing of badges.
     */
    public function index()
    {
        $badges = Badge::orderBy('nama')->get();
        return view('admin.badges.index', compact('badges'));
    }

    /**
     * Show form to create a new badge.
     */
    public function create()
    {
        return view('admin.badges.create');
    }

    /**
     * Store a newly created badge.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:100|unique:badges,nama',
            'deskripsi'    => 'required|string|max:255',
            'ikon'         => 'required|string|max:50', // Emoji or icon name
            'syarat_tipe'  => 'required|string|in:quiz_count,perfect_score,streak',
            'syarat_nilai' => 'required|integer|min:1',
        ]);

        Badge::create($validated);

        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge pencapaian berhasil ditambahkan.');
    }

    /**
     * Show form to edit an existing badge.
     */
    public function edit(int $id)
    {
        $badge = Badge::findOrFail($id);
        return view('admin.badges.edit', compact('badge'));
    }

    /**
     * Update an existing badge.
     */
    public function update(Request $request, int $id)
    {
        $badge = Badge::findOrFail($id);

        $validated = $request->validate([
            'nama'         => 'required|string|max:100|unique:badges,nama,' . $id,
            'deskripsi'    => 'required|string|max:255',
            'ikon'         => 'required|string|max:50',
            'syarat_tipe'  => 'required|string|in:quiz_count,perfect_score,streak',
            'syarat_nilai' => 'required|integer|min:1',
        ]);

        $badge->update($validated);

        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge pencapaian berhasil diperbarui.');
    }

    /**
     * Delete an existing badge.
     */
    public function destroy(int $id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();

        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge pencapaian berhasil dihapus.');
    }
}
