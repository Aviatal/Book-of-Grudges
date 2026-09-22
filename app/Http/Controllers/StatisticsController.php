<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use App\Services\FortunePointSatisfactionService;
use App\Services\SkillTestStatisticsService;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function __construct(
        private readonly FortunePointSatisfactionService $fortunePointSatisfactionService,
        private readonly SkillTestStatisticsService $skillTestStatisticsService,
    ) {}

    public function index()
    {
        $campaignId = $this->currentCampaign()->id();

        $playerHero = Hero::select('id', 'name')
            ->where('campaign_id', $campaignId)
            ->where('user_id', Auth::id())
            ->first();

        $isGm = $this->currentCampaign()->isGm();
        $globalStatistics = $this->fortunePointSatisfactionService->getCampaignStatistics($campaignId, $isGm);
        $playerStatistics = $playerHero ? $this->fortunePointSatisfactionService->getHeroStatistics($playerHero) : null;
        $rollStatistics = $playerHero ? $this->skillTestStatisticsService->getHeroStatistics($playerHero->id) : null;

        return view('statistics.index', [
            'globalStatistics' => $globalStatistics,
            'playerStatistics' => $playerStatistics,
            'playerHeroName' => $playerHero?->name,
            'showHeroBreakdown' => $isGm,
            'rollStatistics' => $rollStatistics,
        ]);
    }
}
