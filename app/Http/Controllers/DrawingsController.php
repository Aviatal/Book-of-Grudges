<?php

namespace App\Http\Controllers;

use App\Events\Session\DrawingCreateEvent;
use App\Events\Session\DrawingDeleteEvent;
use App\Events\Session\DrawingUpdateEvent;
use App\Http\Requests\StoreDrawingRequest;
use App\Repositories\DrawingsRepository;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DrawingsController extends Controller
{
    public function getDrawings(DrawingsRepository $drawingsRepository): ?\Illuminate\Http\JsonResponse
    {
        try {
            return response()->json($drawingsRepository->fetchDrawings());
        } catch (\Throwable $exception) {
            Log::error('ERROR STORING DRAWING');
            Log::error($exception);
            return response()->json('error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeDrawing(StoreDrawingRequest $request, DrawingsRepository $drawingsRepository): \Illuminate\Http\JsonResponse
    {
        try {
            $drawing = $drawingsRepository->storeDrawing($request->all());
            event(new DrawingCreateEvent($drawing->getAttribute('data')));
            return response()->json('ok', Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            Log::error('ERROR STORING DRAWING');
            Log::error($exception);
            return response()->json('error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateDrawing(StoreDrawingRequest $request, int $drawingId, DrawingsRepository $drawingsRepository): \Illuminate\Http\JsonResponse
    {
        try {
            $drawingData = $request->input('data');
            $drawingsRepository->updateDrawing($drawingId, $drawingData);
            event(new DrawingUpdateEvent($drawingId, $drawingData));
            return response()->json('ok', Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            Log::error('ERROR STORING DRAWING');
            Log::error($exception);
            return response()->json('error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteDrawing(int $drawingId, DrawingsRepository $drawingsRepository): \Illuminate\Http\JsonResponse
    {
        try {
            $drawingsRepository->deleteDrawing($drawingId);
            event(new DrawingDeleteEvent($drawingId));
            return response()->json('ok', Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            Log::error('ERROR STORING DRAWING');
            Log::error($exception);
            return response()->json('error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
