<?php

namespace App\Repositories;

use App\Models\Token;
use Illuminate\Database\Eloquent\Builder;

class TokensRepository
{
    public function getTokens(array $relations = []): \Illuminate\Database\Eloquent\Collection
    {
        return Token::query()
            ->when(count($relations) > 0, function (Builder $query) use ($relations) {
                $query->with($relations);
            })
            ->get();
    }

    public function moveToken(int $tokenId, int $x, int $y): int
    {
        return Token::query()->where('id', $tokenId)->update(['x' => $x, 'y' => $y]);
    }
}
