<?php

namespace App\Repositories\Contracts;

interface ChildRepositoryInterface extends RepositoryInterface
{
    /**
     * Get children by parent user ID.
     */
    public function getByParent(int $userId);

    /**
     * Get children by disability type.
     */
    public function getByDisabilityType(string $type);
}
