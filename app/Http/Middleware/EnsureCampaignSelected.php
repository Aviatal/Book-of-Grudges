<?php

namespace App\Http\Middleware;

use App\Models\CampaignMember;
use App\Support\CurrentCampaign;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ustala, w kontekście której kampanii działa bieżące żądanie — wybór trzymany jest
 * w sesji (session('current_campaign_id')), bo appka jest multi-page bez własnego store'u JS.
 */
class EnsureCampaignSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $membership = $this->resolveMembership($request, $user->id);

        if ($membership === null) {
            return redirect()->route('campaigns.index');
        }

        $campaign = $membership->campaign;
        app()->instance(CurrentCampaign::class, new CurrentCampaign($campaign, $membership->role));

        View::share('currentCampaign', $campaign);
        View::share('isCampaignGm', $membership->isGm());
        View::share('isSuperadmin', (bool) $user->is_superadmin);
        View::share('currentCampaignHero', $user->heroInCampaign($campaign->id));

        return $next($request);
    }

    private function resolveMembership(Request $request, int $userId): ?CampaignMember
    {
        $campaignId = $request->session()->get('current_campaign_id');

        if ($campaignId !== null) {
            $membership = CampaignMember::with('campaign')
                ->where('campaign_id', $campaignId)
                ->where('user_id', $userId)
                ->first();

            if ($membership !== null) {
                return $membership;
            }

            $request->session()->forget('current_campaign_id');
        }

        $memberships = CampaignMember::with('campaign')->where('user_id', $userId)->get();

        if ($memberships->count() === 1) {
            $membership = $memberships->first();
            $request->session()->put('current_campaign_id', $membership->campaign_id);

            return $membership;
        }

        return null;
    }
}
