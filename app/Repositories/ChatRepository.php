<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

class ChatRepository
{
    public function getMessages(int $campaignId, int $hours): Collection
    {
        return Message::query()
            ->where('campaign_id', $campaignId)
            ->where('created_at', '>=', now()->subHours($hours))
            ->get();
    }

    public function saveMessage(int $userId, string $authorName, string $text, int $campaignId, string $type = 'chat'): Message
    {
        return Message::create([
            'user_id'     => $userId,
            'author_name' => $authorName,
            'text'        => $text,
            'type'        => $type,
            'campaign_id' => $campaignId,
        ]);
    }
}
