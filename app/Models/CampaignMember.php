<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignMember extends Model
{
    public const string ROLE_GM = 'gm';
    public const string ROLE_PLAYER = 'player';

    protected $fillable = ['campaign_id', 'user_id', 'role', 'joined_at'];

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isGm(): bool
    {
        return $this->role === self::ROLE_GM;
    }
}
