<?php

use App\Models\CampaignMember;
use App\Models\Hero;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('hero.{heroId}', static function ($user, $heroId) {
    return $user->id === Hero::findOrFail($heroId)?->user_id;
});

$isCampaignMember = static function ($user, $campaignId): bool {
    return CampaignMember::query()
        ->where('campaign_id', $campaignId)
        ->where('user_id', $user->id)
        ->exists();
};

// Kanały stołu do gry, odizolowane per kampania — każdy członek danej kampanii może słuchać.
// Mutacje (rysunki, ruch tokenów, walka) są dodatkowo bramkowane rolą MG w kontrolerach.
Broadcast::channel('session-chat.{campaignId}', $isCampaignMember);
Broadcast::channel('token-move.{campaignId}', $isCampaignMember);
Broadcast::channel('drawings.{campaignId}', $isCampaignMember);
Broadcast::channel('combat.{campaignId}', $isCampaignMember);

// Prywatna skrzynka użytkownika w kampanii — tylko właściciel skrzynki, i tylko dopóki wciąż
// jest członkiem tej kampanii.
Broadcast::channel('private-chat.{campaignId}.{userId}', static function ($user, $campaignId, $userId) use ($isCampaignMember) {
    return (int) $user->id === (int) $userId && $isCampaignMember($user, $campaignId);
});
