<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Lesson;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display list of categories.
     */
    public function index()
    {
        $categories = Category::withCount('lessons')
            ->ordered()
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show form to create a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a new category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
            'ikon'      => 'required|string|max:10',
            'warna'     => 'required|string|max:20',
            'urutan'    => 'required|integer|min:0',
            'aktif'     => 'boolean',
        ]);

        $validated['aktif'] = $request->boolean('aktif', true);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Show form to edit a category.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update a category.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
            'ikon'      => 'required|string|max:10',
            'warna'     => 'required|string|max:20',
            'urutan'    => 'required|integer|min:0',
            'aktif'     => 'boolean',
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Delete a category.
     */
    public function destroy(Category $category)
    {
        // Prevent deletion if there are associated lessons
        if ($category->lessons()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki ' . $category->lessons()->count() . ' materi pembelajaran.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
