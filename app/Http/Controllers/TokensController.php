<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTokenRequest;
use App\Models\Token;
use App\Repositories\HeroesRepository;
use App\Repositories\TokensRepository;
use App\Services\TokenService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TokensController extends Controller
{
    public function getTokens(TokensRepository $tokensRepository): \Illuminate\Database\Eloquent\Collection
    {
        return $tokensRepository->getTokens(['hero.user']);
    }
    public function getToken(int $id, TokensRepository $tokensRepository): Token
    {
        return $tokensRepository->getToken($id, ['hero.user']);
    }

    public function index()
    {
        return view('Panel.tokens.index');
    }

    public function create(HeroesRepository $heroesRepository)
    {
        $activeHeroes = $heroesRepository->getHeroes(['id', 'name']);
        return view('Panel.tokens.create', ['heroes' => $activeHeroes]);
    }

    public function edit(int $id, HeroesRepository $heroesRepository)
    {
        $activeHeroes = $heroesRepository->getHeroes(['id', 'name']);
        return view('Panel.tokens.edit', ['tokenId' => $id, 'heroes' => $activeHeroes]);
    }

    public function store(UpdateTokenRequest $request, TokenService $tokenService)
    {
        try {
            $tokenService->storeToken($request->all());
            return response()->json([
                'redirect_url' => route('panel.tokens.index')
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Nie znaleziono tokenu'], ResponseAlias::HTTP_NOT_FOUND);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            Log::error('Error during creating token');
            Log::error($exception);
            return response()->json(['error' => 'Wystąpił błąd podczas dodawania tokenu.'], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateTokenRequest $request, int $id, TokenService $tokenService)
    {
        try {
            return $tokenService->updateToken($id, $request->all());
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Nie znaleziono tokenu'], ResponseAlias::HTTP_NOT_FOUND);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            Log::error('Error during updating token');
            Log::error($exception);
            return response()->json(['error' => 'Wystąpił błąd podczas edytowania tokenu.'], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function delete(int $id, TokenService $tokenService)
    {
        try {
            return $tokenService->deleteToken($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Nie znaleziono tokenu'], ResponseAlias::HTTP_NOT_FOUND);
        } catch (\Throwable $exception) {
            Log::error('Error during deleting token');
            Log::error($exception);
            return response()->json(['error' => 'Wystąpił błąd podczas usuwania tokenu.'], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
