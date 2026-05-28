<?php

namespace App\Events\Session;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CombatEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param string     $type  'updated' | 'ended'
     * @param array|null $state pełny stan walki lub null gdy zakończona
     */
    public function __construct(
        public readonly string  $type,
        public readonly ?array  $state,
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('combat')];
    }

    public function broadcastAs(): string
    {
        return 'combat';
    }

    public function broadcastWith(): array
    {
        return [
            'type'  => $this->type,
            'state' => $this->state,
        ];
    }
}
