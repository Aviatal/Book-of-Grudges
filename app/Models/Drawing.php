<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Drawing extends Model
{
    protected $casts = [
        'data' => 'array',
    ];
    protected $fillable = ['type', 'layer', 'data', 'campaign_id'];

    public const array DRAWING_TYPES = ['pen', 'rect', 'circle', 'image', 'fog', 'fog_meta', 'fog_hide'];
    public const array LAYERS = ['map', 'gm'];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
