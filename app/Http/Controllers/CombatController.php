<?php

namespace App\Http\Controllers;

use App\Events\Session\CombatEvent;
use App\Events\Session\MessageSentEvent;
use App\Models\Token;
use App\Repositories\ChatRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CombatController extends Controller
{
    private const CACHE_KEY = 'combat_state';
    private const CACHE_TTL = 3600; // 1 godzina

    public function __construct(private readonly ChatRepository $chatRepository) {}

    // ── GET /session/combat ──────────────────────────────────────────────────
    public function state(): JsonResponse
    {
        $state = Cache::get(self::CACHE_KEY);

        if (!$state || (isset($state['active']) && !$state['active'])) {
            return response()->json(null);
        }

        return response()->json($state);
    }

    // ── POST /session/combat/start ───────────────────────────────────────────
    public function start(Request $request): JsonResponse
    {
        $request->validate([
            'token_ids'   => ['required', 'array', 'min:1'],
            'token_ids.*' => ['integer'],
        ]);

        $tokens = Token::with(['hero.characteristic'])
            ->whereIn('id', $request->input('token_ids'))
            ->where('on_map', true)
            ->get();

        $participants = $tokens->map(function (Token $token): array {
            $isNpc = is_null($token->hero_id);

            if ($isNpc) {
                $zr = (int) ($token->sheet['characteristics']['Zr'] ?? 0);
            } else {
                $char = $token->hero?->characteristic['Zr'] ?? null;
                $zr   = $char ? ($char->pivot->start_value + $char->pivot->advancement) : 0;
            }

            return [
                'token_id'   => $token->id,
                'name'       => $token->name,
                'image_url'  => $token->image ? $token->image_url : null,
                'color'      => $token->color,
                'is_npc'     => $isNpc,
                'hero_id'    => $token->hero_id,
                'zr'         => $zr,
                'initiative' => null,
                'roll'       => null,
            ];
        })->values()->toArray();

        $state = [
            'active'        => true,
            'round'         => 1,
            'current_index' => 0,
            'participants'  => $participants,
        ];

        Cache::put(self::CACHE_KEY, $state, self::CACHE_TTL);
        broadcast(new CombatEvent('updated', $state));

        return response()->json($state, Response::HTTP_CREATED);
    }

    // ── POST /session/combat/roll-npc ────────────────────────────────────────
    // Opcjonalnie: token_id → rzut tylko dla konkretnego NPC
    //              brak token_id → rzut dla wszystkich nierolniętych NPC
    public function rollNpcInitiative(Request $request): JsonResponse
    {
        $state = Cache::get(self::CACHE_KEY);
        if (!$state) {
            return response()->json(['error' => 'Brak aktywnej walki'], Response::HTTP_NOT_FOUND);
        }

        $tokenId = $request->integer('token_id', 0); // 0 = wszyscy NPC

        foreach ($state['participants'] as &$p) {
            if ($p['is_npc'] && $p['initiative'] === null) {
                if ($tokenId === 0 || (int) $p['token_id'] === $tokenId) {
                    $roll          = random_int(1, 10);
                    $p['roll']       = $roll;
                    $p['initiative'] = $p['zr'] + $roll;
                }
            }
        }
        unset($p);

        $state['participants'] = $this->sortParticipants($state['participants']);

        Cache::put(self::CACHE_KEY, $state, self::CACHE_TTL);
        broadcast(new CombatEvent('updated', $state));

        // Wiadomości czatu dla każdego rzuconego NPC
        foreach ($state['participants'] as $p) {
            if ($p['is_npc'] && $p['initiative'] !== null) {
                if ($tokenId === 0 || (int) $p['token_id'] === $tokenId) {
                    $text    = "🎲 Rzut na inicjatywę: Zr ({$p['zr']}) + k10 [{$p['roll']}] = {$p['initiative']}";
                    $message = $this->chatRepository->saveMessage($request->user()->id, $p['name'], $text, 'roll');
                    broadcast(new MessageSentEvent($message));
                }
            }
        }

        return response()->json($state);
    }

    // ── POST /session/combat/roll-hero ───────────────────────────────────────
    public function rollHeroInitiative(Request $request): JsonResponse
    {
        $state = Cache::get(self::CACHE_KEY);
        if (!$state) {
            return response()->json(['error' => 'Brak aktywnej walki'], Response::HTTP_NOT_FOUND);
        }

        $heroId = (int) $request->user()->hero()->value('id');
        $rolled = false;

        foreach ($state['participants'] as &$p) {
            if (!$p['is_npc'] && (int) $p['hero_id'] === $heroId && $p['initiative'] === null) {
                $roll          = random_int(1, 10);
                $p['roll']       = $roll;
                $p['initiative'] = $p['zr'] + $roll;
                $rolled        = true;
            }
        }
        unset($p);

        if (!$rolled) {
            return response()->json(
                ['error' => 'Już rzucono lub brak uczestnika'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $state['participants'] = $this->sortParticipants($state['participants']);

        Cache::put(self::CACHE_KEY, $state, self::CACHE_TTL);
        broadcast(new CombatEvent('updated', $state));

        // Wiadomość czatu dla rzutu bohatera
        foreach ($state['participants'] as $p) {
            if (!$p['is_npc'] && (int) $p['hero_id'] === $heroId) {
                $text    = "🎲 Rzut na inicjatywę: Zr ({$p['zr']}) + k10 [{$p['roll']}] = {$p['initiative']}";
                $message = $this->chatRepository->saveMessage($request->user()->id, $p['name'], $text, 'roll');
                broadcast(new MessageSentEvent($message));
                break;
            }
        }

        return response()->json($state);
    }

    // ── PATCH /session/combat/turn ───────────────────────────────────────────
    public function setTurn(Request $request): JsonResponse
    {
        $request->validate(['direction' => ['required', 'in:next,prev']]);

        $state = Cache::get(self::CACHE_KEY);
        if (!$state) {
            return response()->json(['error' => 'Brak aktywnej walki'], Response::HTTP_NOT_FOUND);
        }

        $count     = count($state['participants']);
        $direction = $request->input('direction');

        if ($direction === 'next') {
            $newIndex = $state['current_index'] + 1;

            if ($newIndex >= $count) {
                // Koniec rundy → nowa runda, kolejność inicjatywy bez zmian
                $state['round']++;
                $state['current_index'] = 0;
            } else {
                $state['current_index'] = $newIndex;
            }
        } else {
            // Cofnięcie — nie wychodzimy poza rundę
            $state['current_index'] = max(0, $state['current_index'] - 1);
        }

        Cache::put(self::CACHE_KEY, $state, self::CACHE_TTL);
        broadcast(new CombatEvent('updated', $state));

        return response()->json($state);
    }

    // ── DELETE /session/combat ───────────────────────────────────────────────
    public function end(): JsonResponse
    {
        // Nadpisujemy stan jako nieaktywny zamiast kasować klucz —
        // Cache::forget może zawodzić na Windows (blokady pliku).
        Cache::put(self::CACHE_KEY, ['active' => false], 60);
        broadcast(new CombatEvent('ended', null));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    // ── POST /session/tokens/{token}/roll ────────────────────────────────────
    // MG rzuca test cechy / umiejętności za NPC
    public function rollNpcTest(Request $request, Token $token): JsonResponse
    {
        $request->validate([
            'characteristic' => ['required', 'string', 'max:10'],
            'label'          => ['nullable', 'string', 'max:80'],
            'modifier'       => ['integer', 'min:-40', 'max:40'],
            'half'           => ['boolean'],
        ]);

        $sheet = $token->sheet;
        if (!$sheet || empty($sheet['characteristics'])) {
            return response()->json(['error' => 'Token nie ma karty postaci'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $char     = $request->input('characteristic');
        $rawValue = $sheet['characteristics'][$char] ?? null;

        if ($rawValue === null) {
            return response()->json(['error' => "Brak cechy: {$char}"], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $modifier  = $request->integer('modifier', 0);
        $half      = $request->boolean('half', false);
        $label     = $request->input('label', $char);
        $base      = $half ? intdiv((int) $rawValue, 2) : (int) $rawValue;
        $effective = max(0, $base + $modifier);

        $roll   = random_int(1, 100);
        $passed = $roll <= $effective;

        $text = json_encode([
            'skill'                => $label,
            'characteristic'       => $char,
            'characteristic_value' => (int) $rawValue,
            'effective_value'      => $effective,
            'modifier'             => $modifier,
            'half'                 => $half,
            'roll'                 => $roll,
            'passed'               => $passed,
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        $message = $this->chatRepository->saveMessage($request->user()->id, $token->name, $text, 'skill_test');
        broadcast(new MessageSentEvent($message));

        return response()->json(['message' => $message], Response::HTTP_CREATED);
    }

    // ────────────────────────────────────────────────────────────────────────
    private function sortParticipants(array $participants): array
    {
        usort($participants, static function (array $a, array $b): int {
            $ai = $a['initiative'] ?? PHP_INT_MIN;
            $bi = $b['initiative'] ?? PHP_INT_MIN;
            return $bi <=> $ai; // malejąco
        });

        return $participants;
    }
}
