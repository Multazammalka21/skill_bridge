<?php

namespace App\Repositories\Contracts;

interface QuizRepositoryInterface extends RepositoryInterface
{
    /**
     * Get quiz questions by lesson ID.
     */
    public function getByLesson(int $lessonId);

    /**
     * Get quiz questions by world type (tunanetra/tunarungu mapped to audio/visual).
     */
    public function getByWorldType(string $tipeDunia);

    /**
     * Get quiz questions with pagination for admin.
     */
    public function getPaginated(int $perPage = 15, array $filters = []);
}
