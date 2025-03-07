<?php

namespace Database\Seeders;

use App\Models\Weapon;
use App\Models\WeaponTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeaponTraitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $weaponTraits = [
            [
                'name' => 'Ciężki',
                'description' => 'Broń jest nieporęczna i władająca nią postać szybko się męczy.
                    Zasady dotyczące broni druzgoczącej mają zastosowanie tylko w pierwszej rundzie walki'
            ],
            [
                'name' => 'Druzgoczący',
                'description' => 'Tego rodzaju oręź jest wyjątkowo ciężki uderza z wielką siłą,
                    zadając zwiększone obrażenia. Po udanym trafieniu tego rodzaju
                    bronią rzucasz na obrażenia 2k10 i wybierasz wyższy rezultat.'
            ],
            [
                'name' => 'Eskperymentalny',
                'description' => 'Bronie eksperymentalne to najnowsze osiagnięcia mysli
                    inżynierskiej. Niestety, są bardziej zawodne niż tradycyjne,
                    wypróbowane w działaniu rodzaje broni. Jeżeli wynik rzutu
                    na trafienie wynosi 96-98, bron zacina się. Jej naprawienie
                    wymaga udanego testu rzemiosła (rusznikarstwo). Jeżeli wynik
                    rzutu na trafienie wynosi 99 lub 00, broń eksploduje, ulegając
                    calkowitemu zniszczeniu zadając strzelcowi trafienie z siłą 8'
            ],
            [
                'name' => 'Odłamkowy',
                'description' => 'Tego rodzaju broń palna strzela ładunkami zlożonymi z
                    kawałków metalu, gwoździ, tłuczonego szkla i innych
                    drobnych, ostrych przedmiotów, Nie jest to oręż dla strzelca
                    wyborowego, a raczej dla kogoś, kto chce zasypać przeciwników
                    gradem odłamków. Strzelając z broni odłamkowej, postać nie
                    musi wykonywać testu Umiejętności Strzeleckich. Wystarczy
                    odmierzyć linię prostą na odleglosć nie przekraczającą długiego
                    zasięgu broni (w przypadku garlacza to 32 metry) i szeroką na
                    2 metry. Každa postać objeta polem ostrzalu musi wykonac test
                    Zręczności. Nieudany test oznacza pełne obrażenia od broni.'
            ],
            [
                'name' => 'Ogłuszający',
                'description' => 'Używająca tego rodzaju oręża postać może jednym ciosem pozbawić
                    przeciwnika przytomności. Otrzymuje modyfikator +10 do Krzepy przy probach ogluszania przeciwnika'
            ],
            [
                'name' => 'Parujący',
                'description' => 'Ta broń została zaprojektowana do blokowania ciosów. Postać
                    używająca jej w walce otrzymuje modyfikator +10 do WW przy parowaniu ataków.'
            ],
            [
                'name' => 'Powolny',
                'description' => 'Ze względu na nieporęczność i duże rozmiary, tego rodzaju
                    bronią trudno się włada. Łatwiej też unikać zadawanych nią
                    ciosów, Przeciwnik otrzymuje modyfikator +10 do parowania
                    lub unikania ciosów zadawanych bronią powolną.'
            ],
            [
                'name' => 'Precyzyjny',
                'description' => 'Tego rodzaju oręż. jest często używany do pojedynków. Wartość
                    každego trafienia krytycznego zadanego przy użyciu broni precyzyjnej zwiększa się o 1.
                    Efekt zdolności morderczy atak kumuluje się z efektem użycia broni precyzyjnej.'
            ],
            [
                'name' => 'Przebijający zbroję',
                'description' => 'Oręż przebijający zbroję jest szczególnie przydatny przeciwko
                    opancerzonym przeciwnikom. Ciosy lub strzały zadane za jego pomocą
                    ignorują 1 Punkt Zbroi. W przypadku, gdy cel nie nosi zbroi, cecha oręża
                    nie powoduje żadnych dodatkowych efektów. Efekt zdolnosci strzał
                    przebijający kumuluje się z efektem użycia broni przebijającej zbroję.'
            ],
            [
                'name' => 'Przebijający zbroję',
                'description' => 'Oręż przebijający zbroję jest szczególnie przydatny przeciwko
                    opancerzonym przeciwnikom. Ciosy lub strzały zadane za jego pomocą
                    ignorują 1 Punkt Zbroi. W przypadku, gdy cel nie nosi zbroi, cecha oręża
                    nie powoduje żadnych dodatkowych efektów. Efekt zdolnosci strzał
                    przebijający kumuluje się z efektem użycia broni przebijającej zbroję.'
            ],
            [
                'name' => 'Specjalny (bez broni)',
                'description' => 'W przytpadku ataków bez broni. Punkty zbroi przeciwnika liczą się podwójnie'
            ],
            [
                'name' => 'Specjalny (halabarda)',
                'description' => 'Halabardy można używać na dwa sposoby: jako włóczni i jako zwykłej broni dwuręcznej.
                            W przypadku używania halabardy jako włóczni otrzymuje ona cechy "szybki", a jeśli jako
                            broni dwuręcznej to "druzgoczący" i "powolny"'
            ],
            [
                'name' => 'Specjalny (łamacz mieczy)',
                'description' => 'Jeśli postać wykona udany atak łamaczem mieczy - może spróbować złamać miecz, sztylet
                            lewak, szpadę lub rapier trzymany przez przeciwnika. Wykonywany jest przeciwstawny test K obu
                            walczących. Jeśli postać używająca łamacza wygra, swoim orężem łamie klingę przeciwnika, która od
                            tej pory traktowana jest jako broń improwizowana'
            ],
            [
                'name' => 'Specjalny (kusza samopowtarzalna)',
                'description' => 'Ponowne załadowanie 10 bełtów wymaga 4 akcji podwójnych. Wartość podania
                        w cechach broni dotyczy wystrzeliwania załadowanych pocisków'
            ],
            [
                'name' => 'Specjalny (wielostrzałowy)',
                'description' => 'Ponowne załadowanie wszystkich luf wymaga 6 akcji podwójnych. Wartość podania
                        w cechach broni dotyczy wystrzeliwania załadowanych pocisków'
            ],
            [
                'name' => 'Szybki',
                'description' => 'Broń szybka umożliwia zadawanie błyskawicznych ciosów.
                            Przeciwnik otrzymuje modyfikator 10 do WW przy parowaniu lub
                            unikaniu ciosów zadawanych tego rodzaju orężem'
            ],
            [
                'name' => 'Unieruchamiający',
                'description' => 'Tego rodzaju broń nie jest przeznaczona do zabijania, lecz raczej
                            do pochwycenia żywcem i unieruchomienia wroga. Po udanym
                            trafieniu cel zostaje unieruchomiony i nie może nic robić, poza próbą
                            uwolnienia się. Aby tego dokonać, musi rozerwać więzy (wykonując
                            udany test Krzepy) lub próbować wysliznąć się (test Zręczności)
                            Dopóki nie uda mu sie oswobodzić, jest traktowany jako bezbronny.'
            ],
            [
                'name' => 'Wyważony',
                'description' => 'Tego rodzaju oręż jest doskonale wyważony i może być trzymany
                        w drugiej ręce jako pomoc w walce. Zwykle korzysta się z niego w
                        połączeniu z rapierem albo szpadą. Jeśli broń trzymana w drugiej
                        ręce jest wyważona, postać nie otrzymuje modyfikatora -20 do Walki
                        Wręcz związanego z używaniem broni w słabszej ręce'
            ],
            [
                'name' => 'Zawodny',
                'description' => 'Tego rodzaju broń często sie zacina lub psuje. Jeśli wynik rzutu na
                        trafienie wynosi 96-99, użycie broni kończy się niepowodzeniem. Jej
                        naprawienie wymaga udanego testu rzemiosła (rusznikarstwo). Jeśli
                        wynik rzutu na atak wynosi 00, broń eksploduje, zadając strzelcowi
                        normalne obrażenia i ulegając całkowitemu zniszczeniu.'
            ],
        ];
        WeaponTrait::insert($weaponTraits);

        $weapons = Weapon::all();
        $pivotData = [];
        foreach ($weapons as $weapon) {
            switch ($weapon->name) {
                case 'Bez broni':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Specjalny (bez broni)', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Broń dwuręczna':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Powolny', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Druzgoczący', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Halabarda':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Specjalny (halabarda)', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Kij':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Ogłuszający', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Parujący', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Kopia':
                case 'Lanca':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Ciężki', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Druzgoczący', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Szybki', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Korbacz':
                case 'Morgensztern':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Druzgoczący', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Lewak':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Parujący', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Wyważony', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Łamacz mieczy':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Specjalny (łamacz mieczy)', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Wyważony', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Puklerz':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Ogłuszający', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Parujący', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Wyważony', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Włócznia':
                case 'Rapier':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Szybki', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Rękawica/kastet':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Ogłuszający', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Szpada':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Precyzyjny', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Szybki', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Tarcza':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Parujący', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Specjalny (tarcza)', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Arkan':
                case 'Sieć':
                case 'Bolas':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Unieruchamiajacy', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Bicz':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Szybki', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Unieruchamiajacy', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Długi łuk':
                case 'Elfi łuk':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Przebijający zbroję', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Garłacz':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Odłamkowy', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Kusza samopowtarzalna':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Specjalny (kusza samopowtarzalna)', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Muszkiet Hochlandzki':
                case 'Pistolet':
                case 'Rusznica':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Druzgoczący', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Zawodny', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
                case 'Rusznica wielostrzałowa':
                case 'Pistolet wielostrzałowy':
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Eksperymentalny', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Specjalny (wielostrzałowy)', array_column($weaponTraits, 'name')) + 1
                    ];
                    $pivotData[] = [
                        'weapon_id' => $weapon->id,
                        'trait_id' => array_search('Powolny', array_column($weaponTraits, 'name')) + 1
                    ];
                    break;
            }
        }
        DB::table('weapon_traits_weapons')->insert($pivotData);
    }
}
