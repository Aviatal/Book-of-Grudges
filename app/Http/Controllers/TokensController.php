<?php

namespace App\Http\Controllers;

use App\Repositories\TokensRepository;

class TokensController extends Controller
{
    public function getTokens(TokensRepository $tokensRepository): \Illuminate\Database\Eloquent\Collection
    {
        return $tokensRepository->getTokens();
    }
}
