<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'category_id',
        'judul',
        'deskripsi',
        'tipe_dunia',
        'kategori_usia',
        'urutan',
        'prerequisite_lesson_id',
        'gambar',
        'animasi_lottie',
        'efek_suara',
        'teks_narasi',
        'teks_keterangan',
        'konten_tipe',
        'audio_story_url',
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
     * Get the category this lesson belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the prerequisite lesson (for learning path unlock).
     */
    public function prerequisite(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'prerequisite_lesson_id');
    }

    /**
     * Lessons that require THIS lesson as prerequisite.
     */
    public function dependents(): HasMany
    {
        return $this->hasMany(Lesson::class, 'prerequisite_lesson_id');
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
     * Scope: filter by age category.
     */
    public function scopeForAge($query, string $kategoriUsia)
    {
        return $query->where('kategori_usia', $kategoriUsia);
    }

    /**
     * Return only non-media fields for listing (lazy load support).
     */
    public function scopeListing($query)
    {
        return $query->select([
            'id', 'judul', 'deskripsi', 'tipe_dunia', 'kategori_usia',
            'urutan', 'durasi_menit', 'aktif',
            'created_at', 'updated_at',
        ]);
    }
}
