<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'tipe_dunia',
        'urutan',
        'gambar',
        'animasi_lottie',
        'efek_suara',
        'teks_narasi',
        'teks_keterangan',
        'durasi_menit',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    /**
     * Get quiz questions for this lesson.
     */
    public function quizQuestions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * Get quiz results for this lesson.
     */
    public function quizResults(): HasMany
    {
        return $this->hasMany(QuizResult::class);
    }

    /**
     * Get lesson completions.
     */
    public function completions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    /**
     * Get study sessions for this lesson.
     */
    public function studySessions(): HasMany
    {
        return $this->hasMany(StudySession::class);
    }

    /**
     * Scope: only active lessons.
     */
    public function scopeActive($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope: filter by world type.
     */
    public function scopeForWorld($query, string $tipeDunia)
    {
        return $query->where('tipe_dunia', $tipeDunia);
    }

    /**
     * Return only non-media fields for listing (lazy load support).
     */
    public function scopeListing($query)
    {
        return $query->select([
            'id', 'judul', 'deskripsi', 'tipe_dunia',
            'urutan', 'durasi_menit', 'aktif',
            'created_at', 'updated_at',
        ]);
    }
}
