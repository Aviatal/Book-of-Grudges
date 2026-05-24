<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drawing extends Model
{
    protected $casts = [
        'data' => 'array',
    ];
    protected $fillable = ['type', 'layer', 'data'];

    public const array DRAWING_TYPES = ['pen', 'rect', 'circle', 'image'];
    public const array LAYERS = ['map', 'gm'];
}
