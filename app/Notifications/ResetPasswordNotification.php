<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Polska wersja maila z linkiem do resetu hasła.
 * Wygląd (logo, kolory, przycisk) pochodzi z motywu maili — resources/views/vendor/mail.
 */
class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        return $this->buildMailMessage($this->resetUrl($notifiable))
            ->greeting("Witaj, {$notifiable->name}!");
    }

    protected function buildMailMessage($url): MailMessage
    {
        $expire = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new MailMessage)
            ->subject('Reset hasła')
            ->line('Otrzymujesz tę wiadomość, ponieważ ktoś poprosił o zresetowanie hasła do Twojego konta w Book of Grudges.')
            ->action('Ustaw nowe hasło', $url)
            ->line("Link jest ważny przez {$expire} minut.")
            ->line('Jeśli nie chcesz zmieniać hasła, zignoruj tę wiadomość — Twoje hasło pozostanie bez zmian.');
    }
}
