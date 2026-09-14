<?php

namespace App\Http\Controllers;

use App\Services\CampaignService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function __construct(private readonly CampaignService $campaignService) {}

    public function index(Request $request): View
    {
        $memberships = $this->campaignService->membershipsForUser($request->user());

        return view('Pages.campaigns.index', compact('memberships'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:80']]);

        $campaign = $this->campaignService->createCampaign($request->user(), $data['name']);
        $request->session()->put('current_campaign_id', $campaign->id);

        return redirect()->route('home');
    }

    public function join(Request $request): RedirectResponse
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:32']]);

        try {
            $campaign = $this->campaignService->joinByCode($request->user(), $data['code']);
        } catch (\InvalidArgumentException $exception) {
            return back()->withErrors(['code' => $exception->getMessage()]);
        }

        $request->session()->put('current_campaign_id', $campaign->id);

        return redirect()->route('home');
    }

    public function joinByLink(Request $request, string $code): RedirectResponse
    {
        try {
            $campaign = $this->campaignService->joinByCode($request->user(), $code);
        } catch (\InvalidArgumentException $exception) {
            return redirect()->route('campaigns.index')->withErrors(['code' => $exception->getMessage()]);
        }

        $request->session()->put('current_campaign_id', $campaign->id);

        return redirect()->route('home');
    }

    public function switch(Request $request, int $campaign): RedirectResponse
    {
        $membership = $this->campaignService->membershipFor($request->user(), $campaign);
        abort_if($membership === null, 403);

        $request->session()->put('current_campaign_id', $campaign);

        return redirect()->route('home');
    }
}
