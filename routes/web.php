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
Route::group(['prefix' => 'opancerzenie'], function () {
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
        Route::get('/{id}', [CharactersController::class, 'index'])->name('character-sheet.index');
        Route::get('/{id}/get-hero', [CharactersController::class, 'getHero'])->name('character-sheet.get-hero');
        Route::post('/{hero}/update-hero', [CharactersController::class, 'updateHero'])->name('character-sheet.update-hero');
        Route::post('/{hero}/update-description', [CharactersController::class, 'updateDescription'])->name('character-sheet.update-description');
        Route::post('/{hero}/update-characteristic', [CharactersController::class, 'updateCharacteristic'])->name('character-sheet.update-characteristic');

        Route::post('/{hero}/add-weapon', [CharactersController::class, 'addWeapon'])->name('character-sheet.add-weapon');
        Route::post('/{hero}/edit-weapon', [CharactersController::class, 'editWeapon'])->name('character-sheet.edit-weapon');
        Route::post('/{hero}/drop-weapon', [CharactersController::class, 'dropWeapon'])->name('character-sheet.drop-weapon');
        Route::post('/{hero}/unequip-weapon', [CharactersController::class, 'unequipWeapon'])->name('character-sheet.unequip-weapon');

        Route::post('/{hero}/add-armor', [CharactersController::class, 'addArmor'])->name('character-sheet.add-armor');
        Route::post('/{hero}/drop-armor', [CharactersController::class, 'dropArmor'])->name('character-sheet.drop-armor');
        Route::post('/{hero}/unequip-armor', [CharactersController::class, 'unequipArmor'])->name('character-sheet.unequip-armor');

        Route::post('/{hero}/update-skill', [CharactersController::class, 'updateSkill'])->name('character-sheet.update-skill');

        Route::post('/{hero}/add-talent', [CharactersController::class, 'addTalent'])->name('character-sheet.add-talent');
        Route::post('/{hero}/drop-talent', [CharactersController::class, 'dropTalent'])->name('character-sheet.drop-talent');

        Route::post('/{hero}/add-inventory-item', [CharactersController::class, 'addItem'])->name('character-sheet.add-item');
        Route::post('/{hero}/drop-item-from-inventory', [CharactersController::class, 'dropInventoryItem'])->name('character-sheet.drop-inventory-item');
    });

    Route::get('/professions/get-professions', [ProfessionsController::class, 'getProfessions'])->name('get-professions');

    Route::get('/migrate-fresh', function(){
        ini_set('memory_limit', '4069M');
        ini_set('max_execution_time', 5000);
        \Artisan::call('migrate:fresh', ['--seed' => true]);
        dd("Migracje zostały wykonane");
    });
});
