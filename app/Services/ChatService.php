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

    public function rollInitiative(User $user): Message
    {
        $hero = $user->hero()->with('characteristic')->first();
        $authorName = $hero?->name ?? $user->name;

        $zr = $hero?->characteristic['Zr'];
        $zrValue = $zr ? ($zr->pivot->start_value + $zr->pivot->advancement) : 0;

        $roll = random_int(1, 10);
        $total = $zrValue + $roll;

        $text = "🎲 Rzut na inicjatywę: Zr ({$zrValue}) + k10 [{$roll}] = {$total}";

        $message = $this->chatRepository->saveMessage($user->id, $authorName, $text, 'roll');

        broadcast(new MessageSentEvent($message));

        return $message;
    }
}
