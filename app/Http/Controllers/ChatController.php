<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Exceptions\FortuneRerollNotAllowedException;
use App\Exceptions\NotEnoughFortunePointsException;
use App\Repositories\ChatRepository;
use App\Services\ChatService;
use App\Support\CurrentCampaign;
use Illuminate\Auth\Access\AuthorizationException;
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
            return response()->json($this->chatRepository->getMessages($this->currentCampaign()->id(), 24));
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
            $message = $this->chatService->sendMessage($request->user(), $request->input('text'), $this->currentCampaign()->id());
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Error during sending message', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas wysyłania wiadomości'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rollInitiative(Request $request): JsonResponse
    {
        try {
            $message = $this->chatService->rollInitiative($request->user(), $this->currentCampaign()->id());
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Error during rolling initiative', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas rzutu na inicjatywę'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSkillsForRoll(Request $request): JsonResponse
    {
        try {
            return response()->json($this->chatService->getSkillsForHero($request->user(), $this->currentCampaign()->id()));
        } catch (\Throwable $exception) {
            Log::error('Error during getting skills for roll', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas pobierania umiejętności'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rollCharacteristic(Request $request): JsonResponse
    {
        $request->validate([
            'characteristic' => ['required', 'string', 'max:10'],
            'modifier'       => ['integer', 'min:-40', 'max:40'],
            'half'           => ['boolean'],
        ]);

        try {
            $message = $this->chatService->rollCharacteristic(
                $request->user(),
                $request->input('characteristic'),
                $this->currentCampaign()->id(),
                $request->integer('modifier'),
                $request->boolean('half'),
            );
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Error during characteristic roll', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas rzutu na cechę'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rollDice(Request $request): JsonResponse
    {
        $request->validate([
            'count' => ['required', 'integer', 'min:1', 'max:20'],
            'sides' => ['required', 'integer', 'in:4,6,8,10,12,20,100'],
        ]);

        try {
            $message = $this->chatService->rollDice(
                $request->user(),
                $request->integer('count'),
                $request->integer('sides'),
                $this->currentCampaign()->id(),
            );
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Error during dice roll', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas rzutu kośćmi'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
                $this->currentCampaign()->id(),
                $request->integer('modifier', 0),
                $request->boolean('half', false),
            );
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Error during skill roll', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas testu umiejętności'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rerollWithFortunePoint(Request $request): JsonResponse
    {
        $request->validate([
            'message_id' => ['required', 'integer'],
        ]);

        try {
            $message = $this->chatService->rerollWithFortunePoint(
                $request->user(),
                $request->integer('message_id'),
                $this->currentCampaign()->id(),
            );
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (NotEnoughFortunePointsException $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (FortuneRerollNotAllowedException $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $exception) {
            Log::error('Error during fortune point reroll', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas wydawania punktu szczęścia'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getPrivateMessages(Request $request): JsonResponse
    {
        try {
            return response()->json($this->chatRepository->getPrivateMessages(
                $this->currentCampaign()->id(),
                $request->user()->getAuthIdentifier(),
                24,
            ));
        } catch (\Throwable $exception) {
            Log::error('Error during getting private messages', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas pobierania wiadomości prywatnych'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getPrivateContacts(): JsonResponse
    {
        try {
            return response()->json($this->chatService->getPrivateContacts($this->currentCampaign()->id(), $this->currentCampaign()->isGm()));
        } catch (\Throwable $exception) {
            Log::error('Error during getting private contacts', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas pobierania listy rozmówców'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sendPrivateMessage(Request $request): JsonResponse
    {
        $request->validate([
            'text'         => ['required', 'string', 'min:1', 'max:1000'],
            'recipient_id' => ['required', 'integer'],
        ]);

        try {
            $message = $this->chatService->sendPrivateMessage(
                $request->user(),
                $request->input('text'),
                $request->integer('recipient_id'),
                $this->currentCampaign()->id(),
                $this->currentCampaign()->isGm(),
            );
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (AuthorizationException $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (\Throwable $exception) {
            Log::error('Error during sending private message', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas wysyłania wiadomości'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rollPrivateCharacteristic(Request $request): JsonResponse
    {
        $request->validate([
            'recipient_id'   => ['required', 'integer'],
            'characteristic' => ['required', 'string', 'max:10'],
            'modifier'       => ['integer', 'min:-40', 'max:40'],
            'half'           => ['boolean'],
        ]);

        try {
            $message = $this->chatService->rollPrivateCharacteristic(
                $request->user(),
                $request->input('characteristic'),
                $request->integer('recipient_id'),
                $this->currentCampaign()->id(),
                $this->currentCampaign()->isGm(),
                $request->integer('modifier'),
                $request->boolean('half'),
            );
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (AuthorizationException $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (\Throwable $exception) {
            Log::error('Error during private characteristic roll', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas rzutu na cechę'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rollPrivateSkill(Request $request): JsonResponse
    {
        $request->validate([
            'recipient_id' => ['required', 'integer'],
            'skill_id'     => ['required', 'integer', 'exists:skills,id'],
            'modifier'     => ['integer', 'min:-40', 'max:40', 'multiple_of:10'],
            'half'         => ['boolean'],
        ]);

        try {
            $message = $this->chatService->rollPrivateSkill(
                $request->user(),
                $request->integer('skill_id'),
                $request->integer('recipient_id'),
                $this->currentCampaign()->id(),
                $this->currentCampaign()->isGm(),
                $request->integer('modifier', 0),
                $request->boolean('half', false),
            );
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (AuthorizationException $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (\Throwable $exception) {
            Log::error('Error during private skill roll', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas testu umiejętności'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rollPrivateDice(Request $request): JsonResponse
    {
        $request->validate([
            'recipient_id' => ['required', 'integer'],
            'count'        => ['required', 'integer', 'min:1', 'max:20'],
            'sides'        => ['required', 'integer', 'in:4,6,8,10,12,20,100'],
        ]);

        try {
            $message = $this->chatService->rollPrivateDice(
                $request->user(),
                $request->integer('count'),
                $request->integer('sides'),
                $request->integer('recipient_id'),
                $this->currentCampaign()->id(),
                $this->currentCampaign()->isGm(),
            );
            return response()->json(['message' => $message], Response::HTTP_CREATED);
        } catch (AuthorizationException $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (\Throwable $exception) {
            Log::error('Error during private dice roll', ['exception' => $exception]);
            return response()->json(['error' => 'Wystąpił błąd podczas rzutu kośćmi'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
