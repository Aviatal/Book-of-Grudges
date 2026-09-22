<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignMember;
use App\Models\Hero;
use App\Models\Skill;
use App\Models\SkillTestLog;
use App\Models\User;
use App\Services\SkillTestStatisticsService;
use App\Support\SkillTestOutcome;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SkillTestStatisticsTest extends TestCase
{
    use RefreshDatabase;

    // ID-ki z migracji 2025_03_13_180713_change_hero_characteristics_structure — tabela
    // `characteristics` jest zasilana wprost w tej migracji, więc te ID są stałe w każdej bazie.
    private const ZR_ID = 5;

    private const INT_ID = 6;

    private const SZ_ID = 13; // cecha drugorzędna (Szybkość) — nie da się jej testować

    private function createCampaignWithHero(): array
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
        ]);

        $hero->characteristic()->attach([
            self::ZR_ID => ['start_value' => 40, 'advancement' => 0],
            self::INT_ID => ['start_value' => 30, 'advancement' => 10],
            self::SZ_ID => ['start_value' => 4, 'advancement' => 0],
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

    public function test_rolling_characteristic_and_skill_logs_test_statistics(): void
    {
        [$campaign, $player, $hero] = $this->createCampaignWithHero();
        $skill = $this->createSkill('Spostrzegawczość', 'Int');

        $characteristicResponse = $this->actingAs($player)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->postJson('/session/chat/roll-characteristic', ['characteristic' => 'Zr', 'modifier' => 0, 'half' => false])
            ->assertCreated();

        $skillResponse = $this->actingAs($player)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->postJson('/session/chat/roll-skill', ['skill_id' => $skill->id, 'modifier' => 10, 'half' => false])
            ->assertCreated();

        $this->assertSame(2, SkillTestLog::where('hero_id', $hero->id)->count());

        $characteristicLog = SkillTestLog::where('hero_id', $hero->id)->whereNull('skill_id')->firstOrFail();
        $this->assertSame($campaign->id, $characteristicLog->campaign_id);
        $this->assertSame('Zr', $characteristicLog->characteristic);
        $this->assertFalse($characteristicLog->has_modifier);

        $skillLog = SkillTestLog::where('hero_id', $hero->id)->whereNotNull('skill_id')->firstOrFail();
        $this->assertSame($skill->id, $skillLog->skill_id);
        $this->assertSame('Spostrzegawczość', $skillLog->skill_name);
        // Test umiejętności "Spostrzegawczość" liczy się do statystyk cechy Int, do której jest przypisana.
        $this->assertSame('Int', $skillLog->characteristic);
        $this->assertTrue($skillLog->has_modifier);

        // Wiadomość na czacie niesie te same pola "pech"/"poziomy", wyliczone z faktycznego rzutu.
        $characteristicMessage = json_decode($characteristicResponse->json('message.text'), true);
        $this->assertSame(SkillTestOutcome::isFumble($characteristicLog->roll), $characteristicMessage['fumble']);
        $this->assertSame(
            SkillTestOutcome::levels($characteristicLog->roll, $characteristicLog->effective_value),
            $characteristicMessage['levels'],
        );

        $skillMessage = json_decode($skillResponse->json('message.text'), true);
        $this->assertSame(SkillTestOutcome::isFumble($skillLog->roll), $skillMessage['fumble']);
        $this->assertSame(SkillTestOutcome::levels($skillLog->roll, $skillLog->effective_value), $skillMessage['levels']);
    }

    public function test_secondary_characteristics_are_not_offered_for_rolling(): void
    {
        [$campaign, $player] = $this->createCampaignWithHero();

        $response = $this->actingAs($player)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->getJson('/session/chat/skills');

        $response->assertOk();
        $this->assertArrayNotHasKey('Sz', $response->json('characteristics'));
        $this->assertArrayHasKey('Zr', $response->json('characteristics'));
    }

    public function test_rolling_a_secondary_characteristic_is_rejected(): void
    {
        [$campaign, $player, $hero] = $this->createCampaignWithHero();

        $this->actingAs($player)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->postJson('/session/chat/roll-characteristic', ['characteristic' => 'Sz', 'modifier' => 0, 'half' => false])
            ->assertServerError();

        $this->assertSame(0, SkillTestLog::where('hero_id', $hero->id)->count());
    }

    public function test_half_test_counts_as_having_a_modifier(): void
    {
        [$campaign, $player, $hero] = $this->createCampaignWithHero();

        $this->actingAs($player)
            ->withSession(['current_campaign_id' => $campaign->id])
            ->postJson('/session/chat/roll-characteristic', ['characteristic' => 'Zr', 'modifier' => 0, 'half' => true])
            ->assertCreated();

        $log = SkillTestLog::where('hero_id', $hero->id)->firstOrFail();
        $this->assertTrue($log->half);
        $this->assertTrue($log->has_modifier);
    }

    public function test_hero_statistics_are_aggregated_per_characteristic_and_per_skill(): void
    {
        [, , $hero] = $this->createCampaignWithHero();
        $skill = $this->createSkill('Spostrzegawczość', 'Int');

        // Czysty test cechy Zr, bez modyfikatora.
        SkillTestLog::create([
            'hero_id' => $hero->id,
            'campaign_id' => $hero->campaign_id,
            'skill_id' => null,
            'skill_name' => null,
            'characteristic' => 'Zr',
            'characteristic_value' => 40,
            'effective_value' => 40,
            'modifier' => 0,
            'half' => false,
            'has_modifier' => false,
            'roll' => 12,
            'passed' => true,
        ]);

        // Dwa testy umiejętności Spostrzegawczość (Int) — jeden zdany bez modyfikatora, jeden nieudany z modyfikatorem.
        SkillTestLog::create([
            'hero_id' => $hero->id,
            'campaign_id' => $hero->campaign_id,
            'skill_id' => $skill->id,
            'skill_name' => $skill->name,
            'characteristic' => 'Int',
            'characteristic_value' => 40,
            'effective_value' => 40,
            'modifier' => 0,
            'half' => false,
            'has_modifier' => false,
            'roll' => 30,
            'passed' => true,
        ]);
        SkillTestLog::create([
            'hero_id' => $hero->id,
            'campaign_id' => $hero->campaign_id,
            'skill_id' => $skill->id,
            'skill_name' => $skill->name,
            'characteristic' => 'Int',
            'characteristic_value' => 40,
            'effective_value' => 30,
            'modifier' => -10,
            'half' => false,
            'has_modifier' => true,
            'roll' => 90,
            'passed' => false,
        ]);
        // Pech (99) na tej samej umiejętności — powinien policzyć się jako pech na wszystkich poziomach.
        SkillTestLog::create([
            'hero_id' => $hero->id,
            'campaign_id' => $hero->campaign_id,
            'skill_id' => $skill->id,
            'skill_name' => $skill->name,
            'characteristic' => 'Int',
            'characteristic_value' => 40,
            'effective_value' => 40,
            'modifier' => 0,
            'half' => false,
            'has_modifier' => false,
            'roll' => 99,
            'passed' => false,
        ]);

        $statistics = app(SkillTestStatisticsService::class)->getHeroStatistics($hero->id);

        $this->assertSame(4, $statistics['overall']['combined']['total']);
        $this->assertSame(2, $statistics['overall']['combined']['passed']);
        $this->assertSame(1, $statistics['overall']['fumbles']);

        $characteristics = collect($statistics['characteristics'])->keyBy('characteristic');
        $this->assertSame(1, $characteristics['Zr']['combined']['total']);
        $this->assertSame(0, $characteristics['Zr']['fumbles']);
        // Testy Spostrzegawczości (Int) wliczają się do statystyk cechy Int.
        $this->assertSame(3, $characteristics['Int']['combined']['total']);
        $this->assertSame(1, $characteristics['Int']['with_modifier']['total']);
        $this->assertSame(2, $characteristics['Int']['without_modifier']['total']);
        // Pech liczy się do statystyk cechy niezależnie od tego, że test formalnie nie wyszedł.
        $this->assertSame(1, $characteristics['Int']['fumbles']);

        // Rozkład wg konkretnej wartości modyfikatora — 0 i -10 to dwa osobne warianty.
        $intByModifier = collect($characteristics['Int']['by_modifier'])->keyBy(fn ($row) => $row['modifier'].':'.($row['half'] ? 'half' : 'full'));
        $this->assertSame(2, $intByModifier['0:full']['total']);
        $this->assertSame(1, $intByModifier['-10:full']['total']);

        $this->assertCount(1, $statistics['skills']);
        $skillStats = $statistics['skills'][0];
        $this->assertSame($skill->id, $skillStats['skill_id']);
        $this->assertSame(3, $skillStats['combined']['total']);
        $this->assertSame(1, $skillStats['combined']['passed']);
        $this->assertSame(1, $skillStats['fumbles']);
    }

    public function test_statistics_page_shows_only_own_hero_roll_statistics(): void
    {
        [$campaignA, $playerA, $heroA] = $this->createCampaignWithHero();
        SkillTestLog::create([
            'hero_id' => $heroA->id,
            'campaign_id' => $campaignA->id,
            'skill_id' => null,
            'skill_name' => null,
            'characteristic' => 'Zr',
            'characteristic_value' => 40,
            'effective_value' => 40,
            'modifier' => 0,
            'half' => false,
            'has_modifier' => false,
            'roll' => 10,
            'passed' => true,
        ]);

        [$campaignB, , $heroB] = $this->createCampaignWithHero();
        SkillTestLog::create([
            'hero_id' => $heroB->id,
            'campaign_id' => $campaignB->id,
            'skill_id' => null,
            'skill_name' => null,
            'characteristic' => 'Int',
            'characteristic_value' => 30,
            'effective_value' => 30,
            'modifier' => 0,
            'half' => false,
            'has_modifier' => false,
            'roll' => 10,
            'passed' => true,
        ]);

        $response = $this->actingAs($playerA)
            ->withSession(['current_campaign_id' => $campaignA->id])
            ->get('/statystyki');

        $response->assertOk();
        $response->assertViewHas('rollStatistics', function (array $rollStatistics) {
            return $rollStatistics['overall']['combined']['total'] === 1
                && collect($rollStatistics['characteristics'])->pluck('characteristic')->all() === ['Zr'];
        });
    }
}
