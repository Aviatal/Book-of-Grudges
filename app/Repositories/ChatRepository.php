<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

class ChatRepository
{
    public function getMessages(int $hours): Collection
    {
        return Message::query()
            ->where('created_at', '>=', now()->subHours($hours))
            ->get();
    }

    public function saveMessage(int $userId, string $authorName, string $text, string $type = 'chat'): Message
    {
        return Message::create([
            'user_id'     => $userId,
            'author_name' => $authorName,
            'text'        => $text,
            'type'        => $type,
        ]);
    }
}
