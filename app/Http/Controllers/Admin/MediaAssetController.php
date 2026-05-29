<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaAssetController extends Controller
{
    // Allowed mime types per type
    protected array $allowedTypes = [
        'audio'  => ['mp3', 'wav', 'ogg', 'm4a', 'aac'],
        'image'  => ['jpg', 'jpeg', 'png', 'webp', 'svg'],
        'gif'    => ['gif'],
        'lottie' => ['json'],
    ];

    /**
     * Display the media library.
     */
    public function index(Request $request)
    {
        $tipe  = $request->get('tipe');
        $query = MediaAsset::with('uploader')->latest();

        if ($tipe) {
            $query->where('tipe', $tipe);
        }

        $assets = $query->paginate(24)->withQueryString();
        $counts = [
            'all'    => MediaAsset::count(),
            'audio'  => MediaAsset::where('tipe', 'audio')->count(),
            'image'  => MediaAsset::where('tipe', 'image')->count(),
            'gif'    => MediaAsset::where('tipe', 'gif')->count(),
            'lottie' => MediaAsset::where('tipe', 'lottie')->count(),
        ];

        return view('admin.media.index', compact('assets', 'counts', 'tipe'));
    }

    /**
     * Upload a new media asset.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file'        => 'required|file|max:20480', // 20MB max
            'nama'        => 'required|string|max:200',
            'keterangan'  => 'nullable|string|max:500',
        ]);

        $file      = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $mime      = $file->getMimeType();

        // Detect type from extension
        $tipe = $this->detectType($extension);

        if (! $tipe) {
            return back()->with('error', "Format file .{$extension} tidak didukung. Gunakan: MP3/WAV/OGG (audio), JPG/PNG/WebP (gambar), GIF, atau JSON (Lottie).");
        }

        // Determine storage folder
        $folder    = "media/{$tipe}";
        $path      = $file->store($folder, 'public');
        $url       = '/storage/' . $path;
        $fileSize  = $file->getSize();

        MediaAsset::create([
            'nama'         => $request->nama,
            'tipe'         => $tipe,
            'path'         => $path,
            'url'          => $url,
            'ukuran_bytes' => $fileSize,
            'mime_type'    => $mime,
            'uploaded_by'  => Auth::id(),
            'keterangan'   => $request->keterangan,
        ]);

        return back()->with('success', 'Aset media "' . $request->nama . '" berhasil diupload.');
    }

    /**
     * Delete a media asset.
     */
    public function destroy(MediaAsset $asset)
    {
        // Remove from storage
        Storage::disk('public')->delete($asset->path);

        $name = $asset->nama;
        $asset->delete();

        return back()->with('success', 'Aset "' . $name . '" berhasil dihapus.');
    }

    /**
     * Detect media type from file extension.
     */
    private function detectType(string $extension): ?string
    {
        foreach ($this->allowedTypes as $tipe => $extensions) {
            if (in_array($extension, $extensions)) {
                return $tipe;
            }
        }
        return null;
    }
}
