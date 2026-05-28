<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    protected $fillable = [
        'lesson_id',
        'pertanyaan',
        'jawaban_benar',
        'pilihan',
        'tipe',
        'gambar',
        'audio_url',
        'animasi_url',
        'efek_suara_url',
        'poin',
    ];

    protected function casts(): array
    {
        return [
            'pilihan' => 'array',
        ];
    }

    /**
     * Get the lesson this question belongs to.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get all results for this question.
     */
    public function results(): HasMany
    {
        return $this->hasMany(QuizResult::class);
    }
}
