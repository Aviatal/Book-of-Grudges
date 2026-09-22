<?php

namespace App\Repositories;

use App\Models\Characteristic;
use App\Models\SkillTestLog;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class SkillTestLogRepository
{
    public function log(array $attributes): void
    {
        SkillTestLog::create($attributes);
    }

    /**
     * Zbiorcze statystyki wszystkich testów (cech i umiejętności) danego bohatera.
     */
    public function getHeroOverallSummary(int $heroId): array
    {
        $rows = $this->query()
            ->where('hero_id', $heroId)
            ->groupBy('modifier', 'half')
            ->selectRaw('modifier, half, '.$this->countSelectRaw())
            ->get();

        return $this->toBreakdown($rows);
    }

    /**
     * Rozbicie statystyk testów bohatera na poszczególne cechy — testy bezpośrednio na cechę
     * oraz testy umiejętności, które są przypisane do danej cechy, wliczają się razem.
     */
    public function getHeroCharacteristicBreakdown(int $heroId): Collection
    {
        return $this->query()
            ->where('hero_id', $heroId)
            ->groupBy('characteristic', 'modifier', 'half')
            ->selectRaw('characteristic, modifier, half, '.$this->countSelectRaw())
            ->get()
            ->groupBy('characteristic')
            ->map(fn ($rows, $characteristic) => array_merge(['characteristic' => $characteristic], $this->toBreakdown($rows)))
            ->values()
            ->sortBy(fn ($row) => $this->characteristicSortKey($row['characteristic']))
            ->values();
    }

    /**
     * Rozbicie statystyk testów bohatera na poszczególne umiejętności (bez czystych testów cechy).
     */
    public function getHeroSkillBreakdown(int $heroId): Collection
    {
        return $this->query()
            ->where('hero_id', $heroId)
            ->whereNotNull('skill_id')
            ->groupBy('skill_id', 'skill_name', 'characteristic', 'modifier', 'half')
            ->selectRaw('skill_id, skill_name, characteristic, modifier, half, '.$this->countSelectRaw())
            ->get()
            ->groupBy('skill_id')
            ->map(fn ($rows) => array_merge([
                'skill_id' => (int) $rows->first()->skill_id,
                'skill_name' => $rows->first()->skill_name,
                'characteristic' => $rows->first()->characteristic,
            ], $this->toBreakdown($rows)))
            ->sortByDesc(fn ($row) => $row['combined']['total'])
            ->values();
    }

    /**
     * Query builder bazowy (bez hydracji Eloquenta) — aliasy w agregatach (np. `passed`)
     * pokrywają się z nazwami kolumn modelu, więc hydracja nałożyłaby na nie jego casty
     * (`passed` -> bool) i popsuła zliczone sumy.
     */
    private function query(): Builder
    {
        return SkillTestLog::query()->toBase();
    }

    private function countSelectRaw(): string
    {
        return 'count(*) as total, sum(case when passed then 1 else 0 end) as passed_total';
    }

    /**
     * Buduje podsumowanie (łącznie / z modyfikatorem / bez modyfikatora / wg konkretnego
     * modyfikatora) na podstawie wierszy pogrupowanych po (modifier, half).
     */
    private function toBreakdown(Collection $rows): array
    {
        $combinedTotal = 0;
        $combinedPassed = 0;
        $withModifierTotal = 0;
        $withModifierPassed = 0;
        $withoutModifierTotal = 0;
        $withoutModifierPassed = 0;
        $byModifier = [];

        foreach ($rows as $row) {
            $total = (int) $row->total;
            $passed = (int) $row->passed_total;
            $modifier = (int) $row->modifier;
            $half = (bool) $row->half;

            $combinedTotal += $total;
            $combinedPassed += $passed;

            if ($modifier !== 0 || $half) {
                $withModifierTotal += $total;
                $withModifierPassed += $passed;
            } else {
                $withoutModifierTotal += $total;
                $withoutModifierPassed += $passed;
            }

            $byModifier[] = array_merge(
                ['modifier' => $modifier, 'half' => $half],
                $this->toSummary($total, $passed),
            );
        }

        usort($byModifier, fn (array $a, array $b) => [$a['half'], $a['modifier']] <=> [$b['half'], $b['modifier']]);

        return [
            'combined' => $this->toSummary($combinedTotal, $combinedPassed),
            'with_modifier' => $this->toSummary($withModifierTotal, $withModifierPassed),
            'without_modifier' => $this->toSummary($withoutModifierTotal, $withoutModifierPassed),
            'by_modifier' => $byModifier,
        ];
    }

    private function toSummary(int $total, int $passed): array
    {
        return [
            'total' => $total,
            'passed' => $passed,
            'failed' => $total - $passed,
            'pass_percent' => $total > 0 ? round($passed / $total * 100, 1) : null,
        ];
    }

    /**
     * Cechy podstawowe w standardowej kolejności z karty postaci, reszta alfabetycznie za nimi.
     */
    private function characteristicSortKey(string $characteristic): array
    {
        $index = array_search(strtoupper($characteristic), Characteristic::PRIMARY_CHARACTERISTICS, true);

        return [$index === false ? 1 : 0, $index === false ? $characteristic : $index];
    }
}
