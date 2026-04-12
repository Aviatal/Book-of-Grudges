<?php

namespace App\Repositories;

use App\Models\Drawing;

class DrawingsRepository
{
    public function fetchDrawings(): \Illuminate\Database\Eloquent\Collection
    {
        return Drawing::all();
    }
    public function storeDrawing(array $data)
    {
        return Drawing::query()->create($data);
    }
    public function updateDrawing(int $drawingId, array $data): int
    {
        return Drawing::query()->where('id', $drawingId)->update(['data' => $data]);
    }
    public function deleteDrawing(int $drawingId)
    {
        return Drawing::query()->where('id', $drawingId)->delete();
    }
}
