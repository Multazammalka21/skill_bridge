<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Child extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal_lahir',
        'jenis_disabilitas',
        'nama_panggilan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    /**
     * Get the parent user of this child.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all quiz results for this child.
     */
    public function quizResults(): HasMany
    {
        return $this->hasMany(QuizResult::class);
    }

    /**
     * Get all lesson completions for this child.
     */
    public function lessonCompletions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    /**
     * Get all study sessions for this child.
     */
    public function studySessions(): HasMany
    {
        return $this->hasMany(StudySession::class);
    }

    /**
     * Check if this child uses Audio World (tunanetra).
     */
    public function isAudioWorld(): bool
    {
        return $this->jenis_disabilitas === 'tunanetra';
    }

    /**
     * Check if this child uses Visual World (tunarungu).
     */
    public function isVisualWorld(): bool
    {
        return $this->jenis_disabilitas === 'tunarungu';
    }

    /**
     * Get all badges earned by this child.
     */
    public function badges(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'child_badges')
            ->withPivot('earned_at')
            ->withTimestamps();
    }
}
