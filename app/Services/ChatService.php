<?php

namespace App\Services;

use App\Events\Session\MessageSentEvent;
use App\Exceptions\HeroNotFoundException;
use App\Models\Hero;
use App\Models\Message;
use App\Models\Skill;
use App\Models\User;
use App\Repositories\ChatRepository;

class ChatService
{
    public function __construct(private readonly ChatRepository $chatRepository) {}

    public function sendMessage(User $user, string $text): Message
    {
        $authorName = $user->hero()->value('name') ?? $user->name;

        $message = $this->chatRepository->saveMessage($user->id, $authorName, $text);

        broadcast(new MessageSentEvent($message));

        return $message;
    }

    public function rollInitiative(User $user): Message
    {
        $hero = $user->hero()->with('characteristic')->first();
        $authorName = $hero?->name ?? $user->name;

        $zr = $hero?->characteristic['Zr'];
        $zrValue = $zr ? ($zr->pivot->start_value + $zr->pivot->advancement) : 0;

        $roll = random_int(1, 10);
        $total = $zrValue + $roll;

        $text = "🎲 Rzut na inicjatywę: Zr ({$zrValue}) + k10 [{$roll}] = {$total}";

        $message = $this->chatRepository->saveMessage($user->id, $authorName, $text, 'roll');

        broadcast(new MessageSentEvent($message));

        return $message;
    }

    public function getSkillsForHero(User $user): array
    {
        $hero = $user->hero()->with(['skills', 'characteristic'])->first();

        if (!$hero) {
            return ['characteristics' => [], 'skills' => []];
        }

        // Mapa cech: short_name => wartość
        $charMap = [];
        foreach ($hero->characteristic as $char) {
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

    public function rollCharacteristic(User $user, string $characteristic, int $modifier = 0, bool $half = false): Message
    {
        $hero = $user->hero()->with('characteristic')->first();

        if (!$hero) {
            throw new HeroNotFoundException("User {$user->id} has no hero assigned.");
        }

        $char = $hero->characteristic[$characteristic] ?? null;
        if (!$char) {
            throw new \InvalidArgumentException("Brak cechy: {$characteristic}");
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

        $message = $this->chatRepository->saveMessage($user->id, $hero->name, $text, 'skill_test');
        broadcast(new MessageSentEvent($message));

        return $message;
    }

    public function rollSkill(User $user, int $skillId, int $modifier = 0, bool $half = false): Message
    {
        $hero = $user->hero()->with('characteristic')->first();

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

        $message = $this->chatRepository->saveMessage($user->id, $authorName, $text, 'skill_test');

        broadcast(new MessageSentEvent($message));

        return $message;
    }
}
