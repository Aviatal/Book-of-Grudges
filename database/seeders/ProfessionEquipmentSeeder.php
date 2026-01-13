<?php

namespace Database\Seeders;

use App\Models\Ammunition;
use App\Models\Armor;
use App\Models\CommonItems;
use App\Models\Profession;
use App\Models\Weapon;
use DB;
use Illuminate\Database\Seeder;

class ProfessionEquipmentSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $professions = Profession::all()->pluck('id', 'name')->toArray();
        $commonItems = CommonItems::with('marketplaceItem')
            ->get()
            ->keyBy('name');
        $weapons = Weapon::with('marketplaceItem')
            ->get()
            ->keyBy('name');
        $armors = Armor::select([DB::raw("CONCAT(name, '-', category) as name"), 'id'])
            ->with('marketplaceItem')
            ->get()
            ->keyBy('name');
        $ammunition = Ammunition::with('marketplaceItem')
            ->get()
            ->keyBy('name');
        $professionsEquipment = [
            'Akolita' => [
                ['item_name' => '', 'option_1' => $commonItems['Szaty kapłańskie']->marketplaceItem->id],
                ['item_name' => 'Symbol Boga', 'option_1' => $commonItems['Symbol religijny']->marketplaceItem->id],
            ],
            'Banita' => [
                ['item_name' => '', 'option_1' => $weapons['Łuk']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Strzały']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Strzały']->marketplaceItem->id],
                ['item_name' => 'Skórzany kaftan', 'option_1' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Tarcza']->marketplaceItem->id],
            ],
            'Berserker z Norski' => [
                ['item_name' => '', 'option_1' => $weapons['Broń dwuręczna']->marketplaceItem->id, 'option_2' => $weapons['Tarcza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Butelka gorzałki']->marketplaceItem->id],
            ],
            'Chłop' => [
                ['item_name' => '', 'option_1' => $weapons['Proca']->marketplaceItem->id, 'option_2' => $weapons['Kij']->marketplaceItem->id],
                ['item_name' => 'Skórzany bukłak', 'option_1' => $commonItems['Bukłak']->marketplaceItem->id],
            ],
            'Ciura obozowa' => [
                ['item_name' => '', 'option_1' => $commonItems['Talizman szczęścia']->marketplaceItem->id, 'option_2' => $commonItems['Narzędzia (rzemieślnika)']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Sakiewka']->marketplaceItem->id],
            ],
            'Cyrkowiec' => [
                ['item_name' => '', 'option_1' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id],
                [
                    'item_name' => '',
                    'option_1' => $weapons['Nóż do rzucania']->marketplaceItem->id,
                    'option_2' => $weapons['Topór do rzucania']->marketplaceItem->id,
                    'option_3' => $commonItems['Instrument muzyczny']->marketplaceItem->id,
                ],
                ['item_name' => '', 'option_1' => $commonItems['Narzędzia (kuglarza)']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Kostium (cyrkowca)']->marketplaceItem->id, 'option_2' => $commonItems['Dobre ubranie']->marketplaceItem->id],
            ],
            'Cyrulik' => [
                ['item_name' => '', 'option_1' => $commonItems['Narzędzia (cyrulika)']->marketplaceItem->id],
            ],
            'Fanatyk' => [
                ['item_name' => '', 'option_1' => $weapons['Korbacz']->marketplaceItem->id, 'option_2' => $weapons['Morgensztern']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Butelka gorzałki']->marketplaceItem->id, 'option_2' => $commonItems['Butelka spirytusu']->marketplaceItem->id],
            ],
            'Flisak' => [
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Łódź wiosłowa']->marketplaceItem->id],
            ],
            'Giermek' => [
                ['item_name' => '', 'option_1' => $weapons['Lanca']->marketplaceItem->id],
                ['item_name' => 'Kaftan kolczy', 'option_1' => $armors['Kaftan-KOLCZA']->marketplaceItem->id],
                ['item_name' => 'Czepiec kolczy', 'option_1' => $armors['Czepiec-KOLCZA']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Tarcza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Koń']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Siodło']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Uprząż']->marketplaceItem->id],
            ],
            'Gladiator' => [
                ['item_name' => '', 'option_1' => $weapons['Broń dwuręczna']->marketplaceItem->id, 'option_2' => $weapons['Korbacz']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Kastet']->marketplaceItem->id],
                ['item_name' => 'Kaftan kolczy', 'option_1' => $armors['Kaftan-KOLCZA']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Tarcza']->marketplaceItem->id, 'option_2' => $weapons['Puklerz']->marketplaceItem->id],
            ],
            'Goniec' => [
                ['item_name' => '', 'option_1' => $weapons['Kusza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Mikstura lecznicza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Talizman szczęścia']->marketplaceItem->id],
            ],
            'Górnik' => [
                ['item_name' => 'Dwuręczny kilof', 'option_1' => $weapons['Broń dwuręczna']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Kilof']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Łopata']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Latarnia sztormowa']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Olej do latarni']->marketplaceItem->id],
            ],
            'Guślarz' => [
                ['item_name' => '', 'option_1' => $commonItems['Mikstura lecznicza']->marketplaceItem->id],
                ['item_name' => 'Płaszcz z kapturem', 'option_1' => $commonItems['Płaszcz']->marketplaceItem->id],
            ],
            'Hiena cmentarna' => [
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Łom']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Latarnia']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Olej do latarni']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Lina']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Worek']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Worek']->marketplaceItem->id],
            ],
            'Kanciarz' => [[
                    'item_name' => '',
                    'option_1' => $commonItems['Szykowne ubranie']->marketplaceItem->id,
                    'option_2' => $commonItems['Kości do gry']->marketplaceItem->id,
                    'option_3' => $commonItems['Talia kart']->marketplaceItem->id
                ],
            ],
            'Kozak kislevski' => [
                ['item_name' => '', 'option_1' => $weapons['Łuk']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Strzały']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Strzały']->marketplaceItem->id],
                ['item_name' => 'Dwuręczny topór', 'option_1' => $weapons['Broń dwuręczna']->marketplaceItem->id],
                ['item_name' => 'Kolczuga', 'option_1' => $armors['Kolczuga-KOLCZA']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Odtrutki']->marketplaceItem->id],

            ],
            'Leśnik' => [
                ['item_name' => 'Dwuręczny topór', 'option_1' => $weapons['Broń dwuręczna']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Odtrutki']->marketplaceItem->id],
            ],
            'Łowca' => [
                ['item_name' => '', 'option_1' => $weapons['Długi łuk']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Strzały']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Strzały']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Potrzask']->marketplaceItem->id, 'option_2' => $commonItems['Wnyki']->marketplaceItem->id],
            ],
            'Łowca nagród' => [
                ['item_name' => '', 'option_1' => $weapons['Kusza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Sieć']->marketplaceItem->id],
                ['item_name' => 'Skórzany Kaftan', 'option_1' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => 'Skórzana hełm', 'option_1' => $armors['Hełm-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Kajdany']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Lina']->marketplaceItem->id],
            ],
            'Mieszczanin' => [
                ['item_name' => '', 'option_1' => $commonItems['Dobre ubranie']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Liczydło']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Latarnia']->marketplaceItem->id],
            ],
            'Mytnik' => [
                ['item_name' => '', 'option_1' => $weapons['Kusza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => 'Kaftan kolczy', 'option_1' => $armors['Kaftan-KOLCZA']->marketplaceItem->id],
                ['item_name' => 'Skórzany kaftan', 'option_1' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Tarcza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Zamykana na kłódkę skrzynia']->marketplaceItem->id],
            ],
            'Najemnik' => [
                ['item_name' => '', 'option_1' => $weapons['Kusza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => 'Kaftan kolczy', 'option_1' => $armors['Kaftan-KOLCZA']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Tarcza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Mikstura lecznicza']->marketplaceItem->id],
            ],
            'Ochotnik' => [
                ['item_name' => '', 'option_1' => $weapons['Halabarda']->marketplaceItem->id, 'option_2' => $weapons['Łuk']->marketplaceItem->id],
                ['item_name' => 'Skórzana Kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => 'Skórzana hełm', 'option_1' => $armors['Hełm-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Mundur']->marketplaceItem->id],
            ],
            'Ochroniarz' => [
                ['item_name' => '', 'option_1' => $weapons['Topór do rzucania']->marketplaceItem->id, 'option_2' => $weapons['Nóż do rzucania']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Topór do rzucania']->marketplaceItem->id, 'option_2' => $weapons['Nóż do rzucania']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Kastet']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Kastet']->marketplaceItem->id],
                ['item_name' => 'Skórzana Kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Puklerz']->marketplaceItem->id],
            ],
            'Oprych' => [
                ['item_name' => '', 'option_1' => $weapons['Kastet']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Kastet']->marketplaceItem->id],
                ['item_name' => 'Kaftan kolczy', 'option_1' => $armors['Kaftan-KOLCZA']->marketplaceItem->id],
                ['item_name' => 'Skórzany kaftan', 'option_1' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id],
            ],
            'Paź' => [
                ['item_name' => 'Ubranie najlepszej jakości', 'option_1' => $commonItems['Szykowne ubranie']->marketplaceItem->id],
                ['item_name' => 'Ubranie najlepszej jakości', 'option_1' => $commonItems['Szykowne ubranie']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Liberia']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Perfumy']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Mieszek']->marketplaceItem->id],
            ],
            'Podżegacz' => [
                ['item_name' => 'Skórzana Kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Dobre ubranie']->marketplaceItem->id],
                ['item_name' => 'Ulotki różnej treści', 'option_1' => $commonItems['Przybory do pisania']->marketplaceItem->id],
            ],
            'Porywacz zwłok' => [
                ['item_name' => '', 'option_1' => $commonItems['Latarnia']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Olej do latarni']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Kilof']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Łopata']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Worek']->marketplaceItem->id],
            ],
            'Posłaniec' => [
                ['item_name' => 'Skórzana Kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Tarcza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Koń']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Siodło']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Uprząż']->marketplaceItem->id],
                ['item_name' => 'Tuba na mapy', 'option_1' => $commonItems['Tuba na mapy/pergaminy']->marketplaceItem->id],
            ],
            'Przemytnik' => [
                ['item_name' => 'Skórzana Kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => 'Koń pociągowy i wóz', 'option_1' => $commonItems['Koń pociągowy']->marketplaceItem->id, 'option_2' => $commonItems['Łódź wiosłowa']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Pochodnia']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Pochodnia']->marketplaceItem->id],
            ],
            'Przepatrywacz' => [
                ['item_name' => '', 'option_1' => $weapons['Łuk']->marketplaceItem->id, 'option_2' => $weapons['Kusza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Strzały']->marketplaceItem->id, 'option_2' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Strzały']->marketplaceItem->id, 'option_2' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => 'Skórzana Kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Tarcza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Koń']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Siodło']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Uprząż']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Lina']->marketplaceItem->id],
            ],
            'Przewoźnik' => [
                ['item_name' => '', 'option_1' => $weapons['Garłacz']->marketplaceItem->id, 'option_2' => $weapons['Kusza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Kule do broni palnej']->marketplaceItem->id, 'option_2' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => 'Skórzana Kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
            ],
            'Rybak' => [
                ['item_name' => '', 'option_1' => $weapons['Włócznia']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Sieć']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Lina']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Kotwiczka do wspinaczki']->marketplaceItem->id],
            ],
            'Rzecznik rodu' => [
                ['item_name' => 'Skórzana Kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Dobre ubranie']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Dobre ubranie']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Przybory do pisania']->marketplaceItem->id],
            ],
            'Rzemieślnik' => [
                ['item_name' => 'Skórzany kaftan', 'option_1' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id],
            ],
            'Rzezimieszek' => [
                ['item_name' => 'Kaftan kolczy', 'option_1' => $armors['Kaftan-KOLCZA']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Tarcza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Koń']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Siodło']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Uprząż']->marketplaceItem->id],
            ],
            'Skryba' => [
                ['item_name' => '', 'option_1' => $weapons['Nóż']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Świeczka woskowa']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Świeczka woskowa']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Zapałka']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Zapałka']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Zapałka']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Zapałka']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Zapałka']->marketplaceItem->id],
                ['item_name' => 'Ilustrowana księga', 'option_1' => $commonItems['Książka drukowana']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Przybory do pisania']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Wosk do pieczęci']->marketplaceItem->id],
            ],
            'Sługa' => [
                ['item_name' => '', 'option_1' => $commonItems['Dobre ubranie']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Manierka metalowa']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Hubka i krzesiwo']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Latarnia sztormowa']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Olej do latarni']->marketplaceItem->id],
            ],
            'Strażnik' => [
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Mundur']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Latarnia na drągu']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Olej do latarni']->marketplaceItem->id],
            ],
            'Strażnik dróg' => [
                ['item_name' => '', 'option_1' => $weapons['Pistolet']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Kule do broni palnej']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Proch strzelniczy']->marketplaceItem->id],
                ['item_name' => 'Kaftan kolczy', 'option_1' => $armors['Kaftan-KOLCZA']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Tarcza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Lekki koń bojowy']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Siodło']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Uprząż']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Lina']->marketplaceItem->id],
            ],
            'Strażnik pól' => [
                ['item_name' => '', 'option_1' => $weapons['Proca']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Kuc']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Siodło']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Uprząż']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Latarnia']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Olej do latarni']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Łopata']->marketplaceItem->id],
            ],
            'Strażnik więzienny' => [
                [
                    'item_name' => '',
                    'option_1' => $weapons['Bolas']->marketplaceItem->id,
                    'option_2' => $weapons['Sieć']->marketplaceItem->id,
                    'option_3' => $weapons['Arkan']->marketplaceItem->id
                ],
                ['item_name' => 'Butelka podłego wina', 'option_1' => $commonItems['Wino pospolite']->marketplaceItem->id],
                ['item_name' => 'Butelka podłego wina', 'option_1' => $commonItems['Manierka skórzana']->marketplaceItem->id],
            ],
            'Szczurołap' => [
                ['item_name' => '', 'option_1' => $weapons['Proca']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Pułapka na szczury']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Pułapka na szczury']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Pułapka na szczury']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Pułapka na szczury']->marketplaceItem->id],
                ['item_name' => 'Drąg z martwymi szczurami (1k10zk)', 'option_1' => $commonItems['Latarnia na drągu']->marketplaceItem->id],
                ['item_name' => 'Mały, ale zajadły pies', 'option_1' => $commonItems['Pies']->marketplaceItem->id],
            ],
            'Szermierz estalijski' => [
                ['item_name' => '', 'option_1' => $weapons['Szpada']->marketplaceItem->id, 'option_2' => $weapons['Rapier']->marketplaceItem->id],
                ['item_name' => 'Ubranie najlepszej jakości', 'option_1' => $commonItems['Szykowne ubranie']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Perfumy']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Mikstura lecznicza']->marketplaceItem->id],
            ],
            'Szlachcic' => [
                ['item_name' => '', 'option_1' => $weapons['Szpada']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Lewak']->marketplaceItem->id],
                ['item_name' => 'Strój szlachecki z herbem', 'option_1' => $commonItems['Szykowne ubranie']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Koń']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Siodło']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Uprząż']->marketplaceItem->id],
                ['item_name' => 'Biżuteria (wartość 6x10zk)', 'option_1' => $commonItems['Kolczyk']->marketplaceItem->id],
            ],
            'Śmieciarz' => [
                ['item_name' => '', 'option_1' => $commonItems['Wózek']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Worek']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Worek']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Worek']->marketplaceItem->id],
            ],
            'Tarczownik' => [
                ['item_name' => '', 'option_1' => $weapons['Kusza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => 'Kolczuga', 'option_1' => $armors['Kolczuga-KOLCZA']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => 'Skórzane nogawice', 'option_1' => $armors['Nogawice-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Tarcza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Lina']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Kotwiczka do wspinaczki']->marketplaceItem->id],
                ['item_name' => 'Bukłak z wodą', 'option_1' => $commonItems['Bukłak']->marketplaceItem->id],
            ],
            'Uczeń czarodzieja' => [
                ['item_name' => '', 'option_1' => $weapons['Kij']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Księga wiedzy tajemnej']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Plecak']->marketplaceItem->id],
            ],
            'Węglarz' => [
                ['item_name' => 'Pałka', 'option_1' => $weapons['Bron jednoręczna']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Pochodnia']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Pochodnia']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Pochodnia']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Hubka i krzesiwo']->marketplaceItem->id],
            ],
            'Włóczykij' => [
                ['item_name' => '', 'option_1' => $commonItems['Plecak']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Prowiant (porcje na 1 tydzień)']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Namiot']->marketplaceItem->id],
                ['item_name' => 'Bukłak z wodą', 'option_1' => $commonItems['Bukłak']->marketplaceItem->id],
            ],
            'Wojownik klanowy' => [
                ['item_name' => '', 'option_1' => $weapons['Elfi łuk']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Strzały']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Strzały']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
            ],
            'Woźnica' => [
                ['item_name' => '', 'option_1' => $weapons['Garłacz']->marketplaceItem->id,],
                ['item_name' => '', 'option_1' => $ammunition['Kule do broni palnej']->marketplaceItem->id],
                ['item_name' => 'Kaftan kolczy', 'option_1' => $armors['Kaftan-KOLCZA']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => 'Róg woźnicy', 'option_1' => $commonItems['Instrument muzyczny']->marketplaceItem->id],
            ],
            'Zabójca trolli' => [
                ['item_name' => '', 'option_1' => $weapons['Broń dwuręczna']->marketplaceItem->id],
                ['item_name' => 'Skórzany kaftan', 'option_1' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Butelka gorzałki']->marketplaceItem->id, 'option_2' => $commonItems['Butelka spirytusu']->marketplaceItem->id],
            ],
            'Zarządca' => [
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => 'Skórzany hełm', 'option_1' => $armors['Hełm-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Dobre ubranie']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Koń']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Siodło']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Uprząż']->marketplaceItem->id],
            ],
            'Złodziej' => [
                ['item_name' => 'Skórzany kaftan', 'option_1' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Worek']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Lina']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Wytrychy']->marketplaceItem->id],

            ],
            'Żak' => [
                ['item_name' => 'Księga związana z wybraną dziedziną nauki', 'option_1' => $commonItems['Ilustrowana księga']->marketplaceItem->id],
                ['item_name' => 'Księga związana z wybraną dziedziną nauki', 'option_1' => $commonItems['Ilustrowana księga']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Przybory do pisania']->marketplaceItem->id],
            ],
            'Żeglarz' => [
                ['item_name' => 'Skórzany kaftan', 'option_1' => $armors['Kaftan-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => 'Butelka gorzałki kiepskiej jakości', 'option_1' => $commonItems['Butelka gorzałki']->marketplaceItem->id],
            ],
            'Żołnierz' => [
                ['item_name' => '', 'option_1' => $weapons['Halabarda']->marketplaceItem->id, 'option_2' => $weapons['Rusznica']->marketplaceItem->id],
                ['item_name' => 'Skórznia', 'option_1' => $armors['Skórznia-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Mundur']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $weapons['Tarcza']->marketplaceItem->id],
            ],
            'Żołnierz okrętowy' => [
                ['item_name' => '', 'option_1' => $weapons['Łuk']->marketplaceItem->id, 'option_2' => $weapons['Kusza']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Strzały']->marketplaceItem->id, 'option_2' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $ammunition['Strzały']->marketplaceItem->id, 'option_2' => $ammunition['Bełty']->marketplaceItem->id],
                ['item_name' => 'Skórzana kurta', 'option_1' => $armors['Kurta-SKÓRZANA']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Lina']->marketplaceItem->id],
                ['item_name' => '', 'option_1' => $commonItems['Kotwiczka do wspinaczki']->marketplaceItem->id],
            ],
        ];
        $insertData = [];
        foreach ($professionsEquipment as $professionName => $equipment) {
            foreach ($equipment as $equipmentItem) {
                $item = $equipmentItem;
                $item['profession_id'] = $professions[$professionName];
                if (!isset($item['option_2'])) {
                    $item['option_2'] = null;
                }
                if (!isset($item['option_3'])) {
                    $item['option_3'] = null;
                }
                $insertData[] = $item;
            }
        }
        DB::table('profession_equipment')->insert($insertData);
    }
}
