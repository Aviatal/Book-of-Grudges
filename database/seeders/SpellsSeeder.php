<?php

namespace Database\Seeders;

use App\Models\Spell;
use App\Models\Talent;
use Illuminate\Database\Seeder;

class SpellsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $talents = Talent::select(['id', 'name'])->whereLike('name', 'Magia%')->pluck('id', 'name');

        $pettySpells = [
            // --- MAGIA PROSTA (GUSŁA) ---
            [
                'type' => 'PROSTA',
                'specialization' => 'Gusła',
                'name' => 'Ochrona przed deszczem',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'świeżo zerwany liść (+1)',
                'effect_duration' => '1 godzina, ale czarodziej może przerwać czar w dowolnym momencie',
                'description' => 'Czar chroni czarodzieja przed deszczem i innymi opadami. Nawet podczas najcięższej ulewy, czarodziej pozostaje suchy (wraz z rzeczami, które ma przy sobie).',
                'casting_number' => 3,
                'talent_id' => $talents['Magia prosta (gusła)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Gusła',
                'name' => 'Magiczny płomień',
                'casting_duration' => 'akcja',
                'ingredient' => 'kawałek krzemienia (+1)',
                'effect_duration' => 'do chwili rzucenia następnego zaklęcia, ale czarodziej może przerwać czar w dowolnym momencie, zamykając dłoń',
                'description' => 'We wnętrzu dłoni czarodzieja pojawia się błękitny płomyk. Płomień jest zbyt słaby, by zadać w walce obrażenia, ale daje tyle światła, co zwykła świeca. Może też zostać wykorzystany do zapalenia papieru, pochodni lub innego łatwopalnego materiału.',
                'casting_number' => 3,
                'talent_id' => $talents['Magia prosta (gusła)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Gusła',
                'name' => 'Podmuch',
                'casting_duration' => 'akcja',
                'ingredient' => 'ptasie pióro (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Ruch rąk czarodzieja sprawia, że okolicę owiewa lekki podmuch wiatru. Jest na tyle silny, by zdmuchnąć płomień świecy i porozrzucać papiery, ale nie jest w stanie przewrócić żadnego przedmiotu.',
                'casting_number' => 4,
                'talent_id' => $talents['Magia prosta (gusła)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Gusła',
                'name' => 'Jak kamień w wodę...',
                'casting_duration' => '3 akcje',
                'ingredient' => 'szczypta piasku (+1)',
                'effect_duration' => '1 godzina',
                'description' => 'Czarodziej nie zostawia śladów, bez względu na teren, po jakim się porusza. Wszelkie próby jego tropienia wykonywane w trakcie trwania czaru obarczone są dodatkowym modyfikatorem -30.',
                'casting_number' => 4,
                'talent_id' => $talents['Magia prosta (gusła)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Gusła',
                'name' => 'Pech',
                'casting_duration' => '3 akcje',
                'ingredient' => 'lalka przedstawiająca osobę będącą celem czaru (+1)',
                'effect_duration' => '24 godziny',
                'description' => 'Czarodziej zaklina mocą dowolny przedmiot, którego dotyka. Jeśli przedmiot trzyma inna osoba, czarodziej musi wykonać test Walki Wręcz. Zgodnie z zasadami rzucania czarów dotykowych. Postać, która nosi przeklęty przedmiot, zaczyna prześladować pech. W trakcie trwania czaru otrzymuje ona ujemny modyfikator do wszystkich testów, równy wartości Magii czarodzieja.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia prosta (gusła)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Gusła',
                'name' => 'Porażenie',
                'casting_duration' => 'akcja',
                'ingredient' => 'szpilka (+1)',
                'effect_duration' => 'liczba rund równa wartości Magii czarodzieja',
                'description' => 'Dotyk czarodzieja ogłusza wybranego przeciwnika. Ofiara może odeprzeć czar, wykonując udany test Siły Woli. Nieudany test oznacza, że w trakcie trwania czaru postać jest traktowana jako ogłuszona. Porażenie jest czarem dotykowym.',
                'casting_number' => 6,
                'talent_id' => $talents['Magia prosta (gusła)']
            ],

            // --- MAGIA PROSTA (KAPŁAŃSKA) ---
            [
                'type' => 'PROSTA',
                'specialization' => 'Kapłańska',
                'name' => 'Błogosławieństwo odwagi',
                'casting_duration' => 'akcja',
                'ingredient' => 'kłębek sierści psa (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Inspirujące słowa kapłana wzbudzają nadzieję w sercu towarzysza. Dowolna postać w odległości 24 metrów, która znajduje się pod wpływem Strachu lub Grozy, natychmiast opanowuje się i może działać normalnie.',
                'casting_number' => 3,
                'talent_id' => $talents['Magia prosta (kapłańska)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Kapłańska',
                'name' => 'Błogosławieństwo chyżości',
                'casting_duration' => 'akcja',
                'ingredient' => 'łuska węża (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Kapłan modli się o przychylność swego bóstwa. Dowolna dotknięta przez niego postać otrzymuje modyfikator +5 do Zręczności oraz +1 do Szybkości. Postać może znajdować się pod wpływem najwyżej jednego błogosławieństwa chyżości w danej chwili. Jest to czar dotykowy. Kapłan może go rzucić również na siebie.',
                'casting_number' => 4,
                'talent_id' => $talents['Magia prosta (kapłańska)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Kapłańska',
                'name' => 'Błogosławieństwo hartu ducha',
                'casting_duration' => 'akcja',
                'ingredient' => 'kawałek skorupy żółwia (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Kapłan prosi boskiego patrona o łaskę i natchnienie. Dowolna dotknięta przez niego postać otrzymuje modyfikator +5 do Odporności i Siły Woli. Postać może znajdować się pod wpływem najwyżej jednego błogosławieństwa hartu ducha w danej chwili. Jest to czar dotykowy. Kapłan może go rzucić również na siebie.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia prosta (kapłańska)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Kapłańska',
                'name' => 'Błogosławieństwo uzdrawiania',
                'casting_duration' => 'akcja',
                'ingredient' => 'wianek ostrokrzewu (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Bóstwo zsyła na kapłana moc uzdrawiania ran. Jego dotyk przywraca rannej postaci 1 punkt Żywotności. W ten sposób można uzdrowić postać tylko raz w czasie potyczki, podczas której odniosła rany, lub bezpośrednio po jej zakończeniu. Jest to czar dotykowy. Kapłan może go rzucić również na siebie.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia prosta (kapłańska)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Kapłańska',
                'name' => 'Błogosławieństwo siły',
                'casting_duration' => 'akcja',
                'ingredient' => 'żelazny ćwiek (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Kapłan prosi boga o odrobinę boskiej siły. Dowolna dotknięta przez niego postać otrzymuje modyfikator +5 do Walki Wręcz i Krzepy. Postać może znajdować się pod wpływem najwyżej jednego błogosławieństwa siły w danej chwili. Jest to czar dotykowy. Kapłan może go rzucić również na siebie.',
                'casting_number' => 6,
                'talent_id' => $talents['Magia prosta (kapłańska)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Kapłańska',
                'name' => 'Błogosławieństwo ochrony',
                'casting_duration' => 'akcja',
                'ingredient' => 'niewielki amulet z symbolem boga (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Bóstwo czuwa nad swoim żarliwym wyznawcą. Otrzymuje on modyfikator +10 do Odporności oraz do Siły Woli podczas testów przeciwko magii. Kapłan może znajdować się pod wpływem najwyżej jednego błogosławieństwa ochrony w danej chwili.',
                'casting_number' => 6,
                'talent_id' => $talents['Magia prosta (kapłańska)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Tajemna',
                'name' => 'Poblask',
                'casting_duration' => 'akcja',
                'ingredient' => 'kropła oliwy do lampy (+1)',
                'effect_duration' => '1 godzina, ale czarodziej może przerwać czar w dowolnym momencie',
                'description' => 'Przedmiot trzymany przez czarodzieja zaczyna świecić niczym zwykła latarnia.',
                'casting_number' => 3,
                'talent_id' => $talents['Magia prosta (tajemna)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Tajemna',
                'name' => 'Niezdarność',
                'casting_duration' => 'akcja',
                'ingredient' => 'odrobina masła (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Dowolna postać znajdująca się w odległości do 24 metrów, upuszcza trzymane w dłoniach przedmioty. Postać może odeprzeć czar, wykonując udany test Siły Woli.',
                'casting_number' => 4,
                'talent_id' => $talents['Magia prosta (tajemna)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Tajemna',
                'name' => 'Odgłosy',
                'casting_duration' => 'akcja',
                'ingredient' => 'maleńki dzwoneczek (+1)',
                'effect_duration' => '1 runda',
                'description' => 'Czarodziej wywołuje złudzenie słuchowe o dowolnym natężeniu dźwięku, od cichego szumu aż do ogłuszającego huku. Czarodziej decyduje o rodzaju odgłosu. Może wywołać złudzenie przypominające dowolny dźwięk lub hałas, za wyjątkiem mowy.',
                'casting_number' => 4,
                'talent_id' => $talents['Magia prosta (tajemna)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Tajemna',
                'name' => 'Błędne ogniki',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'świetlik (+1)',
                'effect_duration' => '1 godzina, potem ogniki z wolna gasną i nikną',
                'description' => 'W odległości 100 metrów od czarodzieja pojawiają się światła dające złudzenie odległych, płonących latarni lub pochodni. Czarodziej może je posłać w dowolnym kierunku. Ogniki samodzielnie poruszają się po linii prostej lub podążają istniejącymi korytarzami bądź wytyczonymi ścieżkami. Czarodziej może sterować ich ruchem, jeśli pozostają w zasięgu jego wzroku. W tym czasie nie może wykonywać żadnych dodatkowych akcji, całkowicie koncentrując się na kierowaniu błędnymi ognikami. Ogniki poruszają się z różną prędkością, zwykle od 8 do 16 metrów na rundę.',
                'casting_number' => 6,
                'talent_id' => $talents['Magia prosta (tajemna)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Tajemna',
                'name' => 'Magiczne żądło',
                'casting_duration' => 'akcja',
                'ingredient' => 'niewielka strzałka (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czarodziej miota pociskiem magicznej energii w kierunku dowolnego przeciwnika znajdującego się w odległości do 16 metrów. Mimo niewielkich rozmiarów, magiczny pocisk uderza z całkiem dużą mocą (Siła 3).',
                'casting_number' => 6,
                'talent_id' => $talents['Magia prosta (tajemna)']
            ],
            [
                'type' => 'PROSTA',
                'specialization' => 'Tajemna',
                'name' => 'Uśpienie',
                'casting_duration' => 'akcja',
                'ingredient' => 'kłębek puchu (+1)',
                'effect_duration' => '1k10 rund',
                'description' => 'Czarodziej dotykiem usypia wybraną postać. Ofiara może odeprzeć czar, wykonując udany test Siły Woli. Nieudany test oznacza, że postać natychmiast zasypia i jest traktowana jako bezbronna. Uśpienie jest czarem dotykowym.',
                'casting_number' => 6,
                'talent_id' => $talents['Magia prosta (tajemna)']
            ],
        ];
        $commonSpells = [
            [
                'type' => 'POWSZECHNA',
                'specialization' => null,
                'name' => 'Dotyk na odległość',
                'casting_duration' => 'akcja',
                'ingredient' => 'niewielki wachlarz (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Adept używa magicznej mocy, by poruszać i manipulować niewielkimi przedmiotami. Może siłą woli przesunąć każdy niezabezpieczony, lekki przedmiot (Obciążenie 10 lub niższe) na odległość nie przekraczającą 12 metrów. Może również otworzyć lub zamknąć drzwi (jeśli nie były zamknięte na zamek lub skobel) lub przewracać przedmioty o Obciążeniu 50 lub mniejszym, znajdujące się w odległości do 24 metrów.',
                'casting_number' => 4,
                'talent_id' => $talents['Magia powszechna (Dotyk na odległość)']
            ],
            [
                'type' => 'POWSZECHNA',
                'specialization' => null,
                'name' => 'Pancerz Eteru',
                'casting_duration' => 'akcja',
                'ingredient' => 'ogniwo kolczugi (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Adept splata wokół siebie Wiatry Magii, tworząc niewidzialną barierę, która chroni go przed zranieniem. Pancerz Eteru zapewnia dodatkowe Punkty Zbroi na każdej lokacji ciała adepta. Liczba dodatkowych PZ równa jest wartości jego Magii. Adept nie może rzucić tego czaru, jeśli nosi zbroję. Jeśli w trakcie trwania czaru założy zbroję lub weźmie do ręki tarczę, czar natychmiast przestaje działać.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia powszechna (Pancerz Eteru)']
            ],
            [
                'type' => 'POWSZECHNA',
                'specialization' => null,
                'name' => 'Magiczna broń',
                'casting_duration' => 'akcja',
                'ingredient' => 'odrobina poświęconej wody (+1)',
                'effect_duration' => '1 godzina',
                'description' => 'Adept czerpie moc z Wiatrów Magii, zamykając ją w dowolnej broni białej (lub broni rzucanej) lub maksymalnie w pięciu pociskach do broni strzeleckiej (strzałach, bełtach, kulach do procy). Nasycona mocą broń nie zadaje dodatkowych obrażeń, ale w trakcie trwania czaru jest traktowana jako broń magiczna. Może ranić duchy, upiory i potwory odporne na ciosy zadawane zwykłą bronią.',
                'casting_number' => 6,
                'talent_id' => $talents['Magia powszechna (Magiczna broń)']
            ],
            [
                'type' => 'POWSZECHNA',
                'specialization' => null,
                'name' => 'Magiczne zamknięcie',
                'casting_duration' => '1 minuta',
                'ingredient' => 'mały klucz (+1)',
                'effect_duration' => '1 tydzień',
                'description' => 'Zaklęcie to można nałożyć na dowolny zamek (skobel, zasuwkę, itp.), znajdujący się w odległości do 2 metrów od adepta. W trakcie trwania czaru zamka nie można otworzyć żadnym kluczem ani wytrychem, choć można wyważyć chronione w ten sposób drzwi lub rozbić zabezpieczone czarem wieko kufra.',
                'casting_number' => 7,
                'talent_id' => $talents['Magia powszechna (Magiczne zamknięcie)']
            ],
            [
                'type' => 'POWSZECHNA',
                'specialization' => null,
                'name' => 'Magiczny alarm',
                'casting_duration' => '1 minuta',
                'ingredient' => 'mosiężny dzwoneczek (+1)',
                'effect_duration' => 'do wyzwolenia efektu',
                'description' => 'Adept ogniskuje moc w dowolnym miejscu. Jeśli ktokolwiek znajdzie się w odległości 2 metrów od tego miejsca, adept odbierze przekaz mentalny, który poinformuje go o tym, że ktoś wyzwolił efekt zaklęcia. Jeżeli adept śpi, po uruchomieniu magicznego alarmu natychmiast się budzi. Czar pełni funkcję wyłącznie ostrzegawczą – nie powstrzymuje intruza, ani nie informuje adepta o tożsamości osoby, która uruchomiła alarm.',
                'casting_number' => 8,
                'talent_id' => $talents['Magia powszechna (Magiczny alarm)']
            ],
            [
                'type' => 'POWSZECHNA',
                'specialization' => null,
                'name' => 'Uciszenie',
                'casting_duration' => 'akcja',
                'ingredient' => 'knebel (+1)',
                'effect_duration' => 'liczba rund równa wartości Magii adepta',
                'description' => 'Adept splata Wiatry Magii w zamkniętą sferę ciszy, otaczając nią dowolną postać znajdującą się w odległości do 24 metrów. Ofiara może odeprzeć czar, wykonując udany test Siły Woli. Nieudany test oznacza, że w trakcie trwania czaru postać nie wydaje żadnych dźwięków.',
                'casting_number' => 10,
                'talent_id' => $talents['Magia powszechna (Uciszenie)']
            ],
            [
                'type' => 'POWSZECHNA',
                'specialization' => null,
                'name' => 'Podniebny chód',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'orle pióro (+2)',
                'effect_duration' => '1 runda',
                'description' => 'Czar pozwala przez krótką chwilę chodzić w powietrzu jak po stałym gruncie. Adept może przebyć odległość równą potrojonej wartości jego Szybkości, a potem powoli opada na ziemię. Maksymalna wysokość, na jaką może się wznieść, wynosi 6 metrów, co pozwala z łatwością przekraczać rozmaite przeszkody naziemne.',
                'casting_number' => 11,
                'talent_id' => $talents['Magia powszechna (Podniebny chód)']
            ],
            [
                'type' => 'POWSZECHNA',
                'specialization' => null,
                'name' => 'Rozproszenie magii',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'srebrny młoteczek (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czar ten umożliwia rozproszenie efektu dowolnego zaklęcia, rzucanego lub działającego na obszarze w odległości do 12 metrów od adepta. Czar nie może zostać użyty do rozproszenia magii rytualnej. Adept, który rzuca rozproszenie magii, musi wykonać udany test splatania magii. Otrzymuje modyfikator -10 za każdy punkt Magii postaci, która rzuciła czar będący celem rozproszenia magii. Rozproszenie magii nie działa na ożywieńców i demony.',
                'casting_number' => 13,
                'talent_id' => $talents['Magia powszechna (Rozproszenie magii)']
            ],
        ];
        $arcaneShadowSpells = [
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Cienia',
                'name' => 'Płaszcz cieni',
                'casting_duration' => 'akcja',
                'ingredient' => 'kawałek węgla drzewnego (+1)',
                'effect_duration' => 'liczba minut równa wartości Magii czarodzieja',
                'description' => 'Czarodziej przywołuje moc Ulgu, która okrywa jego ciało zasłoną utkaną z cieni. W trakcie trwania zaklęcia czarodziej otrzymuje modyfikator +20 do testów ukrywania się.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia tajemna (tradycja cienia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Cienia',
                'name' => 'Sobowtór',
                'casting_duration' => '3 akcje',
                'ingredient' => 'pukiel włosów przedstawiciela rasy, w którą ma się przeobrazić czarodziej (+1)',
                'effect_duration' => '1k10 rund',
                'description' => 'Czarodziej przybiera wygląd (wraz z ubraniem, zbroją, itp.) dowolnej żywej istoty humanoidalnej mającej poniżej 3 metrów wzrostu. Zaklęcie zmienia tylko wygląd zewnętrzny, nie modyfikuje wiedzy ani barwy głosu.',
                'casting_number' => 7,
                'talent_id' => $talents['Magia tajemna (tradycja cienia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Cienia',
                'name' => 'Oszołomienie',
                'casting_duration' => 'akcja',
                'ingredient' => 'trochę piwa (+1)',
                'effect_duration' => 'liczba rund równa wartości Magii czarodzieja',
                'description' => 'Czarodziej mocą swego umysłu ogłupia dowolną postać znajdującą się w odległości do 24 metrów. Ofiara może odeprzeć czar testem Siły Woli. Niepowodzenie oznacza oszołomienie (rzut w tabeli oszołomienia określa konkretny efekt, np. otępienie, ucieczkę lub paraliż).',
                'casting_number' => 8,
                'talent_id' => $talents['Magia tajemna (tradycja cienia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Cienia',
                'name' => 'Maska iluzji',
                'casting_duration' => 'akcja',
                'ingredient' => 'szkło wyobrażające planowaną czynność (+2)',
                'effect_duration' => '1k10 rund',
                'description' => 'Czarodziej okrywa się magiczną iluzją, która pozwala mu wykonywać dowolne działania, przy zachowaniu wrażenia, że robi coś zupełnie innego (np. atakuje, gdy wydaje się, że czyta książkę).',
                'casting_number' => 12,
                'talent_id' => $talents['Magia tajemna (tradycja cienia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Cienia',
                'name' => 'Strefa mroku',
                'casting_duration' => 'akcja',
                'ingredient' => 'oczy salamandry (+2)',
                'effect_duration' => 'liczba rund równa wartości Magii czarodzieja',
                'description' => 'Tworzy obszar nieprzeniknionej ciemności w dowolnym miejscu w odległości do 48 metrów. Wszystkie postacie w promieniu 5 metrów wokół wskazanego miejsca przestają widzieć cokolwiek.',
                'casting_number' => 15,
                'talent_id' => $talents['Magia tajemna (tradycja cienia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Cienia',
                'name' => 'Całun niewidzialności',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'nić babiego lata (+2)',
                'effect_duration' => '1k10 rund',
                'description' => 'Czarodziej staje się niewidzialny. Nie może być celem ataków dystansowych ani magicznych pocisków. Otrzymuje modyfikator +20 do Walki Wręcz. Postacie w pobliżu mogą próbować go wykryć testem spostrzegawczości (-20).',
                'casting_number' => 17,
                'talent_id' => $talents['Magia tajemna (tradycja cienia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Cienia',
                'name' => 'Przerażający wygląd',
                'casting_duration' => 'akcja',
                'ingredient' => 'skrawek szaty upiora (+3)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Czarodziej przybiera wygląd przerażającej istoty, wzbudzając Grozę wśród wszystkich, którzy go widzą.',
                'casting_number' => 21,
                'talent_id' => $talents['Magia tajemna (tradycja cienia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Cienia',
                'name' => 'Sztylety cienia',
                'casting_duration' => 'akcja',
                'ingredient' => 'nóż ze skuwanego na zimno żelaza (+3)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czarodziej tworzy i rzuca magicznymi sztyletami (liczba równa Magii) w przeciwników do 48 metrów. Są to pociski o Sile 3, które przebijają każdą niemagiczną zbroję (ignorują punkty pancerza).',
                'casting_number' => 21,
                'talent_id' => $talents['Magia tajemna (tradycja cienia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Cienia',
                'name' => 'Iluzja',
                'casting_duration' => '3 akcje',
                'ingredient' => 'kryształowy pryzmat (+3)',
                'effect_duration' => 'liczba rund równa wartości Magii czarodzieja',
                'description' => 'Tworzy niemal doskonałą iluzję (wizualną, dźwiękową i zapachową) o średnicy 10 metrów w odległości do 48 metrów. Postacie mogą przejrzeć iluzję testem Inteligencji.',
                'casting_number' => 24,
                'talent_id' => $talents['Magia tajemna (tradycja cienia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Cienia',
                'name' => 'Strefa zamętu',
                'casting_duration' => 'akcja',
                'ingredient' => 'oczy chimery (+3)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Potężniejsza wersja oszołomienia działająca obszarowo. Wszystkie postacie w promieniu 5 metrów od wskazanego punktu muszą wykonać test Siły Woli pod groźbą efektów czaru Oszołomienie.',
                'casting_number' => 27,
                'talent_id' => $talents['Magia tajemna (tradycja cienia)']
            ],
        ];
        $metalSpells = [
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Metalu',
                'name' => 'Zapora ze stali',
                'casting_duration' => 'akcja',
                'ingredient' => 'stalowa kula (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Czarodziej skupia moc wokół siebie, otaczając się zaporą wirujących stalowych kul. W trakcie trwania czaru wszystkie wymierzone w niego ataki wykonywane są z modyfikatorem -10 do WW lub US.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia tajemna (tradycja metalu)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Metalu',
                'name' => 'Prawo logiki',
                'casting_duration' => '1k10 akcji podwójnych',
                'ingredient' => 'czysta karta papieru (+1)',
                'effect_duration' => '5 minut lub do momentu wykonania wybranego testu',
                'description' => 'Czarodziej czerpie moc z Wiatru Magii, by znaleźć logiczne rozwiązanie problemu. Czarodziej lub towarzysz w odległości do 12 metrów otrzymuje modyfikator +20 do dowolnego testu umiejętności lub cechy.',
                'casting_number' => 7,
                'talent_id' => $talents['Magia tajemna (tradycja metalu)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Metalu',
                'name' => 'Korozja',
                'casting_duration' => 'akcja',
                'ingredient' => 'zardzewiały ćwiek (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Dowolny metalowy przedmiot w odległości do 12 metrów koroduje i rdzewieje, stając się bezużyteczny. Cel może mieć maksymalne Obciążenie 75.',
                'casting_number' => 9,
                'talent_id' => $talents['Magia tajemna (tradycja metalu)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Metalu',
                'name' => 'Srebrzyste strzały Arha',
                'casting_duration' => 'akcja',
                'ingredient' => 'srebrny grot strzały (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czarodziej tworzy magiczne strzały z czystego srebra (liczba równa wartości Magii). Są to pociski o Sile 3 o zasięgu do 48 metrów.',
                'casting_number' => 13,
                'talent_id' => $talents['Magia tajemna (tradycja metalu)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Metalu',
                'name' => 'Pancerz z ołowiu',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'miniaturowy hełm z ołowiu (+2)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Zbroje przeciwników w promieniu 5 metrów od wskazanego punktu (zasięg 48m) stają się ciężkie. Otrzymują oni modyfikator -10 do WW, US i Zręczności oraz -1 do Szybkości.',
                'casting_number' => 14,
                'talent_id' => $talents['Magia tajemna (tradycja metalu)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Metalu',
                'name' => 'Metoda prób i błędów',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'pusta szklana fiolka (+2)',
                'effect_duration' => '1 runda',
                'description' => 'Towarzysze w odległości do 12 metrów mogą przerzucić wynik pojedynczego testu lub rzutu na obrażenia. Wynik drugiego rzutu jest ostateczny.',
                'casting_number' => 16,
                'talent_id' => $talents['Magia tajemna (tradycja metalu)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Metalu',
                'name' => 'Przekształcenie metalu',
                'casting_duration' => '1 minuta',
                'ingredient' => 'talizman w kształcie młota i kowadła (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Przemiana metalowego przedmiotu w inny (bez zmiany rodzaju metalu). Jakość przedmiotu zależy od testu splatania magii. Jest to czar dotykowy.',
                'casting_number' => 18,
                'talent_id' => $talents['Magia tajemna (tradycja metalu)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Metalu',
                'name' => 'Umagicznienie',
                'casting_duration' => '1 minuta',
                'ingredient' => 'pióro gryfa (+3)',
                'effect_duration' => '1 godzina',
                'description' => 'Nadaje przedmiotowi magiczną energię. Posiadacz otrzymuje modyfikator +5 do cechy związanej z funkcją przedmiotu (np. WW dla miecza). Czar dotykowy.',
                'casting_number' => 21,
                'talent_id' => $talents['Magia tajemna (tradycja metalu)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Metalu',
                'name' => 'Siła umysłu',
                'casting_duration' => '10 minut',
                'ingredient' => 'strona z księgi napisanej przez szaleńca (+3)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Uzdrowienie obłąkanej postaci (test splatania magii). Sukces usuwa 1k10 Punktów Obłędu, porażka dodaje 1k10. Czar dotykowy, nie działa na zwierzęta.',
                'casting_number' => 23,
                'talent_id' => $talents['Magia tajemna (tradycja metalu)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Metalu',
                'name' => 'Tłumienic magii',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'niewielka ozdobna pochwa ze złota o wartości min. 75 zk (+3)',
                'effect_duration' => '1k10 rund',
                'description' => 'Magiczny przedmiot w odległości do 24 metrów traci wszystkie swoje właściwości magiczne na czas trwania czaru.',
                'casting_number' => 26,
                'talent_id' => $talents['Magia tajemna (tradycja metalu)']
            ],
        ];
        $celestialSpells = [
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Niebios',
                'name' => 'Wróżba',
                'casting_duration' => '1 minuta',
                'ingredient' => 'wątroba małego zwierzęcia (+1)',
                'effect_duration' => '2k10 godzin',
                'description' => 'Czarodziej wróży z gwiazd, przepowiadając najbliższą przyszłość. Po rzuceniu czaru może spróbować określić, czy dana chwila sprzyja wykonaniu określonego działania (test Inteligencji).',
                'casting_number' => 4,
                'talent_id' => $talents['Magia tajemna (tradycja niebios)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Niebios',
                'name' => 'Pierwsze proroctwo Amul',
                'casting_duration' => 'akcja',
                'ingredient' => 'odłamek szkła (+1)',
                'effect_duration' => '1 runda',
                'description' => 'Czarodziej przepowiada najbliższą przyszłość. W swojej następnej turze gracz może przerzucić wynik jednej kostki użytej podczas dowolnego rzutu (testu cechy, rzutu na obrażenia itp.).',
                'casting_number' => 6,
                'talent_id' => $talents['Magia tajemna (tradycja niebios)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Niebios',
                'name' => 'Błyskawica',
                'casting_duration' => 'akcja',
                'ingredient' => 'kamerton (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czarodziej ciska błyskawicą w dowolnego przeciwnika znajdującego się w odległości do 36 metrów. Jest to magiczny pocisk o Sile 5.',
                'casting_number' => 10,
                'talent_id' => $talents['Magia tajemna (tradycja niebios)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Niebios',
                'name' => 'Drugie proroctwo Amul',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'odłamek barwionego szkła (+2)',
                'effect_duration' => '1 godzina lub do momentu wykorzystania obu przerzutów',
                'description' => 'Zaklęcie działa podobnie jak Pierwsze proroctwo Amul, z tą różnicą, że gracz może powtórzyć rzuty dowolnych dwóch kostek.',
                'casting_number' => 12,
                'talent_id' => $talents['Magia tajemna (tradycja niebios)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Niebios',
                'name' => 'Podmuch wiatru',
                'casting_duration' => 'akcja',
                'ingredient' => 'zwierzęcy pęcherz (+2)',
                'effect_duration' => 'liczba rund równa wartości Magii czarodzieja',
                'description' => 'Przywołuje gwałtowny wicher na wskazanym obszarze (promień 5m). Postacie w obszarze przewracają się i są ogłuszone na 1 rundę. Nie można używać broni strzeleckich w obszarze działania.',
                'casting_number' => 14,
                'talent_id' => $talents['Magia tajemna (tradycja niebios)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Niebios',
                'name' => 'Klątwa',
                'casting_duration' => 'akcja',
                'ingredient' => 'pęknięte zwierciadło (+2)',
                'effect_duration' => '24 godziny',
                'description' => 'Rzuca klątwę na postać w odległości do 24 metrów. Ofiara otrzymuje modyfikator -10 do wszystkich testów, a wymierzone w nią ataki zadają dodatkowy 1 punkt obrażeń.',
                'casting_number' => 16,
                'talent_id' => $talents['Magia tajemna (tradycja niebios)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Niebios',
                'name' => 'Niebiańskie skrzydła',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'gołębie pióro (+2)',
                'effect_duration' => 'liczba minut równa wartości Magii czarodzieja',
                'description' => 'Czarodziej może latać z Szybkością 6. Zaklęcie można rzucić tylko na siebie.',
                'casting_number' => 18,
                'talent_id' => $talents['Magia tajemna (tradycja niebios)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Niebios',
                'name' => 'Gwiezdny blask',
                'casting_duration' => '3 akcje',
                'ingredient' => 'mapa gwiezdna (+3)',
                'effect_duration' => 'liczba minut równa wartości Magii czarodzieja',
                'description' => 'Obszar w promieniu 48 metrów zostaje oświetlony łagodnym blaskiem, który rozprasza ciemność, ujawnia niewidzialne lub ukryte postacie oraz odkrywa tajemne przejścia.',
                'casting_number' => 22,
                'talent_id' => $talents['Magia tajemna (tradycja niebios)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Niebios',
                'name' => 'Nawałnica',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'chorągiewka służąca do ustalania kierunku i siły wiatru (+3)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Przywołuje magiczną burzę z piorunami (promień 5m). Wszystkie postacie w obszarze otrzymują trafienie z Siłą 5.',
                'casting_number' => 25,
                'talent_id' => $talents['Magia tajemna (tradycja niebios)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Niebios',
                'name' => 'Mroczne przeznaczenie',
                'casting_duration' => '1 godzina',
                'ingredient' => 'pętla wisielca (+3)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Odmienia przeznaczenie istoty w odległości do 1 km. Ofiara musi wykonać Bardzo Trudny (-30) test Siły Woli. Nieudany test oznacza natychmiastową utratę 1 Punktu Przeznaczenia.',
                'casting_number' => 31,
                'talent_id' => $talents['Magia tajemna (tradycja niebios)']
            ],
        ];
        $fireSpells = [
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Ognia',
                'name' => 'Przypalenie',
                'casting_duration' => 'akcja',
                'ingredient' => 'kawałek węgla drzewnego (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czarodziej kładzie dłonie na otwartej ranie i wypala ją dotykiem. Nie przywraca to punktów Żywotności, ale jest traktowane jako pomoc medyczna, która może ocalić od śmierci ciężko ranną postać. Czar ten przydaje się też do innych celów, na przykład można za jego pomocą kogoś napiętnować.',
                'casting_number' => 4,
                'talent_id' => $talents['Magia tajemna (tradycja ognia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Ognia',
                'name' => 'Ogień U\'zhul',
                'casting_duration' => 'akcja',
                'ingredient' => 'zapałka (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czarodziej ciska ognistym pociskiem w dowolnego przeciwnika znajdującego się w odległości do 36 metrów. Jest to magiczny pocisk o Sile 4.',
                'casting_number' => 6,
                'talent_id' => $talents['Magia tajemna (tradycja ognia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Ognia',
                'name' => 'Ognista korona',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'złota moneta (-1)',
                'effect_duration' => 'liczba minut równa wartości Magii czarodzieja',
                'description' => 'Nad głową czarodzieja pojawia się korona z płomieni, zapewniająca modyfikator +20 do testów dowodzenia i zastraszania. Każdy przeciwnik zamierzający zaatakować maga wręcz musi zdać test Siły Woli, inaczej strach uniemożliwia mu atak. Korona daje światło jak pochodnia i może podpalać przedmioty; czarodziej nie otrzymuje od niej obrażeń.',
                'casting_number' => 8,
                'talent_id' => $talents['Magia tajemna (tradycja ognia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Ognia',
                'name' => 'Ognista kula',
                'casting_duration' => 'akcja',
                'ingredient' => 'bryłka siarki (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czarodziej tworzy kilka ognistych kul (tyle, ile wynosi wartość jego Magii). Może cisnąć nimi w jednego lub kilku przeciwników w promieniu 48 metrów. Kule są magicznymi pociskami o Sile 3.',
                'casting_number' => 12,
                'talent_id' => $talents['Magia tajemna (tradycja ognia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Ognia',
                'name' => 'Tarcza Aqshy',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'żelazny amulet (+2)',
                'effect_duration' => '1k10 minut',
                'description' => 'Czarodziej otacza się wirującymi strumieniami czerwonego Wiatru Magii. Otrzymuje modyfikator +20 do Odporności przeciwko ognistym atakom (zionięcia, kule itp.). Zaklęcie działa tylko na rzucającego.',
                'casting_number' => 12,
                'talent_id' => $talents['Magia tajemna (tradycja ognia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Ognia',
                'name' => 'Gorejący miecz',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'pochodnia (+2)',
                'effect_duration' => 'liczba rund równa wartości Magii czarodzieja',
                'description' => 'W dłoni czarodzieja pojawia się ognisty miecz traktowany jako broń magiczna o Sile 4 z cechą „druzgocący”. Mag otrzymuje +1 do Ataków; może przedłużyć czas trwania testem Siły Woli co rundę.',
                'casting_number' => 14,
                'talent_id' => $talents['Magia tajemna (tradycja ognia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Ognia',
                'name' => 'Żar serc',
                'casting_duration' => '2 akcje podwójne',
                'ingredient' => 'fiolka z mieszanką krwi i oleju (+2)',
                'effect_duration' => '10 minut',
                'description' => 'Rozpala gniew towarzyszy w promieniu 30 metrów, dając im modyfikator +20 do Siły Woli podczas testów Strachu i Grozy. Efekt znika, gdy postać oddali się od maga na ponad 30 metrów.',
                'casting_number' => 16,
                'talent_id' => $talents['Magia tajemna (tradycja ognia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Ognia',
                'name' => 'Ognisty podmuch',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'sztylet z potrójnie hartowanej stali (+3)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Mag posyła 1k10 ognistych podmuchów (minimum tyle, ile wynosi jego Magia) w przeciwników do 48 metrów. Podmuchy to magiczne pociski o Sile 4.',
                'casting_number' => 22,
                'talent_id' => $talents['Magia tajemna (tradycja ognia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Ognia',
                'name' => 'Ognisty dech',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'łuska smoka (+3)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Mag zieje płomieniem w kształcie stożka na odległość 16 metrów. Wszystkie objęte nim postacie otrzymują trafienie z Sile 8 (S4 przy udanym teście Siły Woli).',
                'casting_number' => 25,
                'talent_id' => $talents['Magia tajemna (tradycja ognia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Ognia',
                'name' => 'Pożoga zagłady',
                'casting_duration' => '3 akcje',
                'ingredient' => 'ząb smoka (+3)',
                'effect_duration' => 'do śmierci wszystkich istot w sferze działania',
                'description' => 'Tworzy ogniste piekło w promieniu 5 metrów (zasięg 48m). Postacie wewnątrz otrzymują tyle trafień z Siłą 4, ile wynosi wartość Magii rzucającego; co rundę muszą zdawać test Siły Woli, by uniknąć ponownych obrażeń.',
                'casting_number' => 31,
                'talent_id' => $talents['Magia tajemna (tradycja ognia)']
            ],
        ];
        $deathSpells = [
            // --- MAGIA TAJEMNA (TRADYCJA ŚMIERCI) ---
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Śmierci',
                'name' => 'Wizja śmierci',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'garść ziemi zebranej z mogiły (+1)',
                'effect_duration' => '1 godzina',
                'description' => 'Czarodziej widzi duchy i zjawy, normalnie niewidoczne dla zwykłych ludzi. Obserwując śmierć żywej istoty, czarodziej może dostrzec duszę opuszczającą martwe ciało.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia tajemna (tradycja śmierci)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Śmierci',
                'name' => 'Dotyk śmierci',
                'casting_duration' => 'akcja',
                'ingredient' => 'dwa miedziane pensy (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czarodziej dotyka ciężko rannej osoby, pomagając jej umrzeć. Zaklęcie zabija dowolną postać, która ma 0 punktów Żywotności i otrzymała przynajmniej 1 trafienie krytyczne. Jest to czar dotykowy. Dusze odesłane w ten sposób są odporne na czary typu ostatnie słowa.',
                'casting_number' => 7,
                'talent_id' => $talents['Magia tajemna (tradycja śmierci)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Śmierci',
                'name' => 'Kosa żniwiarza',
                'casting_duration' => 'akcja',
                'ingredient' => 'miniaturowa żelazna kosa (+1)',
                'effect_duration' => 'liczba rund równa wartości Magii czarodzieja (możliwość przedłużenia testem Siły Woli)',
                'description' => 'W ręku czarodzieja pojawia się kosa stworzona z ametystowej energii. Jest to broń magiczna (Siła 5) z cechą oręża „szybki”. Władający nią mag otrzymuje modyfikator +10 do Walki Wręcz.',
                'casting_number' => 8,
                'talent_id' => $talents['Magia tajemna (tradycja śmierci)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Śmierci',
                'name' => 'Upływ lat',
                'casting_duration' => 'akcja',
                'ingredient' => 'niewielka klepsydra (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Sprawia, że jeden niemagiczny przedmiot (Obciążenie do 75) błyskawicznie niszczeje. Przedmioty kiepskiej i zwykłej jakości rozpadają się w pył, te wyższej jakości tracą jeden stopień jakości.',
                'casting_number' => 11,
                'talent_id' => $talents['Magia tajemna (tradycja śmierci)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Śmierci',
                'name' => 'Akceptacja przeznaczenia',
                'casting_duration' => '3 akcje',
                'ingredient' => 'gwóźdź z trumny (+2)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Wszyscy towarzysze w promieniu 12 metrów przestają bać się śmierci i są traktowani, jakby posiadali zdolność nieustraszony.',
                'casting_number' => 14,
                'talent_id' => $talents['Magia tajemna (tradycja śmierci)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Śmierci',
                'name' => 'Wyssanie życia',
                'casting_duration' => 'akcja',
                'ingredient' => 'fiolka wypełniona krwią (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Mag wysysa energię z przeciwnika w odległości do 12 metrów, by uzdrowić własne ciało. Cel traci 1k10 Żywotności (bez względu na Wytrzymałość i zbroję), a punkty te leczą maga (do jego maksimum). Nie działa na ożywieńców i demony.',
                'casting_number' => 16,
                'talent_id' => $talents['Magia tajemna (tradycja śmierci)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Śmierci',
                'name' => 'Ostatnie słowa',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'skrawek papieru welinowego (+2)',
                'effect_duration' => '6 rund',
                'description' => 'Pozwala zadać jedno pytanie duszy zabitej postaci (do 12m), o ile czar rzucono w ciągu minuty od jej śmierci. Dusza nie musi odpowiadać prawdą. Nie działa na istoty bez duszy.',
                'casting_number' => 18,
                'talent_id' => $talents['Magia tajemna (tradycja śmierci)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Śmierci',
                'name' => 'Do ostatniego tchu',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'fiolka z substancją balsamiczną (+2)',
                'effect_duration' => 'liczba minut równa wartości Magii czarodzieja',
                'description' => 'Powstrzymuje na chwilę śmierć towarzyszy w promieniu 24 metrów. Jeśli ktoś odniesie śmiertelną ranę, może wykonać jeszcze jedną, ostatnią akcję w swojej turze przed natychmiastową śmiercią.',
                'casting_number' => 20,
                'talent_id' => $talents['Magia tajemna (tradycja śmierci)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Śmierci',
                'name' => 'Klątwa starości',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'gałązka bluszczu rosnącego na mogile kapłana (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Dowolna postać (do 12m) starzeje się o lata. Nieudany test Siły Woli oznacza trwałą utratę 1k10 punktów Krzepy i Odporności. Działa na zwierzęta, nie działa na demony, ożywieńców i rośliny.',
                'casting_number' => 23,
                'talent_id' => $talents['Magia tajemna (tradycja śmierci)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Śmierci',
                'name' => 'Wicher śmierci',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'ametyst o wartości min. 50 zk (+3)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Przywołuje zabójczy wicher energii Shyish (promień 5m, zasięg 48m). Wszystkie postacie w obszarze tracą 1k10 Żywotności bez względu na Wytrzymałość i zbroję. Wyczuwalne dla innych magów w promieniu 5 km.',
                'casting_number' => 27,
                'talent_id' => $talents['Magia tajemna (tradycja śmierci)']
            ],
        ];
        $lightSpells = [
            // --- MAGIA TAJEMNA (TRADYCJA ŚWIATŁA) ---
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Światła',
                'name' => 'Rozbłysk',
                'casting_duration' => 'akcja',
                'ingredient' => 'niewielkie zwierciadło (+1)',
                'effect_duration' => '1k10 rund',
                'description' => 'Czarodziej wywołuje rozbłysk światła w dowolnym miejscu w odległości do 36 metrów. Wszystkie postacie w promieniu 3 metrów wokół wskazanego miejsca otrzymują modyfikator -10 do Walki Wręcz, Umiejętności Strzeleckich, Zręczności i wszystkich testów związanych ze wzrokiem.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia tajemna (tradycja światła)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Światła',
                'name' => 'Gorejący wzrok',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'soczewka (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Z oczu czarodzieja tryska promień oślepiającego światła, który trafia we wskazaną postać znajdującą się w odległości do 16 metrów. Jest to magiczny pocisk o Sile 6.',
                'casting_number' => 7,
                'talent_id' => $talents['Magia tajemna (tradycja światła)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Światła',
                'name' => 'Migotliwa tarcza',
                'casting_duration' => 'akcja',
                'ingredient' => 'świeca (+1)',
                'effect_duration' => 'liczba minut równa wartości Magii czarodzieja',
                'description' => 'Wokół czarodzieja pojawia się świetlista sfera, która chroni go przed wszelkimi niemagicznymi pociskami. W trakcie trwania czaru każdy atak bronią strzelecką wymierzony w czarodzieja ma Siłę zmniejszoną do 0. Próby ukrywania się automatycznie kończą się porażką.',
                'casting_number' => 8,
                'talent_id' => $talents['Magia tajemna (tradycja światła)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Światła',
                'name' => 'Uzdrowienie',
                'casting_duration' => 'akcja',
                'ingredient' => 'przezroczysty szklany paciorek (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Dotyk czarodzieja uzdrawia ranną postać. Przywraca jej tyle punktów Żywotności, ile wynosi wartość Magii czarodzieja. Czar dotykowy, który można rzucić również na siebie.',
                'casting_number' => 10,
                'talent_id' => $talents['Magia tajemna (tradycja światła)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Światła',
                'name' => 'Odesłanie demona',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'dębowa różdżka (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czarodziej odsyła dowolnego demona w odległości do 24 metrów z powrotem do Domeny Chaosu (przeciwstawny test Siły Woli). Zaklęcie może być użyte do uzdrowienia postaci opętanej przez demona.',
                'casting_number' => 13,
                'talent_id' => $talents['Magia tajemna (tradycja światła)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Światła',
                'name' => 'Natchnienie',
                'casting_duration' => '1 minuta',
                'ingredient' => 'strona z książki (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Świetlista moc natchnie czarodzieja mądrością zapewniającą rozwiązanie jednej zagadki lub problemu. Mag otrzymuje modyfikator +30 do następnego testu wiedzy.',
                'casting_number' => 16,
                'talent_id' => $talents['Magia tajemna (tradycja światła)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Światła',
                'name' => 'Oczy prawdy',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'szklana kula (+2)',
                'effect_duration' => 'liczba rund równa wartości Magii czarodzieja',
                'description' => 'Czarodziej dostrzega niewidzialne obiekty i postacie, przenika iluzje i kamuflaż, zauważa ukryte istoty oraz widzi w ciemnościach do 48 metrów.',
                'casting_number' => 20,
                'talent_id' => $talents['Magia tajemna (tradycja światła)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Światła',
                'name' => 'Oślepiający blask',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'wypolerowany dysk ze srebra (+3)',
                'effect_duration' => '1k10 rund',
                'description' => 'Wywołuje rozbłysk oślepiającego światła w odległości do 48 metrów. Postacie w promieniu 5 metrów, które nie zdadzą testu Zręczności, tracą wzrok; ich Zręczność, Szybkość i WW spadają o połowę, a US do 0.',
                'casting_number' => 24,
                'talent_id' => $talents['Magia tajemna (tradycja światła)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Światła',
                'name' => 'Zguba demonów',
                'casting_duration' => '3 akcje',
                'ingredient' => 'różdżka wykonana z trafionego piorunem dębu (+3)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Odsyła grupę demonów znajdujących się w odległości do 48 metrów. Wszystkie demony w promieniu 5 metrów wokół wskazanego miejsca muszą wykonać test Siły Woli pod groźbą odesłania.',
                'casting_number' => 26,
                'talent_id' => $talents['Magia tajemna (tradycja światła)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Światła',
                'name' => 'Kolumna światłości',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'diament o wartości przynajmniej 100 zk (+3)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Tworzy kolumnę oślepiającej jasności w odległości do 48 metrów. Postacie w promieniu 5 metrów otrzymują trafienie z Siłą 4 i muszą wykonać test Zręczności pod groźbą efektu czaru rozbłysk. Wyczuwalne przez magów w promieniu 5 km.',
                'casting_number' => 28,
                'talent_id' => $talents['Magia tajemna (tradycja światła)']
            ],
        ];
        $beastSpells = [
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Zwierząt',
                'name' => 'Poskromienie',
                'casting_duration' => 'akcja',
                'ingredient' => 'grudka cukru (+1)',
                'effect_duration' => 'liczba godzin równa wartości Magii czarodzieja',
                'description' => 'Uspokaja dowolne zwierzę w odległości do 48 metrów (test Siły Woli). Pozwala bezpiecznie podejść lub dotknąć zwierzęcia, a w przypadku wierzchowca daje modyfikator +10 do testów jeździectwa.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia tajemna (tradycja zwierząt)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Zwierząt',
                'name' => 'Przemiana w kruka',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'krucze pióro (+1)',
                'effect_duration' => '1 godzina (może zostać przerwany wcześniej lub przez trafienie krytyczne)',
                'description' => 'Czarodziej wraz z ekwipunkiem zamienia się w kruka. Zachowuje zdolności umysłowe, Inteligencję i Siłę Woli, ale nie może mówić ani rzucać czarów.',
                'casting_number' => 7,
                'talent_id' => $talents['Magia tajemna (tradycja zwierząt)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Zwierząt',
                'name' => 'Szpony furii',
                'casting_duration' => 'akcja',
                'ingredient' => 'koci pazur (+1)',
                'effect_duration' => 'liczba minut równa wartości Magii czarodzieja',
                'description' => 'Paznokcie czarodzieja zmieniają się w ostre brzytwy. Zapewniają +1 do Ataków i modyfikator +10 do Walki Wręcz. Traktowane jako broń jednoręczna z cechą „szybki”.',
                'casting_number' => 8,
                'talent_id' => $talents['Magia tajemna (tradycja zwierząt)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Zwierząt',
                'name' => 'Mowa zwierząt',
                'casting_duration' => 'akcja',
                'ingredient' => 'język zwierzęcia, w które chce zmienić się czarodziej (+2)',
                'effect_duration' => 'liczba minut równa wartości Magii czarodzieja',
                'description' => 'Pozwala mówić ludzkim głosem po przemianie w zwierzę lub rzucony na zwierzę (do 24m) sprawia, że może ono przemawiać ludzkim głosem.',
                'casting_number' => 11,
                'talent_id' => $talents['Magia tajemna (tradycja zwierząt)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Zwierząt',
                'name' => 'Głos pana',
                'casting_duration' => 'akcja',
                'ingredient' => 'miniaturowy bicz ukręcony ze zwierzęcej sierści (+2)',
                'effect_duration' => '1 runda',
                'description' => 'Zmusza dowolne zwierzę w odległości do 24 metrów do posłuszeństwa (test Siły Woli). W następnej rundzie zwierzę będzie całkowicie posłuszne rozkazom maga.',
                'casting_number' => 13,
                'talent_id' => $talents['Magia tajemna (tradycja zwierząt)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Zwierząt',
                'name' => 'Przemiana w wilka',
                'casting_duration' => '2 akcje podwójne',
                'ingredient' => 'wilcza łapa (+2)',
                'effect_duration' => 'jak przemiana w kruka',
                'description' => 'Czarodziej zamienia się w wilka. Statystyki i opis wilka znajdują się w Bestiariuszu.',
                'casting_number' => 15,
                'talent_id' => $talents['Magia tajemna (tradycja zwierząt)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Zwierząt',
                'name' => 'Uczta kruków',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'żywy kruk (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Przywołuje chmarę morderczych kruków w odległości do 48 metrów. Wszystkie postacie w promieniu 5 metrów zostają zaatakowane i otrzymują po jednym trafieniu z Siłą 3 w głowę.',
                'casting_number' => 17,
                'talent_id' => $talents['Magia tajemna (tradycja zwierząt)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Zwierząt',
                'name' => 'Zwierzęcy szał',
                'casting_duration' => '2 akcje podwójne',
                'ingredient' => 'serce wilka (+2)',
                'effect_duration' => 'zgodnie z zasadami zdolności szał bojowy',
                'description' => 'Przyjazna postać w odległości do 12 metrów natychmiast wpada w szał bojowy. Zaklęcie nie działa na zwierzęta.',
                'casting_number' => 19,
                'talent_id' => $talents['Magia tajemna (tradycja zwierząt)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Zwierząt',
                'name' => 'Przemiana w niedźwiedzia',
                'casting_duration' => '3 akcje podwójne',
                'ingredient' => 'pazur niedźwiedzia (+3)',
                'effect_duration' => 'jak przemiana w kruka',
                'description' => 'Czarodziej zamienia się w niedźwiedzia. Statystyki i opis niedźwiedzia znajdują się w Bestiariuszu.',
                'casting_number' => 21,
                'talent_id' => $talents['Magia tajemna (tradycja zwierząt)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Zwierząt',
                'name' => 'Skrzydła sokoła',
                'casting_duration' => '2 akcje podwójne',
                'ingredient' => 'żywy sokół (+3)',
                'effect_duration' => 'liczba minut równa wartości Magii czarodzieja',
                'description' => 'Z pleców czarodzieja wyrastają skrzydła, pozwalające latać z Szybkością 4. Może to zostać uznane przez zwykłych ludzi za oznakę mutacji Chaosu.',
                'casting_number' => 25,
                'talent_id' => $talents['Magia tajemna (tradycja zwierząt)']
            ],
        ];
        $lifeSpells = [
            // --- MAGIA TAJEMNA (TRADYCJA ŻYCIA) ---
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Życia',
                'name' => 'Klątwa cierni',
                'casting_duration' => 'akcja',
                'ingredient' => 'cierń (+1)',
                'effect_duration' => '1k10 rund',
                'description' => 'W ciało dowolnej postaci znajdującej się w odległości do 36 metrów wyrastają kolce. Jeśli cel nie zda testu Siły Woli, traci 1 punkt Żywotności (bez względu na Wytrzymałość i zbroję) oraz otrzymuje modyfikator -20 do wszystkich testów wykonywanych w tej rundzie.',
                'casting_number' => 6,
                'talent_id' => $talents['Magia tajemna (tradycja życia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Życia',
                'name' => 'Ziemia karmicielka',
                'casting_duration' => '1 minuta',
                'ingredient' => 'garść karmy dla zwierząt (+1)',
                'effect_duration' => '1 tydzień',
                'description' => 'Czarodziej dotknięciem sprawia, że postać napełnia się energią Ghyran. Przez tydzień postać może całkowicie powstrzymać się od jedzenia (choć musi pić). Jest to czar dotykowy.',
                'casting_number' => 8,
                'talent_id' => $talents['Magia tajemna (tradycja życia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Życia',
                'name' => 'Krew ziemi',
                'casting_duration' => '1-10 akcji',
                'ingredient' => 'sztylet (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czarodziej wchłania energię życiową z ziemi, na której stoi. Przywraca mu to tyle punktów Żywotności, ile akcji przeznaczył na rzucenie zaklęcia. Czarodziej nie może użyć tego czaru do uzdrowienia innej postaci.',
                'casting_number' => 9,
                'talent_id' => $talents['Magia tajemna (tradycja życia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Życia',
                'name' => 'Żar z nieba',
                'casting_duration' => 'akcja',
                'ingredient' => 'fiolka wypełniona potem zacnego człowieka (+2)',
                'effect_duration' => '1k10 rund',
                'description' => 'Wybrany obszar ogarnia skwar. Wszystkie postacie w promieniu 5 metrów są niemiłosiernie zmęczone i otrzymują modyfikator -20 do wszystkich testów.',
                'casting_number' => 12,
                'talent_id' => $talents['Magia tajemna (tradycja życia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Życia',
                'name' => 'Wrota ziemi',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'żelazny klucz (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czarodziej zapada się pod ziemię i pojawia w dowolnym miejscu w odległości do 48 metrów. Oba miejsca muszą znajdować się na obszarze pokrytym ziemią (nie działa wewnątrz budynków lub na bruku).',
                'casting_number' => 14,
                'talent_id' => $talents['Magia tajemna (tradycja życia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Życia',
                'name' => 'Szept rzeki',
                'casting_duration' => '1 minuta',
                'ingredient' => 'flaszka wina (+2)',
                'effect_duration' => 'liczba rund równa wartości Magii czarodzieja',
                'description' => 'Stojąc w rzece, czarodziej może komunikować się z jej duchem. Pozwala to uzyskać informacje o wydarzeniach w nurcie do 1 km w górę i w dół rzeki z ostatnich 24 godzin.',
                'casting_number' => 15,
                'talent_id' => $talents['Magia tajemna (tradycja życia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Życia',
                'name' => 'Wiosenny rozkwit',
                'casting_duration' => '10 minut',
                'ingredient' => 'garść nawozu (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Zwiększa płodność pola uprawnego lub wybranej istoty. Żywa istota w ciągu miesiąca zajdzie w ciążę (jeśli zaistnieją pozostałe niezbędne okoliczności).',
                'casting_number' => 18,
                'talent_id' => $talents['Magia tajemna (tradycja życia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Życia',
                'name' => 'Gejzer',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'różdżka do poszukiwania wody, poświęcona przez kapłana Taala (+3)',
                'effect_duration' => '1 godzina',
                'description' => 'Z ziemi tryska gejzer wody (zasięg 24m). Postacie w promieniu 3 metrów otrzymują trafienie z Siłą 4 i zostają odrzucone. Po rzuceniu czaru z gejzera wypływa czysta woda.',
                'casting_number' => 22,
                'talent_id' => $talents['Magia tajemna (tradycja życia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Życia',
                'name' => 'Zlodowacenie',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'fiolka z roztopionym śniegiem ze szczytu górskiego (+3)',
                'effect_duration' => 'liczba minut równa wartości Magii czarodzieja',
                'description' => 'Pokrywa lodem obszar w promieniu 5 metrów (zasięg 48m). Postacie otrzymują trafienie z Siłą 4 (test Siły Woli). Przez 1 rundę są bezbronne, a potem poruszają się z połową Szybkości.',
                'casting_number' => 25,
                'talent_id' => $talents['Magia tajemna (tradycja życia)']
            ],
            [
                'type' => 'TAJEMNA',
                'specialization' => 'Tradycja Życia',
                'name' => 'Siła natury',
                'casting_duration' => '10 minut',
                'ingredient' => 'fiolka wypełniona wodą z poświęconej sadzawki (+3)',
                'effect_duration' => 'do końca trwającej pory roku',
                'description' => 'Oczyszcza z zarazy obszar o powierzchni jednego kilometra kwadratowego lub leczy 2k10 postaci dotkniętych chorobą (skracając czas jej trwania o połowę).',
                'casting_number' => 27,
                'talent_id' => $talents['Magia tajemna (tradycja życia)']
            ],
        ];
        $chaosSpells = [
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja Chaosu',
                'name' => 'Wizja męki',
                'casting_duration' => 'akcja',
                'ingredient' => 'niewielka maska (+1)',
                'effect_duration' => '1 runda',
                'description' => 'Czarnoksiężnik sprawia, że dowolną postać w odległości do 24 metrów nawiedzają piekielne wizje. Nieudany test Siły Woli oznacza ogłuszenie. Po otrząśnięciu się z szoku ofiara musi zdać kolejny test Siły Woli, by uniknąć otrzymania 1 Punktu Obłędu.',
                'casting_number' => 7,
                'talent_id' => $talents['Magia czarnoksięska (tradycja chaosu)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja Chaosu',
                'name' => 'Łaska Chaosu',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'amulet z symbolem jednego z bogów Chaosu (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Czarnoksiężnik zwraca się do Mrocznych Potęg o błogosławieństwo. W trakcie trwania czaru otrzymuje modyfikator +10 do Walki Wręcz, Odporności, Siły Woli i Ogłady.',
                'casting_number' => 9,
                'talent_id' => $talents['Magia czarnoksięska (tradycja chaosu)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja Chaosu',
                'name' => 'Przyzwanie pomniejszego demona',
                'casting_duration' => '2 akcje podwójne',
                'ingredient' => 'wciąż bijące serce istoty humanoidalnej (+2)',
                'effect_duration' => '1k10 minut',
                'description' => 'Czarnoksiężnik przyzywa pomniejszego demona w dowolnym miejscu w odległości do 12 metrów. Udany test Siły Woli pozwala magowi kontrolować demona.',
                'casting_number' => 12,
                'talent_id' => $talents['Magia czarnoksięska (tradycja chaosu)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja Chaosu',
                'name' => 'Paląca krew',
                'casting_duration' => 'akcja',
                'ingredient' => 'fiolka wypełniona krwią demona (+3)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Mag pluje krwią palącą niczym kwas. Jest to magiczny pocisk o Sile 4. Cel w odległości do 24 metrów otrzymuje tyle trafień, ile wynosi wartość Magii rzucającego.',
                'casting_number' => 13,
                'talent_id' => $talents['Magia czarnoksięska (tradycja chaosu)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja Chaosu',
                'name' => 'Pokusa Chaosu',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'zbezczeszczony święty symbol (+2)',
                'effect_duration' => '1 runda',
                'description' => 'Oczarowuje postać w odległości do 24 metrów wizją Chaosu. Przy nieudanym teście Siły Woli ofiara w swojej następnej turze musi wykonywać polecenia czarnoksiężnika. Nie działa na ożywieńców.',
                'casting_number' => 16,
                'talent_id' => $talents['Magia czarnoksięska (tradycja chaosu)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja Chaosu',
                'name' => 'Mroczna dłoń zniszczenia',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'dłoń wisielca (+2)',
                'effect_duration' => 'liczba rund równa wartości Magii czarnoksiężnika',
                'description' => 'Ręka maga staje się magiczną bronią o Sile 7 z cechą „przebijający zbroję”. Czarnoksiężnik otrzymuje modyfikator +10 do Walki Wręcz przy atakach tą ręką. Działanie można przedłużyć testem Siły Woli.',
                'casting_number' => 17,
                'talent_id' => $talents['Magia czarnoksięska (tradycja chaosu)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja Chaosu',
                'name' => 'Dotyk Chaosu',
                'casting_duration' => 'akcja',
                'ingredient' => 'róg zwierzoczłeka (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czar dotykowy wtłaczający energię Chaosu. Jeśli ofiara nie zda testu Siły Woli, następuje błyskawiczna przemiana (losowa mutacja Chaosu) oraz ryzyko ogłuszenia na 1 rundę. Nie działa na ożywieńców.',
                'casting_number' => 20,
                'talent_id' => $talents['Magia czarnoksięska (tradycja chaosu)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja Chaosu',
                'name' => 'Welon zepsucia',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'miecz wojownika Chaosu (+3)',
                'effect_duration' => 'do chwili wykonania udanego testu Siły Woli',
                'description' => 'Przywołuje chmurę zepsucia (promień 5m, zasięg 36m). Postacie wewnątrz tracą 1 punkt Żywotności (test Siły Woli). Rany gniją, powodując dalszą utratę zdrowia co turę. Przy większych obrażeniach grozi mutacja.',
                'casting_number' => 24,
                'talent_id' => $talents['Magia czarnoksięska (tradycja chaosu)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja Chaosu',
                'name' => 'Przyzwanie grupy demonów',
                'casting_duration' => '2 akcje podwójne',
                'ingredient' => 'wciąż bijące serca sześciu humanoidów (+3)',
                'effect_duration' => '1k10 minut',
                'description' => 'Mag przywołuje grupę pomniejszych demonów (liczba równa Magii rzucającego) w dowolnym miejscu w odległości do 12 metrów.',
                'casting_number' => 25,
                'talent_id' => $talents['Magia czarnoksięska (tradycja chaosu)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja Chaosu',
                'name' => 'Słowo bólu',
                'casting_duration' => 'akcja',
                'ingredient' => 'krew demona (+3)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Wypowiedzenie imienia Bóstwa Chaosu zadaje trafienie z Siłą 8 wszystkim istotom w promieniu 10 metrów od maga. Ofiary mogą zostać ogłuszone na 1 rundę (test Siły Woli). Zaklęcie nie rani rzucającego.',
                'casting_number' => 27,
                'talent_id' => $talents['Magia czarnoksięska (tradycja chaosu)']
            ],
        ];
        $necromancySpells = [
            // --- CZARNOKSIĘSKA (TRADYCJA NEKROMANCJI) ---
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja nekromancji',
                'name' => 'Oblicze śmierci',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'czaszka (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Twarz nekromanty przybiera wygląd wyszczerzonej czaszki – symbolu śmierci. W trakcie trwania czaru czarnoksiężnik wzbudza Strach, zgodnie ze zwykłymi zasadami.',
                'casting_number' => 6,
                'talent_id' => $talents['Magia czarnoksięska (tradycja nekromancji)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja nekromancji',
                'name' => 'Ożywienie',
                'casting_duration' => 'jedna akcja za każde ożywione zwłoki',
                'ingredient' => 'pył z mogiły (+1)',
                'effect_duration' => 'stały (wymaga kontroli)',
                'description' => 'Nekromanta ożywia zwłoki, tworząc zombi lub szkielety w liczbie równej wartości jego Magii. Musi znajdować się nie dalej niż 12 metrów od zwłok lub kości.',
                'casting_number' => 8,
                'talent_id' => $talents['Magia czarnoksięska (tradycja nekromancji)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja nekromancji',
                'name' => 'Witalność',
                'casting_duration' => '3 akcje',
                'ingredient' => 'kły nietoperza wampira (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Nekromanta pije krew trupa (zmarłego nie dawniej niż godzinę temu) i leczy własne rany, przywracając sobie 1k10 punktów Żywotności.',
                'casting_number' => 11,
                'talent_id' => $talents['Magia czarnoksięska (tradycja nekromancji)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja nekromancji',
                'name' => 'Śmiertelny dotyk',
                'casting_duration' => 'akcja',
                'ingredient' => 'dłoń mordercy (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Czar dotykowy raniący ciało przeciwnika. Ofiara traci 1k10 punktów Żywotności bez względu na Wytrzymałość i zbroję. Nie działa na ożywieńców.',
                'casting_number' => 13,
                'talent_id' => $talents['Magia czarnoksięska (tradycja nekromancji)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja nekromancji',
                'name' => 'Zew Vanhela',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'niewielka srebrna trąbka (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Napełnia energią Dhar 1k10 kontrolowanych ożywieńców (szkieletów, zombi lub upiorów), pozwalając im natychmiast wykonać akcję „ruch” lub „zwykły atak”.',
                'casting_number' => 15,
                'talent_id' => $talents['Magia czarnoksięska (tradycja nekromancji)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja nekromancji',
                'name' => 'Przyzwanie ducha',
                'casting_duration' => 'akcja',
                'ingredient' => 'kawałek drewna ze zbezczeszczonej trumny (+2)',
                'effect_duration' => '24 godziny',
                'description' => 'Nekromanta przyzywa i zmusza do posłuszeństwa ducha, widmo lub zjawę w odległości do 24 metrów (wymagany nieudany test Siły Woli ożywieńca).',
                'casting_number' => 17,
                'talent_id' => $talents['Magia czarnoksięska (tradycja nekromancji)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja nekromancji',
                'name' => 'Martwica',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'kawałek ciała upiora (+3)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Ciało nekromanty zyskuje twardość trupa. Wartość Krytyczna każdego otrzymanego trafienia krytycznego zmniejsza się o wartość Magii czarnoksiężnika.',
                'casting_number' => 19,
                'talent_id' => $talents['Magia czarnoksięska (tradycja nekromancji)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja nekromancji',
                'name' => 'Ożywienie umarłych',
                'casting_duration' => '2 akcje podwójne',
                'ingredient' => 'pył z mumii (+3)',
                'effect_duration' => 'stały (wymaga kontroli)',
                'description' => 'Potężniejsza wersja ożywienia – nekromanta ożywia 2k10 szkieletów lub zombi z dowolnych zwłok w odległości do 24 metrów.',
                'casting_number' => 22,
                'talent_id' => $talents['Magia czarnoksięska (tradycja nekromancji)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja nekromancji',
                'name' => 'Przebudzenie upiora',
                'casting_duration' => '2 akcje podwójne',
                'ingredient' => 'korona z żelaza hartowanego w ludzkiej krwi (+3)',
                'effect_duration' => 'stały (wymaga kontroli)',
                'description' => 'Nekromanta ożywia upiora. Zwłoki muszą należeć do postaci, która za życia wykonywała profesję zaawansowaną.',
                'casting_number' => 24,
                'talent_id' => $talents['Magia czarnoksięska (tradycja nekromancji)']
            ],
            [
                'type' => 'CZARNOKSIĘSKA',
                'specialization' => 'Tradycja nekromancji',
                'name' => 'Z prochu powstałeś...',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'fiolka wypełniona wodą święconą (+3)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Tworzy wir wysysający energię z ożywieńców (zasięg 48m). Ożywieńcy w promieniu 5 metrów otrzymują trafienie z Siłą 5; szkielety i zombi są niszczone automatycznie.',
                'casting_number' => 26,
                'talent_id' => $talents['Magia czarnoksięska (tradycja nekromancji)']
            ],
        ];
        $clericalSpells = [
            // --- DZIEDZINA MANNANA ---
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Mananna',
                'name' => 'Błogosławiony rejs',
                'casting_duration' => '1 minuta',
                'ingredient' => 'flaszka wina (+1)',
                'effect_duration' => 'jedna podróż morska, do momentu zawinięcia do portu',
                'description' => 'Kapłan modli się do boga o przychylność i opiekę. Tak długo, jak na pokładzie statku znajduje się kapłan, wszystkie testy nawigacji wykonywane są z modyfikatorem +10.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Mananna)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Mananna',
                'name' => 'Oddychanie pod wodą',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'żywa ryba (+1)',
                'effect_duration' => '1 godzina',
                'description' => 'Dotyk kapłana powoduje, że dowolna postać może oddychać pod wodą. Jest to czar dotykowy. Kapłan może go rzucić również na siebie.',
                'casting_number' => 7,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Mananna)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Mananna',
                'name' => 'Bicz wodny',
                'casting_duration' => 'akcja',
                'ingredient' => 'fiolka wypełniona wodą morską (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Z wyciągniętych dłoni kapłana tryskają strumienie morskiej wody. Uderzają w dowolnego przeciwnika znajdującego się w odległości do 36 metrów. Jest to magiczny pocisk o Sile 4. Trafiona postać musi wykonać test Krzepy. Nieudany test oznacza, że pada na ziemię.',
                'casting_number' => 10,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Mananna)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Mananna',
                'name' => 'Chodzenie po wodzie',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'wyschnięty bicz wodny (+2)',
                'effect_duration' => 'liczba minut równa wartości Magii kapłana',
                'description' => 'Bóg morza tchnie w kapłana moc chodzenia po wodzie, mokradłach i bagnach. W trakcie trwania czaru kapłan może poruszać się w takim terenie tak, jakby stąpał po twardym gruncie.',
                'casting_number' => 14,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Mananna)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Mananna',
                'name' => 'Cisza morska',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'sztylet z fiszbinu wieloryba (+2)',
                'effect_duration' => '1 godzina',
                'description' => 'Kapłan modli się do Mannana, prosząc go o łaskę. Kapłan wskazuje dowolny statek znajdujący się w odległości do 96 metrów. W jego pobliżu natychmiast cichnie wiatr. Żagle opadają bezwładnie. Jeśli statek nie ma wioseł, to do końca trwania czaru stoi w miejscu lub dryfuje, znoszony prądami.',
                'casting_number' => 16,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Mananna)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Mananna',
                'name' => 'Klątwa albatrosa',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'pióro albatrosa (+2)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Kapłan kieruje klątwę Mannana przeciwko swoim wrogom znajdującym się w odległości do 48 metrów. Wszystkie postacie w promieniu 5 metrów wokół wskazanego miejsca zostają objęte czarem. W trakcie jego trwania Wartość Krytyczna wszystkich trafień krytycznych zadanych tym postaciom zwiększa się o 2.',
                'casting_number' => 19,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Mananna)']
            ],

            // --- DZIEDZINA MORRA ---
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Morra',
                'name' => 'Mumifikacja',
                'casting_duration' => '1 minuta',
                'ingredient' => 'kawałek świeżego owocu (+1)',
                'effect_duration' => '24 godziny',
                'description' => 'Kapłan na pewien czas powstrzymuje proces gnicia zwłok. W trakcie trwania czaru poddane mumifikacji zwłoki nie mogą zostać ożywione za pomocą magii nekromanckiej.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Morra)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Morra',
                'name' => 'Znak kruka',
                'casting_duration' => 'akcja',
                'ingredient' => 'krucze pióro (+1)',
                'effect_duration' => '1 runda',
                'description' => 'Kapłan przywołuje symbol Morra – widmowego kruka – który spowija go cieniem śmierci. W trakcie trwania czaru kapłan, a także wszyscy jego towarzysze znajdujący się w odległości do 12 metrów, zadają obrażenia zwiększone o 1.',
                'casting_number' => 9,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Morra)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Morra',
                'name' => 'Posłaniec ze snu',
                'casting_duration' => '1 minuta',
                'ingredient' => 'kłębek wełny (+1)',
                'effect_duration' => '30 sekund (3 rundy)',
                'description' => 'Kapłan objawia się we śnie wybranej postaci. Wizja trwa 30 sekund. W tym czasie kapłan może przekazać dowolną wiadomość. Odbiorca musi być osobą, którą kapłan spotkał osobiście, musi rozumieć język, w którym przemawia kapłan i, oczywiście, musi spać w momencie rzucenia zaklęcia.',
                'casting_number' => 10,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Morra)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Morra',
                'name' => 'Wieczny odpoczynek',
                'casting_duration' => 'akcja',
                'ingredient' => 'drewniany kołek (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Kapłan modli się do boga, prosząc go, by uspokoił ożywionego trupa. Dotyk kapłana zadaje ożywieńcowi trafienie z Siłą 8. Jest to czar dotykowy.',
                'casting_number' => 13,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Morra)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Morra',
                'name' => 'Wizja Morra',
                'casting_duration' => '1 minuta',
                'ingredient' => 'grzyb zebrany z mogiły (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Kapłan modli się do Morra, prosząc go o objawienie i wyjaśnienie zaistniałego problemu lub zagadki. Udany test ogłady oznacza, że kapłan otrzymuje wizję, która zawiera pomocne wskazówki.',
                'casting_number' => 15,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Morra)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Morra',
                'name' => 'Letarg',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'mała srebrna kosa (+2)',
                'effect_duration' => '1k10 rund',
                'description' => 'Kapłan wykorzystuje moc Morra, by uśpić dowolną grupę osób znajdujących się w odległości do 24 metrów. Wszystkie postacie w promieniu 5 metrów wokół wskazanego miejsca muszą wykonać test Siły Woli. Nieudany test oznacza, że zapadają w sen, który jest nie do odróżnienia od śmierci. W trakcie trwania czaru postacie są traktowane jako bezbronne.',
                'casting_number' => 20,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Morra)']
            ],
        ];
        $myrmidiaSpells = [
            // --- DZIEDZINA MYRMIDII ---
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Myrmidii',
                'name' => 'Włócznia Myrmidii',
                'casting_duration' => 'akcja',
                'ingredient' => 'osełka (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Włócznia trzymana przez kapłana zostaje napełniona mocą bóstwa. W trakcie trwania czaru jest traktowana jako broń magiczna z cechą oręża „przebijający zbroję”.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Myrmidii)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Myrmidii',
                'name' => 'Geniusz taktyczny',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'buława (+1)',
                'effect_duration' => 'liczba minut równa wartości Magii kapłana',
                'description' => 'Kapłan otacza się aurą męstwa. Otrzymuje +20 do testów dowodzenia i nauki (taktyka/strategia). Towarzysze w promieniu 12 metrów mogą powtórzyć każdy nieudany test Strachu lub Grozy.',
                'casting_number' => 7,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Myrmidii)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Myrmidii',
                'name' => 'Waleczność',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'wiązka patyków (+1)',
                'effect_duration' => 'liczba rund równa wartości Magii kapłana',
                'description' => 'Kapłan natchnie sprzymierzeńców maestrią bojową. Towarzysze w promieniu 24 metrów otrzymują modyfikator +10 do Walki Wręcz.',
                'casting_number' => 10,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Myrmidii)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Myrmidii',
                'name' => 'Błyskawiczny cios',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'amulet z wygrawerowaną błyskawicą (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Kapłan otrzymuje +1 do Ataków i może wykonywać atak wielokrotny, poświęcając na to zwykłą akcję (wciąż obowiązuje ograniczenie do jednej akcji „atak” w rundzie).',
                'casting_number' => 14,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Myrmidii)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Myrmidii',
                'name' => 'Groza wojny',
                'casting_duration' => 'akcja',
                'ingredient' => 'miedziana maska (+2)',
                'effect_duration' => 'liczba rund równa wartości Magii kapłana',
                'description' => 'Kapłan staje się przerażającym przeciwnikiem. Za każdym razem, gdy wykona udany atak w walce wręcz, jego przeciwnik musi wykonać test Grozy.',
                'casting_number' => 16,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Myrmidii)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Myrmidii',
                'name' => 'Tarcza Myrmidii',
                'casting_duration' => '3 akcje',
                'ingredient' => 'tarcza (+2)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Wszyscy sprzymierzeńcy w promieniu 24 metrów otrzymują +1 Punkt Zbroi na wszystkich lokacjach ciała (do maksymalnie 5 PZ na lokację).',
                'casting_number' => 20,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Myrmidii)']
            ],
        ];
        $ranaldSpells = [
            // --- DZIEDZINA RANALDA ---
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Ranalda',
                'name' => 'Spryt Ranalda',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'kocia skórka (+1)',
                'effect_duration' => 'liczba minut równa wartości Magii kapłana',
                'description' => 'Ranald obdarza kapłana niesamowitą zręcznością. W trakcie trwania czaru kapłan otrzymuje modyfikator +20 do testów ukrywania się i skradania się. Jeżeli przejdzie obok miejsca, na które został nałożony magiczny alarm, może wykonać test splatania magii. Udany test oznacza, że kapłan przemyka się niepostrzeżenie przez chroniony obszar.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Ranalda)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Ranalda',
                'name' => 'Ślepy traf',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'królicza łapka (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Kapłan obdarza dowolną postać łaską Ranalda. Gracz może odwrócić kolejność kostek po jednym teście cechy lub umiejętności, który jego Bohater wykona w ciągu trwania czaru. Na przykład wynik testu ukrywania się wynoszący 82 może zostać zamieniony na 28. Jest to czar dotykowy. Kapłan może go rzucić również na siebie. Postać może znajdować się pod wpływem tylko jednego ślepego trafu w danej chwili.',
                'casting_number' => 7,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Ranalda)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Ranalda',
                'name' => 'Otwarcie',
                'casting_duration' => 'akcja',
                'ingredient' => 'klucz (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Kapłan wykorzystuje magiczną moc do otwarcia dowolnego zamka, rygla lub skobla znajdującego się w odległości do 2 metrów. W trakcie trwania czaru nikt, oprócz kapłana, nie może go zamknąć. Udany test splatania magii pozwala kapłanowi przełamać moc zaklęcia magiczne zamknięcie.',
                'casting_number' => 9,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Ranalda)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Ranalda',
                'name' => 'Urok',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'kłębek wełny (+1)',
                'effect_duration' => '1 runda',
                'description' => 'Kapłan wykorzystuje moc oszukańczego bóstwa, by zmusić do posłuszeństwa dowolną istotę humanoidalną (człowieka, elfa, orka, zwierzoczłeka, itp.), znajdującą się w odległości do 24 metrów. Ofiara może odeprzeć czar, wykonując udany test Siły Woli. Nieudany test oznacza, że w następnej rundzie jej poczynaniami kieruje kapłan. Zaklęcie urok nie działa na demony i ożywieńców.',
                'casting_number' => 14,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Ranalda)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Ranalda',
                'name' => 'Wykrycie pułapek',
                'casting_duration' => '2 akcje podwójne',
                'ingredient' => 'oczy sokoła (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Kapłan koncentruje się i magicznie sonduje najbliższą okolicę. Automatycznie wykrywa wszystkie pułapki w promieniu 12 metrów. Zaklęcie nie rozbraja pułapek, a jedynie informuje o ich istnieniu i położeniu.',
                'casting_number' => 16,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Ranalda)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Ranalda',
                'name' => 'Uśmiech losu',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'posrebrzane kości do wróżenia (+2)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Zaklęcie działa podobnie jak ślepy traf, z tą różnicą, że obejmuje działaniem kapłana i wszystkich jego towarzyszy znajdujących się w odległości do 24 metrów.',
                'casting_number' => 20,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Ranalda)']
            ],
        ];
        $shallyaSpells = [
            // --- DZIEDZINA SHALLYI ---
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Shallyi',
                'name' => 'Zwalczanie trucizny',
                'casting_duration' => 'akcja',
                'ingredient' => 'kieł żmii (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Dotyk kapłana uzdrawia postać cierpiącą z powodu zatrucia. Cały jad w ciele postaci zostaje zneutralizowany, a wszelkie jego efekty ustają. Zaklęciem nie można wskrzesić postaci zmarłej na skutek zatrucia. Jest to czar dotykowy.',
                'casting_number' => 4,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Shallyi)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Shallyi',
                'name' => 'Leczenie ran',
                'casting_duration' => 'akcja',
                'ingredient' => 'pijawka (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Dotyk kapłana uzdrawia dowolną postać, przywracając jej 1k10 punktów Żywotności plus tyle, ile wynosi wartość Magii kapłana. Jest to czar dotykowy. Kapłan może go rzucić również na siebie.',
                'casting_number' => 6,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Shallyi)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Shallyi',
                'name' => 'Leczenie choroby',
                'casting_duration' => '3 akcje',
                'ingredient' => 'kompres (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Kapłan uzdrawia dowolną chorą postać. Wszystkie efekty choroby natychmiast ustają. Zaklęciem nie można wskrzesić postaci, która zmarła na skutek choroby. Jest to czar dotykowy.',
                'casting_number' => 11,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Shallyi)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Shallyi',
                'name' => 'Męczeństwo',
                'casting_duration' => 'akcja',
                'ingredient' => 'kosmyk włosów postaci będącej celem czaru (+2)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Kapłan nawiązuje magiczną więź z dowolną postacią znajdującą się w odległości do 24 metrów. W trakcie trwania czaru wszystkie obrażenia zadawane tej postaci zostają przeniesione na kapłana.',
                'casting_number' => 14,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Shallyi)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Shallyi',
                'name' => 'Oczyszczenie',
                'casting_duration' => 'akcja',
                'ingredient' => 'płonąca pochodnia (+2)',
                'effect_duration' => '1 runda',
                'description' => 'Kapłan przyzywa oczyszczającą moc Shallyi i kieruje ją przeciwko dowolnemu demonowi lub wyznawcy Nurgla znajdującemu się w odległości do 48 metrów. Ofiara natychmiast traci 1k10 punktów Żywotności, bez względu na Wytrzymałość i noszoną zbroję. Musi także wykonać test Siły Woli. Nieudany test oznacza, że postać zostaje ogłuszona na 1 rundę.',
                'casting_number' => 16,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Shallyi)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Shallyi',
                'name' => 'Leczenie obłędu',
                'casting_duration' => '1 godzina',
                'ingredient' => 'kropidło (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Łaska bogini napełnia kapłana mocą oczyszczania umysłów. Jego dotyk uzdrawia dowolną obłąkaną postać, lecząc ją z jednej choroby umysłu. Jest to czar dotykowy.',
                'casting_number' => 20,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Shallyi)']
            ],
        ];
        $sigmarSpells = [
            // --- DZIEDZINA SIGMARA ---
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Sigmara',
                'name' => 'Młot Sigmara',
                'casting_duration' => 'akcja',
                'ingredient' => 'amulet z wyrytym symbolem Sigmara (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Kapłan napełnia potęgą Sigmara zwykły młot bojowy. W trakcie trwania czaru młot traktowany jest jako broń magiczna z cechą oręża „druzgocący”.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Sigmara)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Sigmara',
                'name' => 'Pancerz prawości',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'żelazny pierścień (+1)',
                'effect_duration' => 'liczba minut równa wartości Magii kapłana',
                'description' => 'Kapłan otacza się ochronną aurą mocy. Otrzymuje +1 Punkt Zbroi na wszystkich lokacjach ciała (wciąż obowiązuje go ograniczenie do 5 PZ na każdej lokacji).',
                'casting_number' => 6,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Sigmara)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Sigmara',
                'name' => 'Kojący dotyk',
                'casting_duration' => '3 akcje',
                'ingredient' => 'skórzana rękawica (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Dotyk kapłana przywraca rannej postaci 1k10 punktów Żywotności. Jest to czar dotykowy. Kapłan może go rzucić również na siebie.',
                'casting_number' => 12,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Sigmara)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Sigmara',
                'name' => 'Aura odwagi',
                'casting_duration' => 'akcja',
                'ingredient' => 'pryzmat (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Majestat Sigmara przepełnia kapłana, który zaczyna jaśnieć aurą męstwa i niezłomnej wiary. Na jego widok wszyscy towarzysze ogarnięci Strachem lub Grozą opanowują się i mogą działać normalnie.',
                'casting_number' => 14,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Sigmara)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Sigmara',
                'name' => 'Kometa Sigmara',
                'casting_duration' => 'akcja',
                'ingredient' => 'złoty grot strzały (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Kapłan zbiera moc i kształtuje ją w płonący pocisk, który przybiera postać symbolu Sigmara. Miniaturowa kometa o dwóch ogonach uderza w dowolną postać znajdującą się w odległości do 24 metrów. Jest to magiczny pocisk o Sile 6.',
                'casting_number' => 16,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Sigmara)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Sigmara',
                'name' => 'Płomień wiary',
                'casting_duration' => 'akcja',
                'ingredient' => 'złoty amulet z wizerunkiem komety o dwóch ogonach (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Kapłan przyzywa gniew Sigmara, otaczając się płomieniami, które go nie ranią. Wszystkie postacie w promieniu 10 metrów wokół niego otrzymują trafienie z Siłą 3. Demony i ożywieńcy znajdujący się w zasięgu płomieni otrzymują trafienie z Siłą 5. Od otrzymanych obrażeń nie odejmuje się Punktów Zbroi.',
                'casting_number' => 20,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Sigmara)']
            ],
        ];
        $taalRhyaSpells = [
            // --- DZIEDZINA TAALA I RHYI ---
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Taala i Rhyi',
                'name' => 'Przyjaciel zwierząt',
                'casting_duration' => '3 akcje',
                'ingredient' => 'język zwierzęcia (+1)',
                'effect_duration' => '10 minut',
                'description' => 'Dzięki pomocy Taala kapłan może porozumiewać się z dowolnym zwierzęciem znajdującym się w odległości do 12 metrów. W trakcie trwania czaru kapłan otrzymuje modyfikator +20 do testów oswajania zwierzęcia, z którym rozmawia. Zwierzęta nie są przyzwyczajone do pogawędek z ludźmi i w związku z tym mają kłopoty z wyrażaniem swych myśli. Jako Mistrz Gry, to Ty decydujesz, co zwierzę ma do powiedzenia. Musisz jednak pamiętać, że zwierzę inaczej postrzega świat i nie jest zdolne do myślenia abstrakcyjnego.',
                'casting_number' => 4,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Taala i Rhyi)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Taala i Rhyi',
                'name' => 'Rączy jeleń',
                'casting_duration' => 'akcja',
                'ingredient' => 'kłębek sierści jelenia (+1)',
                'effect_duration' => 'liczba minut równa wartości Magii kapłana',
                'description' => 'Kapłan czuje w sobie moc dzikiego jelenia. W trakcie trwania czaru otrzymuje +1 do Szybkości i może wykonywać „szarżę” w ramach akcji zwykłej, a nie podwójnej.',
                'casting_number' => 6,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Taala i Rhyi)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Taala i Rhyi',
                'name' => 'Oplatanie',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'winorośl (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Kapłan przyzywa moc drzemiącą w roślinach. W dowolnym miejscu, znajdującym się w odległości do 48 metrów, z ziemi wyrastają pnącza. Wszystkie postacie w promieniu 5 metrów wokół wskazanego miejsca muszą wykonać test Krzepy. Nieudany test oznacza, że w trakcie trwania czaru nie mogą się ruszać, unieruchomieni przez pnącza. Udany test umożliwia im poruszanie się z połową Szybkości. Każdy, kto wejdzie na wskazany obszar, podlega działaniu zaklęcia.',
                'casting_number' => 8,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Taala i Rhyi)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Taala i Rhyi',
                'name' => 'Grzmot',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'niewielki gong (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Kapłan przyzywa moc burzy. W dowolnym miejscu w odległości do 48 metrów rozlega się ogłuszający grzmot. Wszystkie postacie w promieniu 5 metrów wokół wskazanego miejsca muszą wykonać test Odporności. Nieudany test oznacza, że zostają ogłuszone na 1 rundę. Odgłos grzmotu słyszalny jest w promieniu 1 kilometra.',
                'casting_number' => 12,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Taala i Rhyi)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Taala i Rhyi',
                'name' => 'Siła niedźwiedzia',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'niedźwiedzi pazur (+2)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Moc Taala przepływa przez kapłana, który może obdarzyć dowolną postać siłą niedźwiedzia górskiego. W trakcie trwania czaru wzmocniona postać otrzymuje modyfikator +20 do Krzepy (czyli także +2 do Siły). Jest to czar dotykowy. Kapłan może go rzucić również na siebie.',
                'casting_number' => 15,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Taala i Rhyi)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Taala i Rhyi',
                'name' => 'Ukojenie Rhyi',
                'casting_duration' => '1 minuta',
                'ingredient' => 'kubek świeżego mleka (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Kapłan błaga Matkę Ziemię, by wspomogła w potrzebie swoje dzieci. Wszyscy towarzysze kapłana, znajdujący się w odległości do 10 metrów, czują się odświeżeni i wypoczęci, jakby odpoczywali przez trzy dni. Odzyskują punkty Żywotności, zgodnie ze zwykłymi zasadami odpoczynku.',
                'casting_number' => 18,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Taala i Rhyi)']
            ],
        ];
        $ulricSpells = [
            // --- DZIEDZINA ULRYKA ---
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Ulryka',
                'name' => 'Zimowy chłód',
                'casting_duration' => 'akcja',
                'ingredient' => 'kłębek zwierzęcej sierści (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Kapłan zaczyna emanować przenikającym do szpiku kości zimnem. W trakcie trwania czaru wszystkie atakujące go postacie otrzymują modyfikator -10 do Walki Wręcz.',
                'casting_number' => 5,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Ulryka)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Ulryka',
                'name' => 'Bitewny szał',
                'casting_duration' => 'akcja podwójna',
                'ingredient' => 'odrobina świeżej krwi (+1)',
                'effect_duration' => 'liczba minut równa wartości Magii kapłana',
                'description' => 'W kapłana wstępuje duch Ulryka, wzbudzając w nim żądzę krwi. W trakcie trwania czaru kapłan otrzymuje +1 do Ataków, ale zawsze musi atakować najbliższego przeciwnika (wykonując „szarżę”, „szaleńczy atak” lub „atak wielokrotny”) i nie może uciekać ani wykonywać akcji „odwrót”.',
                'casting_number' => 7,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Ulryka)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Ulryka',
                'name' => 'Wilczy zew',
                'casting_duration' => 'akcja',
                'ingredient' => 'język wilka (+2)',
                'effect_duration' => '1 runda',
                'description' => 'Kapłan wyje niczym jeden z wilków Ulryka, wzbudzając w towarzyszach żądzę mordu. W trakcie trwania czaru wszyscy towarzysze kapłana w promieniu 24 metrów wokół niego mogą atakować dwukrotnie w czasie „szarży”, niezależnie od posiadanej liczby Ataków.',
                'casting_number' => 11,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Ulryka)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Ulryka',
                'name' => 'Dar Ulryka',
                'casting_duration' => '3 akcje',
                'ingredient' => 'topór (+2)',
                'effect_duration' => '1 godzina',
                'description' => 'Ulryk obdarza jednego z towarzyszy kapłana siłą i odwagą godną berserkera. W trakcie trwania czaru postać, która stała się jego celem, jest traktowana, jakby posiadała zdolność szał bojowy. Jest to czar dotykowy. Kapłan nie może go rzucić na siebie.',
                'casting_number' => 15,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Ulryka)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Ulryka',
                'name' => 'Wilcze serce',
                'casting_duration' => '3 akcje',
                'ingredient' => 'serce wilka (+2)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Kapłan napełnia bitewnym duchem swoich towarzyszy. W trakcie trwania czaru są oni odporni na Strach i Grozę oraz na efekty umiejętności zastraszanie i zdolności niepokojący.',
                'casting_number' => 18,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Ulryka)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Ulryka',
                'name' => 'Lodowa zamieć',
                'casting_duration' => 'akcja',
                'ingredient' => 'sopel lodu (+2)',
                'effect_duration' => '1 runda',
                'description' => 'Kapłan przyzywa gwałtowną burzę lodową w dowolnym miejscu w odległości do 24 metrów. Wszystkie postacie w promieniu 5 metrów wokół wskazanego miejsca otrzymują trafienie z Siłą 5 i muszą wykonać test Siły Woli pod groźbą ogłuszenia na jedną rundę.',
                'casting_number' => 20,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Ulryka)']
            ],
        ];
        $verenaSpells = [
            // --- DZIEDZINA VERENY ---
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Vereny',
                'name' => 'Kajdany Vereny',
                'casting_duration' => 'akcja',
                'ingredient' => 'żelazne kajdany (+1)',
                'effect_duration' => 'do momentu uwolnienia się',
                'description' => 'Czar tworzy niewidzialne kajdany, które unieruchamiają dowolną postać w zasięgu wzroku kapłana. Ofiara może odeprzeć czar, wykonując udany test Siły Woli. Nieudany test oznacza, że jest traktowana jako bezbronna. Nie może wykonywać żadnych akcji, za wyjątkiem próby rozerwania więzów. Jest to rozstrzygane za pomocą przeciwstawnego testu jej Krzepy i zdolności splatania magii kapłana Vereny.',
                'casting_number' => 6,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Vereny)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Vereny',
                'name' => 'Wizja przeszłości',
                'casting_duration' => '1 minuta',
                'ingredient' => 'oczy sowy (+1)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Kapłan koncentruje się i dotyka przedmiotu, próbując poznać jego historię. Może dowiedzieć się o trzech najważniejszych zdarzeniach należących do historii przedmiotu (ich ustalenie należy do MG). Zwykle są to informacje dotyczące osoby, która wykonała przedmiot, jego najważniejszych właścicieli lub znaczących wydarzeń, podczas których został użyty. Ponowne rzucenie tego czaru na ten sam przedmiot nie przynosi żadnego efektu.',
                'casting_number' => 8,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Vereny)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Vereny',
                'name' => 'Miecz sprawiedliwości',
                'casting_duration' => 'akcja',
                'ingredient' => 'amulet z wygrawerowaną wagą (+1)',
                'effect_duration' => '1 minuta (6 rund)',
                'description' => 'Gdy zawodzą inne możliwości, tylko surowa kara może przywrócić poczucie sprawiedliwości. W trakcie trwania czaru miecz kapłana staje się narzędziem Vereny. Jest traktowany jako broń magiczna z cechą oręża „precyzyjny”. Kapłan otrzymuje dodatkowo modyfikator +10 do Walki Wręcz, gdy tą bronią atakuje winnego popełnionej zbrodni (kapłan musi być przekonany o winie przeciwnika).',
                'casting_number' => 10,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Vereny)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Vereny',
                'name' => 'Prawdomówność',
                'casting_duration' => '2 akcje podwójne',
                'ingredient' => 'zwierciadło (+2)',
                'effect_duration' => 'natychmiastowy',
                'description' => 'Kapłan zadaje jedno pytanie wybranej postaci. Musi ona rozumieć język, którym posługuje się kapłan. Postać może odeprzeć czar, wykonując udany test Siły Woli. Nieudany test oznacza, że musi zgodnie z prawdą odpowiedzieć na zadane pytanie. Postać odpowiada zgodnie ze stanem swojej wiedzy i przekonaniami. Jeśli celem czaru jest BN, test Siły Woli wykonuje się w tajemnicy. Udany test oznacza, że postać może udzielić nieprawdziwej odpowiedzi lub wcale nie musi odpowiadać. Kolejne rzucenie czaru na tę samą postać wymaga zadania znacząco innego pytania.',
                'casting_number' => 13,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Vereny)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Vereny',
                'name' => 'Podsłuchiwanie',
                'casting_duration' => '1 minuta',
                'ingredient' => 'tuba przyłożona do ucha (+2)',
                'effect_duration' => 'liczba minut równa wartości Magii kapłana',
                'description' => 'Kapłan może podsłuchać wszystko, co dzieje się w dowolnie wybranym miejscu w zasięgu jego wzroku. W trakcie trwania czaru słyszy wszystkie dźwięki i odgłosy tak wyraźnie, jakby stał we wskazanym miejscu.',
                'casting_number' => 15,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Vereny)']
            ],
            [
                'type' => 'KAPŁAŃSKA',
                'specialization' => 'Dziedzina Vereny',
                'name' => 'Próba ognia',
                'casting_duration' => '1 minuta',
                'ingredient' => 'opal ognisty o wartości przynajmniej 50 zk (+2)',
                'effect_duration' => 'liczba rund równa wartości Magii kapłana',
                'description' => 'Kapłan poddaje ostatecznej próbie dowolną postać znajdującą się w odległości do 12 metrów. Jeśli postać jest niewinna, płomienie gasną po jednej rundzie bez rzadania obrażeń. W przeciwnym razie płonąca postać co rundę otrzymuje trafienie z Siłą 8 (obrażenia od ognia). Czar używany niezwykle rzadko i tylko wobec największych zbrodniarzy.',
                'casting_number' => 18,
                'talent_id' => $talents['Magia kapłańska (Dziedzina Vereny)']
            ],
        ];

        Spell::upsert($pettySpells, ['name']);
        Spell::upsert($commonSpells, ['name']);
        Spell::upsert($arcaneShadowSpells, ['name']);
        Spell::upsert($metalSpells, ['name']);
        Spell::upsert($celestialSpells, ['name']);
        Spell::upsert($fireSpells, ['name']);
        Spell::upsert($deathSpells, ['name']);
        Spell::upsert($lightSpells, ['name']);
        Spell::upsert($beastSpells, ['name']);
        Spell::upsert($lifeSpells, ['name']);
        Spell::upsert($chaosSpells, ['name']);
        Spell::upsert($necromancySpells, ['name']);
        Spell::upsert($clericalSpells, ['name']);
        Spell::upsert($myrmidiaSpells, ['name']);
        Spell::upsert($ranaldSpells, ['name']);
        Spell::upsert($shallyaSpells, ['name']);
        Spell::upsert($sigmarSpells, ['name']);
        Spell::upsert($taalRhyaSpells, ['name']);
        Spell::upsert($ulricSpells, ['name']);
        Spell::upsert($verenaSpells, ['name']);
    }
}
