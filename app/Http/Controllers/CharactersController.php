<?php

namespace App\Http\Controllers;

use App\Exceptions\NotEnoughFortunePointsException;
use App\Exceptions\NotEnoughMoneyException;
use App\Models\Armor;
use App\Models\Characteristic;
use App\Models\Hero;
use App\Models\HeroInventory;
use App\Models\Talent;
use App\Models\Weapon;
use App\Repositories\HeroesRepository;
use App\Services\CreateCharacterService;
use App\Services\FortunePointSatisfactionService;
use App\Services\HeroService;
use App\Services\TransactionsService;
use Auth;
use DB;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class CharactersController extends Controller
{
    public function __construct(
        private readonly HeroService                     $heroService,
        private readonly FortunePointSatisfactionService $fortunePointSatisfactionService,
        private readonly CreateCharacterService          $createCharacterService,
        private readonly HeroesRepository                $heroesRepository
    ) {}

    public function getHero(int $userId): JsonResponse
    {
        try {
            return response()->json($this->heroesRepository->getHero($userId));
        } catch (\Throwable $exception) {
            return response()->json(['message' => 'Wystąpił błąd podczas pobierania bohatera'], 500);
        }
    }

    public function index(Request $request, int $id)
    {
        return view('Pages.character-sheet', compact('id'));
    }

    public function updateHero(Request $request, Hero $hero): ?JsonResponse
    {
        try {
            if (!$request->has(['field', 'value'])) {
                throw new \Exception('Nie przesłano wymaganych danych');
            }
            if ($request->get('field') === 'fortune_points' && $hero->fortune_points < $request->get('value')) {
                throw new \Exception('Nie możesz zwiększać ręcznie punktów szczęścia!');
            }
            $hero->update([$request->get('field') => $request->get('value')]);
            return response()->json(['message' => 'Pomyślnie zaktualizowano bohatera', 'characteristic' => $hero->load('characteristic')->getRelation('characteristic')]);
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function updateDescription(Request $request, Hero $hero)
    {
        try {
            if (!$request->has(['field', 'value'])) {
                throw new \Exception('Nie przesłano wymaganych danych');
            }
            $hero->description()->update([$request->get('field') => $request->get('value')]);
            return response()->json(['message' => 'Pomyślnie zaktualizowano opis bohatera']);
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function updateCharacteristic(Request $request, Hero $hero)
    {
        $hero->loadMissing('characteristic');
        try {
            DB::beginTransaction();
            if (!$request->has('characteristic')) {
                throw new \Exception('Nie przesłano wymaganych pól');
            }
            $characteristic = $request->get("characteristic");
            $developedValue = $characteristic['type'] === 'MAIN' ? 5 : 1;
            $expNeeded = 0;
            if (!in_array($characteristic['short_name'], Characteristic::DEVELOPED_WITHOUT_EXPERIENCE, true)) {
                $availableAdvancement = $characteristic['available_advancement'] ?? 0;
                $currentValue = $characteristic['pivot']['start_value'] + $characteristic['pivot']['advancement'];
                $expNeeded = ($currentValue + $developedValue > $characteristic['pivot']['start_value'] + $availableAdvancement) ?
                    Characteristic::DEVELOP_EXP_NEEDED['OUTSIDE_SCHEMA']
                    : Characteristic::DEVELOP_EXP_NEEDED['BASIC'];
                if ($hero->current_experience < $expNeeded) {
                    throw new \Exception('Nie masz wymaganej liczby punktktów doświadczenia. Brakuje Ci ' . $expNeeded - $hero->current_experience . 'PD');
                }
                $hero->update(['current_experience' => $hero->current_experience - $expNeeded]);
            }
            $hero->characteristic()->syncWithoutDetaching([
                $characteristic['id'] => [
                    'start_value' => $characteristic['pivot']['start_value'],
                    'advancement' => $characteristic['pivot']['advancement'] + $developedValue
                ]
            ]);
            $changeCurrentWounds = 0;
            if (
                $characteristic['short_name'] === 'Żyw' &&
                $hero->getRelation('characteristic')[$characteristic['short_name']]->pivot->current_value === $hero->current_wounds
            ) {
                $hero->update(['current_wounds' => $characteristic['pivot']['current_value']]);
                $changeCurrentWounds = $characteristic['pivot']['current_value'];
            }
            DB::commit();
        } catch (\Throwable $exception) {
            try {
                DB::rollBack();
            } catch (\Throwable $e) {
                \Log::error('FATAL ERROR DURING ROLLBACK CHANGE ' . $e->getMessage());
                return response()->json(['message' => $exception->getMessage()]);
            }
            return response()->json(['message' => $exception->getMessage()], 500);
        }

        return response()->json(['message' => 'Udało się zakutalizować cechę bohatera', 'changeCurrentWounds' => $changeCurrentWounds, 'spentExperience' => $expNeeded, 'developedValue' => $developedValue]);
    }

    public function addWeapon(Request $request, Hero $hero)
    {
        $weapon = Weapon::query()->find($request->get('weaponId'));
        try {
            if (DB::table('hero_weapons')->where('hero_id', $hero->id)->where('weapon_id', $weapon->id)->first()) {
                throw new \Exception('Już posiadasz taką broń! Jeśli chcesz jej używać, aktualną broń tego typu usuń z zakładki "Broń" i zapisz do ekwipunku');
            }
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
        $hero->weapons()->syncWithoutDetaching([
            $weapon->id => ['additional_weapon_name' => $request->get('additionalWeaponName')]
        ]);

        return response()->json(collect([
            'id' => $weapon->id,
            'is_ranged' => $weapon->is_ranged,
            'name' => $weapon->name,
            'loading' => $weapon->loading,
            'category' => $weapon->category,
            'power' => $weapon->power,
            'short_range' => $weapon->short_range,
            'long_range' => $weapon->long_range,
            'reload_time' => $weapon->reload_time,
            'traits' => $weapon->traits,
            'pivot' => [
                'additional_weapon_name' => $request->get('additionalWeaponName')
            ]
        ]));
    }

    public function editWeapon(Request $request, Hero $hero)
    {
        $hero->weapons()->syncWithoutDetaching([
            $request->get('weapon')['id'] => ['additional_weapon_name' => $request->get('additionalWeaponName')]
        ]);

        return response()->json(['message' => 'Pomyślnie zedytowano broń']);
    }

    public function dropWeapon(Request $request, Hero $hero)
    {
        $weapon = $request->get('weapon');
        $hero->weapons()->detach($weapon['id']);

        return response()->json(['message' => 'Pomyślnie usunięto broń']);
    }

    public function unequipWeapon(Request $request, Hero $hero)
    {
        $weapon = $request->get('weapon');
        $hero->weapons()->detach($weapon['id']);
        $newInventoryItem = $hero->inventory()->create([
            'name' => $weapon['name'],
            'loading' => $weapon['loading'],
            'description' => $weapon['pivot']['additional_weapon_name']
        ]);

        return response()->json(['message' => 'Pomyślnie schowano broń do ekwipunku', 'inventory' => $newInventoryItem]);
    }

    public function addArmor(Request $request, Hero $hero)
    {
        $armor = Armor::query()->with('locations')->find($request->get('armorId'));
        try {
            if (DB::table('hero_armors')->where('hero_id', $hero->id)->where('armor_id', $armor->id)->first()) {
                throw new \Exception('Już nosisz taką zbroję!');
            }
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
        $hero->armors()->syncWithoutDetaching($armor->id);

        return response()->json($armor);
    }

    public function dropArmor(Request $request, Hero $hero)
    {
        $armor = $request->get('armor');
        $hero->armors()->detach($armor['id']);

        return response()->json(['message' => 'Pomyślnie usunięto zbroje']);
    }

    public function unequipArmor(Request $request, Hero $hero)
    {
        $armor = $request->get('armor');
        $hero->armors()->detach($armor['id']);
        $newInventoryItem = $hero->inventory()->create([
            'name' => $armor['name'],
            'loading' => $armor['loading'],
            'description' => $armor['category']
        ]);

        return response()->json(['message' => 'Pomyślnie schowano zbroję do ekwipunku', 'inventory' => $newInventoryItem]);
    }

    public function updateSkill(Request $request, Hero $hero)
    {
        $skill = $request->get('skill');
        try {
            $newSkill = $hero->updateOrCreateSkill($skill['pivot']['id'], [
                'hero_id' => $skill['pivot']['hero_id'],
                'skill_id' => $skill['id'],
                'additional_skill_name' => $skill['pivot']['additional_skill_name'],
                'hurdled' => $skill['pivot']['hurdled'],
                'first_level' => $skill['pivot']['first_level'],
                'second_level' => $skill['pivot']['second_level']
            ], $request->get('action'));

            return response()->json(['message' => 'Zaktualizowano umiejętność', 'skill' => $newSkill]);
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function addTalent(Request $request, Hero $hero)
    {
        $talent = Talent::query()->find($request->get('talentId'));
        try {
            if (DB::table('hero_talent')->where('hero_id', $hero->id)->where('talent_id', $talent->id)->first()) {
                throw new \Exception('Już posiadasz tą zdolnosć!');
            }
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
        $hero->talents()->syncWithoutDetaching($talent->id);
        return response()->json($talent);
    }

    public function dropTalent(Request $request, Hero $hero)
    {
        $talent = $request->get('talent');
        $hero->talents()->detach($talent['id']);
        return response()->json(['message' => 'Pomyślnie usunięto zdolność']);
    }

    public function addItem(Request $request, int $id)
    {
        return response()->json(HeroInventory::query()->create([
            'hero_id' => $id,
            'name' => $request->get('name'),
            'loading' => $request->get('loading'),
            'description' => $request->get('description')
        ]));
    }

    public function equipMarketplaceItem(Request $request, int $id): ?JsonResponse
    {
        try {
            return new TransactionsService()->equipMarketplaceItem($request, $id);
        } catch (NotEnoughMoneyException $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_PAYMENT_REQUIRED);
        } catch (\Throwable $exception) {
            \Log::error('ERROR EQUIPPING MARKETPLACE ITEM');
            \Log::error($exception);
            return response()->json(['message' => 'Wystąpił błąd podczas zakładania ekwipunku. Majątek nie został pomniejszony'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function editItem(Request $request, Hero $hero): JsonResponse
    {
        $item = $request->get('item');
        HeroInventory::query()->where('hero_id', $hero->id)->where('id', $item['id'])->update([
            'name' => $item['name'],
            'description' => $item['description'],
        ]);
        return response()->json(['message' => 'Pomyślnie edytowano przedmiot']);
    }

    public function dropInventoryItem(Request $request, Hero $hero)
    {
        $item = $request->get('item');
        HeroInventory::query()->where('hero_id', $hero->id)->where('id', $item['id'])->delete();
        return response()->json(['message' => 'Pomyślnie usunięto przedmiot']);
    }

    public static function createEmptyCharacter(int $userId)
    {
        $hero = Hero::create([
            'user_id' => $userId,
            'name' => '',
            'race' => 'Człowiek',
            'current_experience' => 0,
            'all_experience' => 0,
            'current_wounds' => 0,
            'gold_crowns' => 0,
            'silver_shillings' => 0,
            'brass_pennies' => 0,
            'fortune_pennies' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        for($i = 1; $i <= 16; $i++) {
            $hero->characteristic()->attach($i, [
                'start_value' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        for($i = 1; $i <= 47; $i++) {
            $hero->skills()->attach($i, [
                'hurdled' => 0,
                'first_level' => 0,
                'second_level' => 0,
            ]);
        }

        $hero->description()->create([
            'age' => 0,
            'gender' => 'M',
        ]);
    }

    public function spendFortunePoint(Hero $hero): ?JsonResponse
    {
        try {
            $this->heroService->spendFortunePoint($hero);
            return response()->json('ok', Response::HTTP_OK);
        } catch (NotEnoughFortunePointsException $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            \Log::error('ERROR SPENDING FORTUNE POINTS: ' . $exception->getMessage());
            return response()->json(['message' => 'Nie udało się wydać punktu szczęścia'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logFortunePointsSatisfaction(Request $request, Hero $hero): ?JsonResponse
    {

        try {
            $this->fortunePointSatisfactionService->logSatisfaction($request, $hero);
            return response()->json('ok', Response::HTTP_OK);
        } catch (\Throwable $exception) {
            \Log::error('ERROR LOGGING FORTUNE POINTS SATISFACTION: ' . $exception->getMessage());
            return response()->json(['message' => 'Nie udało się zapisać oceny punktu szczęścia'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRolledProfession(Request $request): JsonResponse
    {
        try {
            if (!$request->query('race') || !$request->query('rolledValue')) {
                throw new BadRequestException('Nie przesłano wymaganych danych');
            }
            return response()->json($this->createCharacterService->getFormatedRolledProfession($request->query('race'), $request->query('rolledValue')), Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            \Log::error('PROFESSION NOT FOUND. Request data:' . print_r($request->all(), true) . $exception->getMessage());
            return response()->json(['message' => 'Nie znaleziono profesji tego wyniku rzutu.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            \Log::error('ERROR DURING GETTING ROLLED PROFESSION: ' . $exception->getMessage());
            return response()->json(['message' => 'Nie udało się pobrać profesji'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRaceProfessions(string $race): JsonResponse
    {
        try {
            return response()->json($this->createCharacterService->getFormatedRolledProfession($race), Response::HTTP_OK);
        } catch (\Throwable $exception) {
            \Log::error('ERROR DURING GETTING ROLLED PROFESSION: ' . $exception->getMessage());
            return response()->json(['message' => 'Nie udało się pobrać profesji'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRaces(): JsonResponse
    {
        try {
            return response()->json($this->createCharacterService->getRaces(), Response::HTTP_OK);
        } catch (\Throwable $exception) {
            \Log::error('ERROR DURING GETTING RACES: ' . $exception->getMessage());
            return response()->json(['message' => 'Nie udało się pobrać rasy'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCharacter(Request $request)
    {
        try {
            return response()->json($this->createCharacterService->createHero($request->all()), Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $exception) {
            \Log::error('WOUNDS OR/AND FATE NOT FOUND IN REQUEST');
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (BadRequestException $exception) {
            \Log::error('BAD REQUEST DURING CHARACTER CREATION: ' . $exception->getMessage());
            return response()->json(['message' => 'Nieprawidłowe dane'], Response::HTTP_BAD_REQUEST);
        } catch (AuthorizationException $exception) {
            \Log::error('BAD REQUEST DURING CHARACTER CREATION: ' . $exception->getMessage());
            return response()->json(['message' => 'Nie jesteś zalogowany lub nastąpiło wylogowanie. Niestety musisz stworzyć postać ponownie'], Response::HTTP_FORBIDDEN);
        } catch (\Throwable $exception) {
            dd($exception);
            \Log::error('ERROR DURING CHARACTER CREATION: ' . $exception->getMessage());
            return response()->json(['message' => 'Nie udało się utworzyć postaci'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteHero(Hero $hero): JsonResponse
    {
        try {
            if ($hero->user_id !== Auth::id()) {
                throw new AuthorizationException('Nie masz uprawnień do usunięcia tego bohatera');
            }
            $hero->delete();
            return response()->json(['message' => 'Bohater został usunięty'], Response::HTTP_OK);
        } catch (AuthorizationException $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (\Throwable $exception) {
            \Log::error('ERROR DELETING HERO: ' . $exception->getMessage());
            return response()->json(['message' => 'Wystąpił błąd podczas usuwania bohatera'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
