<?php

namespace App\Repositories\Contracts;

interface LessonRepositoryInterface extends RepositoryInterface
{
    /**
     * Get lessons by world type ('audio' or 'visual').
     */
    public function getByWorld(string $tipeDunia);

    /**
     * Get lessons by age category ('5-7' or '8-10').
     */
    public function getByAge(string $kategoriUsia);

    /**
     * Get only active lessons.
     */
    public function getActive();
}
