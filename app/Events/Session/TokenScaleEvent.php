<?php

namespace App\Events\Session;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TokenScaleEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $id, public float $scale) {}

    public function broadcastOn(): array
    {
        return [new Channel('token-move')];
    }

    public function broadcastAs(): string
    {
        return 'scale';
    }
}
