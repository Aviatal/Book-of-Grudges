<?php

namespace Database\Seeders;

use App\Models\CommonItems;
use Illuminate\Database\Seeder;

class CommonItemsSeeder extends Seeder
{
    public function run(): void
    {
        CommonItems::truncate();
        CommonItems::insert([
            //ubrania
            [
                'name' => 'Łachmany',
                'price' => 1,
                'loading' => 5
            ],
            [
                'name' => 'Kiepskie odzienie',
                'price' => 120,
                'loading' => 10
            ],
            [
                'name' => 'Zwykłe ubranie',
                'price' => 240,
                'loading' => 15
            ],
            [
                'name' => 'Dobre ubranie',
                'price' => 720,
                'loading' => 15
            ],
            [
                'name' => 'Szykowne ubranie',
                'price' => 2400,
                'loading' => 20
            ],
            [
                'name' => 'Szaty',
                'price' => 3600,
                'loading' => 25
            ],
            [
                'name' => 'Kostium',
                'price' => 1200,
                'loading' => 10
            ],
            [
                'name' => 'Uniform',
                'price' => 3600,
                'loading' => 15
            ],
            [
                'name' => 'Strój szlachecki',
                'price' => 12000,
                'loading' => 30
            ],
            [
                'name' => 'Strój arystokraty',
                'price' => 24000,
                'loading' => 50
            ],
            [
                'name' => 'Peleryna',
                'price' => 1200,
                'loading' => 10
            ],
            [
                'name' => 'Płaszcz',
                'price' => 2400,
                'loading' => 15
            ],
            [
                'name' => 'Kapelusz zwykły',
                'price' => 120,
                'loading' => 1
            ],
            [
                'name' => 'Kapelusz z szerokim rondem',
                'price' => 240,
                'loading' => 5
            ],
            [
                'name' => 'Kaptur lub maska',
                'price' => 120,
                'loading' => 2
            ],

            //Żywność
            [
                'name' => 'Obrok (porcja na 1 dzień)',
                'price' => 5,
                'loading' => 50
            ],
            [
                'name' => 'Bochenek chleba',
                'price' => 2,
                'loading' => 2
            ],
            [
                'name' => 'Połeć mięsa',
                'price' => 12,
                'loading' => 10
            ],
            [
                'name' => 'Kiepskie jedzenie (porcja na 1 dzień)',
                'price' => 5,
                'loading' => 10
            ],
            [
                'name' => 'Dobre jedzenie (porcja na 1 dzień)',
                'price' => 10,
                'loading' => 10
            ],
            [
                'name' => 'Doskonałe jedzenie (porcja na 1 dzień)',
                'price' => 18,
                'loading' => 10
            ],
            [
                'name' => 'Prowiant (porcje na 1 tydzień)',
                'price' => 72,
                'loading' => 50
            ],
            [
                'name' => 'Słodycze',
                'price' => 2,
                'loading' => 2
            ],
            [
                'name' => 'Smakołyki',
                'price' => 36,
                'loading' => 0
            ],
            [
                'name' => 'Ciemne piwo',
                'price' => 2,
                'loading' => 2
            ],
            [
                'name' => 'Jasne piwo',
                'price' => 1,
                'loading' => 2
            ],
            [
                'name' => 'Antałek piwa jasnego lub ciemnego',
                'price' => 36,
                'loading' => 30
            ],
            [
                'name' => 'Butelka gorzałki',
                'price' => 12,
                'loading' => 5
            ],
            [
                'name' => 'Wino pospolite',
                'price' => 12,
                'loading' => 5
            ],
            [
                'name' => 'Wino szlachetne',
                'price' => 120,
                'loading' => 5
            ],

            [
                'name' => 'Bukłak',
                'price' => 96,
                'loading' => 1
            ],
            [
                'name' => 'Flaszka',
                'price' => 48,
                'loading' => 10
            ],
            [
                'name' => 'Juki',
                'price' => 480,
                'loading' => 5
            ],
            [
                'name' => 'Kuferek',
                'price' => 1200,
                'loading' => 40
            ],
            [
                'name' => 'Manierka metalowa',
                'price' => 480,
                'loading' => 15
            ],
            [
                'name' => 'Manierka skórzana',
                'price' => 180,
                'loading' => 5
            ],
            [
                'name' => 'Mieszek',
                'price' => 24,
                'loading' => 1
            ],
            [
                'name' => 'Plecak',
                'price' => 360,
                'loading' => 20
            ],
            [
                'name' => 'Sakiewka',
                'price' => 60,
                'loading' => 1
            ],
            [
                'name' => 'Tobołek',
                'price' => 480,
                'loading' => 5
            ],
            [
                'name' => 'Tuba na mapy/pergaminy',
                'price' => 240,
                'loading' => 2
            ],
            [
                'name' => 'Worek',
                'price' => 60,
                'loading' => 7
            ],

            // Tabela 5-10: Oświetlenie
            [
                'name' => 'Drewno na opał',
                'price' => 24,
                'loading' => 5
            ],
            [
                'name' => 'Kaganek',
                'price' => 60,
                'loading' => 20
            ],
            [
                'name' => 'Latarnia',
                'price' => 1200,
                'loading' => 20
            ],
            [
                'name' => 'Latarnia sztormowa',
                'price' => 2880,
                'loading' => 30
            ],
            [
                'name' => 'Olej do latarni',
                'price' => 60,
                'loading' => 5
            ],
            [
                'name' => 'Pochodnia',
                'price' => 5,
                'loading' => 5
            ],
            [
                'name' => 'Świeczka łojowa',
                'price' => 36,
                'loading' => 5
            ],
            [
                'name' => 'Świeczka woskowa',
                'price' => 72,
                'loading' => 5
            ],
            [
                'name' => 'Zapałka',
                'price' => 1,
                'loading' => 0
            ],

            // Tabela 5-11: Ekwipunek ogólny
            [
                'name' => 'Drabina',
                'price' => 120,
                'loading' => 50
            ],
            [
                'name' => 'Hubka i krzesiwo',
                'price' => 360,
                'loading' => 5
            ],
            [
                'name' => 'Imbryk',
                'price' => 360,
                'loading' => 10
            ],
            [
                'name' => 'Instrument muzyczny',
                'price' => 1200,
                'loading' => 5
            ],
            [
                'name' => 'Kłódka dobrej jakości',
                'price' => 2400,
                'loading' => 5
            ],
            [
                'name' => 'Kłódka zwykłej jakości',
                'price' => 240,
                'loading' => 5
            ],
            [
                'name' => 'Koc',
                'price' => 300,
                'loading' => 10
            ],
            [
                'name' => 'Kociołek',
                'price' => 240,
                'loading' => 20
            ],
            [
                'name' => 'Kości do gry',
                'price' => 72,
                'loading' => 0
            ],
            [
                'name' => 'Kufel drewniany',
                'price' => 120,
                'loading' => 5
            ],
            [
                'name' => 'Kufel ze szkła barwionego',
                'price' => 240,
                'loading' => 5
            ],
            [
                'name' => 'Lina',
                'price' => 240,
                'loading' => 50
            ],
            [
                'name' => 'Luneta',
                'price' => 24000,
                'loading' => 5
            ],
            [
                'name' => 'Lustro',
                'price' => 2400,
                'loading' => 5
            ],
            [
                'name' => 'Namiot',
                'price' => 180,
                'loading' => 20
            ],
            [
                'name' => 'Papier',
                'price' => 12,
                'loading' => 0
            ],
            [
                'name' => 'Perfumy',
                'price' => 240,
                'loading' => 0
            ],
            [
                'name' => 'Pergamin',
                'price' => 60,
                'loading' => 0
            ],
            [
                'name' => 'Symbol religijny',
                'price' => 240,
                'loading' => 5
            ],
            [
                'name' => 'Sztućce drewniane',
                'price' => 60,
                'loading' => 2
            ],
            [
                'name' => 'Sztućce metalowe',
                'price' => 720,
                'loading' => 4
            ],
            [
                'name' => 'Sztućce srebrne',
                'price' => 3600,
                'loading' => 3
            ],
            [
                'name' => 'Talia kart',
                'price' => 240,
                'loading' => 1
            ],

            [
                'name' => 'Drąg, cena za metr',
                'price' => 12,
                'loading' => 10
            ],
            [
                'name' => 'Drewniany klin',
                'price' => 8,
                'loading' => 2
            ],
            [
                'name' => 'Haczyk na ryby i żyłka',
                'price' => 36,
                'loading' => 2
            ],
            [
                'name' => 'Kajdany',
                'price' => 1200,
                'loading' => 20
            ],
            [
                'name' => 'Kilof',
                'price' => 300,
                'loading' => 20
            ],
            [
                'name' => 'Kołki',
                'price' => 60,
                'loading' => 5
            ],
            [
                'name' => 'Kotwiczka do wspinaczki',
                'price' => 960,
                'loading' => 20
            ],
            [
                'name' => 'Książka drukowana',
                'price' => 24000,
                'loading' => 35
            ],
            [
                'name' => 'Książka ilustrowana',
                'price' => 84000,
                'loading' => 50
            ],
            [
                'name' => 'Liczydło',
                'price' => 2400,
                'loading' => 5
            ],
            [
                'name' => 'Łańcuch, cena za metr',
                'price' => 360,
                'loading' => 5
            ],
            [
                'name' => 'Łom',
                'price' => 120,
                'loading' => 10
            ],
            [
                'name' => 'Łopata',
                'price' => 300,
                'loading' => 20
            ],
            [
                'name' => 'Młot',
                'price' => 240,
                'loading' => 40
            ],
            [
                'name' => 'Narzędzia',
                'price' => 12000,
                'loading' => 50
            ],
            [
                'name' => 'Potrzask',
                'price' => 12,
                'loading' => 2
            ],
            [
                'name' => 'Przybory do pisania',
                'price' => 2400,
                'loading' => 5
            ],
            [
                'name' => 'Sztaba metalu',
                'price' => 300,
                'loading' => 20
            ],
            [
                'name' => 'Wnyki',
                'price' => 480,
                'loading' => 20
            ],
            [
                'name' => 'Wytrychy',
                'price' => 2400,
                'loading' => 0
            ],
            [
                'name' => 'Zestaw do charakteryzacji',
                'price' => 1200,
                'loading' => 10
            ],

            [
                'name' => 'Łódź rzeczna (za milę)',
                'price' => 12,
                'loading' => 0
            ],
            [
                'name' => 'Łódź rzeczna (za dzień)',
                'price' => 60,
                'loading' => 0
            ],
            [
                'name' => 'Powóz (za milę)',
                'price' => 240,
                'loading' => 0
            ],
            [
                'name' => 'Powóz (za dzień)',
                'price' => 1680,
                'loading' => 0
            ],
            [
                'name' => 'Statek (za milę)',
                'price' => 240,
                'loading' => 0
            ],
            [
                'name' => 'Statek (za dzień)',
                'price' => 1200,
                'loading' => 0
            ],
            [
                'name' => 'Wóz dwukonny (za milę)',
                'price' => 12,
                'loading' => 0
            ],
            [
                'name' => 'Wóz dwukonny (za dzień)',
                'price' => 720,
                'loading' => 0
            ],
            [
                'name' => 'Wóz trzykonny (za milę)',
                'price' => 120,
                'loading' => 0
            ],
            [
                'name' => 'Wóz trzykonny (za dzień)',
                'price' => 960,
                'loading' => 0
            ],
            [
                'name' => 'Wózek lub wóz (za milę)',
                'price' => 1,
                'loading' => 0
            ],
            [
                'name' => 'Wózek lub wóz (za dzień)',
                'price' => 15,
                'loading' => 0
            ],

            // Tabela 5-17: Noclegi
            [
                'name' => 'Kąpiel',
                'price' => 12,
                'loading' => 0
            ],
            [
                'name' => 'Osobny pokój',
                'price' => 120,
                'loading' => 0
            ],
            [
                'name' => 'Stajnia, 1 noc',
                'price' => 10,
                'loading' => 0
            ],
            [
                'name' => 'Wspólna sala, 1 noc',
                'price' => 5,
                'loading' => 0
            ],

            // Tabela 5-18: Usługi rozmaite - Zwykłe usługi
            [
                'name' => 'Cyrkowiec (dzień)',
                'price' => 28,
                'loading' => 0
            ],
            [
                'name' => 'Cyrkowiec (tydzień)',
                'price' => 168,
                'loading' => 0
            ],
            [
                'name' => 'Medyk (dzień)',
                'price' => 60,
                'loading' => 0
            ],
            [
                'name' => 'Medyk (tydzień)',
                'price' => 360,
                'loading' => 0
            ],
            [
                'name' => 'Robotnik (dzień)',
                'price' => 10,
                'loading' => 0
            ],
            [
                'name' => 'Robotnik (tydzień)',
                'price' => 60,
                'loading' => 0
            ],
            [
                'name' => 'Rzemieślnik (dzień)',
                'price' => 34,
                'loading' => 0
            ],
            [
                'name' => 'Rzemieślnik (tydzień)',
                'price' => 204,
                'loading' => 0
            ],
            [
                'name' => 'Sługa (dzień)',
                'price' => 12,
                'loading' => 0
            ],
            [
                'name' => 'Sługa (tydzień)',
                'price' => 72,
                'loading' => 0
            ],

            // Tabela 5-18: Usługi specjalne (Stawka dzienna wg sumy PD)
            [
                'name' => 'Specjalista (100 PD)',
                'price' => 72,
                'loading' => 0
            ],
            [
                'name' => 'Specjalista (400 PD)',
                'price' => 120,
                'loading' => 0
            ],
            [
                'name' => 'Specjalista (800 PD)',
                'price' => 180,
                'loading' => 0
            ],
            [
                'name' => 'Specjalista (1200 PD)',
                'price' => 300,
                'loading' => 0
            ],
            [
                'name' => 'Specjalista (1600 PD)',
                'price' => 420,
                'loading' => 0
            ],
            [
                'name' => 'Specjalista (2000 PD)',
                'price' => 600,
                'loading' => 0
            ],

            [
                'name' => 'Wózek',
                'price' => 12000,
                'loading' => 0
            ],
            [
                'name' => 'Wóz',
                'price' => 21600,
                'loading' => 0
            ],
            [
                'name' => 'Powóz',
                'price' => 120000,
                'loading' => 0
            ],
            [
                'name' => 'Łódź rzeczna',
                'price' => 144000,
                'loading' => 0
            ],
            [
                'name' => 'Łódź wiosłowa',
                'price' => 21600,
                'loading' => 900
            ],
            [
                'name' => 'Statek',
                'price' => 2880000,
                'loading' => 0
            ],

            // Tabela 5-14: Wierzchowce
            [
                'name' => 'Rumak',
                'price' => 120000,
                'loading' => 0
            ],
            [
                'name' => 'Lekki koń bojowy',
                'price' => 72000,
                'loading' => 0
            ],
            [
                'name' => 'Koń',
                'price' => 19200,
                'loading' => 0
            ],
            [
                'name' => 'Kuc',
                'price' => 12000,
                'loading' => 0
            ],
            [
                'name' => 'Siodło',
                'price' => 1200,
                'loading' => 50
            ],
            [
                'name' => 'Uprząż',
                'price' => 240,
                'loading' => 20
            ],

            // Tabela 5-15: Inwentarz żywy
            [
                'name' => 'Gołąb pocztowy',
                'price' => 240,
                'loading' => 0
            ],
            [
                'name' => 'Jastrząb',
                'price' => 19200,
                'loading' => 0
            ],
            [
                'name' => 'Koń juczny',
                'price' => 9600,
                'loading' => 0
            ],
            [
                'name' => 'Koń pociągowy lub muł',
                'price' => 6000,
                'loading' => 0
            ],
            [
                'name' => 'Kot',
                'price' => 12,
                'loading' => 0
            ],
            [
                'name' => 'Koza',
                'price' => 480,
                'loading' => 0
            ],
            [
                'name' => 'Krowa',
                'price' => 2400,
                'loading' => 0
            ],
            [
                'name' => 'Kurczak',
                'price' => 5,
                'loading' => 0
            ],
            [
                'name' => 'Owca',
                'price' => 480,
                'loading' => 0
            ],
            [
                'name' => 'Pies (rasowy)',
                'price' => 720,
                'loading' => 0
            ],
            [
                'name' => 'Pies bojowy',
                'price' => 7200,
                'loading' => 0
            ],
            [
                'name' => 'Świnia',
                'price' => 720,
                'loading' => 0
            ],
            [
                'name' => 'Wół',
                'price' => 7200,
                'loading' => 0
            ],

            [
                'name' => 'Dar Grety',
                'price' => 7200,
                'loading' => 0
            ],
            [
                'name' => 'Mikstura lecznicza',
                'price' => 1200,
                'loading' => 0
            ],
            [
                'name' => 'Piwo Bugmana',
                'price' => 12000,
                'loading' => 5
            ],

            // Trucizny
            [
                'name' => 'Czarny Jad',
                'price' => 7200,
                'loading' => 0
            ],
            [
                'name' => 'Czarny lotos',
                'price' => 4800,
                'loading' => 0
            ],
            [
                'name' => 'Grzybki Szalonego Kapelusznika',
                'price' => 7200,
                'loading' => 0
            ],
            [
                'name' => 'Jad mantikory',
                'price' => 15600,
                'loading' => 0
            ],
            [
                'name' => 'Korzeń mandragory',
                'price' => 6000,
                'loading' => 0
            ],
            [
                'name' => 'Sercobój',
                'price' => 192000,
                'loading' => 0
            ],
            [
                'name' => 'Szkarłatny Cień',
                'price' => 8400,
                'loading' => 0
            ],
            [
                'name' => 'Ślina chimery',
                'price' => 36000,
                'loading' => 0
            ],

            // Osobliwości
            [
                'name' => 'Księga wiedzy tajemnej',
                'price' => 120000,
                'loading' => 0
            ],
            [
                'name' => 'Napar kojący',
                'price' => 5,
                'loading' => 0
            ],
            [
                'name' => 'Odtrutki',
                'price' => 720,
                'loading' => 0
            ],
            [
                'name' => 'Relikwia',
                'price' => 1200,
                'loading' => 0
            ],
            [
                'name' => 'Talizman szczęścia',
                'price' => 3600,
                'loading' => 0
            ],
            [
                'name' => 'Woda święcona',
                'price' => 2400,
                'loading' => 0
            ],

            // Protezy
            [
                'name' => 'Hak',
                'price' => 36,
                'loading' => 0
            ],
            [
                'name' => 'Dłoń weterana',
                'price' => 14400,
                'loading' => 0
            ],
            [
                'name' => 'Drewniane zęby',
                'price' => 36,
                'loading' => 0
            ],
            [
                'name' => 'Kolczyk',
                'price' => 12,
                'loading' => 0
            ],
            [
                'name' => 'Opaska na oko',
                'price' => 6,
                'loading' => 0
            ],
            [
                'name' => 'Płytka czaszkowa',
                'price' => 12,
                'loading' => 0
            ],
            [
                'name' => 'Pozłacany nos',
                'price' => 72,
                'loading' => 0
            ],
            [
                'name' => 'Szklane oko',
                'price' => 12,
                'loading' => 0
            ],
            [
                'name' => 'Sztuczna noga',
                'price' => 72,
                'loading' => 0
            ],
            [
                'name' => 'Tatuaż',
                'price' => 36,
                'loading' => 0
            ],
        ]);
    }
}
