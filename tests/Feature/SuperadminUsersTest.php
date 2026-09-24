<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignMember;
use App\Models\Hero;
use App\Models\HeroInventory;
use App\Models\Message;
use App\Models\Token;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SuperadminUsersTest extends TestCase
{
    use RefreshDatabase;

    private function superadmin(): User
    {
        return User::factory()->create(['is_active' => true, 'is_superadmin' => true]);
    }

    private function player(array $attributes = []): User
    {
        return User::factory()->create(['is_active' => true] + $attributes);
    }

    private function campaignWith(User $owner): Campaign
    {
        $campaign = Campaign::create(['name' => 'Kampania', 'owner_id' => $owner->id, 'invite_code' => Str::random(10)]);
        CampaignMember::create([
            'campaign_id' => $campaign->id,
            'user_id' => $owner->id,
            'role' => CampaignMember::ROLE_GM,
            'joined_at' => now(),
        ]);

        return $campaign;
    }

    private function heroFor(User $user, ?Campaign $campaign = null): Hero
    {
        return Hero::create([
            'user_id' => $user->id,
            'campaign_id' => $campaign?->id,
            'name' => 'Bohater ' . $user->id,
            'race' => 'Człowiek',
        ]);
    }

    public function test_non_superadmin_cannot_access_users_panel(): void
    {
        $user = $this->player();

        $this->actingAs($user)->get('/panel/superadmin/uzytkownicy')->assertRedirect(url('/'));
        $this->actingAs($user)->put("/panel/superadmin/uzytkownicy/{$user->id}", ['name' => 'X', 'email' => 'x@example.com'])
            ->assertRedirect(url('/'));
        $this->actingAs($user)->delete("/panel/superadmin/uzytkownicy/{$user->id}")->assertRedirect(url('/'));
    }

    public function test_superadmin_sees_and_searches_users(): void
    {
        $admin = $this->superadmin();
        $this->player(['name' => 'Gerhard Kowal', 'email' => 'gerhard@example.com']);
        $this->player(['name' => 'Ulrika Weber', 'email' => 'ulrika@example.com']);

        $this->actingAs($admin)->get('/panel/superadmin/uzytkownicy')
            ->assertOk()->assertSee('Gerhard Kowal')->assertSee('Ulrika Weber');

        $this->actingAs($admin)->get('/panel/superadmin/uzytkownicy?q=GERHARD')
            ->assertOk()->assertSee('Gerhard Kowal')->assertDontSee('Ulrika Weber');
    }

    public function test_superadmin_can_edit_user_and_email_change_resets_verification(): void
    {
        $admin = $this->superadmin();
        $user = $this->player(['email' => 'stary@example.com']);

        $this->actingAs($admin)->put("/panel/superadmin/uzytkownicy/{$user->id}", [
            'name' => 'Nowa Nazwa',
            'email' => 'nowy@example.com',
            'is_active' => '1',
            'is_superadmin' => '1',
        ])->assertRedirect(route('panel.superadmin.users.show', $user));

        $user->refresh();
        $this->assertSame('Nowa Nazwa', $user->name);
        $this->assertSame('nowy@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
        $this->assertTrue($user->is_superadmin);
    }

    public function test_unchecked_checkboxes_are_saved_as_false(): void
    {
        $admin = $this->superadmin();
        $user = $this->player(['is_superadmin' => true]);

        $this->actingAs($admin)->put("/panel/superadmin/uzytkownicy/{$user->id}", [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $user->refresh();
        $this->assertFalse($user->is_active);
        $this->assertFalse($user->is_superadmin);
    }

    public function test_email_must_stay_unique(): void
    {
        $admin = $this->superadmin();
        $other = $this->player(['email' => 'zajety@example.com']);
        $user = $this->player();

        $this->actingAs($admin)->from('/x')->put("/panel/superadmin/uzytkownicy/{$user->id}", [
            'name' => $user->name,
            'email' => $other->email,
        ])->assertSessionHasErrors('email');
    }

    public function test_superadmin_cannot_demote_or_deactivate_self(): void
    {
        $admin = $this->superadmin();

        $this->actingAs($admin)->put("/panel/superadmin/uzytkownicy/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'is_active' => '1',
        ])->assertSessionHasErrors('user');

        $this->assertTrue($admin->fresh()->is_superadmin);
    }

    public function test_delete_removes_user_with_heroes_including_soft_deleted_ones(): void
    {
        $admin = $this->superadmin();
        $gm = $this->player();
        $campaign = $this->campaignWith($gm);
        $user = $this->player();
        CampaignMember::create([
            'campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'role' => CampaignMember::ROLE_PLAYER,
            'joined_at' => now(),
        ]);

        $hero = $this->heroFor($user, $campaign);
        HeroInventory::create(['hero_id' => $hero->id, 'name' => 'Lina']);
        $token = Token::create(['name' => 'Token', 'x' => 0, 'y' => 0, 'campaign_id' => $campaign->id, 'hero_id' => $hero->id]);
        $deletedHero = $this->heroFor($user);
        $deletedHero->delete();
        Message::query()->insert([
            'user_id' => $user->id, 'campaign_id' => $campaign->id, 'author_name' => $user->name, 'text' => 'Hej',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($admin)->delete("/panel/superadmin/uzytkownicy/{$user->id}")
            ->assertRedirect(route('panel.superadmin.users.index'));

        $this->assertNull(User::find($user->id));
        $this->assertSame(0, Hero::withTrashed()->where('user_id', $user->id)->count());
        $this->assertSame(0, Message::where('user_id', $user->id)->count());
        // Token zostaje w kampanii — tylko traci powiązanie z usuniętym bohaterem.
        $this->assertNull($token->fresh()->hero_id);
        $this->assertNotNull(Campaign::find($campaign->id));
    }

    public function test_cannot_delete_campaign_owner(): void
    {
        $admin = $this->superadmin();
        $gm = $this->player();
        $campaign = $this->campaignWith($gm);

        $this->actingAs($admin)->delete("/panel/superadmin/uzytkownicy/{$gm->id}")->assertSessionHasErrors('user');

        $this->assertNotNull(User::find($gm->id));
        $this->assertNotNull(Campaign::find($campaign->id));
    }

    public function test_cannot_delete_self_or_another_superadmin(): void
    {
        $admin = $this->superadmin();
        $otherAdmin = $this->superadmin();

        $this->actingAs($admin)->delete("/panel/superadmin/uzytkownicy/{$admin->id}")->assertSessionHasErrors('user');
        $this->actingAs($admin)->delete("/panel/superadmin/uzytkownicy/{$otherAdmin->id}")->assertSessionHasErrors('user');

        $this->assertNotNull(User::find($admin->id));
        $this->assertNotNull(User::find($otherAdmin->id));
    }

    public function test_superadmin_can_view_hero_from_any_campaign_including_soft_deleted(): void
    {
        $admin = $this->superadmin();
        $gm = $this->player();
        $campaign = $this->campaignWith($gm);
        $user = $this->player();
        $hero = $this->heroFor($user, $campaign);
        $deletedHero = $this->heroFor($user);
        $deletedHero->delete();

        // Sesja superadmina wskazuje inną kampanię — podgląd nie może się do niej zawężać.
        $this->actingAs($admin)->withSession(['current_campaign_id' => 999_999])
            ->get("/panel/superadmin/bohaterowie/{$hero->id}")
            ->assertOk()->assertSee($hero->name);

        $this->actingAs($admin)->get("/panel/superadmin/bohaterowie/{$deletedHero->id}")->assertOk();
        $this->actingAs($admin)->get('/panel/superadmin/bohaterowie/999999')->assertNotFound();

        $this->actingAs($admin)->get("/panel/superadmin/uzytkownicy/{$user->id}")
            ->assertOk()->assertSee($hero->name);
    }

    public function test_campaign_show_route_still_works_next_to_users_routes(): void
    {
        $admin = $this->superadmin();
        $campaign = $this->campaignWith($this->player());

        $this->actingAs($admin)->get("/panel/superadmin/{$campaign->id}")->assertOk()->assertSee('Kampania');
    }
}
