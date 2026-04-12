<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $guarded = ['id'];
    protected $appends = ['image_url'];

    public function hero(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }

    public function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                return asset('/tokens/' . $this->image);
            }
        );
    }
}
