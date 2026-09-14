<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Asset extends Model
{
    protected $fillable = ['name', 'type', 'file_path', 'campaign_id'];

    protected $appends = ['file_url'];

    public const array TYPES = ['map', 'token', 'image'];

    public function getFileUrlAttribute(): string
    {
        return Storage::disk(config('filesystems.media'))->url($this->file_path);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
