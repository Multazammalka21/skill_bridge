<?php

namespace App\Repositories;

use App\Models\Lesson;
use App\Repositories\Contracts\LessonRepositoryInterface;

class LessonRepository implements LessonRepositoryInterface
{
    public function getAll()
    {
        return Lesson::orderBy('urutan')->get();
    }

    public function findById(int $id)
    {
        return Lesson::findOrFail($id);
    }

    public function create(array $data)
    {
        return Lesson::create($data);
    }

    public function update(int $id, array $data)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->update($data);
        return $lesson;
    }

    public function delete(int $id): bool
    {
        return Lesson::findOrFail($id)->delete();
    }

    public function getByWorld(string $tipeDunia)
    {
        return Lesson::active()->forWorld($tipeDunia)->orderBy('urutan')->get();
    }

    public function getByAge(string $kategoriUsia)
    {
        return Lesson::active()->forAge($kategoriUsia)->orderBy('urutan')->get();
    }

    public function getActive()
    {
        return Lesson::active()->orderBy('urutan')->get();
    }
}
