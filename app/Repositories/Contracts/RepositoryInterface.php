<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    /**
     * Get all records.
     */
    public function getAll();

    /**
     * Find a record by ID.
     */
    public function findById(int $id);

    /**
     * Create a new record.
     */
    public function create(array $data);

    /**
     * Update a record by ID.
     */
    public function update(int $id, array $data);

    /**
     * Delete a record by ID.
     */
    public function delete(int $id): bool;
}
