<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Polska wersja maila z linkiem potwierdzającym adres e-mail.
 * Wygląd (logo, kolory, przycisk) pochodzi z motywu maili — resources/views/vendor/mail.
 */
class VerifyEmailNotification extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        return $this->buildMailMessage($this->verificationUrl($notifiable))
            ->greeting("Witaj, {$notifiable->name}!");
    }

    protected function buildMailMessage($url): MailMessage
    {
        $expire = config('auth.verification.expire', 60);

        return (new MailMessage)
            ->subject('Potwierdź swój adres e-mail')
            ->line('Dziękujemy za założenie konta w Book of Grudges. Kliknij poniższy przycisk, aby potwierdzić swój adres e-mail i wejść do księgi.')
            ->action('Potwierdź adres e-mail', $url)
            ->line("Link jest ważny przez {$expire} minut.")
            ->line('Jeśli konto nie zostało założone przez Ciebie, po prostu zignoruj tę wiadomość.');
    }
}
