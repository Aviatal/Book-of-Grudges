<?php

namespace App\Repositories;

use App\Models\Token;

class TokensRepository
{
    public function getTokens(): \Illuminate\Database\Eloquent\Collection
    {
        return Token::all();
    }

    public function moveToken(int $tokenId, int $x, int $y): int
    {
        return Token::query()->where('id', $tokenId)->update(['x' => $x, 'y' => $y]);
    }
}
