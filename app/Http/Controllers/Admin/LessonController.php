<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    /**
     * Display paginated lessons with filters.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['category_id', 'tipe_dunia', 'kategori_usia', 'aktif', 'search']);

        $query = Lesson::with('category')
            ->when($filters['category_id'] ?? null, fn($q, $v) => $q->where('category_id', $v))
            ->when($filters['tipe_dunia'] ?? null, fn($q, $v) => $q->where('tipe_dunia', $v))
            ->when($filters['kategori_usia'] ?? null, fn($q, $v) => $q->where('kategori_usia', $v))
            ->when(isset($filters['aktif']) && $filters['aktif'] !== '', fn($q) => $q->where('aktif', $filters['aktif']))
            ->when($filters['search'] ?? null, fn($q, $v) => $q->where('judul', 'like', "%{$v}%"))
            ->orderBy('urutan')
            ->orderBy('judul');

        $lessons    = $query->paginate(15)->withQueryString();
        $categories = Category::ordered()->get();

        return view('admin.lessons.index', compact('lessons', 'categories', 'filters'));
    }

    /**
     * Show form to create a new lesson.
     */
    public function create()
    {
        $categories = Category::active()->ordered()->get();
        $allLessons = Lesson::orderBy('judul')->get(); // For prerequisite picker

        return view('admin.lessons.create', compact('categories', 'allLessons'));
    }

    /**
     * Store a new lesson.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'             => 'nullable|exists:categories,id',
            'judul'                   => 'required|string|max:200',
            'deskripsi'               => 'nullable|string|max:1000',
            'tipe_dunia'              => 'required|in:audio,visual',
            'kategori_usia'           => 'required|in:5-7,8-10',
            'urutan'                  => 'required|integer|min:0',
            'prerequisite_lesson_id'  => 'nullable|exists:lessons,id',
            'konten_tipe'             => 'nullable|in:audio_story,gambar_interaktif,animasi_lottie,teks,campuran',
            'teks_narasi'             => 'nullable|string',
            'teks_keterangan'         => 'nullable|string',
            'animasi_lottie'          => 'nullable|string|max:500',
            'audio_story_url'         => 'nullable|string|max:500',
            'durasi_menit'            => 'required|integer|min:1|max:120',
            'aktif'                   => 'boolean',
            // File uploads
            'gambar_file'             => 'nullable|image|max:3072',
            'efek_suara_file'         => 'nullable|mimes:mp3,wav,ogg,m4a|max:10240',
        ]);

        // Handle image upload
        if ($request->hasFile('gambar_file')) {
            $path = $request->file('gambar_file')->store('lessons/images', 'public');
            $validated['gambar'] = '/storage/' . $path;
        }

        // Handle audio upload
        if ($request->hasFile('efek_suara_file')) {
            $path = $request->file('efek_suara_file')->store('lessons/audio', 'public');
            $validated['efek_suara'] = '/storage/' . $path;
        }

        $validated['aktif'] = $request->boolean('aktif', true);

        // Remove file keys (not real columns)
        unset($validated['gambar_file'], $validated['efek_suara_file']);

        Lesson::create($validated);

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Materi pembelajaran berhasil ditambahkan.');
    }

    /**
     * Show form to edit a lesson.
     */
    public function edit(Lesson $lesson)
    {
        $categories = Category::active()->ordered()->get();
        $allLessons = Lesson::where('id', '!=', $lesson->id)->orderBy('judul')->get();

        return view('admin.lessons.edit', compact('lesson', 'categories', 'allLessons'));
    }

    /**
     * Update a lesson.
     */
    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'category_id'             => 'nullable|exists:categories,id',
            'judul'                   => 'required|string|max:200',
            'deskripsi'               => 'nullable|string|max:1000',
            'tipe_dunia'              => 'required|in:audio,visual',
            'kategori_usia'           => 'required|in:5-7,8-10',
            'urutan'                  => 'required|integer|min:0',
            'prerequisite_lesson_id'  => 'nullable|exists:lessons,id',
            'konten_tipe'             => 'nullable|in:audio_story,gambar_interaktif,animasi_lottie,teks,campuran',
            'teks_narasi'             => 'nullable|string',
            'teks_keterangan'         => 'nullable|string',
            'animasi_lottie'          => 'nullable|string|max:500',
            'audio_story_url'         => 'nullable|string|max:500',
            'durasi_menit'            => 'required|integer|min:1|max:120',
            'aktif'                   => 'boolean',
            'gambar_file'             => 'nullable|image|max:3072',
            'efek_suara_file'         => 'nullable|mimes:mp3,wav,ogg,m4a|max:10240',
        ]);

        // Handle image upload (replace old)
        if ($request->hasFile('gambar_file')) {
            if ($lesson->gambar) {
                Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $lesson->gambar), '/'));
            }
            $path = $request->file('gambar_file')->store('lessons/images', 'public');
            $validated['gambar'] = '/storage/' . $path;
        } else {
            $validated['gambar'] = $lesson->gambar;
        }

        // Handle audio upload (replace old)
        if ($request->hasFile('efek_suara_file')) {
            if ($lesson->efek_suara) {
                Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $lesson->efek_suara), '/'));
            }
            $path = $request->file('efek_suara_file')->store('lessons/audio', 'public');
            $validated['efek_suara'] = '/storage/' . $path;
        } else {
            $validated['efek_suara'] = $lesson->efek_suara;
        }

        // Handle clear media flags
        if ($request->boolean('hapus_gambar') && $lesson->gambar) {
            Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $lesson->gambar), '/'));
            $validated['gambar'] = null;
        }
        if ($request->boolean('hapus_audio') && $lesson->efek_suara) {
            Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $lesson->efek_suara), '/'));
            $validated['efek_suara'] = null;
        }

        $validated['aktif'] = $request->boolean('aktif');
        unset($validated['gambar_file'], $validated['efek_suara_file']);

        $lesson->update($validated);

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Materi pembelajaran berhasil diperbarui.');
    }

    /**
     * Delete a lesson.
     */
    public function destroy(Lesson $lesson)
    {
        // Clean up stored files
        if ($lesson->gambar) {
            Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $lesson->gambar), '/'));
        }
        if ($lesson->efek_suara) {
            Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $lesson->efek_suara), '/'));
        }

        $lesson->delete();

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Materi pembelajaran berhasil dihapus.');
    }

    /**
     * Reorder lessons via AJAX (drag and drop).
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'ordered_ids'   => 'required|array',
            'ordered_ids.*' => 'integer|exists:lessons,id',
        ]);

        foreach ($request->ordered_ids as $urutan => $lessonId) {
            Lesson::where('id', $lessonId)->update(['urutan' => $urutan]);
        }

        return response()->json(['message' => 'Urutan berhasil disimpan.']);
    }
}
