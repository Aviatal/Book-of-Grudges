<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends Controller
{
    public function __construct(private readonly ChatRepository $chatRepository) {}
    public function getMessages()
    {
        try {
            return response()->json($this->chatRepository->getMessages(24));
        } catch (\Throwable $exception) {
            Log::error('Error during getting messages');
            Log::error($exception);
            return response()->json(['error' => 'Wystąpił bład podczas pobierania wiadomości', Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
