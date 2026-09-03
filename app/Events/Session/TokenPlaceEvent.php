<?php

namespace App\Events\Session;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TokenPlaceEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int $id,
        public readonly int $x,
        public readonly int $y,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('token-move')];
    }

    public function broadcastAs(): string
    {
        return 'token-placed';
    }

    public function broadcastWith(): array
    {
        return ['id' => $this->id, 'x' => $this->x, 'y' => $this->y];
    }
}
