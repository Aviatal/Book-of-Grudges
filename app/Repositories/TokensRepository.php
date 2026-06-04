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
    public function getToken(int $id, array $relations = []): Token
    {
        return Token::query()
            ->when(count($relations) > 0, function (Builder $query) use ($relations) {
                $query->with($relations);
            })
            ->findOrFail($id);
    }

    public function createToken(array $data): Token
    {
        return Token::query()->create($data);
    }

    public function updateAndRequestToken(Token $token, array $data): Token
    {
        $token->update($data);
        return $token->refresh();
    }

    public function deleteToken(Token $token): bool
    {
        return $token->delete();
    }

    public function moveToken(int $tokenId, int $x, int $y): int
    {
        return Token::query()->where('id', $tokenId)->update(['x' => $x, 'y' => $y]);
    }
    public function moveMultipleToken(array $tokens): int
    {
        return Token::query()->upsert($tokens, 'id', ['x', 'y']);
    }

    public function placeToken(int $tokenId, int $x, int $y): int
    {
        return Token::query()->where('id', $tokenId)->update(['x' => $x, 'y' => $y, 'on_map' => true]);
    }

    public function removeTokenFromMap(int $tokenId): int
    {
        return Token::query()->where('id', $tokenId)->update(['on_map' => false]);
    }

    public function scaleToken(int $tokenId, float $scale): int
    {
        return Token::query()->where('id', $tokenId)->update(['scale' => $scale]);
    }
}
