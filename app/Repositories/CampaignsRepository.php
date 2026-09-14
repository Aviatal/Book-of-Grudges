<?php

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\CampaignMember;
use App\Models\Hero;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CampaignsRepository
{
    public function membershipsForUser(int $userId): Collection
    {
        return CampaignMember::with('campaign')->where('user_id', $userId)->get();
    }

    public function findByInviteCode(string $code): ?Campaign
    {
        return Campaign::where('invite_code', $code)->first();
    }

    public function create(string $name, User $owner): Campaign
    {
        $campaign = Campaign::create([
            'name'        => $name,
            'owner_id'    => $owner->id,
            'invite_code' => $this->generateInviteCode(),
        ]);

        CampaignMember::create([
            'campaign_id' => $campaign->id,
            'user_id'     => $owner->id,
            'role'        => CampaignMember::ROLE_GM,
            'joined_at'   => now(),
        ]);

        return $campaign;
    }

    public function addMember(Campaign $campaign, User $user, string $role = CampaignMember::ROLE_PLAYER): CampaignMember
    {
        return CampaignMember::firstOrCreate(
            ['campaign_id' => $campaign->id, 'user_id' => $user->id],
            ['role' => $role, 'joined_at' => now()]
        );
    }

    public function removeMember(Campaign $campaign, int $userId): void
    {
        CampaignMember::where('campaign_id', $campaign->id)->where('user_id', $userId)->delete();
    }

    public function membersWithHeroes(Campaign $campaign): Collection
    {
        $heroNamesByUserId = Hero::where('campaign_id', $campaign->id)->pluck('name', 'user_id');

        return CampaignMember::with('user:id,name,email')
            ->where('campaign_id', $campaign->id)
            ->get()
            ->map(function (CampaignMember $member) use ($heroNamesByUserId) {
                $member->setAttribute('hero_name', $heroNamesByUserId->get($member->user_id));
                return $member;
            });
    }

    public function regenerateInviteCode(Campaign $campaign): Campaign
    {
        $campaign->update(['invite_code' => $this->generateInviteCode()]);
        return $campaign;
    }

    public function rename(Campaign $campaign, string $name): Campaign
    {
        $campaign->update(['name' => $name]);
        return $campaign;
    }

    private function generateInviteCode(): string
    {
        do {
            $code = Str::random(10);
        } while (Campaign::where('invite_code', $code)->exists());

        return $code;
    }
}
