<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->call(static function () {
            Log::info('Updating fortune points');;
            $heroes = \App\Models\Hero::query()->with('characteristics')->get();
            foreach ($heroes as $hero)
            {
                $hero->update(['fortune_points' => $hero->characteristics['PP']->start_value]);
            }
        })->dailyAt('23:00');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
