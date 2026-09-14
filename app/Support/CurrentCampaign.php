<?php

namespace App\Support;

use App\Models\Campaign;
use App\Models\CampaignMember;

readonly class CurrentCampaign
{
    public function __construct(
        private Campaign $campaign,
        private string $role,
    ) {}

    public function id(): int
    {
        return $this->campaign->id;
    }

    public function model(): Campaign
    {
        return $this->campaign;
    }

    public function isGm(): bool
    {
        return $this->role === CampaignMember::ROLE_GM;
    }
}
