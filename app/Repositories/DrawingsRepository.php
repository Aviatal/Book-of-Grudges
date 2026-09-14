<?php

namespace App\Repositories;

use App\Models\Drawing;
use Illuminate\Database\Eloquent\Collection;

class DrawingsRepository
{
    public function fetchDrawings(int $campaignId): Collection
    {
        return Drawing::where('campaign_id', $campaignId)->get();
    }

    public function storeDrawing(array $data, int $campaignId): Drawing
    {
        return Drawing::query()->create([
            'type'        => $data['type'],
            'layer'       => $data['layer'] ?? 'map',
            'data'        => $data['data'] ?? [],
            'campaign_id' => $campaignId,
        ]);
    }

    public function updateDrawing(int $drawingId, int $campaignId, array $data): bool
    {
        return (bool) Drawing::query()->where('id', $drawingId)->where('campaign_id', $campaignId)->update(['data' => $data]);
    }

    public function updateDrawingLayer(int $drawingId, int $campaignId, string $layer): bool
    {
        return (bool) Drawing::query()->where('id', $drawingId)->where('campaign_id', $campaignId)->update(['layer' => $layer]);
    }

    public function deleteDrawing(int $drawingId, int $campaignId): bool
    {
        return (bool) Drawing::query()->where('id', $drawingId)->where('campaign_id', $campaignId)->delete();
    }
}
