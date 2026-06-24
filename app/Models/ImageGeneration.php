<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImageGeneration extends Model
{
    protected $fillable = [
        'user_id',
        'prompt',
        'negative_prompt',
        'status',
        'prediction_id',
        'image_url',
        'error_message',
    ];

    // ── Status Constants ──────────────────────────────────────────────────────
    const STATUS_PENDING    = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCEEDED  = 'succeeded';
    const STATUS_FAILED     = 'failed';

    // ── Relationships ─────────────────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    public function isPending(): bool    { return $this->status === self::STATUS_PENDING; }
    public function isProcessing(): bool { return $this->status === self::STATUS_PROCESSING; }
    public function isSucceeded(): bool  { return $this->status === self::STATUS_SUCCEEDED; }
    public function isFailed(): bool     { return $this->status === self::STATUS_FAILED; }
}
