<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    private function verificationUrl(User $user, ?string $hash = null): string
    {
        return URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $user->id,
            'hash' => $hash ?? sha1($user->email),
        ]);
    }

    public function test_registration_sends_verification_notification(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'Testowy Gracz',
            'email' => 'gracz@example.com',
            'password' => 'haslo-testowe-123',
            'password_confirmation' => 'haslo-testowe-123',
        ])->assertRedirect();

        $user = User::where('email', 'gracz@example.com')->firstOrFail();

        $this->assertFalse($user->hasVerifiedEmail());
        Notification::assertSentTo($user, VerifyEmailNotification::class);
    }

    public function test_unverified_user_is_redirected_to_verification_notice(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->get(route('campaigns.index'))
            ->assertRedirect(route('verification.notice'));
        $this->actingAs($user)->get(route('home'))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_verification_notice_page_is_shown_to_unverified_user(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->get(route('verification.notice'))
            ->assertOk()
            ->assertSee('POTWIERDŹ E-MAIL')
            ->assertSee($user->email);
    }

    public function test_verified_user_can_access_app(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('campaigns.index'))->assertOk();
    }

    public function test_signed_link_verifies_email_and_shows_confirmation_page(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->get($this->verificationUrl($user))
            ->assertOk()
            ->assertSee('ADRES POTWIERDZONY');

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_link_with_wrong_hash_is_forbidden(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->get($this->verificationUrl($user, sha1('inny@example.com')))
            ->assertForbidden();

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_unsigned_link_is_forbidden(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get(route('verification.verify', ['id' => $user->id, 'hash' => sha1($user->email)]))
            ->assertForbidden();
    }

    public function test_verification_email_can_be_resent(): void
    {
        Notification::fake();
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->post(route('verification.resend'))
            ->assertRedirect()
            ->assertSessionHas('resent');

        Notification::assertSentTo($user, VerifyEmailNotification::class);
    }

    public function test_verification_email_is_polish_and_themed(): void
    {
        $user = User::factory()->unverified()->create(['name' => 'Gimli']);

        $mail = (new VerifyEmailNotification)->toMail($user);
        $html = (string) $mail->render();

        $this->assertSame('Potwierdź swój adres e-mail', $mail->subject);
        $this->assertStringContainsString('Witaj, Gimli!', $html);
        $this->assertStringContainsString('Potwierdź adres e-mail', $html);
        $this->assertStringContainsString('images/logo-mark.png', $html);
        $this->assertStringContainsString('BOOK OF GRUDGES', $html);
        $this->assertStringContainsString('/email/verify/'.$user->id, $html);
    }
}
