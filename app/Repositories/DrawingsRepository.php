<?php

namespace App\Repositories;

use App\Models\Drawing;
use Illuminate\Database\Eloquent\Collection;

class DrawingsRepository
{
    public function fetchDrawings(): Collection
    {
        return Drawing::all();
    }

    public function storeDrawing(array $data): Drawing
    {
        return Drawing::query()->create([
            'type'  => $data['type'],
            'layer' => $data['layer'] ?? 'map',
            'data'  => $data['data'] ?? [],
        ]);
    }

    public function updateDrawing(int $drawingId, array $data): bool
    {
        return (bool) Drawing::query()->where('id', $drawingId)->update(['data' => $data]);
    }

    public function updateDrawingLayer(int $drawingId, string $layer): bool
    {
        return (bool) Drawing::query()->where('id', $drawingId)->update(['layer' => $layer]);
    }

    public function deleteDrawing(int $drawingId): bool
    {
        return (bool) Drawing::query()->where('id', $drawingId)->delete();
    }
}
