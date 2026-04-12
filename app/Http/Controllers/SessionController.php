<?php

namespace App\Http\Controllers;

use App\Events\Session\MoveBatchTokenEvent;
use App\Events\Session\MoveTokenEvent;
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
        return view('Pages.session.index', compact('heroId'));
    }

    public function moveToken(Request $request, Token $token, TokensRepository $tokensRepository): \Illuminate\Http\JsonResponse
    {
        $tokensRepository->moveToken($token->getAttribute('id'), $request->input('x'), $request->input('y'));
        broadcast(new MoveTokenEvent($token->getAttribute('id'), $token->getAttribute('x'), $token->getAttribute('y')))->toOthers();
        return response()->json($token);
    }

    public function bulkMove(Request $request, TokensRepository $tokensRepository): \Illuminate\Http\JsonResponse
    {
        $tokensRepository->moveMultipleToken($request->input('tokens'));
        broadcast(new MoveBatchTokenEvent($request->input('tokens')))->toOthers();
        return response()->json('OK', Response::HTTP_OK);
    }
}
