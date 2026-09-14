<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Services\CampaignService;
use App\Support\CurrentCampaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function __construct(private readonly CampaignService $campaignService) {}

    public function index(): View
    {
        $campaign = $this->currentCampaign()->model();
        $members = $this->campaignService->membersOverview($campaign);
        $inviteUrl = route('campaigns.join-by-link', $campaign->invite_code);

        return view('Panel.campaign.index', compact('campaign', 'members', 'inviteUrl'));
    }

    public function rename(Request $request): JsonResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:80']]);
        $campaign = $this->campaignService->renameCampaign($this->currentCampaign()->model(), $data['name']);

        return response()->json($campaign);
    }

    public function regenerateInviteCode(): JsonResponse
    {
        $campaign = $this->campaignService->regenerateInviteCode($this->currentCampaign()->model());

        return response()->json([
            'campaign'  => $campaign,
            'inviteUrl' => route('campaigns.join-by-link', $campaign->invite_code),
        ]);
    }

    public function removeMember(int $userId): JsonResponse
    {
        $this->campaignService->removeMember($this->currentCampaign()->model(), $userId);

        return response()->json(null, 204);
    }
}
