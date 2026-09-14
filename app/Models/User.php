<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Bohaterowie tego użytkownika we wszystkich kampaniach — po jednym na kampanię.
     * Do bohatera w konkretnej kampanii użyj heroInCampaign() / CurrentCampaign.
     */
    public function heroes(): HasMany
    {
        return $this->hasMany(Hero::class);
    }

    public function heroInCampaign(?int $campaignId): ?Hero
    {
        if ($campaignId === null) {
            return null;
        }

        return $this->heroes()->where('campaign_id', $campaignId)->first();
    }

    public function campaignMemberships(): HasMany
    {
        return $this->hasMany(CampaignMember::class);
    }
}
