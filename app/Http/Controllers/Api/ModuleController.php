<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Repositories\ChildContentRepository;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    protected $repository;

    public function __construct(ChildContentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get modules (lessons) filtered by the child's profile from token/session.
     */
    public function index(Request $request)
    {
        // Dalam implementasi nyata, ID anak bisa didapat dari payload token yang diset saat milih profile,
        // atau dikirim via header/parameter.
        // Di sini kita asumsikan dikirim via query parameter ?child_id=X 
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id'
        ]);

        $child = Child::findOrFail($validated['child_id']);

        // Pastikan anak tersebut milik user yang login
        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $modules = $this->repository->getModulesForChild($child);

        return response()->json([
            'usia' => \Carbon\Carbon::parse($child->tanggal_lahir)->age,
            'jenis_disabilitas' => $child->jenis_disabilitas,
            'modules' => $modules,
        ]);
    }
}
