<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpUpdate extends Model
{
    protected $table = 'exp_update';

    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }
}
