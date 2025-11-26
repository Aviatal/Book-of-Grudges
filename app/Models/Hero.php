<?php

namespace App\Models;

use App\Exceptions\NotEnoughMoneyException;
use App\Helpers\Traits\BelongsToManyKeyBy;
use App\Helpers\Traits\HasManyKeyBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Hero extends Model
{
    use HasFactory, HasManyKeyBy, BelongsToManyKeyBy;
    protected $guarded = ['id'];
    const int GOLD_CROWS_TO_BRASS_ENCOUNTER = 240;
    const int SILVER_SHILLINGS_TO_BRASS_ENCOUNTER = 12;

    /**
     * @throws NotEnoughMoneyException
     */
    public function pay(int $price): void
    {
        $totalHeroWealth = $this->getWealthInBrass();
        if ($totalHeroWealth < $price) {
            throw new NotEnoughMoneyException();
        }
        $wealthLeft = $totalHeroWealth - $price;
        $goldCrowns = floor($wealthLeft / self::GOLD_CROWS_TO_BRASS_ENCOUNTER);
        $wealthLeft %= self::GOLD_CROWS_TO_BRASS_ENCOUNTER;

        $silverShillings = floor($wealthLeft / self::SILVER_SHILLINGS_TO_BRASS_ENCOUNTER);
        $wealthLeft %= self::SILVER_SHILLINGS_TO_BRASS_ENCOUNTER;

        $brassPennies = $wealthLeft;

        $this->update([
            'gold_crowns' => $goldCrowns,
            'silver_shillings' => $silverShillings,
            'brass_pennies' => $brassPennies,
        ]);
    }

    private function getWealthInBrass(): int
    {
        return ($this->gold_crowns * self::GOLD_CROWS_TO_BRASS_ENCOUNTER) +
            ($this->silver_shillings * self::SILVER_SHILLINGS_TO_BRASS_ENCOUNTER) +
            $this->brass_pennies;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currentProfession(): BelongsTo
    {
        return $this->belongsTo(Profession::class, 'current_profession_id');
    }

    public function previousProfession(): BelongsTo
    {
        return $this->belongsTo(Profession::class, 'previous_profession_id');
    }

    public function description(): HasOne
    {
        return $this->hasOne(HeroDescription::class);
    }

    public function characteristic(): BelongsToMany
    {
        return $this->belongsToManyKeyBy(
            'short_name',
            Characteristic::class,
            'hero_characteristics',
            'hero_id',
            'characteristic_id',
            'id',
            'id',
            'characteristic'
        )
            ->withPivot('start_value', 'advancement', 'profession_characteristics.available_advancement as available_advancement')
            ->leftJoin('profession_characteristics', function ($join) {
                $join->on('hero_characteristics.characteristic_id', '=', 'profession_characteristics.characteristic_id')
                    ->whereRaw('profession_characteristics.profession_id = (SELECT current_profession_id FROM heroes WHERE heroes.id = hero_characteristics.hero_id)');
            });
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(HeroInventory::class);
    }

    public function weapons(): BelongsToMany
    {
        return $this->belongsToMany(Weapon::class, 'hero_weapons', 'hero_id', 'weapon_id')->withPivot(['additional_weapon_name']);
    }
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withPivot(['id', 'additional_skill_name', 'hurdled', 'first_level', 'second_level']);
    }

    public function updateOrCreateSkill($heroSkillId, $data, $action): HeroSkill|bool
    {

        return match ($action) {
            'add' => HeroSkill::create([
                'hero_id' => $data['hero_id'],
                'skill_id' => $data['skill_id'],
                'hurdled' => 1,
                'first_level' => 0,
                'second_level' => 0,
                'additional_skill_name' => null
            ]),
            'remove' => HeroSkill::where('id', $heroSkillId)->firstOrFail()->delete(),
            default => HeroSkill::updateOrCreate(['id' => $heroSkillId], $data),
        };
    }

    public function talents(): BelongsToMany
    {
        return $this->belongsToMany(Talent::class, 'hero_talent', 'hero_id', 'talent_id')->withPivot(['additional_talent_name']);
    }

    public function armors(): BelongsToMany
    {
        return $this->belongsToMany(Armor::class, 'hero_armors', 'hero_id', 'armor_id');
    }

    public function coldWeapons(): BelongsToMany
    {
        return $this->weapons()->where('is_ranged', 0);
    }

    public function rangedWeapons(): BelongsToMany
    {
        return $this->weapons()->where('is_ranged', 1);
    }

    public function scopeOnlyActiveUsers($query)
    {
        return $query->whereRelation('user', 'is_active', true);
    }
}
