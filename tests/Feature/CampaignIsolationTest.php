<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignMember;
use App\Models\Hero;
use App\Models\Token;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignIsolationTest extends TestCase
{
    use RefreshDatabase;

    private function createCampaign(): array
    {
        $gm = User::factory()->create(['is_active' => true]);
        $campaign = Campaign::create([
            'name'        => 'Kampania testowa',
            'owner_id'    => $gm->id,
            'invite_code' => \Illuminate\Support\Str::random(10),
        ]);
        CampaignMember::create([
            'campaign_id' => $campaign->id,
            'user_id'     => $gm->id,
            'role'        => CampaignMember::ROLE_GM,
            'joined_at'   => now(),
        ]);

        return [$campaign, $gm];
    }

    private function addPlayer(Campaign $campaign): User
    {
        $player = User::factory()->create(['is_active' => true]);
        CampaignMember::create([
            'campaign_id' => $campaign->id,
            'user_id'     => $player->id,
            'role'        => CampaignMember::ROLE_PLAYER,
            'joined_at'   => now(),
        ]);

        return $player;
    }

    private function createHero(Campaign $campaign, User $user): Hero
    {
        return Hero::create([
            'user_id'     => $user->id,
            'campaign_id' => $campaign->id,
            'name'        => 'Bohater ' . $user->id,
            'race'        => 'Człowiek',
        ]);
    }

    public function test_player_cannot_act_on_hero_from_another_campaign(): void
    {
        [$campaignA] = $this->createCampaign();
        $playerA = $this->addPlayer($campaignA);
        $heroA = $this->createHero($campaignA, $playerA);

        [$campaignB] = $this->createCampaign();
        $playerB = $this->addPlayer($campaignB);
        $this->createHero($campaignB, $playerB);

        // playerB wybiera campaignB jako bieżącą, próbuje działać na bohaterze z campaignA
        $this->actingAs($playerB)
            ->withSession(['current_campaign_id' => $campaignB->id])
            ->post("/karta-postaci/{$heroA->id}/update-hero", ['field' => 'name', 'value' => 'Zhackowano'])
            ->assertNotFound();

        $this->assertSame('Bohater ' . $playerA->id, $heroA->fresh()->name);
    }

    public function test_gm_cannot_move_token_from_another_campaign(): void
    {
        [$campaignA] = $this->createCampaign();
        [$campaignB, $gmB] = $this->createCampaign();

        $tokenA = Token::create(['name' => 'Token A', 'x' => 0, 'y' => 0, 'campaign_id' => $campaignA->id]);

        $this->actingAs($gmB)
            ->withSession(['current_campaign_id' => $campaignB->id])
            ->patchJson("/session/tokens/{$tokenA->id}/move", ['x' => 5, 'y' => 5])
            ->assertNotFound();
    }

    public function test_join_by_code_adds_player_role_only(): void
    {
        [$campaign] = $this->createCampaign();
        $newUser = User::factory()->create(['is_active' => true]);

        $this->actingAs($newUser)
            ->post('/kampanie/dolacz', ['code' => $campaign->invite_code])
            ->assertRedirect(route('home'));

        $membership = CampaignMember::where('campaign_id', $campaign->id)->where('user_id', $newUser->id)->first();

        $this->assertNotNull($membership);
        $this->assertSame(CampaignMember::ROLE_PLAYER, $membership->role);
    }

    public function test_player_cannot_access_gm_panel(): void
    {
        [$campaign] = $this->createCampaign();
        $player = $this->addPlayer($campaign);

        $this->actingAs($player)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->get('/panel/experience/show-experience-form')
            ->assertRedirect(url('/'));
    }

    public function test_non_superadmin_cannot_access_superadmin_panel(): void
    {
        [$campaign, $gm] = $this->createCampaign();

        $this->actingAs($gm)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->get('/panel/superadmin')
            ->assertRedirect(url('/'));
    }

    public function test_gm_cannot_award_experience_to_hero_from_another_campaign(): void
    {
        [$campaignA, $gmA] = $this->createCampaign();
        [$campaignB] = $this->createCampaign();
        $playerB = $this->addPlayer($campaignB);
        $heroB = $this->createHero($campaignB, $playerB);

        $this->actingAs($gmA)
            ->withSession(['current_campaign_id' => $campaignA->id])
            ->post('/panel/experience/save-experience', [
                'commonExperience' => 10,
                'heroesExperience' => [$heroB->id => 5],
                'heroesNotes'      => [$heroB->id => ''],
            ]);

        $this->assertSame(0, (int) $heroB->fresh()->current_experience);
    }
}
