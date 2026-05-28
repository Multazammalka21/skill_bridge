<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Repositories\Contracts\ChildRepositoryInterface;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    protected ChildRepositoryInterface $childRepo;

    public function __construct(ChildRepositoryInterface $childRepo)
    {
        $this->childRepo = $childRepo;
    }

    /**
     * List children for the authenticated parent.
     */
    public function index(Request $request)
    {
        $children = $this->childRepo->getByParent($request->user()->id);

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

        $validated['user_id'] = $request->user()->id;
        $child = $this->childRepo->create($validated);

        return response()->json([
            'message' => 'Anak berhasil ditambahkan.',
            'child' => $child,
        ], 201);
    }

    /**
     * Show a specific child.
     */
    public function show(Request $request, int $id)
    {
        $child = $this->childRepo->findById($id);

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
    public function update(Request $request, int $id)
    {
        $child = $this->childRepo->findById($id);

        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $validated = $request->validate([
            'nama_panggilan' => 'sometimes|string|max:100',
            'tanggal_lahir' => 'sometimes|date',
            'jenis_disabilitas' => 'sometimes|in:tunanetra,tunarungu',
        ]);

        $updatedChild = $this->childRepo->update($id, $validated);

        return response()->json([
            'message' => 'Data anak berhasil diperbarui.',
            'child' => $updatedChild,
        ]);
    }

    /**
     * Delete a child.
     */
    public function destroy(Request $request, int $id)
    {
        $child = $this->childRepo->findById($id);

        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $this->childRepo->delete($id);

        return response()->json([
            'message' => 'Data anak berhasil dihapus.',
        ]);
    }
}
