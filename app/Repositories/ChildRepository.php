<?php

namespace App\Repositories;

use App\Models\Child;
use App\Repositories\Contracts\ChildRepositoryInterface;

class ChildRepository implements ChildRepositoryInterface
{
    public function getAll()
    {
        return Child::all();
    }

    public function findById(int $id)
    {
        return Child::findOrFail($id);
    }

    public function create(array $data)
    {
        return Child::create($data);
    }

    public function update(int $id, array $data)
    {
        $child = Child::findOrFail($id);
        $child->update($data);
        return $child;
    }

    public function delete(int $id): bool
    {
        return Child::findOrFail($id)->delete();
    }

    public function getByParent(int $userId)
    {
        return Child::where('user_id', $userId)->get();
    }

    public function getByDisabilityType(string $type)
    {
        return Child::where('jenis_disabilitas', $type)->get();
    }
}
