<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::call(static function () {
    Log::info('Updating fortune points');;
    $heroes = \App\Models\Hero::query()->with('characteristics')->get();
    foreach ($heroes as $hero)
    {
        $hero->update(['fortune_points' => $hero->characteristics['PP']->start_value]);
    }
})->dailyAt('03:10');
