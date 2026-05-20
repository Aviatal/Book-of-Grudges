<?php

namespace App\Services;

use App\Events\Session\MessageSentEvent;
use App\Models\Message;
use App\Models\User;
use App\Repositories\ChatRepository;

class ChatService
{
    public function __construct(private readonly ChatRepository $chatRepository) {}

    public function sendMessage(User $user, string $text): Message
    {
        $authorName = $user->hero()->value('name') ?? $user->name;

        $message = $this->chatRepository->saveMessage($user->id, $authorName, $text);

        broadcast(new MessageSentEvent($message));

        return $message;
    }
}
