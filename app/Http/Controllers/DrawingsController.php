<?php

namespace App\Http\Controllers;

use App\Events\Session\DrawingCreateEvent;
use App\Events\Session\DrawingDeleteEvent;
use App\Events\Session\DrawingLayerChangedEvent;
use App\Events\Session\DrawingUpdateEvent;
use App\Http\Requests\StoreDrawingRequest;
use App\Models\Drawing;
use App\Repositories\DrawingsRepository;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DrawingsController extends Controller
{
    public function getDrawings(DrawingsRepository $drawingsRepository): JsonResponse
    {
        try {
            return response()->json($drawingsRepository->fetchDrawings());
        } catch (\Throwable $exception) {
            Log::error('Error fetching drawings', ['exception' => $exception]);
            return response()->json(['error' => 'Błąd pobierania rysunków'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeDrawing(StoreDrawingRequest $request, DrawingsRepository $drawingsRepository): JsonResponse
    {
        try {
            $drawing = $drawingsRepository->storeDrawing($request->all());
            try {
                event(new DrawingCreateEvent($drawing->data, $drawing->layer, $drawing->id));
            } catch (BroadcastException $e) {
                Log::warning('Drawing created but broadcast failed', ['exception' => $e]);
            }
            return response()->json(['id' => $drawing->id], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Error storing drawing', ['exception' => $exception]);
            return response()->json(['error' => 'Błąd zapisu rysunku'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateDrawing(StoreDrawingRequest $request, int $drawingId, DrawingsRepository $drawingsRepository): JsonResponse
    {
        try {
            $drawingData = $request->input('data');
            $drawingsRepository->updateDrawing($drawingId, $drawingData);
            try {
                event(new DrawingUpdateEvent($drawingId, $drawingData));
            } catch (BroadcastException $e) {
                Log::warning('Drawing updated but broadcast failed', ['exception' => $e]);
            }
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            Log::error('Error updating drawing', ['exception' => $exception]);
            return response()->json(['error' => 'Błąd aktualizacji rysunku'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function moveDrawingToLayer(Request $request, int $drawingId, DrawingsRepository $drawingsRepository): JsonResponse
    {
        $request->validate([
            'layer' => ['required', 'string', 'in:' . implode(',', Drawing::LAYERS)],
        ]);

        try {
            $drawingsRepository->updateDrawingLayer($drawingId, $request->string('layer')->value());
            try {
                event(new DrawingLayerChangedEvent($drawingId, $request->string('layer')->value()));
            } catch (BroadcastException $e) {
                Log::warning('Drawing layer changed but broadcast failed', ['exception' => $e]);
            }
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            Log::error('Error moving drawing to layer', ['exception' => $exception]);
            return response()->json(['error' => 'Błąd przenoszenia rysunku'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteDrawing(int $drawingId, DrawingsRepository $drawingsRepository): JsonResponse
    {
        try {
            $drawingsRepository->deleteDrawing($drawingId);
            try {
                event(new DrawingDeleteEvent($drawingId));
            } catch (BroadcastException $e) {
                Log::warning('Drawing deleted but broadcast failed', ['exception' => $e]);
            }
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            Log::error('Error deleting drawing', ['exception' => $exception]);
            return response()->json(['error' => 'Błąd usuwania rysunku'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
