<?php

namespace App\Events\Session;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DrawingCreateEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly array $data,
        public readonly string $type = 'pen',
        public readonly string $layer = 'map',
        public readonly int $id = 0,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('drawings')];
    }

    public function broadcastAs(): string
    {
        return 'drawing-create';
    }

    public function broadcastWith(): array
    {
        return [
            'id'    => $this->id,
            'type'  => $this->type,
            'data'  => $this->data,
            'layer' => $this->layer,
        ];
    }
}
