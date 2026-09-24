<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_request_sends_custom_notification(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_reset_email_is_polish_and_themed(): void
    {
        $user = User::factory()->create(['name' => 'Gimli']);

        $mail = (new ResetPasswordNotification('tajny-token'))->toMail($user);
        $html = (string) $mail->render();

        $this->assertSame('Reset hasła', $mail->subject);
        $this->assertStringContainsString('Witaj, Gimli!', $html);
        $this->assertStringContainsString('Ustaw nowe hasło', $html);
        $this->assertStringContainsString('images/logo-mark.png', $html);
        $this->assertStringContainsString('/password/reset/tajny-token', $html);
    }

    public function test_reset_pages_render(): void
    {
        $this->get(route('password.request'))->assertOk()->assertSee('RESET HASŁA');
        $this->get(route('password.reset', ['token' => 'abc']))->assertOk()->assertSee('NOWE HASŁO');
    }
}
