<?php

use App\Http\Controllers\CharactersController;
use App\Http\Controllers\ProfessionsController;
use App\Http\Controllers\SkillsAndTalentsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/karta-postaci/1');
});

Route::group(['prefix' => 'karta-postaci'], function () {
    Route::get('/{id}', [CharactersController::class, 'getCharacterSheet'])->name('character-sheet');
    Route::post('/{hero}/update-hero-data', [CharactersController::class, 'updateHeroData'])->name('character-sheet.update-hero-data');
});

Route::get('/umiejetnosci', [SkillsAndTalentsController::class, 'getSkills'])->name('skills');
Route::get('/professions/get-professions', [ProfessionsController::class, 'getProfessions'])->name('professions-get');
Route::get('/zdolnosci', [SkillsAndTalentsController::class, 'getTalents'])->name('talents');
