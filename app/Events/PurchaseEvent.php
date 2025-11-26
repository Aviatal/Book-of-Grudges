<?php

namespace App\Events;

use App\Models\MarketplaceItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PurchaseEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $heroId,
        public MarketplaceItem $item,
        public int $price,
        public string $customName
    ){}

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('hero.'.$this->heroId);
    }


    public function broadcastAs(): string
    {
        return 'hero.purchase';
    }
}
