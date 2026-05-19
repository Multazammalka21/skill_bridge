<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    /**
     * List children for the authenticated parent.
     */
    public function index(Request $request)
    {
        $children = $request->user()->children;

        return response()->json([
            'children' => $children,
        ]);
    }

    /**
     * Add a child to the authenticated parent.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_panggilan' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_disabilitas' => 'required|in:tunanetra,tunarungu',
        ]);

        $child = $request->user()->children()->create($validated);

        return response()->json([
            'message' => 'Anak berhasil ditambahkan.',
            'child' => $child,
        ], 201);
    }

    /**
     * Show a specific child.
     */
    public function show(Request $request, Child $child)
    {
        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        return response()->json([
            'child' => $child->load(['lessonCompletions', 'quizResults']),
        ]);
    }

    /**
     * Update a child.
     */
    public function update(Request $request, Child $child)
    {
        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $validated = $request->validate([
            'nama_panggilan' => 'sometimes|string|max:100',
            'tanggal_lahir' => 'sometimes|date',
            'jenis_disabilitas' => 'sometimes|in:tunanetra,tunarungu',
        ]);

        $child->update($validated);

        return response()->json([
            'message' => 'Data anak berhasil diperbarui.',
            'child' => $child,
        ]);
    }

    /**
     * Delete a child.
     */
    public function destroy(Request $request, Child $child)
    {
        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $child->delete();

        return response()->json([
            'message' => 'Data anak berhasil dihapus.',
        ]);
    }
}
