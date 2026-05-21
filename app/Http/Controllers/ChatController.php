<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\ChatRepository;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends Controller
{
    public function __construct(
        private readonly ChatRepository $chatRepository,
        private readonly ChatService $chatService,
    ) {}

    public function getMessages(): JsonResponse
    {
        try {
            return response()->json($this->chatRepository->getMessages(24));
        } catch (\Throwable $exception) {
            Log::error('Error during getting messages', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas pobierania wiadomości'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'text' => ['required', 'string', 'min:1', 'max:1000'],
        ]);

        try {
            $message = $this->chatService->sendMessage($request->user(), $request->input('text'));
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Error during sending message', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas wysyłania wiadomości'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rollInitiative(Request $request): JsonResponse
    {
        try {
            $message = $this->chatService->rollInitiative($request->user());
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Error during rolling initiative', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas rzutu na inicjatywę'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSkillsForRoll(Request $request): JsonResponse
    {
        try {
            return response()->json($this->chatService->getSkillsForHero($request->user()));
        } catch (\Throwable $exception) {
            Log::error('Error during getting skills for roll', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas pobierania umiejętności'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rollSkill(Request $request): JsonResponse
    {
        $request->validate([
            'skill_id' => ['required', 'integer', 'exists:skills,id'],
            'modifier' => ['integer', 'min:-40', 'max:40', 'multiple_of:10'],
            'half'     => ['boolean'],
        ]);

        try {
            $message = $this->chatService->rollSkill(
                $request->user(),
                $request->integer('skill_id'),
                $request->integer('modifier', 0),
                $request->boolean('half', false),
            );
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Error during skill roll', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas testu umiejętności'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
