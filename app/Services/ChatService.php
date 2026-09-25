<?php

namespace App\Services;

use App\Events\Session\MessageSentEvent;
use App\Events\Session\PrivateMessageSentEvent;
use App\Exceptions\FortuneRerollNotAllowedException;
use App\Exceptions\HeroNotFoundException;
use App\Models\CampaignMember;
use App\Models\Characteristic;
use App\Models\Hero;
use App\Models\Message;
use App\Models\Skill;
use App\Models\User;
use App\Repositories\ChatRepository;
use App\Repositories\FortunePointsSatisfactionRepository;
use App\Support\SkillTestOutcome;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatService
{
    public function __construct(
        private readonly ChatRepository $chatRepository,
        private readonly SkillTestStatisticsService $skillTestStatisticsService,
        private readonly HeroService $heroService,
        private readonly FortunePointsSatisfactionRepository $fortunePointsSatisfactionRepository,
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

    public function rollCharacteristic(User $user, string $characteristic, int $campaignId, int $modifier = 0, bool $half = false, bool $fortuneReroll = false): Message
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

        $text = $this->buildSkillTestPayload($characteristic, $characteristic, $charValue, $effective, $modifier, $half, $roll, $passed, null, $fortuneReroll);

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

    public function rollSkill(User $user, int $skillId, int $campaignId, int $modifier = 0, bool $half = false, bool $fortuneReroll = false): Message
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

        $text = $this->buildSkillTestPayload($skill->name, $skill->characteristic, $charValue, $effectiveValue, $modifier, $half, $roll, $passed, $skill->id, $fortuneReroll);

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

    /**
     * Wydaje punkt szczęścia i powtarza ten sam test (ta sama umiejętność/cecha, modyfikator i ½ cechy)
     * zamiast ostatniego, nieudanego rzutu bohatera. Wynik powtórki od razu ląduje w statystyce
     * satysfakcji z punktów szczęścia: udany rzut = „warto było", nieudany = „nie warto".
     *
     * Wszystko w jednej transakcji — jeśli rzut się nie uda technicznie, punkt nie przepada.
     *
     * @throws FortuneRerollNotAllowedException
     * @throws \App\Exceptions\NotEnoughFortunePointsException
     */
    public function rerollWithFortunePoint(User $user, int $messageId, int $campaignId): Message
    {
        return DB::transaction(function () use ($user, $messageId, $campaignId): Message {
            $hero = $this->heroFor($user, $campaignId);
            if (!$hero) {
                throw new HeroNotFoundException("User {$user->id} has no hero assigned.");
            }
            // Blokada wiersza bohatera — dwa równoległe kliknięcia nie wydadzą dwóch punktów na ten sam rzut.
            $hero = Hero::query()->whereKey($hero->id)->lockForUpdate()->firstOrFail();

            $ownSkillTests = fn () => Message::query()
                ->where('campaign_id', $campaignId)
                ->where('user_id', $user->id)
                ->whereNull('recipient_id')
                ->where('type', 'skill_test');

            $source = $ownSkillTests()->whereKey($messageId)->first();
            if (!$source) {
                throw new FortuneRerollNotAllowedException('Nie znaleziono rzutu do powtórzenia.');
            }
            if ((int) $ownSkillTests()->max('id') !== $source->id) {
                throw new FortuneRerollNotAllowedException('Punkt szczęścia można wydać tylko po ostatnim rzucie.');
            }

            $payload = json_decode($source->text, true);
            // Klucz `skill_id` (null dla cechy) mają tylko rzuty zapisane po wprowadzeniu tej funkcji —
            // starszych nie wiemy, jak dokładnie powtórzyć.
            if (!is_array($payload) || !array_key_exists('skill_id', $payload)) {
                throw new FortuneRerollNotAllowedException('Tego rzutu nie da się powtórzyć.');
            }
            // Jeden rzut można poprawić punktem szczęścia tylko raz — powtórka jest ostateczna, nawet nieudana.
            if (!empty($payload['fortune_reroll'])) {
                throw new FortuneRerollNotAllowedException('Ten rzut został już powtórzony punktem szczęścia.');
            }
            if (SkillTestOutcome::isSuccess((int) $payload['roll'], (int) $payload['effective_value'])) {
                throw new FortuneRerollNotAllowedException('Ten rzut się udał — punkt szczęścia nie jest potrzebny.');
            }

            $this->heroService->spendFortunePoint($hero);

            $modifier = (int) $payload['modifier'];
            $half = (bool) $payload['half'];
            $reroll = $payload['skill_id'] !== null
                ? $this->rollSkill($user, (int) $payload['skill_id'], $campaignId, $modifier, $half, true)
                : $this->rollCharacteristic($user, (string) $payload['characteristic'], $campaignId, $modifier, $half, true);

            $result = json_decode($reroll->text, true, 512, JSON_THROW_ON_ERROR);
            $this->fortunePointsSatisfactionRepository->insertSatisfactionToDatabase(
                $hero->id,
                SkillTestOutcome::isSuccess((int) $result['roll'], (int) $result['effective_value']),
            );

            return $reroll;
        });
    }

    /**
     * Wspólny JSON dla wiadomości typu `skill_test` (rzut na cechę i na umiejętność).
     *
     * `fumble` — rzut 97-100 to zawsze pech, niezależnie od tego, czy test formalnie wyszedł.
     * `levels` — o ile pełnych poziomów (10 punktów) różni się rzut od progu; 0, gdy różnica
     * jest mniejsza niż 10 — wtedy front nie pokazuje żadnej dodatkowej informacji o poziomie.
     * `skill_id` — null dla rzutu na cechę; po jego obecności wiadomość da się powtórzyć punktem szczęścia.
     * `fortune_reroll` — to powtórka rzutu za punkt szczęścia.
     */
    private function buildSkillTestPayload(
        string $skill,
        string $characteristic,
        int $characteristicValue,
        int $effectiveValue,
        int $modifier,
        bool $half,
        int $roll,
        bool $passed,
        ?int $skillId = null,
        bool $fortuneReroll = false,
    ): string {
        return json_encode([
            'skill_id'             => $skillId,
            'fortune_reroll'       => $fortuneReroll,
            'skill'                => $skill,
            'characteristic'       => $characteristic,
            'characteristic_value' => $characteristicValue,
            'effective_value'      => $effectiveValue,
            'modifier'             => $modifier,
            'half'                 => $half,
            'roll'                 => $roll,
            'passed'               => $passed,
            'fumble'               => SkillTestOutcome::isFumble($roll),
            'levels'               => SkillTestOutcome::levels($roll, $effectiveValue),
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    private function tryBroadcast(Message $message, int $campaignId): void
    {
        try {
            broadcast(new MessageSentEvent($message, $campaignId));
        } catch (\Throwable $exception) {
            // Wiadomość jest już zapisana w bazie — brak WebSocket (np. Reverb nie działa)
            // nie może zablokować odpowiedzi, ale MUSI być widoczne w logach, inaczej "znikające"
            // wiadomości/rzuty są niemożliwe do zdiagnozowania.
            Log::warning('Message sent but broadcast failed', ['exception' => $exception]);
        }
    }

    private function tryBroadcastPrivate(Message $message, int $campaignId): void
    {
        try {
            broadcast(new PrivateMessageSentEvent($message, $campaignId));
        } catch (\Throwable $exception) {
            Log::warning('Private message sent but broadcast failed', ['exception' => $exception]);
        }
    }

    /**
     * Wspólna autoryzacja dla wszystkich akcji prywatnych (wiadomość, cecha, umiejętność, kości):
     * MG może pisać/rzucać do dowolnego gracza kampanii, gracz — wyłącznie do MG (nawet jako
     * pierwszy, bez istniejącego wątku). Rozmowy gracz-gracz są zablokowane.
     *
     * @throws AuthorizationException
     */
    private function authorizePrivateRecipient(int $campaignId, int $recipientId, bool $senderIsGm): void
    {
        $recipientMember = CampaignMember::query()
            ->where('campaign_id', $campaignId)
            ->where('user_id', $recipientId)
            ->first();

        if (!$recipientMember) {
            throw new AuthorizationException('Odbiorca nie jest członkiem tej kampanii.');
        }

        if ($senderIsGm) {
            if ($recipientMember->isGm()) {
                throw new AuthorizationException('Nie można wysłać prywatnej wiadomości do samego siebie.');
            }

            return;
        }

        if (!$recipientMember->isGm()) {
            throw new AuthorizationException('Prywatną wiadomość można wysłać wyłącznie do Mistrza Gry.');
        }
    }

    public function sendPrivateMessage(User $user, string $text, int $recipientId, int $campaignId, bool $isGm): Message
    {
        $this->authorizePrivateRecipient($campaignId, $recipientId, $isGm);

        $authorName = $this->heroFor($user, $campaignId)?->name ?? $user->name;

        $message = $this->chatRepository->savePrivateMessage($user->id, $recipientId, $authorName, $text, $campaignId);

        $this->tryBroadcastPrivate($message, $campaignId);

        return $message;
    }

    public function rollPrivateCharacteristic(
        User $user,
        string $characteristic,
        int $recipientId,
        int $campaignId,
        bool $isGm,
        int $modifier = 0,
        bool $half = false,
    ): Message {
        $this->authorizePrivateRecipient($campaignId, $recipientId, $isGm);

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

        $text = $this->buildSkillTestPayload($characteristic, $characteristic, $charValue, $effective, $modifier, $half, $roll, $passed);

        $message = $this->chatRepository->savePrivateMessage($user->id, $recipientId, $hero->name, $text, $campaignId, 'skill_test');
        $this->tryBroadcastPrivate($message, $campaignId);

        return $message;
    }

    public function rollPrivateSkill(
        User $user,
        int $skillId,
        int $recipientId,
        int $campaignId,
        bool $isGm,
        int $modifier = 0,
        bool $half = false,
    ): Message {
        $this->authorizePrivateRecipient($campaignId, $recipientId, $isGm);

        $hero = $this->heroFor($user, $campaignId)?->load('characteristic');

        if (!$hero) {
            throw new HeroNotFoundException("User {$user->id} has no hero assigned.");
        }

        $skill = Skill::findOrFail($skillId);

        $char = $hero->characteristic[$skill->characteristic] ?? null;
        $charValue = $char ? ($char->pivot->start_value + $char->pivot->advancement) : 0;

        $base = $half ? intdiv($charValue, 2) : $charValue;
        $effectiveValue = max(0, $base + $modifier);

        $roll = random_int(1, 100);
        $passed = $roll <= $effectiveValue;

        $text = $this->buildSkillTestPayload($skill->name, $skill->characteristic, $charValue, $effectiveValue, $modifier, $half, $roll, $passed);

        $message = $this->chatRepository->savePrivateMessage($user->id, $recipientId, $hero->name, $text, $campaignId, 'skill_test');
        $this->tryBroadcastPrivate($message, $campaignId);

        return $message;
    }

    public function rollPrivateDice(User $user, int $count, int $sides, int $recipientId, int $campaignId, bool $isGm): Message
    {
        $this->authorizePrivateRecipient($campaignId, $recipientId, $isGm);

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

        $message = $this->chatRepository->savePrivateMessage($user->id, $recipientId, $authorName, $text, $campaignId, 'dice_roll');
        $this->tryBroadcastPrivate($message, $campaignId);

        return $message;
    }

    /**
     * @return array<int, array{user_id: int, name: string}>
     */
    public function getPrivateContacts(int $campaignId, bool $isGm): array
    {
        return $this->chatRepository->getPrivateContacts($campaignId, $isGm);
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
