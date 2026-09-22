<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignMember;
use App\Models\Hero;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PrivateMessageAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    // ID z migracji 2025_03_13_180713_change_hero_characteristics_structure — stałe w każdej bazie.
    private const ZR_ID = 5;

    private function createCampaign(): array
    {
        $gm = User::factory()->create(['is_active' => true]);
        $campaign = Campaign::create([
            'name' => 'Kampania testowa',
            'owner_id' => $gm->id,
            'invite_code' => Str::random(10),
        ]);
        CampaignMember::create([
            'campaign_id' => $campaign->id,
            'user_id' => $gm->id,
            'role' => CampaignMember::ROLE_GM,
            'joined_at' => now(),
        ]);

        return [$campaign, $gm];
    }

    private function addPlayer(Campaign $campaign): User
    {
        $player = User::factory()->create(['is_active' => true]);
        CampaignMember::create([
            'campaign_id' => $campaign->id,
            'user_id' => $player->id,
            'role' => CampaignMember::ROLE_PLAYER,
            'joined_at' => now(),
        ]);

        return $player;
    }

    private function createHeroWithCharacteristic(Campaign $campaign, User $user): Hero
    {
        $hero = Hero::create([
            'user_id' => $user->id,
            'campaign_id' => $campaign->id,
            'name' => 'Bohater '.$user->id,
            'race' => 'Człowiek',
        ]);

        $hero->characteristic()->attach([
            self::ZR_ID => ['start_value' => 40, 'advancement' => 0],
        ]);

        return $hero;
    }

    public function test_gm_can_send_private_message_to_player_as_first_message(): void
    {
        [$campaign, $gm] = $this->createCampaign();
        $player = $this->addPlayer($campaign);

        $this->actingAs($gm)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->postJson('/session/chat/private/send', ['text' => 'Sekretna wiadomość', 'recipient_id' => $player->id])
            ->assertCreated();

        $message = Message::where('campaign_id', $campaign->id)->whereNotNull('recipient_id')->firstOrFail();
        $this->assertSame($gm->id, $message->user_id);
        $this->assertSame($player->id, $message->recipient_id);
        $this->assertSame('Sekretna wiadomość', $message->text);
    }

    public function test_player_can_send_private_message_to_gm_as_first_message(): void
    {
        [$campaign, $gm] = $this->createCampaign();
        $player = $this->addPlayer($campaign);

        $this->actingAs($player)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->postJson('/session/chat/private/send', ['text' => 'Pytanie do MG', 'recipient_id' => $gm->id])
            ->assertCreated();

        $message = Message::where('campaign_id', $campaign->id)->whereNotNull('recipient_id')->firstOrFail();
        $this->assertSame($player->id, $message->user_id);
        $this->assertSame($gm->id, $message->recipient_id);
    }

    public function test_player_cannot_send_private_message_to_another_player(): void
    {
        [$campaign] = $this->createCampaign();
        $playerA = $this->addPlayer($campaign);
        $playerB = $this->addPlayer($campaign);

        $this->actingAs($playerA)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->postJson('/session/chat/private/send', ['text' => 'Cześć', 'recipient_id' => $playerB->id])
            ->assertForbidden();

        $this->assertSame(0, Message::whereNotNull('recipient_id')->count());
    }

    public function test_player_cannot_roll_dice_to_another_player(): void
    {
        [$campaign] = $this->createCampaign();
        $playerA = $this->addPlayer($campaign);
        $playerB = $this->addPlayer($campaign);

        $this->actingAs($playerA)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->postJson('/session/chat/private/roll-dice', ['count' => 1, 'sides' => 6, 'recipient_id' => $playerB->id])
            ->assertForbidden();

        $this->assertSame(0, Message::whereNotNull('recipient_id')->count());
    }

    public function test_gm_can_roll_dice_privately_to_player(): void
    {
        [$campaign, $gm] = $this->createCampaign();
        $player = $this->addPlayer($campaign);

        $this->actingAs($gm)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->postJson('/session/chat/private/roll-dice', ['count' => 2, 'sides' => 6, 'recipient_id' => $player->id])
            ->assertCreated();

        $message = Message::where('campaign_id', $campaign->id)->whereNotNull('recipient_id')->firstOrFail();
        $this->assertSame('dice_roll', $message->type);
        $this->assertSame($gm->id, $message->user_id);
        $this->assertSame($player->id, $message->recipient_id);
    }

    public function test_player_can_roll_characteristic_privately_to_gm(): void
    {
        [$campaign, $gm] = $this->createCampaign();
        $player = $this->addPlayer($campaign);
        $this->createHeroWithCharacteristic($campaign, $player);

        $this->actingAs($player)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->postJson('/session/chat/private/roll-characteristic', [
                'characteristic' => 'Zr',
                'modifier' => 0,
                'half' => false,
                'recipient_id' => $gm->id,
            ])
            ->assertCreated();

        $message = Message::where('campaign_id', $campaign->id)->whereNotNull('recipient_id')->firstOrFail();
        $this->assertSame('skill_test', $message->type);
        $this->assertSame($player->id, $message->user_id);
        $this->assertSame($gm->id, $message->recipient_id);
    }

    public function test_private_messages_are_isolated_between_campaigns(): void
    {
        [$campaignA, $gmA] = $this->createCampaign();
        $playerA = $this->addPlayer($campaignA);
        $this->actingAs($gmA)
            ->withSession(['current_campaign_id' => $campaignA->id])
            ->postJson('/session/chat/private/send', ['text' => 'Wiadomość A', 'recipient_id' => $playerA->id])
            ->assertCreated();

        [$campaignB, $gmB] = $this->createCampaign();
        $playerB = $this->addPlayer($campaignB);

        $contacts = $this->actingAs($gmB)
            ->withSession(['current_campaign_id' => $campaignB->id])
            ->getJson('/session/chat/private/contacts')
            ->assertOk()
            ->json();
        $this->assertSame([$playerB->id], collect($contacts)->pluck('user_id')->all());

        $messages = $this->actingAs($playerB)
            ->withSession(['current_campaign_id' => $campaignB->id])
            ->getJson('/session/chat/private')
            ->assertOk()
            ->json();
        $this->assertSame([], $messages);
    }

    public function test_private_message_does_not_appear_in_public_chat(): void
    {
        [$campaign, $gm] = $this->createCampaign();
        $player = $this->addPlayer($campaign);

        $this->actingAs($gm)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->postJson('/session/chat/private/send', ['text' => 'Tajne', 'recipient_id' => $player->id])
            ->assertCreated();

        $publicMessages = $this->actingAs($player)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->getJson('/session/chat')
            ->assertOk()
            ->json();

        $this->assertSame([], $publicMessages);
    }
}
