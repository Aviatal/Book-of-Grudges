<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drawing extends Model
{
    protected $casts = [
        'data' => 'array',
    ];
    protected $fillable = ['id', 'data', 'type'];
    const array DRAWING_TYPES = ['pen', 'rect', 'circle'];
}
