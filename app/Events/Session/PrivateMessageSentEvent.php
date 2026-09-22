<?php

namespace App\Events\Session;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateMessageSentEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message, public int $campaignId) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("private-chat.{$this->campaignId}.{$this->message->user_id}"),
            new PrivateChannel("private-chat.{$this->campaignId}.{$this->message->recipient_id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'private-message-sent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
        ];
    }
}
