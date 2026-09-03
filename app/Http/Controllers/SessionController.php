<?php

namespace App\Http\Controllers;

use App\Events\Session\MoveBatchTokenEvent;
use App\Events\Session\MoveTokenEvent;
use App\Events\Session\PingPlayersEvent;
use App\Events\Session\TokenPlaceEvent;
use App\Events\Session\TokenRemoveFromMapEvent;
use App\Events\Session\TokenScaleEvent;
use App\Services\TokenService;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Support\Facades\Log;
use App\Models\Token;
use App\Repositories\HeroesRepository;
use App\Repositories\TokensRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends Controller
{
    public function index(Request $request, HeroesRepository $heroesRepository)
    {
        $heroId = $heroesRepository->getHero($request->user()->getAuthIdentifier())->id;
        $hasDrawingPermission = $request->user()->is_admin;
        return view('Pages.session.index', compact('heroId', 'hasDrawingPermission'));
    }

    public function moveToken(Request $request, Token $token, TokensRepository $tokensRepository): \Illuminate\Http\JsonResponse
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $data = $request->validate([
            'x' => ['required', 'numeric'],
            'y' => ['required', 'numeric'],
        ]);

        $x = (float) $data['x'];
        $y = (float) $data['y'];

        $tokensRepository->moveToken($token->getAttribute('id'), $x, $y);

        try {
            broadcast(new MoveTokenEvent($token->getAttribute('id'), $x, $y))->toOthers();
        } catch (BroadcastException $e) {
            Log::warning('Token moved but broadcast failed', ['exception' => $e]);
        }

        return response()->json(['id' => $token->getAttribute('id'), 'x' => $x, 'y' => $y]);
    }

    public function bulkMove(Request $request, TokensRepository $tokensRepository): \Illuminate\Http\JsonResponse
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $data = $request->validate([
            'tokens'      => ['required', 'array', 'min:1'],
            'tokens.*.id' => ['required', 'integer'],
            'tokens.*.x'  => ['required', 'numeric'],
            'tokens.*.y'  => ['required', 'numeric'],
        ]);

        // Bierzemy wyłącznie id/x/y — reszta pól z klienta (name/hero_id/image) jest ignorowana
        $tokens = array_map(static fn (array $t): array => [
            'id' => (int) $t['id'],
            'x'  => (float) $t['x'],
            'y'  => (float) $t['y'],
        ], $data['tokens']);

        $tokensRepository->moveMultipleToken($tokens);

        try {
            broadcast(new MoveBatchTokenEvent($tokens))->toOthers();
        } catch (BroadcastException $e) {
            Log::warning('Tokens bulk-moved but broadcast failed', ['exception' => $e]);
        }

        return response()->json('OK', Response::HTTP_OK);
    }

    public function pingPlayers(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'x'     => ['required', 'numeric'],
            'y'     => ['required', 'numeric'],
            'color' => ['nullable', 'string', 'max:32'],
        ]);

        try {
            broadcast(new PingPlayersEvent($data))->toOthers();
        } catch (BroadcastException $e) {
            Log::warning('Ping broadcast failed', ['exception' => $e]);
        }

        return response()->json('OK', Response::HTTP_OK);
    }

    public function placeToken(Request $request, Token $token, TokensRepository $tokensRepository): \Illuminate\Http\JsonResponse
    {
        abort_unless(auth()->user()?->is_admin, 403);
        $request->validate(['x' => 'required|numeric', 'y' => 'required|numeric']);
        $x = (int) $request->input('x');
        $y = (int) $request->input('y');
        $tokensRepository->placeToken($token->id, $x, $y);
        try {
            broadcast(new TokenPlaceEvent($token->id, $x, $y))->toOthers();
        } catch (BroadcastException $e) {
            Log::warning('Token placed but broadcast failed', ['exception' => $e]);
        }
        return response()->json(['id' => $token->id, 'x' => $x, 'y' => $y]);
    }

    public function removeTokenFromMap(Token $token, TokensRepository $tokensRepository): \Illuminate\Http\JsonResponse
    {
        abort_unless(auth()->user()?->is_admin, 403);
        $tokensRepository->removeTokenFromMap($token->id);
        try {
            broadcast(new TokenRemoveFromMapEvent($token->id))->toOthers();
        } catch (BroadcastException $e) {
            Log::warning('Token removed from map but broadcast failed', ['exception' => $e]);
        }
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function scaleToken(Request $request, Token $token, TokensRepository $tokensRepository): \Illuminate\Http\JsonResponse
    {
        abort_unless(auth()->user()?->is_admin, 403);
        $scale = (float) $request->input('scale', 1.0);
        $scale = max(0.1, min(10.0, $scale));
        $tokensRepository->scaleToken($token->id, $scale);
        try {
            broadcast(new TokenScaleEvent($token->id, $scale))->toOthers();
        } catch (BroadcastException $e) {
            Log::warning('Token scaled but broadcast failed', ['exception' => $e]);
        }
        return response()->json(['id' => $token->id, 'scale' => $scale]);
    }

    public function duplicateToken(Token $token, TokenService $tokenService): \Illuminate\Http\JsonResponse
    {
        abort_unless(auth()->user()?->is_admin, 403);
        try {
            $newToken = $tokenService->duplicateToken($token->id);
            return response()->json($newToken->append('image_url'));
        } catch (\Throwable $e) {
            Log::error('Error duplicating token', ['exception' => $e]);
            return response()->json(['error' => 'Błąd duplikowania tokenu'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
