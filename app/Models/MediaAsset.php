<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaAsset extends Model
{
    protected $fillable = [
        'nama',
        'tipe',
        'path',
        'url',
        'ukuran_bytes',
        'mime_type',
        'uploaded_by',
        'keterangan',
    ];

    /**
     * Uploader user.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get human-readable file size.
     */
    public function getReadableSizeAttribute(): string
    {
        $bytes = $this->ukuran_bytes;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    /**
     * Get icon emoji based on type.
     */
    public function getTipeIkonAttribute(): string
    {
        return match($this->tipe) {
            'audio'  => '🎵',
            'image'  => '🖼️',
            'gif'    => '🎞️',
            'lottie' => '✨',
            default  => '📎',
        };
    }

    /**
     * Scope: filter by type.
     */
    public function scopeOfType($query, string $tipe)
    {
        return $query->where('tipe', $tipe);
    }
}
