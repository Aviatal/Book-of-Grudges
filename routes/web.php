<?php

use App\Http\Controllers\CharactersController;
use App\Http\Controllers\SkillsAndTalentsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/karta-postaci');
});

Route::get('/karta-postaci', [CharactersController::class, 'getCharacterSheet'])->name('character-sheet');

Route::get('/umiejetnosci', [SkillsAndTalentsController::class, 'getSkills'])->name('skills');
Route::get('/zdolnosci', [SkillsAndTalentsController::class, 'getTalents'])->name('talents');
