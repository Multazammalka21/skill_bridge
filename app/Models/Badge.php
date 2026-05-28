<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'ikon',
        'syarat_tipe',
        'syarat_nilai',
    ];

    /**
     * Get the children who have earned this badge.
     */
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(Child::class, 'child_badges')
            ->withPivot('earned_at')
            ->withTimestamps();
    }
}
