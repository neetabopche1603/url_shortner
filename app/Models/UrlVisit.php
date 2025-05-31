<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UrlVisit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url_id',
        'ip_address',
        'user_agent',
        'referrer',
    ];

    /**
     * Get the URL that was visited.
     */
    public function url(): BelongsTo
    {
        return $this->belongsTo(Url::class);
    }
}
