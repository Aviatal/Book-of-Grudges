<?php

namespace Database\Seeders;

use App\Models\Ammunition;
use App\Models\Armor;
use App\Models\CommonItems;
use App\Models\Profession;
use App\Models\Weapon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionEquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $professions = Profession::all()->pluck('id', 'name')->toArray();

        $commonItems = CommonItems::with('marketplaceItem')->get()->keyBy('name');
        $weapons = Weapon::with('marketplaceItem')->get()->keyBy('name');
        $armors = Armor::select([DB::raw("CONCAT(name, '-', category) as name"), 'id'])
            ->with('marketplaceItem')
            ->get()
            ->keyBy('name');
        $ammunition = Ammunition::with('marketplaceItem')->get()->keyBy('name');

        $professionsEquipment = [
            'Akolita' => [
                [
                    ['item_id' => $commonItems['Szaty kapłańskie']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $commonItems['Symbol religijny']->marketplaceItem->id, 'item_name' => 'Symbol Boga']
                ],
            ],
            'Banita' => [
                [
                    ['item_id' => $weapons['Łuk']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $ammunition['Strzały']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $ammunition['Strzały']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzany kaftan']
                ],
                [
                    ['item_id' => $weapons['Tarcza']->marketplaceItem->id, 'item_name' => null]
                ],
            ],
            'Berserker z Norski' => [
                [ // Wybór
                    ['item_id' => $weapons['Broń dwuręczna']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Tarcza']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $commonItems['Butelka gorzałki']->marketplaceItem->id, 'item_name' => null]
                ],
            ],
            'Chłop' => [
                [ // Wybór
                    ['item_id' => $weapons['Proca']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Kij']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $commonItems['Bukłak']->marketplaceItem->id, 'item_name' => 'Skórzany bukłak']
                ],
            ],
            'Ciura obozowa' => [
                [ // Wybór
                    ['item_id' => $commonItems['Talizman szczęścia']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $commonItems['Narzędzia (rzemieślnika)']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $commonItems['Sakiewka']->marketplaceItem->id, 'item_name' => null]
                ],
            ],
            'Cyrkowiec' => [
                [
                    ['item_id' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id, 'item_name' => null]
                ],
                [ // Wybór z 3 opcji
                    ['item_id' => $weapons['Nóż do rzucania']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Topór do rzucania']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $commonItems['Instrument muzyczny']->marketplaceItem->id, 'item_name' => null],
                ],
                [
                    ['item_id' => $commonItems['Narzędzia (kuglarza)']->marketplaceItem->id, 'item_name' => null]
                ],
                [ // Wybór
                    ['item_id' => $commonItems['Kostium (cyrkowca)']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $commonItems['Dobre ubranie']->marketplaceItem->id, 'item_name' => null]
                ],
            ],
            'Cyrulik' => [
                [
                    ['item_id' => $commonItems['Narzędzia (cyrulika)']->marketplaceItem->id, 'item_name' => null]
                ],
            ],
            'Fanatyk' => [
                [ // Wybór
                    ['item_id' => $weapons['Korbacz']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Morgensztern']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']
                ],
                [ // Wybór
                    ['item_id' => $commonItems['Butelka gorzałki']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $commonItems['Butelka spirytusu']->marketplaceItem->id, 'item_name' => null]
                ],
            ],
            'Flisak' => [
                [
                    ['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']
                ],
                [
                    ['item_id' => $commonItems['Łódź wiosłowa']->marketplaceItem->id, 'item_name' => null]
                ],
            ],
            'Giermek' => [
                [['item_id' => $weapons['Lanca']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $armors['Kaftan-KOLCZA']->marketplaceItem->id, 'item_name' => 'Kaftan kolczy']],
                [['item_id' => $armors['Czepiec-KOLCZA']->marketplaceItem->id, 'item_name' => 'Czepiec kolczy']],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $weapons['Tarcza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Koń']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Siodło']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Uprząż']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Gladiator' => [
                [
                    ['item_id' => $weapons['Broń dwuręczna']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Korbacz']->marketplaceItem->id, 'item_name' => null]
                ],
                [['item_id' => $weapons['Kastet']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $armors['Kaftan-KOLCZA']->marketplaceItem->id, 'item_name' => 'Kaftan kolczy']],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [
                    ['item_id' => $weapons['Tarcza']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Puklerz']->marketplaceItem->id, 'item_name' => null]
                ],
            ],
            'Goniec' => [
                [['item_id' => $weapons['Kusza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $commonItems['Mikstura lecznicza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Talizman szczęścia']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Górnik' => [
                [['item_id' => $weapons['Broń dwuręczna']->marketplaceItem->id, 'item_name' => 'Dwuręczny kilof']],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $commonItems['Kilof']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Łopata']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Latarnia sztormowa']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Olej do latarni']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Guślarz' => [
                [['item_id' => $commonItems['Mikstura lecznicza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Płaszcz']->marketplaceItem->id, 'item_name' => 'Płaszcz z kapturem']],
            ],
            'Hiena cmentarna' => [
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $commonItems['Łom']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Latarnia']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Olej do latarni']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Lina']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Worek']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Worek']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Kanciarz' => [
                [
                    ['item_id' => $commonItems['Szykowne ubranie']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $commonItems['Kości do gry']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $commonItems['Talia kart']->marketplaceItem->id, 'item_name' => null],
                ]
            ],
            'Kozak kislevski' => [
                [['item_id' => $weapons['Łuk']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Strzały']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Strzały']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $weapons['Broń dwuręczna']->marketplaceItem->id, 'item_name' => 'Dwuręczny topór']],
                [['item_id' => $armors['Kolczuga-KOLCZA']->marketplaceItem->id, 'item_name' => 'Kolczuga']],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $commonItems['Odtrutki']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Leśnik' => [
                [['item_id' => $weapons['Broń dwuręczna']->marketplaceItem->id, 'item_name' => 'Dwuręczny topór']],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $commonItems['Odtrutki']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Łowca' => [
                [['item_id' => $weapons['Długi łuk']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Strzały']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Strzały']->marketplaceItem->id, 'item_name' => null]],
                [
                    ['item_id' => $commonItems['Potrzask']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $commonItems['Wnyki']->marketplaceItem->id, 'item_name' => null]
                ],
            ],
            'Łowca nagród' => [
                [['item_id' => $weapons['Kusza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $weapons['Sieć']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzany Kaftan']],
                [['item_id' => $armors['Hełm-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana hełm']],
                [['item_id' => $commonItems['Kajdany']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Lina']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Mieszczanin' => [
                [['item_id' => $commonItems['Dobre ubranie']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Liczydło']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Latarnia']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Mytnik' => [
                [['item_id' => $weapons['Kusza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $armors['Kaftan-KOLCZA']->marketplaceItem->id, 'item_name' => 'Kaftan kolczy']],
                [['item_id' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzany kaftan']],
                [['item_id' => $weapons['Tarcza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Zamykana na kłódkę skrzynia']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Najemnik' => [
                [['item_id' => $weapons['Kusza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $armors['Kaftan-KOLCZA']->marketplaceItem->id, 'item_name' => 'Kaftan kolczy']],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $weapons['Tarcza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Mikstura lecznicza']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Ochotnik' => [
                [
                    ['item_id' => $weapons['Halabarda']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Łuk']->marketplaceItem->id, 'item_name' => null]
                ],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana Kurta']],
                [['item_id' => $armors['Hełm-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana hełm']],
                [['item_id' => $commonItems['Mundur']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Ochroniarz' => [
                [
                    ['item_id' => $weapons['Topór do rzucania']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Nóż do rzucania']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $weapons['Topór do rzucania']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Nóż do rzucania']->marketplaceItem->id, 'item_name' => null]
                ],
                [['item_id' => $weapons['Kastet']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $weapons['Kastet']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana Kurta']],
                [['item_id' => $weapons['Puklerz']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Oprych' => [
                [['item_id' => $weapons['Kastet']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $weapons['Kastet']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $armors['Kaftan-KOLCZA']->marketplaceItem->id, 'item_name' => 'Kaftan kolczy']],
                [['item_id' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzany kaftan']],
            ],
            'Paź' => [
                [['item_id' => $commonItems['Szykowne ubranie']->marketplaceItem->id, 'item_name' => 'Ubranie najlepszej jakości']],
                [['item_id' => $commonItems['Szykowne ubranie']->marketplaceItem->id, 'item_name' => 'Ubranie najlepszej jakości']],
                [['item_id' => $commonItems['Liberia']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Perfumy']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Mieszek']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Podżegacz' => [
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana Kurta']],
                [['item_id' => $commonItems['Dobre ubranie']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Przybory do pisania']->marketplaceItem->id, 'item_name' => 'Ulotki różnej treści']],
            ],
            'Porywacz zwłok' => [
                [['item_id' => $commonItems['Latarnia']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Olej do latarni']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Kilof']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Łopata']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Worek']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Posłaniec' => [
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana Kurta']],
                [['item_id' => $weapons['Tarcza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Koń']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Siodło']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Uprząż']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Tuba na mapy/pergaminy']->marketplaceItem->id, 'item_name' => 'Tuba na mapy']],
            ],
            'Przemytnik' => [
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana Kurta']],
                [
                    ['item_id' => $commonItems['Koń pociągowy']->marketplaceItem->id, 'item_name' => 'Koń pociągowy i wóz'],
                    ['item_id' => $commonItems['Łódź wiosłowa']->marketplaceItem->id, 'item_name' => 'Koń pociągowy i wóz']
                ],
                [['item_id' => $commonItems['Pochodnia']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Pochodnia']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Przepatrywacz' => [
                [
                    ['item_id' => $weapons['Łuk']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Kusza']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $ammunition['Strzały']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $ammunition['Strzały']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]
                ],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana Kurta']],
                [['item_id' => $weapons['Tarcza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Koń']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Siodło']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Uprząż']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Lina']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Przewoźnik' => [
                [
                    ['item_id' => $weapons['Garłacz']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Kusza']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $ammunition['Kule do broni palnej']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]
                ],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana Kurta']],
            ],
            'Rybak' => [
                [['item_id' => $weapons['Włócznia']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $weapons['Sieć']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Lina']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Kotwiczka do wspinaczki']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Rzecznik rodu' => [
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana Kurta']],
                [['item_id' => $commonItems['Dobre ubranie']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Dobre ubranie']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Przybory do pisania']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Rzemieślnik' => [
                [['item_id' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzany kaftan']],
            ],
            'Rzezimieszek' => [
                [['item_id' => $armors['Kaftan-KOLCZA']->marketplaceItem->id, 'item_name' => 'Kaftan kolczy']],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $weapons['Tarcza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Koń']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Siodło']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Uprząż']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Skryba' => [
                [['item_id' => $weapons['Nóż']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Świeczka woskowa']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Świeczka woskowa']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Zapałka']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Zapałka']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Zapałka']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Zapałka']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Zapałka']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Książka drukowana']->marketplaceItem->id, 'item_name' => 'Ilustrowana księga']],
                [['item_id' => $commonItems['Przybory do pisania']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Wosk do pieczęci']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Sługa' => [
                [['item_id' => $commonItems['Dobre ubranie']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Manierka metalowa']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Hubka i krzesiwo']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Latarnia sztormowa']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Olej do latarni']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Strażnik' => [
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $commonItems['Mundur']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Latarnia na drągu']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Olej do latarni']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Strażnik dróg' => [
                [['item_id' => $weapons['Pistolet']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Kule do broni palnej']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Proch strzelniczy']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $armors['Kaftan-KOLCZA']->marketplaceItem->id, 'item_name' => 'Kaftan kolczy']],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $weapons['Tarcza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Lekki koń bojowy']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Siodło']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Uprząż']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Lina']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Strażnik pól' => [
                [['item_id' => $weapons['Proca']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Kuc']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Siodło']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Uprząż']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Latarnia']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Olej do latarni']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Łopata']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Strażnik więzienny' => [
                [
                    ['item_id' => $weapons['Bolas']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Sieć']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Arkan']->marketplaceItem->id, 'item_name' => null],
                ],
                [['item_id' => $commonItems['Wino pospolite']->marketplaceItem->id, 'item_name' => 'Butelka podłego wina']],
                [['item_id' => $commonItems['Manierka skórzana']->marketplaceItem->id, 'item_name' => 'Butelka podłego wina']],
            ],
            'Szczurołap' => [
                [['item_id' => $weapons['Proca']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Pułapka na szczury']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Pułapka na szczury']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Pułapka na szczury']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Pułapka na szczury']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Latarnia na drągu']->marketplaceItem->id, 'item_name' => 'Drąg z martwymi szczurami (1k10zk)']],
                [['item_id' => $commonItems['Pies']->marketplaceItem->id, 'item_name' => 'Mały, ale zajadły pies']],
            ],
            'Szermierz estalijski' => [
                [
                    ['item_id' => $weapons['Szpada']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Rapier']->marketplaceItem->id, 'item_name' => null]
                ],
                [['item_id' => $commonItems['Szykowne ubranie']->marketplaceItem->id, 'item_name' => 'Ubranie najlepszej jakości']],
                [['item_id' => $commonItems['Perfumy']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Mikstura lecznicza']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Szlachcic' => [
                [['item_id' => $weapons['Szpada']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $weapons['Lewak']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Szykowne ubranie']->marketplaceItem->id, 'item_name' => 'Strój szlachecki z herbem']],
                [['item_id' => $commonItems['Koń']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Siodło']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Uprząż']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Kolczyk']->marketplaceItem->id, 'item_name' => 'Biżuteria (wartość 6x10zk)']],
            ],
            'Śmieciarz' => [
                [['item_id' => $commonItems['Wózek']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Worek']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Worek']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Worek']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Tarczownik' => [
                [['item_id' => $weapons['Kusza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $armors['Kolczuga-KOLCZA']->marketplaceItem->id, 'item_name' => 'Kolczuga']],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $armors['Nogawice-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzane nogawice']],
                [['item_id' => $weapons['Tarcza']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Lina']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Kotwiczka do wspinaczki']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Bukłak']->marketplaceItem->id, 'item_name' => 'Bukłak z wodą']],
            ],
            'Uczeń czarodzieja' => [
                [['item_id' => $weapons['Kij']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Księga wiedzy tajemnej']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Plecak']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Węglarz' => [
                [['item_id' => $weapons['Bron jednoręczna']->marketplaceItem->id, 'item_name' => 'Pałka']],
                [['item_id' => $commonItems['Pochodnia']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Pochodnia']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Pochodnia']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Hubka i krzesiwo']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Włóczykij' => [
                [['item_id' => $commonItems['Plecak']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Prowiant (porcje na 1 tydzień)']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Namiot']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Bukłak']->marketplaceItem->id, 'item_name' => 'Bukłak z wodą']],
            ],
            'Wojownik klanowy' => [
                [['item_id' => $weapons['Elfi łuk']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Strzały']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Strzały']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
            ],
            'Woźnica' => [
                [['item_id' => $weapons['Garłacz']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $ammunition['Kule do broni palnej']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $armors['Kaftan-KOLCZA']->marketplaceItem->id, 'item_name' => 'Kaftan kolczy']],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $commonItems['Instrument muzyczny']->marketplaceItem->id, 'item_name' => 'Róg woźnicy']],
            ],
            'Zabójca trolli' => [
                [['item_id' => $weapons['Broń dwuręczna']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzany kaftan']],
                [
                    ['item_id' => $commonItems['Butelka gorzałki']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $commonItems['Butelka spirytusu']->marketplaceItem->id, 'item_name' => null]
                ],
            ],
            'Zarządca' => [
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $armors['Hełm-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzany hełm']],
                [['item_id' => $commonItems['Dobre ubranie']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Koń']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Siodło']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Uprząż']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Złodziej' => [
                [['item_id' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzany kaftan']],
                [['item_id' => $commonItems['Worek']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Lina']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Wytrychy']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Żak' => [
                [['item_id' => $commonItems['Ilustrowana księga']->marketplaceItem->id, 'item_name' => 'Księga związana z wybraną dziedziną nauki']],
                [['item_id' => $commonItems['Ilustrowana księga']->marketplaceItem->id, 'item_name' => 'Księga związana z wybraną dziedziną nauki']],
                [['item_id' => $commonItems['Przybory do pisania']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Żeglarz' => [
                [['item_id' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzany kaftan']],
                [['item_id' => $commonItems['Butelka gorzałki']->marketplaceItem->id, 'item_name' => 'Butelka gorzałki kiepskiej jakości']],
            ],
            'Żołnierz' => [
                [
                    ['item_id' => $weapons['Halabarda']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Rusznica']->marketplaceItem->id, 'item_name' => null]
                ],
                [['item_id' => $armors['Skórznia-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórznia']],
                [['item_id' => $commonItems['Mundur']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $weapons['Tarcza']->marketplaceItem->id, 'item_name' => null]],
            ],
            'Żołnierz okrętowy' => [
                [
                    ['item_id' => $weapons['Łuk']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $weapons['Kusza']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $ammunition['Strzały']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]
                ],
                [
                    ['item_id' => $ammunition['Strzały']->marketplaceItem->id, 'item_name' => null],
                    ['item_id' => $ammunition['Bełty']->marketplaceItem->id, 'item_name' => null]
                ],
                [['item_id' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id, 'item_name' => 'Skórzana kurta']],
                [['item_id' => $commonItems['Lina']->marketplaceItem->id, 'item_name' => null]],
                [['item_id' => $commonItems['Kotwiczka do wspinaczki']->marketplaceItem->id, 'item_name' => null]],
            ],
        ];

        $insertData = [];
        $globalGroupCounter = 1;

        try {
            foreach ($professionsEquipment as $professionName => $slots) {
                if (!isset($professions[$professionName])) {
                    throw new ModelNotFoundException("Profesja $professionName nie istnieje w bazie danych");
                }
                $profId = $professions[$professionName];

                foreach ($slots as $groupId => $itemOptions) {
                    foreach ($itemOptions as $option) {
                        $insertData[] = [
                            'profession_id' => $profId,
                            'item_id'       => $option['item_id'],
                            'item_name'     => $option['item_name'],
                            'group_id'      => $groupId,
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ];
                    }
                }
            }
            DB::table('profession_equipment')->truncate();
            DB::table('profession_equipment')->insert($insertData);
        } catch (ModelNotFoundException $e) {
            echo $e->getMessage();
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }

    }
}
