<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Asset extends Model
{
    protected $fillable = ['name', 'type', 'file_path'];

    protected $appends = ['file_url'];

    public const array TYPES = ['map', 'token', 'image'];

    public function getFileUrlAttribute(): string
    {
        return Storage::disk(config('filesystems.media'))->url($this->file_path);
    }
}
