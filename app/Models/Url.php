<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Url extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'original_url',
        'short_code',
        'expires_at',
        'is_active',
        'click_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'click_count' => 'integer',
    ];

    /**
     * Get the user that owns the URL.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the visits for the URL.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(UrlVisit::class);
    }

    /**
     * Check if the URL has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && now()->greaterThan($this->expires_at);
    }

    /**
     * Increment the click count for this URL.
     */
    public function incrementClickCount(): void
    {
        $this->increment('click_count');
    }
}
