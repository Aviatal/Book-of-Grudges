<?php

namespace App\Services;

use App\Models\Hero;
use App\Models\Skill;
use App\Repositories\SkillTestLogRepository;

class SkillTestStatisticsService
{
    public function __construct(private readonly SkillTestLogRepository $skillTestLogRepository) {}

    /**
     * Zapisuje wynik testu bezpośrednio na cechę (bez umiejętności).
     */
    public function recordCharacteristicTest(
        Hero $hero,
        int $campaignId,
        string $characteristic,
        int $characteristicValue,
        int $effectiveValue,
        int $modifier,
        bool $half,
        int $roll,
        bool $passed,
    ): void {
        $this->skillTestLogRepository->log([
            'hero_id' => $hero->id,
            'campaign_id' => $campaignId,
            'skill_id' => null,
            'skill_name' => null,
            'characteristic' => $characteristic,
            'characteristic_value' => $characteristicValue,
            'effective_value' => $effectiveValue,
            'modifier' => $modifier,
            'half' => $half,
            'has_modifier' => $modifier !== 0 || $half,
            'roll' => $roll,
            'passed' => $passed,
        ]);
    }

    /**
     * Zapisuje wynik testu umiejętności — liczy się zarówno do statystyk tej umiejętności,
     * jak i do statystyk cechy, pod którą ta umiejętność jest przypisana (np. Spostrzegawczość -> Int).
     */
    public function recordSkillTest(
        Hero $hero,
        int $campaignId,
        Skill $skill,
        int $characteristicValue,
        int $effectiveValue,
        int $modifier,
        bool $half,
        int $roll,
        bool $passed,
    ): void {
        $this->skillTestLogRepository->log([
            'hero_id' => $hero->id,
            'campaign_id' => $campaignId,
            'skill_id' => $skill->id,
            'skill_name' => $skill->name,
            'characteristic' => $skill->characteristic,
            'characteristic_value' => $characteristicValue,
            'effective_value' => $effectiveValue,
            'modifier' => $modifier,
            'half' => $half,
            'has_modifier' => $modifier !== 0 || $half,
            'roll' => $roll,
            'passed' => $passed,
        ]);
    }

    /**
     * Statystyki testów bohatera: ogólne, per cecha oraz per umiejętność.
     */
    public function getHeroStatistics(int $heroId): array
    {
        return [
            'overall' => $this->skillTestLogRepository->getHeroOverallSummary($heroId),
            'characteristics' => $this->skillTestLogRepository->getHeroCharacteristicBreakdown($heroId)->values()->all(),
            'skills' => $this->skillTestLogRepository->getHeroSkillBreakdown($heroId)->values()->all(),
        ];
    }
}
