<?php

namespace App\Events\Session;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TokenRenameEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int $tokenId,
        public readonly string $name,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('token-move'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'rename';
    }

    public function broadcastWith(): array
    {
        return ['id' => $this->tokenId, 'name' => $this->name];
    }
}
