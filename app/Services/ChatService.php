<?php

namespace App\Services;

use App\Events\Session\MessageSentEvent;
use App\Exceptions\HeroNotFoundException;
use App\Models\Characteristic;
use App\Models\Hero;
use App\Models\Message;
use App\Models\Skill;
use App\Models\User;
use App\Repositories\ChatRepository;
use Illuminate\Support\Facades\Log;

class ChatService
{
    public function __construct(
        private readonly ChatRepository $chatRepository,
        private readonly SkillTestStatisticsService $skillTestStatisticsService,
    ) {}

    private function heroFor(User $user, int $campaignId): ?Hero
    {
        return $user->heroes()->where('campaign_id', $campaignId)->first();
    }

    public function sendMessage(User $user, string $text, int $campaignId): Message
    {
        $authorName = $this->heroFor($user, $campaignId)?->name ?? $user->name;

        $message = $this->chatRepository->saveMessage($user->id, $authorName, $text, $campaignId);

        $this->tryBroadcast($message, $campaignId);

        return $message;
    }

    public function rollInitiative(User $user, int $campaignId): Message
    {
        $hero = $this->heroFor($user, $campaignId)?->load('characteristic');
        $authorName = $hero?->name ?? $user->name;

        $zr = $hero?->characteristic['Zr'];
        $zrValue = $zr ? ($zr->pivot->start_value + $zr->pivot->advancement) : 0;

        $roll = random_int(1, 10);
        $total = $zrValue + $roll;

        $text = "🎲 Rzut na inicjatywę: Zr ({$zrValue}) + k10 [{$roll}] = {$total}";

        $message = $this->chatRepository->saveMessage($user->id, $authorName, $text, $campaignId, 'roll');

        $this->tryBroadcast($message, $campaignId);

        return $message;
    }

    public function getSkillsForHero(User $user, int $campaignId): array
    {
        $hero = $this->heroFor($user, $campaignId)?->load(['skills', 'characteristic']);

        if (!$hero) {
            return ['characteristics' => [], 'skills' => []];
        }

        // Mapa cech: short_name => wartość. Tylko cechy podstawowe — drugorzędnych (A, Żyw, S,
        // Wt, Sz, Mag, PO, PP) nie da się testować, więc nie mają co pojawiać się jako rzut.
        $charMap = [];
        foreach ($hero->characteristic as $char) {
            if (!in_array(strtoupper($char->short_name), Characteristic::PRIMARY_CHARACTERISTICS, true)) {
                continue;
            }
            $charMap[$char->short_name] = $char->pivot->start_value + $char->pivot->advancement;
        }

        $heroSkillMap = $hero->skills
            ->filter(fn(Skill $skill) => $skill->pivot->hurdled)
            ->keyBy('id');

        $skills = Skill::orderBy('name')
            ->get()
            ->map(function (Skill $skill) use ($charMap, $heroSkillMap): array {
                $heroSkill = $heroSkillMap->get($skill->id);

                return [
                    'id'                   => $skill->id,
                    'name'                 => $skill->name,
                    'type'                 => $skill->type,
                    'characteristic'       => $skill->characteristic,
                    'characteristic_value' => $charMap[$skill->characteristic] ?? 0,
                    'is_purchased'         => (bool) $heroSkill,
                    'additional_name'      => $heroSkill?->pivot->additional_skill_name,
                ];
            })
            ->values()
            ->toArray();

        return [
            'characteristics' => $charMap,
            'skills'          => $skills,
        ];
    }

    public function rollCharacteristic(User $user, string $characteristic, int $campaignId, int $modifier = 0, bool $half = false): Message
    {
        $hero = $this->heroFor($user, $campaignId)?->load('characteristic');

        if (!$hero) {
            throw new HeroNotFoundException("User {$user->id} has no hero assigned.");
        }

        $char = $hero->characteristic[$characteristic] ?? null;
        if (!$char) {
            throw new \InvalidArgumentException("Brak cechy: {$characteristic}");
        }
        if (!in_array(strtoupper($characteristic), Characteristic::PRIMARY_CHARACTERISTICS, true)) {
            throw new \InvalidArgumentException("Cechy drugorzędnej nie da się testować: {$characteristic}");
        }

        $charValue = $char->pivot->start_value + $char->pivot->advancement;
        $base      = $half ? intdiv($charValue, 2) : $charValue;
        $effective = max(0, $base + $modifier);

        $roll   = random_int(1, 100);
        $passed = $roll <= $effective;

        $text = json_encode([
            'skill'                => $characteristic,
            'characteristic'       => $characteristic,
            'characteristic_value' => $charValue,
            'effective_value'      => $effective,
            'modifier'             => $modifier,
            'half'                 => $half,
            'roll'                 => $roll,
            'passed'               => $passed,
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        $message = $this->chatRepository->saveMessage($user->id, $hero->name, $text, $campaignId, 'skill_test');
        $this->tryBroadcast($message, $campaignId);

        $this->tryLogSkillTestStatistic(fn () => $this->skillTestStatisticsService->recordCharacteristicTest(
            $hero,
            $campaignId,
            $characteristic,
            $charValue,
            $effective,
            $modifier,
            $half,
            $roll,
            $passed,
        ));

        return $message;
    }

    public function rollDice(User $user, int $count, int $sides, int $campaignId): Message
    {
        $hero = $this->heroFor($user, $campaignId);
        $authorName = $hero?->name ?? $user->name;

        $results = [];
        for ($i = 0; $i < $count; $i++) {
            $results[] = random_int(1, $sides);
        }
        $total = array_sum($results);

        $notation = "{$count}k{$sides}";

        $text = json_encode([
            'notation' => $notation,
            'count'    => $count,
            'sides'    => $sides,
            'results'  => $results,
            'total'    => $total,
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        $message = $this->chatRepository->saveMessage($user->id, $authorName, $text, $campaignId, 'dice_roll');
        $this->tryBroadcast($message, $campaignId);

        return $message;
    }

    public function rollSkill(User $user, int $skillId, int $campaignId, int $modifier = 0, bool $half = false): Message
    {
        $hero = $this->heroFor($user, $campaignId)?->load('characteristic');

        if (!$hero) {
            throw new HeroNotFoundException("User {$user->id} has no hero assigned.");
        }

        $authorName = $hero->name;
        $skill = Skill::findOrFail($skillId);

        $char = $hero->characteristic[$skill->characteristic] ?? null;
        $charValue = $char ? ($char->pivot->start_value + $char->pivot->advancement) : 0;

        $base = $half ? intdiv($charValue, 2) : $charValue;
        $effectiveValue = max(0, $base + $modifier);

        $roll = random_int(1, 100);
        $passed = $roll <= $effectiveValue;

        $text = json_encode([
            'skill'                => $skill->name,
            'characteristic'       => $skill->characteristic,
            'characteristic_value' => $charValue,
            'effective_value'      => $effectiveValue,
            'modifier'             => $modifier,
            'half'                 => $half,
            'roll'                 => $roll,
            'passed'               => $passed,
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        $message = $this->chatRepository->saveMessage($user->id, $authorName, $text, $campaignId, 'skill_test');
        $this->tryBroadcast($message, $campaignId);

        $this->tryLogSkillTestStatistic(fn () => $this->skillTestStatisticsService->recordSkillTest(
            $hero,
            $campaignId,
            $skill,
            $charValue,
            $effectiveValue,
            $modifier,
            $half,
            $roll,
            $passed,
        ));

        return $message;
    }

    private function tryBroadcast(Message $message, int $campaignId): void
    {
        try {
            broadcast(new MessageSentEvent($message, $campaignId));
        } catch (\Throwable) {
            // wiadomość jest zapisana w bazie — brak WebSocket nie blokuje odpowiedzi
        }
    }

    private function tryLogSkillTestStatistic(\Closure $log): void
    {
        try {
            $log();
        } catch (\Throwable $exception) {
            // Zapis statystyk nie może zablokować samego rzutu — logujemy błąd i jedziemy dalej.
            Log::error('Error during logging skill test statistic', ['exception' => $exception]);
        }
    }
}
