<?php

namespace App\Repositories;

use App\Models\FortunePointsSatisfaction;
use Illuminate\Support\Collection;

class FortunePointsSatisfactionRepository
{
    public function insertSatisfactionToDatabase(int $heroId, bool $satisfied): void
    {
        FortunePointsSatisfaction::create([
            'hero_id' => $heroId,
            'satisfied' => $satisfied,
        ]);
    }

    /**
     * Zbiorcze statystyki wydawania punktów szczęścia dla wszystkich bohaterów danej kampanii.
     */
    public function getCampaignSummary(int $campaignId): array
    {
        $row = FortunePointsSatisfaction::query()
            ->join('heroes', 'heroes.id', '=', 'fortune_points_satisfaction.hero_id')
            ->where('heroes.campaign_id', $campaignId)
            ->selectRaw('count(*) as total, sum(case when fortune_points_satisfaction.satisfied then 1 else 0 end) as satisfied')
            ->first();

        return $this->toSummary((int) ($row->total ?? 0), (int) ($row->satisfied ?? 0));
    }

    /**
     * Statystyki wydawania punktów szczęścia dla pojedynczego bohatera.
     */
    public function getHeroSummary(int $heroId): array
    {
        $row = FortunePointsSatisfaction::query()
            ->where('hero_id', $heroId)
            ->selectRaw('count(*) as total, sum(case when satisfied then 1 else 0 end) as satisfied')
            ->first();

        return $this->toSummary((int) ($row->total ?? 0), (int) ($row->satisfied ?? 0));
    }

    /**
     * Rozbicie statystyk na poszczególnych bohaterów danej kampanii (do widoku globalnego).
     */
    public function getCampaignHeroBreakdown(int $campaignId): Collection
    {
        return FortunePointsSatisfaction::query()
            ->join('heroes', 'heroes.id', '=', 'fortune_points_satisfaction.hero_id')
            ->where('heroes.campaign_id', $campaignId)
            ->groupBy('heroes.id', 'heroes.name')
            ->selectRaw('heroes.id as hero_id, heroes.name as hero_name, count(*) as total, sum(case when fortune_points_satisfaction.satisfied then 1 else 0 end) as satisfied')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => $this->toSummary((int) $row->total, (int) $row->satisfied, (int) $row->hero_id, $row->hero_name));
    }

    private function toSummary(int $total, int $satisfied, ?int $heroId = null, ?string $heroName = null): array
    {
        $unsatisfied = $total - $satisfied;

        return [
            'hero_id' => $heroId,
            'hero_name' => $heroName,
            'total' => $total,
            'satisfied' => $satisfied,
            'unsatisfied' => $unsatisfied,
            'satisfied_percent' => $total > 0 ? round($satisfied / $total * 100, 1) : null,
        ];
    }
}
