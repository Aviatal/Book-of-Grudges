<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AssetsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Asset::orderBy('created_at', 'desc')->get());
    }

    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'image', 'max:10240'],
            'type' => ['required', 'string', 'in:' . implode(',', Asset::TYPES)],
            'name' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('assets', config('filesystems.media'));

            $asset = Asset::create([
                'name'      => $request->input('name') ?? $file->getClientOriginalName(),
                'type'      => $request->input('type'),
                'file_path' => $path,
            ]);

            return response()->json($asset, Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Error uploading asset', ['exception' => $exception]);
            return response()->json(['error' => 'Błąd zapisu pliku'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $asset = Asset::findOrFail($id);
            Storage::disk(config('filesystems.media'))->delete($asset->file_path);
            $asset->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            Log::error('Error deleting asset', ['exception' => $exception]);
            return response()->json(['error' => 'Błąd usuwania pliku'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
