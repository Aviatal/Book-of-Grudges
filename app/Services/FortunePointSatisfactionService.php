<?php

namespace App\Services;

use App\Models\Hero;
use App\Repositories\FortunePointsSatisfactionRepository;
use Illuminate\Http\Request;

class FortunePointSatisfactionService
{
    private FortunePointsSatisfactionRepository $fortunePointsSatisfactionRepository;
    public function __construct()
    {
        $this->fortunePointsSatisfactionRepository = new FortunePointsSatisfactionRepository();
    }

    public function logSatisfaction(Request $request, Hero $hero): void
    {
        $request->validate(['satisfied' => 'required|boolean']);
        $this->fortunePointsSatisfactionRepository->insertSatisfactionToDatabase($hero->id, $request->input('satisfied'));
    }

    /**
     * Statystyki globalne (cała kampania) — zbiorcze podsumowanie oraz, tylko dla MG,
     * rozbicie na poszczególnych bohaterów (zwykły gracz nie powinien widzieć cudzych statystyk).
     */
    public function getCampaignStatistics(int $campaignId, bool $includeHeroBreakdown = false): array
    {
        return [
            'summary' => $this->fortunePointsSatisfactionRepository->getCampaignSummary($campaignId),
            'heroes' => $includeHeroBreakdown
                ? $this->fortunePointsSatisfactionRepository->getCampaignHeroBreakdown($campaignId)->values()->all()
                : [],
        ];
    }

    /**
     * Statystyki pojedynczego bohatera (bieżącego gracza).
     */
    public function getHeroStatistics(Hero $hero): array
    {
        return $this->fortunePointsSatisfactionRepository->getHeroSummary($hero->id);
    }
}
