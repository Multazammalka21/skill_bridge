<?php

namespace App\Services;

use App\Models\Child;
use App\Models\Badge;
use Carbon\Carbon;

class GamificationService
{
    /**
     * Calculate stars earned (0 to 3) based on score.
     */
    public static function calculateStars(int $score): int
    {
        if ($score >= 100) {
            return 3;
        } elseif ($score >= 70) {
            return 2;
        } elseif ($score >= 40) {
            return 1;
        }
        return 0;
    }

    /**
     * Check and award eligible badges to a child.
     * Returns an array of newly earned Badge models.
     */
    public static function checkAndAwardBadges(Child $child): array
    {
        // Get already earned badge IDs
        $earnedBadgeIds = $child->badges()->pluck('badges.id')->toArray();

        // Get all available badges not yet earned
        $availableBadges = Badge::whereNotIn('id', $earnedBadgeIds)->get();
        $newlyEarned = [];

        if ($availableBadges->isEmpty()) {
            return $newlyEarned;
        }

        // Gather metrics
        $totalQuizzes = $child->quizResults()->count();
        $perfectQuizzes = $child->quizResults()->where('skor', 100)->count();

        // Calculate maximum daily streak of quiz completions
        $streak = self::calculateStreak($child);

        foreach ($availableBadges as $badge) {
            $isEligible = false;

            switch ($badge->syarat_tipe) {
                case 'quiz_count':
                    if ($totalQuizzes >= $badge->syarat_nilai) {
                        $isEligible = true;
                    }
                    break;
                case 'perfect_score':
                    if ($perfectQuizzes >= $badge->syarat_nilai) {
                        $isEligible = true;
                    }
                    break;
                case 'streak':
                    if ($streak >= $badge->syarat_nilai) {
                        $isEligible = true;
                    }
                    break;
            }

            if ($isEligible) {
                $child->badges()->attach($badge->id, ['earned_at' => Carbon::now()]);
                $newlyEarned[] = $badge;
            }
        }

        return $newlyEarned;
    }

    /**
     * Calculate current daily learning streak.
     */
    public static function calculateStreak(Child $child): int
    {
        $dates = $child->quizResults()
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d));

        if ($dates->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Check if child has completed a quiz today or yesterday to continue the streak
        if ($dates->first()->eq($today) || $dates->first()->eq($yesterday)) {
            $streak = 1;
            for ($i = 0; $i < $dates->count() - 1; $i++) {
                $diff = $dates[$i]->diffInDays($dates[$i + 1]);
                if ($diff === 1) {
                    $streak++;
                } elseif ($diff > 1) {
                    break;
                }
            }
        }

        return $streak;
    }
}
