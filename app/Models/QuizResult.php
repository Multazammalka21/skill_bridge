<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizResult extends Model
{
    protected $fillable = [
        'child_id',
        'lesson_id',
        'quiz_question_id',
        'jawaban_anak',
        'benar',
        'skor',
        'percobaan',
        'bintang',
        'waktu_detik',
    ];

    protected function casts(): array
    {
        return [
            'benar' => 'boolean',
        ];
    }

    /**
     * Get the child who took this quiz.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get the lesson for this result.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the quiz question for this result.
     */
    public function quizQuestion(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class);
    }
}
