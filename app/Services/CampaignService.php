<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignMember;
use App\Models\User;
use App\Repositories\CampaignsRepository;
use Illuminate\Support\Collection;

readonly class CampaignService
{
    public function __construct(private CampaignsRepository $campaignsRepository) {}

    public function membershipsForUser(User $user): Collection
    {
        return $this->campaignsRepository->membershipsForUser($user->id);
    }

    public function createCampaign(User $owner, string $name): Campaign
    {
        return $this->campaignsRepository->create($name, $owner);
    }

    public function joinByCode(User $user, string $code): Campaign
    {
        $campaign = $this->campaignsRepository->findByInviteCode($code);

        if ($campaign === null) {
            throw new \InvalidArgumentException('Nieprawidłowy kod zaproszenia');
        }

        $this->campaignsRepository->addMember($campaign, $user);

        return $campaign;
    }

    public function membershipFor(User $user, int $campaignId): ?CampaignMember
    {
        return CampaignMember::where('campaign_id', $campaignId)->where('user_id', $user->id)->first();
    }

    public function renameCampaign(Campaign $campaign, string $name): Campaign
    {
        return $this->campaignsRepository->rename($campaign, $name);
    }

    public function regenerateInviteCode(Campaign $campaign): Campaign
    {
        return $this->campaignsRepository->regenerateInviteCode($campaign);
    }

    public function membersOverview(Campaign $campaign): Collection
    {
        return $this->campaignsRepository->membersWithHeroes($campaign);
    }

    public function removeMember(Campaign $campaign, int $userId): void
    {
        abort_if($userId === $campaign->owner_id, 422, 'Nie można usunąć właściciela kampanii');
        $this->campaignsRepository->removeMember($campaign, $userId);
    }
}
