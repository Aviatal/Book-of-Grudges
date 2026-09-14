<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use App\Models\User;
use App\Support\CurrentCampaign;
use Illuminate\Http\Request;

class FortunePointsController extends Controller
{
    public function getFortunePointsManagement()
    {
        $campaignId = $this->currentCampaign()->id();

        $activeUsers = User::select('id', 'name')
            ->whereHas('campaignMemberships', fn ($q) => $q->where('campaign_id', $campaignId))
            ->where('is_active', 1)
            ->get()
            ->map(function (User $user) use ($campaignId) {
                $hero = Hero::select('id', 'user_id', 'name', 'fortune_points')
                    ->where('campaign_id', $campaignId)
                    ->where('user_id', $user->id)
                    ->first();

                return $hero ? ['id' => $user->id, 'name' => $user->name, 'hero' => $hero] : null;
            })
            ->filter()
            ->values();

        return view('Panel.fortune-points.fp_form', compact('activeUsers'));
    }

    public function assignFortunePoint(Request $request): void
    {
        $heroId = $request->get('heroId');

        $belongsToCampaign = Hero::where('id', $heroId)->where('campaign_id', $this->currentCampaign()->id())->exists();
        abort_unless($belongsToCampaign, 403);

        Hero::query()->where('id', $heroId)->increment('fortune_points');
        event(new \App\Events\FortunePointsAdded($heroId));
    }
}
