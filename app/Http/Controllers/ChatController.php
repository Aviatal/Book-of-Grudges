<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\ChatRepository;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends Controller
{
    public function __construct(
        private readonly ChatRepository $chatRepository,
        private readonly ChatService $chatService,
    ) {}

    public function getMessages()
    {
        try {
            return response()->json($this->chatRepository->getMessages(24));
        } catch (\Throwable $exception) {
            Log::error('Error during getting messages');
            Log::error($exception);
            return response()->json(['error' => 'Wystąpił bład podczas pobierania wiadomości'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'text' => ['required', 'string', 'min:1', 'max:1000'],
        ]);

        try {
            $message = $this->chatService->sendMessage($request->user(), $request->input('text'));
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Error during sending message');
            Log::error($exception);
            return response()->json(['error' => 'Wystąpił błąd podczas wysyłania wiadomości'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
