<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroDescription extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }
}
