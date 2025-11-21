<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('hero.{heroId}', static function ($user, $heroId) {
    return $user->id === \App\Models\Hero::findOrFail($heroId)?->user_id;
});
