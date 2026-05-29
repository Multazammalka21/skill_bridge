<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'ikon',
        'warna',
        'urutan',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    /**
     * Get all lessons in this category.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('urutan');
    }

    /**
     * Scope: only active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope: ordered by urutan.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('nama');
    }

    /**
     * Count active lessons in this category.
     */
    public function activeLessonsCount(): int
    {
        return $this->lessons()->where('aktif', true)->count();
    }
}
