<?php

namespace App\Repositories;

use App\Models\CampaignMember;
use App\Models\Hero;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

class ChatRepository
{
    public function getMessages(int $campaignId, int $hours): Collection
    {
        return Message::query()
            ->where('campaign_id', $campaignId)
            ->whereNull('recipient_id')
            ->where('created_at', '>=', now()->subHours($hours))
            ->get();
    }

    public function saveMessage(int $userId, string $authorName, string $text, int $campaignId, string $type = 'chat'): Message
    {
        return Message::create([
            'user_id' => $userId,
            'author_name' => $authorName,
            'text' => $text,
            'type' => $type,
            'campaign_id' => $campaignId,
        ]);
    }

    public function getPrivateMessages(int $campaignId, int $userId, int $hours): Collection
    {
        return Message::query()
            ->where('campaign_id', $campaignId)
            ->whereNotNull('recipient_id')
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)->orWhere('recipient_id', $userId);
            })
            ->where('created_at', '>=', now()->subHours($hours))
            ->orderBy('created_at')
            ->get();
    }

    public function savePrivateMessage(
        int $userId,
        int $recipientId,
        string $authorName,
        string $text,
        int $campaignId,
        string $type = 'chat',
    ): Message {
        return Message::create([
            'user_id' => $userId,
            'recipient_id' => $recipientId,
            'author_name' => $authorName,
            'text' => $text,
            'type' => $type,
            'campaign_id' => $campaignId,
        ]);
    }

    /**
     * @return array<int, array{user_id: int, name: string}>
     */
    public function getPrivateContacts(int $campaignId, bool $isGm): array
    {
        if ($isGm) {
            $heroNamesByUserId = Hero::where('campaign_id', $campaignId)->pluck('name', 'user_id');

            return CampaignMember::query()
                ->where('campaign_id', $campaignId)
                ->where('role', CampaignMember::ROLE_PLAYER)
                ->with('user')
                ->get()
                ->map(fn (CampaignMember $member): array => [
                    'user_id' => $member->user_id,
                    'name' => $heroNamesByUserId[$member->user_id] ?? $member->user->name,
                ])
                ->values()
                ->all();
        }

        $gmMember = CampaignMember::query()
            ->where('campaign_id', $campaignId)
            ->where('role', CampaignMember::ROLE_GM)
            ->first();

        if (! $gmMember) {
            return [];
        }

        return [['user_id' => $gmMember->user_id, 'name' => 'Mistrz Gry']];
    }
}
