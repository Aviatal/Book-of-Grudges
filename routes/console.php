<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::call(static function () {
    Log::info('Updating fortune points');;
    $heroes = \App\Models\Hero::query()->with('characteristic')->get();
    foreach ($heroes as $hero)
    {
        $hero->update(['fortune_points' => $hero->getRelation('characteristic')['PP']->current_value ?? 0]);
    }
})->dailyAt('23:17');
