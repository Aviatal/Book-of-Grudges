<?php

namespace Database\Seeders;

use App\Models\Profession;
use App\Models\StartingProfessionRoll;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Seeder;
use RuntimeException;

class StartingProfessionRollSeeder extends Seeder
{
    private array $rollValues = [
        'Akolita' => [
            'human' => [
                'min' => 1,
                'max' => 2,
            ],
        ],
        'Banita' => [
            'human' => [
                'min' => 3,
                'max' => 4,
            ],
            'elf' => [
                'min' => 1,
                'max' => 5,
            ],
            'dwarf' => [
                'min' => 1,
                'max' => 3,
            ],
            'halfling' => [
                'min' => 1,
                'max' => 3,
            ],
        ],
        'Berserker z Norski' => [
            'human' => [
                'min' => 5,
                'max' => 5,
            ],
        ],
        'Chłop' => [
            'human' => [
                'min' => 6,
                'max' => 7,
            ],
        ],
        'Ciura obozowa' => [
            'human' => [
                'min' => 8,
                'max' => 9,
            ],
            'halfling' => [
                'min' => 4,
                'max' => 5,
            ],
        ],
        'Cyrkowiec' => [
            'human' => [
                'min' => 10,
                'max' => 11,
            ],
            'elf' => [
                'min' => 6,
                'max' => 10,
            ],
            'dwarf' => [
                'min' => 4,
                'max' => 6,
            ],
            'halfling' => [
                'min' => 6,
                'max' => 8,
            ],
        ],
        'Cyrulik' => [
            'human' => [
                'min' => 12,
                'max' => 12,
            ],
            'halfling' => [
                'min' => 9,
                'max' => 9,
            ],
        ],
        'Fanatyk' => [
            'human' => [
                'min' => 13,
                'max' => 14,
            ],
        ],
        'Flisak' => [
            'human' => [
                'min' => 15,
                'max' => 16,
            ],
        ],
        'Giermek' => [
            'human' => [
                'min' => 17,
                'max' => 18,
            ],
        ],
        'Gladiator' => [
            'human' => [
                'min' => 19,
                'max' => 20,
            ],
            'dwarf' => [
                'min' => 7,
                'max' => 11,
            ],
        ],
        'Goniec' => [
            'dwarf' => [
                'min' => 12,
                'max' => 16,
            ],
        ],
        'Górnik' => [
            'human' => [
                'min' => 21,
                'max' => 22,
            ],
            'dwarf' => [
                'min' => 17,
                'max' => 22,
            ],
        ],
        'Guślarz' => [
            'human' => [
                'min' => 23,
                'max' => 23,
            ],
        ],
        'Hiena cmentarna' => [
            'human' => [
                'min' => 24,
                'max' => 25,
            ],
            'dwarf' => [
                'min' => 23,
                'max' => 25,
            ],
            'halfling' => [
                'min' => 10,
                'max' => 14,
            ],
        ],
        'Kanciarz' => [
            'human' => [
                'min' => 26,
                'max' => 27,
            ],
            'elf' => [
                'min' => 11,
                'max' => 16,
            ],
            'halfling' => [
                'min' => 15,
                'max' => 20,
            ],
        ],
        'Kozak kislevski' => [
            'human' => [
                'min' => 28,
                'max' => 28,
            ],
        ],
        'Leśnik' => [
            'human' => [
                'min' => 29,
                'max' => 30,
            ],
        ],
        'Łowca' => [
            'human' => [
                'min' => 31,
                'max' => 32,
            ],
            'elf' => [
                'min' => 17,
                'max' => 24,
            ],
            'dwarf' => [
                'min' => 26,
                'max' => 29,
            ],
            'halfling' => [
                'min' => 21,
                'max' => 25,
            ],
        ],
        'Łowca nagród' => [
            'human' => [
                'min' => 33,
                'max' => 34,
            ],
            'halfling' => [
                'min' => 26,
                'max' => 27,
            ],
        ],
        'Mieszczanin' => [
            'human' => [
                'min' => 35,
                'max' => 36,
            ],
            'dwarf' => [
                'min' => 30,
                'max' => 33,
            ],
            'halfling' => [
                'min' => 28,
                'max' => 29,
            ],
        ],
        'Mytnik' => [
            'human' => [
                'min' => 37,
                'max' => 38,
            ],
            'dwarf' => [
                'min' => 34,
                'max' => 36,
            ],
            'halfling' => [
                'min' => 30,
                'max' => 31,
            ],
        ],
        'Najemnik' => [
            'human' => [
                'min' => 39,
                'max' => 40,
            ],
            'elf' => [
                'min' => 25,
                'max' => 29,
            ],
            'dwarf' => [
                'min' => 37,
                'max' => 42,
            ],
            'halfling' => [
                'min' => 32,
                'max' => 35,
            ],
        ],
        'Ochotnik' => [
            'human' => [
                'min' => 41,
                'max' => 42,
            ],
            'dwarf' => [
                'min' => 43,
                'max' => 46,
            ],
            'halfling' => [
                'min' => 36,
                'max' => 40,
            ],
        ],
        'Ochroniarz' => [
            'human' => [
                'min' => 43,
                'max' => 44,
            ],
            'dwarf' => [
                'min' => 47,
                'max' => 50,
            ],
        ],
        'Oprych' => [
            'human' => [
                'min' => 45,
                'max' => 46,
            ],
        ],
        'Paź' => [
            'human' => [
                'min' => 47,
                'max' => 48,
            ],
            'elf' => [
                'min' => 30,
                'max' => 31,
            ],
            'halfling' => [
                'min' => 41,
                'max' => 42,
            ],
        ],
        'Podżegacz' => [
            'human' => [
                'min' => 49,
                'max' => 50,
            ],
            'dwarf' => [
                'min' => 51,
                'max' => 52,
            ],
            'halfling' => [
                'min' => 43,
                'max' => 45,
            ],
        ],
        'Porywacz zwłok' => [
            'human' => [
                'min' => 51,
                'max' => 52,
            ],
            'halfling' => [
                'min' => 46,
                'max' => 48,
            ],
        ],
        'Posłaniec' => [
            'human' => [
                'min' => 53,
                'max' => 54,
            ],
            'elf' => [
                'min' => 32,
                'max' => 37,
            ],
            'halfling' => [
                'min' => 49,
                'max' => 53,
            ],
        ],
        'Przemytnik' => [
            'human' => [
                'min' => 55,
                'max' => 56,
            ],
            'dwarf' => [
                'min' => 53,
                'max' => 55,
            ],
            'halfling' => [
                'min' => 54,
                'max' => 56,
            ],
        ],
        'Przepatrywacz' => [
            'human' => [
                'min' => 57,
                'max' => 58,
            ],
            'elf' => [
                'min' => 38,
                'max' => 43,
            ],
        ],
        'Przewoźnik' => [
            'human' => [
                'min' => 59,
                'max' => 59,
            ],
            'halfling' => [
                'min' => 57,
                'max' => 57,
            ],
        ],
        'Rybak' => [
            'human' => [
                'min' => 60,
                'max' => 61,
            ],
            'halfling' => [
                'min' => 58,
                'max' => 58,
            ],
        ],
        'Rzecznik rodu' => [
            'elf' => [
                'min' => 44,
                'max' => 50,
            ],
        ],
        'Rzemieślnik' => [
            'human' => [
                'min' => 62,
                'max' => 63,
            ],
            'elf' => [
                'min' => 51,
                'max' => 57,
            ],
            'dwarf' => [
                'min' => 56,
                'max' => 59,
            ],
            'halfling' => [
                'min' => 59,
                'max' => 63,
            ],
        ],
        'Rzezimieszek' => [
            'human' => [
                'min' => 64,
                'max' => 65,
            ],
            'dwarf' => [
                'min' => 60,
                'max' => 63,
            ],
        ],
        'Skryba' => [
            'human' => [
                'min' => 66,
                'max' => 67,
            ],
            'elf' => [
                'min' => 58,
                'max' => 63,
            ],
            'dwarf' => [
                'min' => 64,
                'max' => 65,
            ],
        ],
        'Sługa' => [
            'human' => [
                'min' => 68,
                'max' => 69,
            ],
            'dwarf' => [
                'min' => 66,
                'max' => 67,
            ],
            'halfling' => [
                'min' => 64,
                'max' => 68,
            ],
        ],
        'Strażnik' => [
            'human' => [
                'min' => 70,
                'max' => 71,
            ],
            'dwarf' => [
                'min' => 68,
                'max' => 69,
            ],
            'halfling' => [
                'min' => 69,
                'max' => 72,
            ],
        ],
        'Strażnik dróg' => [
            'human' => [
                'min' => 72,
                'max' => 73,
            ],
            'halfling' => [
                'min' => 73,
                'max' => 74,
            ],
        ],
        'Strażnik pól' => [
            'halfling' => [
                'min' => 75,
                'max' => 78,
            ],
        ],
        'Strażnik więzienny' => [
            'human' => [
                'min' => 74,
                'max' => 74,
            ],
            'dwarf' => [
                'min' => 70,
                'max' => 73,
            ],
        ],
        'Szczurołap' => [
            'human' => [
                'min' => 75,
                'max' => 76,
            ],
            'dwarf' => [
                'min' => 74,
                'max' => 77,
            ],
            'halfling' => [
                'min' => 79,
                'max' => 82,
            ],
        ],
        'Szermierz estalijski' => [
            'human' => [
                'min' => 77,
                'max' => 77,
            ],
        ],
        'Szlachcic' => [
            'human' => [
                'min' => 78,
                'max' => 79,
            ],
            'dwarf' => [
                'min' => 78,
                'max' => 79,
            ],
        ],
        'Śmieciarz' => [
            'human' => [
                'min' => 80,
                'max' => 81,
            ],
            'halfling' => [
                'min' => 83,
                'max' => 83,
            ],
        ],
        'Tarczownik' => [
            'dwarf' => [
                'min' => 80,
                'max' => 83,
            ],
        ],
        'Uczeń czarodzieja' => [
            'human' => [
                'min' => 82,
                'max' => 83,
            ],
            'elf' => [
                'min' => 64,
                'max' => 70,
            ],
        ],
        'Węglarz' => [
            'human' => [
                'min' => 84,
                'max' => 85
            ],
            'halfling' => [
                'min' => 84,
                'max' => 86,
            ],
        ],
        'Włóczykij' => [
            'human' => [
                'min' => 86,
                'max' => 87,
            ],
            'elf' => [
                'min' => 71,
                'max' => 77,
            ],
            'halfling' => [
                'min' => 87,
                'max' => 90,
            ],
        ],
        'Wojownik klanowy' => [
            'elf' => [
                'min' => 78,
                'max' => 84,
            ],
        ],
        'Woźnica' => [
            'human' => [
                'min' => 88,
                'max' => 89,
            ],
            'dwarf' => [
                'min' => 84,
                'max' => 85
            ],
        ],
        'Zabójca trolli' => [
            'dwarf' => [
                'min' => 86,
                'max' => 89,
            ],
        ],
        'Zarządca' => [
            'human' => [
                'min' => 90,
                'max' => 90,
            ],
        ],
        'Złodziej' => [
            'human' => [
                'min' => 91,
                'max' => 92,
            ],
            'elf' => [
                'min' => 85,
                'max' => 90,
            ],
            'dwarf' => [
                'min' => 90,
                'max' => 92,
            ],
            'halfling' => [
                'min' => 91,
                'max' => 96,
            ],
        ],
        'Żak' => [
            'human' => [
                'min' => 93,
                'max' => 94,
            ],
            'elf' => [
                'min' => 91,
                'max' => 95,
            ],
            'dwarf' => [
                'min' => 93,
                'max' => 94,
            ],
            'halfling' => [
                'min' => 97,
                'max' => 98,
            ],
        ],
        'Żeglarz' => [
            'human' => [
                'min' => 95,
                'max' => 96,
            ],
            'elf' => [
                'min' => 96,
                'max' => 100,
            ],
            'dwarf' => [
                'min' => 95,
                'max' => 95,
            ],
        ],
        'Żołnierz' => [
            'human' => [
                'min' => 97,
                'max' => 98
            ],
            'dwarf' => [
                'min' => 96,
                'max' => 99,
            ],
            'halfling' => [
                'min' => 99,
                'max' => 100,
            ],
        ],
        'Żołnierz okrętowy' => [
            'human' => [
                'min' => 99,
                'max' => 100,
            ],
            'dwarf' => [
                'min' => 100,
                'max' => 100,
            ],
        ],
    ];

    /**
     * Run the database seeds.
     * @throws \Throwable
     */
    public function run()
    {
        $professions = Profession::all()->pluck('id', 'name')->toArray();
        $insertData = [];
        try {
            foreach ($this->rollValues as $professionName => $races) {
                foreach ($races as $raceName => $rolls) {
                    if (!isset($professions[$professionName])) {
                        throw new ModelNotFoundException("Profession {$professionName} not found");
                    }
                    $insertData[] = [
                        'profession_id' => $professions[$professionName],
                        'race' => $raceName,
                        'min_roll' => $rolls['min'],
                        'max_roll' => $rolls['max'],
                    ];
                }
            }

            \DB::transaction(function () use ($insertData) {
                StartingProfessionRoll::insert($insertData);
                $startingProfessionRolls = StartingProfessionRoll::select('profession_id', 'race', 'min_roll', 'max_roll')->get();
                for ($i = 1; $i <= 100; $i++) {
                    foreach (['human', 'elf', 'dwarf', 'halfling'] as $race) {
                        if (
                            $startingProfessionRolls
                                ->where('race', $race)
                                ->where('min_roll', '<=', $i)
                                ->where('max_roll', '>=', $i)
                                ->count() !== 1
                        ) {
                            throw new RuntimeException(
                                "No starting profession roll found (or found more than one) for roll {$i} for race {$race}. Professions found: " .
                                $startingProfessionRolls
                                    ->where('race', $race)
                                    ->where('min_roll', '<=', $i)
                                    ->where('max_roll', '>=', $i)
                                    ->pluck('profession_id')
                                    ->implode(',')
                            );
                        }
                    }
                }
            });
        } catch (ModelNotFoundException $e) {
            \Log::error($e->getMessage());
            throw $e;
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            throw $e;
        }
    }
}
