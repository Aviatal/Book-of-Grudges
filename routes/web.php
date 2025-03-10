<?php

use App\Http\Controllers\ArmorsController;
use App\Http\Controllers\CharactersController;
use App\Http\Controllers\ProfessionsController;
use App\Http\Controllers\SkillsAndTalentsController;
use App\Http\Controllers\WeaponsController;
use Illuminate\Support\Facades\Route;
Auth::routes();

Route::group(['prefix' => 'bronie'], function () {
    Route::get('/', [WeaponsController::class, 'index'])->name('weapons.index');
    Route::get('/get-weapons', [WeaponsController::class, 'getWeapons'])->name('weapons.get-weapons');
});
Route::group(['prefix' => 'opanczerzenie'], function () {
    Route::get('/', [ArmorsController::class, 'index'])->name('armors.index');
    Route::get('/get-armors', [ArmorsController::class, 'getArmors'])->name('armors.get-armors');
});
Route::group(['prefix' => 'umiejetnosci'], function () {
    Route::get('/', [SkillsAndTalentsController::class, 'skillsIndex'])->name('skills-and-talents.skills-index');
    Route::get('/get-skills', [SkillsAndTalentsController::class, 'getSkills'])->name('skills-and-talents.get-skills');
});

Route::group(['prefix' => 'zdolnosci'], function () {
    Route::get('/', [SkillsAndTalentsController::class, 'talentsIndex'])->name('skills-and-talents.talents-index');
    Route::get('/get-talents', [SkillsAndTalentsController::class, 'getTalents'])->name('skills-and-talents.get-talents');
});

Route::middleware('auth')->group(function (){
    Route::get('/', function () {
        return redirect('/karta-postaci/' . Auth::user()->getAuthIdentifier());
    });
    Route::group(['prefix' => 'karta-postaci'], function () {
        Route::get('/{id}', [CharactersController::class, 'getCharacterSheet'])->name('character-sheet');
        Route::post('/{hero}/update-hero-data', [CharactersController::class, 'updateHeroData'])->name('character-sheet.update-hero-data');
        Route::post('/{hero}/update-hero-description', [CharactersController::class, 'updateHeroDescription'])->name('character-sheet.update-hero-description');
        Route::post('/{hero}/update-hero-characteristic', [CharactersController::class, 'updateHeroCharacteristic'])->name('character-sheet.update-hero-characteristic');
        Route::post('/{hero}/add-item', [CharactersController::class, 'addItem'])->name('character-sheet.add-item');
    });

    Route::get('/professions/get-professions', [ProfessionsController::class, 'getProfessions'])->name('get-professions');

    Route::get('/migrate-fresh', function(){
        ini_set('memory_limit', '4069M');
        ini_set('max_execution_time', 5000);
        \Artisan::call('migrate:fresh', ['--seed' => true]);
        dd("Migracje zostały wykonane");
    });
});
