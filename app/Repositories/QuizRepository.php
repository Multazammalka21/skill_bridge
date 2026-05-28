<?php

namespace App\Repositories;

use App\Models\QuizQuestion;
use App\Repositories\Contracts\QuizRepositoryInterface;

class QuizRepository implements QuizRepositoryInterface
{
    public function getAll()
    {
        return QuizQuestion::with('lesson')->get();
    }

    public function findById(int $id)
    {
        return QuizQuestion::with('lesson')->findOrFail($id);
    }

    public function create(array $data)
    {
        return QuizQuestion::create($data);
    }

    public function update(int $id, array $data)
    {
        $question = QuizQuestion::findOrFail($id);
        $question->update($data);
        return $question;
    }

    public function delete(int $id): bool
    {
        return QuizQuestion::findOrFail($id)->delete();
    }

    public function getByLesson(int $lessonId)
    {
        return QuizQuestion::where('lesson_id', $lessonId)->get();
    }

    public function getByWorldType(string $tipeDunia)
    {
        return QuizQuestion::whereHas('lesson', function ($query) use ($tipeDunia) {
            $query->where('tipe_dunia', $tipeDunia);
        })->with('lesson')->get();
    }

    public function getPaginated(int $perPage = 15, array $filters = [])
    {
        $query = QuizQuestion::with('lesson');

        if (! empty($filters['lesson_id'])) {
            $query->where('lesson_id', $filters['lesson_id']);
        }

        if (! empty($filters['tipe_dunia'])) {
            $query->whereHas('lesson', function ($q) use ($filters) {
                $q->where('tipe_dunia', $filters['tipe_dunia']);
            });
        }

        if (! empty($filters['kategori_usia'])) {
            $query->whereHas('lesson', function ($q) use ($filters) {
                $q->where('kategori_usia', $filters['kategori_usia']);
            });
        }

        if (! empty($filters['tipe'])) {
            $query->where('tipe', $filters['tipe']);
        }

        return $query->latest()->paginate($perPage);
    }
}
