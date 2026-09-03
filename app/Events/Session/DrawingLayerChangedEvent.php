<?php

namespace App\Events\Session;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DrawingLayerChangedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int $drawingId,
        public readonly string $layer,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('drawings')];
    }

    public function broadcastAs(): string
    {
        return 'drawing-layer-changed';
    }

    public function broadcastWith(): array
    {
        return [
            'drawingId' => $this->drawingId,
            'layer'     => $this->layer,
        ];
    }
}
