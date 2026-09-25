<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignMember;
use App\Models\FortunePointsSatisfaction;
use App\Models\Hero;
use App\Models\Message;
use App\Models\Skill;
use App\Models\User;
use App\Support\SkillTestOutcome;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class FortuneRerollTest extends TestCase
{
    use RefreshDatabase;

    // Stałe ID cech z migracji 2025_03_13_180713_change_hero_characteristics_structure
    private const ZR_ID = 5;

    private const INT_ID = 6;

    private function createCampaignWithHero(int $fortunePoints = 2): array
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

        $player = User::factory()->create(['is_active' => true]);
        CampaignMember::create([
            'campaign_id' => $campaign->id,
            'user_id' => $player->id,
            'role' => CampaignMember::ROLE_PLAYER,
            'joined_at' => now(),
        ]);

        $hero = Hero::create([
            'user_id' => $player->id,
            'campaign_id' => $campaign->id,
            'name' => 'Bohater testowy',
            'race' => 'Człowiek',
            'fortune_points' => $fortunePoints,
        ]);
        $hero->characteristic()->attach([
            self::ZR_ID => ['start_value' => 40, 'advancement' => 0],
            self::INT_ID => ['start_value' => 30, 'advancement' => 10],
        ]);

        return [$campaign, $player, $hero];
    }

    private function createSkill(string $name, string $characteristic): Skill
    {
        Skill::insert([
            'name' => $name,
            'type' => 'PODSTAWOWA',
            'characteristic' => $characteristic,
            'description' => 'Opis testowy',
        ]);

        return Skill::where('name', $name)->firstOrFail();
    }

    /**
     * Zapisuje rzut zdefiniowany "na sztywno" — test nie zależy od losowania.
     */
    private function storeRoll(User $user, Campaign $campaign, array $overrides = []): Message
    {
        $payload = array_merge([
            'skill' => 'Zr',
            'characteristic' => 'Zr',
            'characteristic_value' => 40,
            'effective_value' => 40,
            'modifier' => 0,
            'half' => false,
            'roll' => 80,
            'passed' => false,
            'fumble' => false,
            'levels' => 4,
            'skill_id' => null,
            'fortune_reroll' => false,
        ], $overrides);

        return Message::create([
            'user_id' => $user->id,
            'author_name' => 'Bohater testowy',
            'text' => json_encode($payload, JSON_UNESCAPED_UNICODE),
            'type' => 'skill_test',
            'campaign_id' => $campaign->id,
        ]);
    }

    private function reroll(User $user, Campaign $campaign, Message $message)
    {
        return $this->actingAs($user)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->postJson('/session/chat/reroll-with-fortune-point', ['message_id' => $message->id]);
    }

    public function test_failed_characteristic_roll_is_repeated_and_satisfaction_is_logged(): void
    {
        [$campaign, $player, $hero] = $this->createCampaignWithHero(2);
        $failed = $this->storeRoll($player, $campaign, ['modifier' => 10, 'effective_value' => 50]);

        $response = $this->reroll($player, $campaign, $failed)->assertCreated();

        $result = json_decode($response->json('message.text'), true);
        $this->assertTrue($result['fortune_reroll']);
        $this->assertSame('Zr', $result['characteristic']);
        $this->assertNull($result['skill_id']);
        // Ten sam modyfikator i próg (40 + 10), tylko nowy rzut k100
        $this->assertSame(10, $result['modifier']);
        $this->assertSame(50, $result['effective_value']);

        $this->assertSame(1, $hero->fresh()->fortune_points);

        $log = FortunePointsSatisfaction::where('hero_id', $hero->id)->sole();
        $this->assertSame(SkillTestOutcome::isSuccess($result['roll'], $result['effective_value']), $log->satisfied);
    }

    public function test_failed_skill_roll_is_repeated_with_the_same_skill(): void
    {
        [$campaign, $player, $hero] = $this->createCampaignWithHero(1);
        $skill = $this->createSkill('Spostrzegawczość', 'Int');
        $failed = $this->storeRoll($player, $campaign, [
            'skill' => $skill->name,
            'characteristic' => 'Int',
            'skill_id' => $skill->id,
            'effective_value' => 40,
        ]);

        $response = $this->reroll($player, $campaign, $failed)->assertCreated();

        $result = json_decode($response->json('message.text'), true);
        $this->assertSame($skill->id, $result['skill_id']);
        $this->assertSame('Spostrzegawczość', $result['skill']);
        $this->assertTrue($result['fortune_reroll']);
        $this->assertSame(0, $hero->fresh()->fortune_points);
        $this->assertSame(1, FortunePointsSatisfaction::where('hero_id', $hero->id)->count());
    }

    public function test_reroll_is_rejected_without_fortune_points(): void
    {
        [$campaign, $player, $hero] = $this->createCampaignWithHero(0);
        $failed = $this->storeRoll($player, $campaign);

        $this->reroll($player, $campaign, $failed)->assertStatus(400);

        $this->assertSame(0, $hero->fresh()->fortune_points);
        $this->assertSame(1, Message::where('type', 'skill_test')->count());
        $this->assertSame(0, FortunePointsSatisfaction::count());
    }

    public function test_successful_roll_cannot_be_repeated(): void
    {
        [$campaign, $player, $hero] = $this->createCampaignWithHero(2);
        $passed = $this->storeRoll($player, $campaign, ['roll' => 20, 'passed' => true]);

        $this->reroll($player, $campaign, $passed)->assertStatus(422);

        $this->assertSame(2, $hero->fresh()->fortune_points);
        $this->assertSame(0, FortunePointsSatisfaction::count());
    }

    public function test_fumble_counts_as_a_failure_even_if_it_formally_passed(): void
    {
        [$campaign, $player, $hero] = $this->createCampaignWithHero(2);
        $fumble = $this->storeRoll($player, $campaign, [
            'effective_value' => 99,
            'roll' => 98,
            'passed' => true,
            'fumble' => true,
        ]);

        $this->reroll($player, $campaign, $fumble)->assertCreated();

        $this->assertSame(1, $hero->fresh()->fortune_points);
    }

    public function test_only_the_latest_roll_can_be_repeated(): void
    {
        [$campaign, $player, $hero] = $this->createCampaignWithHero(2);
        $older = $this->storeRoll($player, $campaign);
        $this->storeRoll($player, $campaign);

        $this->reroll($player, $campaign, $older)->assertStatus(422);

        $this->assertSame(2, $hero->fresh()->fortune_points);
    }

    public function test_the_same_roll_cannot_be_repeated_twice(): void
    {
        [$campaign, $player, $hero] = $this->createCampaignWithHero(2);
        $failed = $this->storeRoll($player, $campaign);

        $this->reroll($player, $campaign, $failed)->assertCreated();
        // Powtórka jest już nowszym rzutem — wydanie punktu na starą wiadomość musi się nie udać
        $this->reroll($player, $campaign, $failed)->assertStatus(422);

        $this->assertSame(1, $hero->fresh()->fortune_points);
        $this->assertSame(1, FortunePointsSatisfaction::where('hero_id', $hero->id)->count());
    }

    public function test_a_roll_already_repeated_with_fortune_point_cannot_be_repeated_again(): void
    {
        [$campaign, $player, $hero] = $this->createCampaignWithHero(2);
        // Nieudana powtórka za punkt szczęścia (ostatni rzut bohatera)
        $failedReroll = $this->storeRoll($player, $campaign, ['fortune_reroll' => true]);

        $this->reroll($player, $campaign, $failedReroll)->assertStatus(422);

        $this->assertSame(2, $hero->fresh()->fortune_points);
        $this->assertSame(0, FortunePointsSatisfaction::count());
        $this->assertSame(1, Message::where('type', 'skill_test')->count());
    }

    public function test_someone_elses_roll_cannot_be_repeated(): void
    {
        [$campaign, $player, $hero] = $this->createCampaignWithHero(2);
        $otherPlayer = User::factory()->create(['is_active' => true]);
        CampaignMember::create([
            'campaign_id' => $campaign->id,
            'user_id' => $otherPlayer->id,
            'role' => CampaignMember::ROLE_PLAYER,
            'joined_at' => now(),
        ]);
        $otherHero = Hero::create([
            'user_id' => $otherPlayer->id,
            'campaign_id' => $campaign->id,
            'name' => 'Inny bohater',
            'race' => 'Krasnolud',
            'fortune_points' => 3,
        ]);
        $otherHero->characteristic()->attach([self::ZR_ID => ['start_value' => 40, 'advancement' => 0]]);
        $othersRoll = $this->storeRoll($otherPlayer, $campaign);

        $this->reroll($player, $campaign, $othersRoll)->assertStatus(422);

        $this->assertSame(2, $hero->fresh()->fortune_points);
        $this->assertSame(3, $otherHero->fresh()->fortune_points);
    }

    public function test_roll_saved_before_this_feature_cannot_be_repeated(): void
    {
        [$campaign, $player, $hero] = $this->createCampaignWithHero(2);
        $legacy = $this->storeRoll($player, $campaign);
        $payload = json_decode($legacy->text, true);
        unset($payload['skill_id'], $payload['fortune_reroll']);
        $legacy->update(['text' => json_encode($payload)]);

        $this->reroll($player, $campaign, $legacy)->assertStatus(422);

        $this->assertSame(2, $hero->fresh()->fortune_points);
    }
}
