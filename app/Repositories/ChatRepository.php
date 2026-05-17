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
}
