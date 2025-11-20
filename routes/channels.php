<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('hero.{heroId}', static function ($user, $heroId) {
    return true;
});
