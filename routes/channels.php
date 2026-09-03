<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('hero.{heroId}', static function ($user, $heroId) {
    return $user->id === \App\Models\Hero::findOrFail($heroId)?->user_id;
});

Broadcast::channel('session-chat', static function ($user) {
    return $user !== null;
});

// Kanały stołu do gry — każdy zalogowany uczestnik sesji może słuchać.
// Mutacje (rysunki, ruch tokenów, walka) są dodatkowo bramkowane rolą MG w kontrolerach.
Broadcast::channel('token-move', static fn ($user) => $user !== null);
Broadcast::channel('drawings', static fn ($user) => $user !== null);
Broadcast::channel('combat', static fn ($user) => $user !== null);
