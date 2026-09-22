<?php

namespace App\Http\Controllers;

use App\Events\Session\MoveBatchTokenEvent;
use App\Events\Session\MoveTokenEvent;
use App\Events\Session\PingPlayersEvent;
use App\Events\Session\TokenPlaceEvent;
use App\Events\Session\TokenRemoveFromMapEvent;
use App\Events\Session\TokenScaleEvent;
use App\Services\TokenService;
use App\Support\CurrentCampaign;
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
        // GM może nie mieć własnej postaci — getHero() zwraca wtedy null; 0 nigdy nie
        // dopasuje się do prawdziwego hero_id, więc "to mój token" po prostu nigdy nie zajdzie
        $heroId = $heroesRepository->getHero($request->user()->getAuthIdentifier(), $this->currentCampaign()->id())?->id ?? 0;
        $hasDrawingPermission = $this->currentCampaign()->isGm();
        $isGm = $this->currentCampaign()->isGm();
        $campaignId = $this->currentCampaign()->id();
        return view('Pages.session.index', compact('heroId', 'hasDrawingPermission', 'isGm', 'campaignId'));
    }

    public function moveToken(Request $request, Token $token, TokensRepository $tokensRepository): \Illuminate\Http\JsonResponse
    {
        $this->abortUnlessGm();

        $data = $request->validate([
            'x' => ['required', 'numeric'],
            'y' => ['required', 'numeric'],
        ]);

        $x = (float) $data['x'];
        $y = (float) $data['y'];

        $tokensRepository->moveToken($token->getAttribute('id'), $this->currentCampaign()->id(), $x, $y);

        try {
            broadcast(new MoveTokenEvent($token->getAttribute('id'), $x, $y, $this->currentCampaign()->id()))->toOthers();
        } catch (BroadcastException $e) {
            Log::warning('Token moved but broadcast failed', ['exception' => $e]);
        }

        return response()->json(['id' => $token->getAttribute('id'), 'x' => $x, 'y' => $y]);
    }

    public function bulkMove(Request $request, TokensRepository $tokensRepository): \Illuminate\Http\JsonResponse
    {
        $this->abortUnlessGm();

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

        $tokensRepository->moveMultipleToken($tokens, $this->currentCampaign()->id());

        try {
            broadcast(new MoveBatchTokenEvent($tokens, $this->currentCampaign()->id()))->toOthers();
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
            broadcast(new PingPlayersEvent($data, $this->currentCampaign()->id()))->toOthers();
        } catch (BroadcastException $e) {
            Log::warning('Ping broadcast failed', ['exception' => $e]);
        }

        return response()->json('OK', Response::HTTP_OK);
    }

    public function placeToken(Request $request, Token $token, TokensRepository $tokensRepository): \Illuminate\Http\JsonResponse
    {
        $this->abortUnlessGm();
        $request->validate(['x' => 'required|numeric', 'y' => 'required|numeric']);
        $x = (int) $request->input('x');
        $y = (int) $request->input('y');
        $tokensRepository->placeToken($token->id, $this->currentCampaign()->id(), $x, $y);
        try {
            broadcast(new TokenPlaceEvent($token->id, $x, $y, $this->currentCampaign()->id()))->toOthers();
        } catch (BroadcastException $e) {
            Log::warning('Token placed but broadcast failed', ['exception' => $e]);
        }
        return response()->json(['id' => $token->id, 'x' => $x, 'y' => $y]);
    }

    public function removeTokenFromMap(Token $token, TokensRepository $tokensRepository): \Illuminate\Http\JsonResponse
    {
        $this->abortUnlessGm();
        $tokensRepository->removeTokenFromMap($token->id, $this->currentCampaign()->id());
        try {
            broadcast(new TokenRemoveFromMapEvent($token->id, $this->currentCampaign()->id()))->toOthers();
        } catch (BroadcastException $e) {
            Log::warning('Token removed from map but broadcast failed', ['exception' => $e]);
        }
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function scaleToken(Request $request, Token $token, TokensRepository $tokensRepository): \Illuminate\Http\JsonResponse
    {
        $this->abortUnlessGm();
        $scale = (float) $request->input('scale', 1.0);
        $scale = max(0.1, min(10.0, $scale));
        $tokensRepository->scaleToken($token->id, $this->currentCampaign()->id(), $scale);
        try {
            broadcast(new TokenScaleEvent($token->id, $scale, $this->currentCampaign()->id()))->toOthers();
        } catch (BroadcastException $e) {
            Log::warning('Token scaled but broadcast failed', ['exception' => $e]);
        }
        return response()->json(['id' => $token->id, 'scale' => $scale]);
    }

    public function duplicateToken(Token $token, TokenService $tokenService): \Illuminate\Http\JsonResponse
    {
        $this->abortUnlessGm();
        try {
            $newToken = $tokenService->duplicateToken($token->id, $this->currentCampaign()->id());
            return response()->json($newToken->append('image_url'));
        } catch (\Throwable $e) {
            Log::error('Error duplicating token', ['exception' => $e]);
            return response()->json(['error' => 'Błąd duplikowania tokenu'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
