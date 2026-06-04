<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Token extends Model
{
    protected $guarded = ['id'];
    protected $appends = ['image_url'];
    protected $casts = [
        'sheet' => 'array',
    ];

    public function hero(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }

    public function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->image) {
                    return null;
                }
                return Storage::disk(config('filesystems.media'))->url('tokens/' . $this->image);
            }
        );
    }
}
