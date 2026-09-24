<?php

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\Hero;
use App\Models\HeroInventory;
use App\Models\Message;
use App\Models\Token;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UsersRepository
{
    public function paginate(?string $search, int $perPage = 20): LengthAwarePaginator
    {
        return User::query()
            ->withCount(['heroes', 'campaignMemberships'])
            ->when($search !== null && $search !== '', function ($query) use ($search): void {
                $like = '%' . addcslashes(mb_strtolower($search), '%_\\') . '%';
                $query->where(fn ($q) => $q
                    ->whereRaw('LOWER(name) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(email) LIKE ?', [$like]));
            })
            ->orderBy('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function loadDetails(User $user): User
    {
        return $user->load([
            'campaignMemberships.campaign:id,name',
            'heroes' => fn ($query) => $query->withTrashed()->with(['campaign:id,name', 'currentProfession:id,name']),
        ]);
    }

    /**
     * @param  array{name: string, email: string, is_active: bool, is_superadmin: bool}  $attributes
     */
    public function update(User $user, array $attributes): User
    {
        $user->fill(['name' => $attributes['name'], 'email' => $attributes['email']]);
        // Flagi uprawnień poza $fillable — żeby nie dało się ich ustawić masowym przypisaniem np. z rejestracji.
        $user->forceFill(['is_active' => $attributes['is_active'], 'is_superadmin' => $attributes['is_superadmin']]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user;
    }

    public function ownsCampaigns(User $user): bool
    {
        return Campaign::where('owner_id', $user->id)->exists();
    }

    /**
     * Usuwa użytkownika razem z jego bohaterami (także miękko usuniętymi) i wiadomościami —
     * klucze obce heroes.user_id, messages.user_id, hero_inventory i tokens.hero_id nie mają kaskady.
     */
    public function delete(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $heroIds = Hero::withTrashed()->where('user_id', $user->id)->pluck('id');

            if ($heroIds->isNotEmpty()) {
                HeroInventory::whereIn('hero_id', $heroIds)->delete();
                Token::whereIn('hero_id', $heroIds)->update(['hero_id' => null]);
                Hero::withTrashed()->whereIn('id', $heroIds)->forceDelete();
            }

            Message::where('user_id', $user->id)->orWhere('recipient_id', $user->id)->delete();

            $user->delete();
        });
    }

    public function findHeroWithDetails(int $heroId): ?Hero
    {
        return Hero::withTrashed()
            ->with([
                'user:id,name,email', 'campaign:id,name',
                'currentProfession', 'previousProfession', 'description',
                'characteristic', 'coldWeapons', 'rangedWeapons', 'armors',
                'skills', 'talents', 'inventory',
            ])
            ->find($heroId);
    }
}
