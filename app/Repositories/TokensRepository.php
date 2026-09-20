<?php

namespace App\Repositories;

use App\Models\Token;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TokensRepository
{
    public function getTokens(int $campaignId, array $relations = []): \Illuminate\Database\Eloquent\Collection
    {
        return Token::query()
            ->where('campaign_id', $campaignId)
            ->when(count($relations) > 0, function (Builder $query) use ($relations) {
                $query->with($relations);
            })
            ->get();
    }
    public function getToken(int $id, int $campaignId, array $relations = []): Token
    {
        return Token::query()
            ->where('campaign_id', $campaignId)
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

    public function moveToken(int $tokenId, int $campaignId, float $x, float $y): int
    {
        return Token::query()->where('id', $tokenId)->where('campaign_id', $campaignId)->update(['x' => $x, 'y' => $y]);
    }
    public function moveMultipleToken(array $tokens, int $campaignId): int
    {
        $allowedIds = Token::query()
            ->where('campaign_id', $campaignId)
            ->whereIn('id', array_column($tokens, 'id'))
            ->pluck('id')
            ->all();

        $filtered = array_values(array_filter($tokens, static fn (array $t): bool => in_array($t['id'], $allowedIds, true)));

        if (empty($filtered)) {
            return 0;
        }

        // Nie używamy upsert(): Postgres sprawdza NOT NULL (np. `name`) na wstawianym wierszu
        // jeszcze przed rozpoznaniem konfliktu, więc INSERT ... ON CONFLICT wywala się na produkcji.
        return DB::transaction(static function () use ($filtered, $campaignId): int {
            $affected = 0;

            foreach ($filtered as $t) {
                $affected += Token::query()
                    ->where('id', $t['id'])
                    ->where('campaign_id', $campaignId)
                    ->update(['x' => $t['x'], 'y' => $t['y']]);
            }

            return $affected;
        });
    }

    public function placeToken(int $tokenId, int $campaignId, float $x, float $y): int
    {
        return Token::query()->where('id', $tokenId)->where('campaign_id', $campaignId)->update(['x' => $x, 'y' => $y, 'on_map' => true]);
    }

    public function removeTokenFromMap(int $tokenId, int $campaignId): int
    {
        return Token::query()->where('id', $tokenId)->where('campaign_id', $campaignId)->update(['on_map' => false]);
    }

    public function scaleToken(int $tokenId, int $campaignId, float $scale): int
    {
        return Token::query()->where('id', $tokenId)->where('campaign_id', $campaignId)->update(['scale' => $scale]);
    }
}
