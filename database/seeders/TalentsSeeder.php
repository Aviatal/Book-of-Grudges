<?php

namespace Database\Seeders;

use App\Models\Talent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TalentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Talent::insert([
            ['name' => 'Strzał mierzony', 'description' => 'Wykonując atak bronią strzelecką, Bohater potrafi skoncentrować się i znacznie lepiej wycelować. Po zadeklarowaniu akcji "wycelowanie" otrzymuje modyfikator +20 do Umiejętności Strzeleckich przy rzucie na trafienie, zamiast normalnego modyfikatora +10.'],
            ['name' => 'Strzał precyzyjny', 'description' => 'Wykonując atak bronią strzelecka, Bohater potrafi precyzyjnie wymierzyć strzał, który zadaje dodatkowe obrażenia. Otrzymuje modyfikator +1 do rzutów na obrażenia podczas ataku z użyciem broni strzeleckiej.'],
            ['name' => 'Strzał przebijający', 'description' => 'Bohater potrafi znaleźć odsłoniętą szczelinę w pancerzu przeciwnika. Po udanym ataku bronią strzelecką może zignorować 1 Punkt Zbroi przeciwnika. Jeśli przeciwnik nie nosi zbroi, ta zdolność jest nieskuteczna.'],
            ['name' => 'Strzelec wyborowy', 'description' => 'Bohater potrafi wyjątkowo celnie strzelać. Otrzymuje +5 do Umiejętności Strzeleckich, dodawane do początkowej wartości cechy.'],
            ['name' => 'Szał bojowy', 'description' => 'W trakcie walki Bohater potrafi wpaść w szał bojowy. Przez jedną rundę musi się doprowadzić do wściekłości (wyjąc, gryząc tarczę, bijąc się w piersi, itd.). W następnej rundzie jego pierwotna natura bierze górę, zapewniając modyfikator +10 do testów Siły Woli i Krzepy, (także +1 do Siły, czyli zadawanych obrażeń), przy jednoczesnym modyfikatorze -10 do testów Inteligencji i Walki Wręcz. Podczas walki zawsze atakuje najbliższego przeciwnika, wykonując „szarżę" lub „szaleńczy atak", nie może uciekać ani wykonywać akcji „odwrót". BG pozostaje pod wpływem szału bojowego do końca starcia.'],
            ['name' => 'Szczęście', 'description' => 'Bohater jest nieprawdopodobnym szczęściarzem. Wydaje się wychodzić obronną ręką z najgorszych opresji. Każdego dnia dostaje dodatkowy Punkt Szczęścia. Szczegółowe zasady przyznawania i wydawania Punktów Szczęścia znajdziesz w Rozdziale VI: Walka, obrażenia i ruch.'],
            ['name' => 'Szósty zmysł', 'description' => 'Bohater jest niezwykle czujny i niemal instynktownie wyczuwa zagrożenie. W przypadku zasadzki lub innego zagrożenia Mistrz Gry może wykonać tajny test Siły Woli Bohatera. Jeśli test okaże się udany, Mistrz Gry powinien Cię poinformować, ze Twój Bohater ma złe przeczucia albo wrażenie, że jest obserwowany. Dzięki temu BG obdarzony szóstym zmysłem może uniknąć zaskoczenia w początkowej fazie walki.'],
            ['name' => 'Szybki refleks', 'description' => 'Bohater jest obdarzony wspaniałym refleksem. Otrzymuje +5 do Zręczności, dodawane do początkowej wartości cechy.'],
            ['name' => 'Szybkie wyciągnięcie', 'description' => 'Dzięki tej zdolności Bohater potrafi szybko zareagować, błyskawicznie dobywając broni lub wyciągając inny przedmiot zza pasa lub z kieszeni. Raz na rundę może użyć akcji „użycie przedmiotu" jako akcji natychmiastowej.'],
            ['name' => 'Magia powszechna', 'description' => 'Bohater potrafi rzucać zaklęcia, które są powszechnie używane przez czarodziejów i kapłanów. Magia powszechna obejmuje wybór czarów, z których każdy jest osobną zdolnością. Na przykład magia powszechna (rozproszenie magii) i magia powszechna (podniebny chód) to odrębne zdolności. Najczęściej wykorzystywane czary z magii powszechnej to: dotyk na odległość, magiczny alarm, magiczna broń, magiczny zamek, pancerz Eteru, podniebny chód, rozproszenie magii oraz uciszenie. Szczegółowe informacje na temat magii powszechnej i opis poszczególnych czarów znajdziesz w Rozdziale VII: Magia. Zdolność magia powszechna wymaga uprzedniego posiadania zdolności magia prosta.'],
            ['name' => 'Magia prosta', 'description' => 'Bohater poznał podstawowe tajniki rzucania czarów. Magia prosta, podobnie jak magia tajemna, obejmuje kilka zdolności. Każdy rodzaj magii prostej jest traktowany jako oddzielna zdolność magiczna. Na przykład magia prosta (tajemna) i magia prosta (kapłańska) to odrębne zdolności. Znajomość dowolnego rodzaju magii prostej i posiadanie cechy Magia przynajmniej na poziomie 1 pozwala na rzucanie czarów z odpowiedniej listy zaklęć. Istnieją trzy podstawowe rodzaje magii prostej: gusła, kapłańska i tajemna. Szczegółowe zasady rzucania zaklęć znajdziesz w Rozdziale VII: Magia.'],
            ['name' => 'Magia tajemna', 'description' => 'Bohater poznał tajniki jednej z kilku tradycji magicznych znanych w Imperium. Magia tajemna wymaga absolutnego poświęcenia i długotrwałych studiów. W konsekwencji Bohater musi dokonać wyboru jednej z dostępnych tradycji i nie będzie mógł uczyć się innych. Każdy rodzaj magii tajemnej jest oddzielną zdolnością. Na przykład magia tajemna (zwierzęta) różni się od magii tajemnej (ogień). W Imperium istnieje osiem Kolegiów Magii, z których każde naucza odrębnej Tradycji, czyli odmiennej magii tajemnej. Są to Tradycje: Cienia, Metalu, Niebios, Ognia, Śmierci, Światła, Zwierząt i Życia. Znajomość magii tajemnej pozwala na rzucanie czarów należących do określonej tradycji. Szczegółowe zasady rzucania zaklęć znajdziesz w Rozdziale VII: Magia.'],
            ['name' => 'Medytacja', 'description' => 'Bohater potrafi skupić się na własnym wnętrzu i wejść w głęboki trans, ignorując doznania ze świata zewnętrznego. Podczas odprawiania rytuału magicznego BG otrzymuje modyfikator do poziomu mocy czaru równy wartości jego cechy Magia.'],
            ['name' => 'Morderczy atak', 'description' => 'Dzięki znajomości podstaw anatomii Bohater potrafi wymierzyć atak w newralgiczną część ciała przeciwnika. Zwiększa to o 1 Wartość Krytyczną wszystkich trafień krytycznych zadanych przez BG.'],
            ['name' => 'Morderczy pocisk', 'description' => 'Bohater specjalizuje się w czarach typu „magiczny pocisk". Otrzymuje modyfikator +1 do rzutów na obrażenia, gdy atakuje czarami tego typu.'],
            ['name' => 'Naśladowca', 'description' => 'Bohater potrafi bezbłędnie naśladować różne odgłosy. Otrzymuje modyfikator +10 do testów: kuglarstwa (aktorstwo, błaznowanie, gawędziarstwo i komedianctwo), charakteryzacji (jeśli jednym z elementów przebrania jest naśladowanie głosu lub dźwięków) oraz znajomości języka, gdy próbuje udawać, że to jego język ojczysty.'],
            ['name' => 'Niepokojący', 'description' => 'Bohater ma w swoim wyglądzie coś takiego, że przeciwnicy stają się niespokojni. Widząc go, wrogowie muszą wykonać test Siły Woli. Nieudany test oznacza, że otrzymują modyfikator -10 do testów Walki Wręcz i Umiejętności Strzeleckich w czasie walki z Bohaterem. Podczas każdej kolejnej rundy mogą próbować przełamać swój lęk (wykonując kolejny test Siły Woli). Efekt mija, gdy jedna ze stron ucieknie z pola walki.'],
            ['name' => 'Nieustraszony', 'description' => 'Bohater nie odczuwa strachu. Może jest naprawdę odważny, a może po prostu szalony. Bez względu na przyczynę, jest odporny na Strach i w mniejszym stopniu ulega Grozie (wykonujesz rzut przeciw Grozie jako rzut przeciw Strachowi). Bohater jest również odporny na działanie umiejętności zastraszanie i zdolności niepokojący. Szczegółowe zasady dotyczące wzbudzania Strachu i Grozy znajdziesz w Rozdziale IX: Mistrz Gry.'],
            ['name' => 'Niezwykle odporny', 'description' => 'Bohater obdarzony jest wyjątkową odpornością. Otrzymuje +5 do Odporności, dodawane do początkowej wartości cechy. Może to zmienić wartość Wytrzymałości, BG zgodnie z zasadami opisanymi na str. 19.'],
            ['name' => 'Obieżyświat', 'description' => 'Bohater w swoim życiu wiele podróżował, zdobywając rozległą wiedzę. Otrzymuje modyfikator +10 do testów wiedzy oraz znajomości języka.'],
            ['name' => 'Oburęczność', 'description' => 'Bohater może używać obu rąk z jednakową sprawnością, nie otrzymując modyfikatora -20 do Walki Wręcz, gdy trzyma broń w słabszej ręce.'],
            ['name' => 'Artylerzysta', 'description' => 'Bohater został świetnie wyszkolony w obsłudze broni palnej. Dzięki tej zdolności może przeładować broń palną w czasie krótszym o akcję. Jeśli Bohater posiada również zdolność błyskawiczne przeładowanie, może korzystać naraz z obu zdolności (skracając czas przeładowania broni palnej o akcję podwójną).'],
            ['name' => 'Bardzo silny', 'description' => 'Bohater obdarzony jest wyjątkową siłą. Otrzymuje +5 do Krzepy, dodawane do początkowej wartości cechy. Może to zmienić wartość Siły BG, zgodnie z zasadami opisanymi na str. 19.'],
            ['name' => 'Bardzo szybki', 'description' => 'Bohater potrafi poruszać się znacznie szybciej niż inni. Otrzymuje +1 do Szybkości, dodawane do początkowej wartości cechy.'],
            ['name' => 'Bijatyka', 'description' => 'Bohater nauczył się walczyć w karczemnych burdach i miejskich rozróbach. Otrzymuje modyfikator +10 do Walki Wręcz podczas ataku bez broni. Dodatkowo otrzymuje modyfikator +1 do obrażeń zadawanych podczas takiego ataku.'],
            ['name' => 'Błyskawiczne przeładowanie', 'description' => 'Bohater jest doświadczonym strzelcem. Dzięki tej zdolności może przeładować broń strzelecką w czasie krótszym o akcję. Bohater używający błyskawicznego przeładowania może napiąć kuszę w czasie jednej akcji zwykłej, podczas gdy normalnie wymaga to akcji podwójnej. Jeśli przeładowanie broni normalnie zajmuje akcję, korzystając z tej zdolności można to wykonać w ramach akcji natychmiastowej. Dzięki temu Bohater może przeładować taką broń praktycznie w mgnieniu oka, co pozwala na wykonanie „ataku wielokrotnego" (z) broni strzeleckiej. Szczegółowe zasady wykonywania „ataku wielokrotnego" znajdziesz w Rozdziale VI: Walka, obrażenia i ruch.'],
            ['name' => 'Błyskawiczny blok', 'description' => 'Bohater, który wykonuje „atak wielokrotny" (więcej na ten temat w Rozdziale VI: Walka, obrażenia i ruch), może poświęcić jeden z ataków, otrzymując w zamian możliwość sparowania ataku przeciwnika. Na przykład Bohater mający 3 Ataki i deklarujący atak wielokrotny, mógłby wykonać dwa ataki i raz sparować atak przeciwnika. Bohater nadal może parować najwyżej jeden atak na rundę.'],
            ['name' => 'Błyskotliwość', 'description' => 'Bohater obdarzony jest wyjątkową inteligencją. Otrzymuje +5 do Inteligencji, dodawane do początkowej wartości cechy.'],
            ['name' => 'Brawura', 'description' => 'Obdarzony tą zdolnością Bohater wykazuje się wyjątkową śmiałością i ruchliwością w walce. Może wykonać akcję „skok" poświęcając na to akcję zwykłą (zamiast akcji podwójnej). Zdolność zwiększa też maksymalny zasięg wszystkich skoków - 1 metr.'],
            ['name' => 'Broń naturalna', 'description' => 'Postać dysponuje ostrymi kłami lub pazurami, których z powodzeniem może używać w walce. W czasie walki bez broni jest traktowana, jak gdyby używała broni jednoręcznej. Broń naturalna nie pozwala na parowanie ciosów. W przypadku broni naturalnej nie można stosować rozbrajania.'],
            ['name' => 'Broń specjalna (różne)', 'description' => 'Bohater potrafi władać bronią, która wymaga specjalistycznego treningu. Każda zdolność broń specjalna jest odrębną zdolnością. Na przykład broń specjalna (dwuręczna) różni się od zdolności broń specjalna (rzucana). Każda zdolność musi zostać wykupiona oddzielnie. Najczęściej spotykanymi rodzajami broni specjalnych są: dwuręczna, kawaleryjska, mechaniczna, palna, parująca, rzucana, szermiercza, unieruchamiająca oraz korbacze, kusze, łuki i proce. Szczegółowy opis poszczególnych rodzajów broni znajdziesz w Rozdziale V: Ekwipunek.'],
            ['name' => 'Bystry wzrok', 'description' => 'Bohater obdarzony jest doskonałym wzrokiem. Otrzymuje modyfikator +10 do testów spostrzegawczości podczas rozglądania się oraz do testów czytania z warg.'],
            ['name' => 'Charyzmatyczny', 'description' => 'Bohater obdarzony jest zniewalającym urokiem osobistym. Otrzymuje +5 do Ogłady, dodawane do początkowej wartości cechy.'],
            ['name' => 'Chirurgia', 'description' => 'Bohater poznał tajniki najnowszej wiedzy medycznej. Otrzymuje modyfikator +10 do testów leczenia. W przypadku leczenia ciężko rannego pacjenta udany test przywraca 2 punkty Żywotności zamiast, jak normalnie, tylko 1. Jeśli w wyniku trafienia krytycznego istnieje ryzyko utraty kończyny, pacjent leczony przez chirurga otrzymuje modyfikator +20 do Odporności podczas testów związanych z ryzykiem utraty kończyny. Szczegółowe zasady dotyczące trafień krytycznych znajdziesz w Rozdziale VI: Walka, obrażenia i ruch.'],
            ['name' => 'Chodu!', 'description' => 'W chwili zagrożenia Bohater odkrywa w sobie zadziwiające możliwości fizyczne. Uciekając z pola walki lub z miejsca zagrożenia, na 1k10 rund otrzymuje +1 do Szybkości.'],
            ['name' => 'Czarnoksięstwo', 'description' => 'Bohater poznał sekret Czarnej Magii Dhar i potrafi jej używać do wspomożenia siły swoich zaklęć. Korzystanie z czarnoksięstwa umożliwia zdobycie większej mocy, ale jest także bardziej ryzykowne. Za każdym razem, gdy Bohater rzuca czar, może wykorzystać energię Dhar do jego wzmocnienia. Wykonując rzut na poziom mocy czaru, możesz rzucić dodatkową kostką k10 i zignorować najmniejszy z uzyskanych wyników, który jednak liczy się przy sprawdzaniu Przekleństwa Tzeentcha. Na przykład czarodziej z Magią 2, który rzuca zaklęcie z wykorzystaniem czarnoksięstwa, rzuca 3k10 i wybiera dwa najwyższe wyniki. Wszystkie trzy kostki są używane do sprawdzania Przekleństwa Tzeentcha. Gdyby na kostkach wypadło (6,6 i 6), poziom mocy czaru wyniósłby 12(6+6), jednak czar wywołałby Poważną Manifestację Chaosu. Znajomość czarnoksięstwa jest wymagana przy rzucaniu czarów (z) magii czarnoksięskiej. Szczegółowe zasady rzucania zaklęć znajdziesz w Rozdziale VII: Magia.'],
            ['name' => 'Odporność na Chaos', 'description' => 'Bohater obdarzony jest naturalną odpornością na wpływ Chaosu. Otrzymuje modyfikator +10 do Siły Woli podczas testów przeciwko magii i efektom Chaosu. Jest również całkowicie odporny na mutacje Chaosu. Jednak nigdy nie będzie mógł rzucać czarów.'],
            ['name' => 'Odporność na choroby', 'description' => 'Bohater jest obdarzony końskim zdrowiem. Otrzymuje modyfikator +10 do Odporności podczas testów przeciwko chorobom.'],
            ['name' => 'Odporność na magię', 'description' => 'Bohater w naturalny sposób opiera się działaniu czarów. Otrzymuje modyfikator +10 do Siły Woli podczas testów przeciwko magii.'],
            ['name' => 'Odporność na trucizny', 'description' => 'Wyjątkowa odporność organizmu pozwala Bohaterowi na osłabienie działania trucizny. Otrzymuje modyfikator +10 do Odporności podczas testów przeciwko truciznom.'],
            ['name' => 'Odporność psychiczna', 'description' => 'Bohater jest mniej podatny na efekt szokujących i przerażających wydarzeń. Do momentu uzbierania 8 Punktów Obłędu nie musisz testować, czy Bohater nabawił się choroby umysłu. Bohater popada w obłęd (wykazuje objawy pierwszej choroby umysłowej) dopiero po uzbieraniu 14 Punktów Obłędu.'],
            ['name' => 'Odwaga', 'description' => 'Bohater odznacza się wyjątkową odwagą. Otrzymuje modyfikator +10 do Siły Woli podczas testów przeciwko Strachowi i Grozie oraz zastraszaniu.'],
            ['name' => 'Ogłuszanie', 'description' => 'Po udanym ataku bronią białą Bohater może zadeklarować próbę ogłuszenia przeciwnika, zamiast zadawać mu obrażenia. W takiej sytuacji należy wykonać przeciwstawny test Krzepy. Jeśli Bohater wygra, jego przeciwnik musi natychmiast wykonać test Odporności, dodając modyfikator +10 za każdy Punkt Zbroi hełmu lub osłony noszonej na głowie. Jeśli ten test będzie nieudany, przeciwnik zostaje ogłuszony na 1k10 rund. W tym czasie nie może podejmować żadnych akcji i nie może stosować umiejętności unik.'],
            ['name' => 'Opanowanie', 'description' => 'Bohater nigdy nie traci zimnej krwi. Otrzymuje +5 do Siły Woli, dodawane do początkowej wartości cechy.'],
            ['name' => 'Ożywiniec', 'description' => 'Postać jest nieumarłym stworzeniem, przywróconym do życia za pomocą magii nekromanckiej (patrz Rozdział VII: Magia). Jest odporna na Strach, Grozę, trucizny, choroby oraz na wszystkie czary, umiejętności i zdolności, które wpływają na emocje i umysły.'],
            ['name' => 'Pancerz wiary', 'description' => 'Dzięki żarliwej modlitwie Bohater potrafi skutecznie rzucać czary, nawet nosząc pancerz. Zdolność umożliwia dodanie +3 do ujemnego modyfikatora do poziomu mocy czaru, który związany jest noszeniem zbroi. Na przykład rzucanie czarów i jednoczesne zasłanianie się ciężką tarczą jest obciążone modyfikatorem -3. Zdolność pancerz wiary redukuje ten modyfikator do 0.'],
            ['name' => 'Poliglota', 'description' => 'Bohater posiada naturalną zdolność uczenia się i zapamiętywania obcych języków. Otrzymuje modyfikator +10 do testów czytania i pisania oraz znajomości języka.'],
            ['name' => 'Przemawianie', 'description' => 'Gdy Bohater przemawia, potrafi skupić uwagę większej grupy słuchaczy. Wykorzystując przekonywanie, może oddziaływać na grupę osób 10 razy liczniejszą niż normalnie.'],
            ['name' => 'Przerażający', 'description' => 'Wygląd postaci wywołuje przerażenie wśród obserwatorów. Swoim zachowaniem wzbudza Grozę, zgodnie z zasadami opisanymi w Rozdziale IX: Mistrz Gry.'],
            ['name' => 'Rozbrajanie', 'description' => 'Po udanym ataku bronią białą, Bohater może podjąć próbę rozbrojenia przeciwnika, zamiast zadawać mu obrażenia. W takiej sytuacji należy wykonać przeciwstawny test Zręczności. Jeśli Bohater wygra, jego przeciwnik zostaje rozbrojony i upuszcza broń. Broń można podnieść, wykonując akcję "użycie przedmiotu". Jeśli przeciwnik wygra, utrzymuje broń w ręce. Nie można rozbrajać przeciwnika, który walczy bronią naturalną (kły, pazury, itp.).'],
            ['name' => 'Silny cios', 'description' => 'Dzięki doświadczeniu nabytemu w wielu walkach Bohater potrafi precyzyjnie wymierzać ciosy w walce wręcz. Otrzymuje modyfikator +1 do obrażeń zadawanych bronią białą.'],
            ['name' => 'Straszny', 'description' => 'Wygląd postaci wywołuje przestrach wśród obserwatorów. Swoim zachowaniem wzbudza Strach, zgodnie z zasadami opisanymi w Rozdziale IX: Mistrz Gry.'],
            ['name' => 'Talent artystyczny', 'description' => 'Bohater potrafi tworzyć dzieła sztuki. Otrzymuje modyfikator +20 do testów rzemiosła (sztuka) oraz modyfikator +10 do testów wyceny przy szacowaniu wartości dzieł sztuki.'],
            ['name' => 'Twardziel', 'description' => 'Bohater jest wyjątkowo odporny na ból i zranienia. Otrzymuje +1 do Żywotności, dodawane do początkowej wartości cechy.'],
            ['name' => 'Ulicznik', 'description' => 'Bohater wychował się w mieście i z łatwością orientuje się w terenie zabudowanym. Otrzymuje modyfikator +10 do testów skradania się i ukrywania się w mieście.'],
            ['name' => 'Urodzony wojownik', 'description' => 'Bohater wyjątkowo sprawnie posługuje się bronią białą. Otrzymuje +5 do Walki Wręcz, dodawane do początkowej wartości cechy.'],
            ['name' => 'Wędrowiec', 'description' => 'Bohater wychował się na wsi i i łatwością orientuje się w terenie wiejskim. Otrzymuje modyfikator +10 do testów skradania się i ukrywania się na terenach poza miastem.'],
            ['name' => 'Widzenie w ciemności', 'description' => 'Bohater dysponuje zdolnością wyraźnego widzenia przy oświetleniu porównywalnym ze światłem gwiazd. Zasięg wzroku w takich warunkach wynosi 30 metrów. Zdolność jest bezużyteczna w całkowitej ciemności.'],
            ['name' => 'Woltyżerka', 'description' => 'Bohater potrafi dokonywać niewiarygodnych wyczynów podczas jazdy konnej. Potrafi stać na rękach na grzbiecie galopującego wierzchowca, przeskakiwać z jednego konia na drugiego, zeskakiwać w pełnym biegu, itp. Bohater wykonuje testy jeździectwa tylko w najbardziej ekstremalnych sytuacjach, a i wtedy otrzymuje modyfikator +10 do testów tej umiejętności.'],
            ['name' => 'Wyczucie kierunku', 'description' => 'Bohater instynktownie potrafi określać strony świata i orientować się w przestrzeni. Prawie nigdy się nie gubi i bez wahania potrafi wskazać kierunek północny. Dodatkowo otrzymuje modyfikator +10 do testów nawigacji.'],
            ['name' => 'Wykrywanie pułapek', 'description' => 'Bohater jest ekspertem w wykrywaniu i rozbrajaniu pułapek. Otrzymuje modyfikator +10 do testów spostrzegawczości i otwierania zamków związanych z wykrywaniem i unieszkodliwianiem pułapek.'],
            ['name' => 'Wyostrzone zmysły', 'description' => 'Bohater posiada wyjątkowo wyczulone zmysły. Otrzymuje modyfikator +20 do wszystkich testów spostrzegawczości.'],
            ['name' => 'Zapasy', 'description' => 'Bohater wyjątkowo dobrze walczy bez broni. Wykonując chwyt, otrzymuje modyfikator +10 do Walki Wręcz. Dodatkowo otrzymuje modyfikator +10 do Krzepy, gdy chwyta przeciwnika lub gdy wyzwala się z uścisku.'],
            ['name' => 'Zapiekła nienawiść', 'description' => 'Bohater podziela głęboko zakorzenioną wśród swojego ludu wrogość wobec wszystkich zielonoskórych. Ta wiekowa już tradycja napełnia go taką nienawiścią, że otrzymuje modyfikator +5 do Walki Wręcz, gdy atakuje gobliny, orki i hobgobliny.'],
            ['name' => 'Zmysł magii', 'description' => 'Bohater potrafi umiejętnie manipulować Wiatrami Magii. Otrzymuje modyfikator +10 do testów splatania magii oraz wykrywania magii.'],
            ['name' => 'Żyłka handlowa', 'description' => 'Bohater potrafi sprzedać niemal wszystko. Otrzymuje modyfikator +10 do testów targowania i wyceny.'],
            [
                'name' => 'Człowick-guma',
                'description' => 'Bohater potrafi wyginać swoje ciało w sposób nicosiagalny dla zwykłych osób. Otrzymuje modyfikator +10 do testów kuglarstwa (akrobatyka) oraz modyfikator +20 do Zręczności podczas testów wyzwalania się z więzów, przeciskania przez szczeliny, itp.'
            ],
            [
                'name' => 'Czuły słuch',
                'description' => 'Bohater obdarzony jest wyjątkowo czulym sluchem. Otrzymuje modyfikator +20 do testów spostrzegawczości podezas nasłuchiwania.'
            ],
            [
                'name' => 'Dotyk mocy',
                'description' => 'Niektóre czary używane w walce wymagają dotknięcia przeciwnika. Bohater, który posiada zdolność dotyk mocy, otrzymuje modyfikator +20 do Walki Wręcz przy testach związanych z rzucaniem czarów dotykowych.'
            ],
            [
                'name' => 'Etykieta',
                'description' => 'Bohater potrafi odpowiednio zachowywać się we wszelkich sytuacjach towarzyskich. Otrzymuje modyfikator +10 do testów plotkowania i przekonywania podczas rozmów z przedstawicielami szlachty i arystokracji. Modyfikator stosuje się również w sytuacjach, gdy wymagana jest znajomość etykiety (na przykład podszywanie się pod szlachcica (z) wykorzystaniem charakteryzacji).'
            ],
            [
                'name' => 'Geniusz arytmetyczny',
                'description' => 'Bohater potrafi blyskawicznic rachować w umyśle oraz, mającdo dyspozycji dostatecznie dużo czasu, może rozwiạzá́ niemal dowolny problem matematyczny. Otrzymuje modyfikator +10 do testów hazardu i nawigacji oraz modyfikator +20 do testów spostrzegawczości zwiq̨zanych z oceną odleglości, ciężaru, itd.'
            ],
            [
                'name' => 'Grotołaz',
                'description' => 'Bohater wychowal się w jaskiniach bądź często po nich wędrowal. Bez trudu potrafi poruszać się w podziemiach. Otrzymuje modyfikator +10 do testów skradania się i ukrywania się wykonywanych pod powierzchnią ziemi lub w jaskiniach.'
            ],
            [
                'name' => 'Grożny',
                'description' => 'Wygląd, wzrost lub zachowanie Bohatera wzbudzają respekt i instynktowny niepokój wśród obserwatorów. Otrzymuje modyfikator +10 do testów zastraszania i torturowania.'
            ],
            [
                'name' => 'Gusła',
                'description' => 'Bohater odkry! w sobie dziki talent magiczny, który udoskonala metodą prób i bę̨dów, nie mając dostępu do magicznych studiów. Bohater potrafi rzucać czary magii prostej (gusła) bez konieczności posiadania umiejętności język tajemny. Do rzucania czarów nadal wymagana jest zdolność magia prosta (gusła). Za każdym razem, gdy Bohater korzysta z zaklęć magii prostej (gusla), musisz rzucić dodatkową kostką k10. Wynik rzutu nie dodaje siẹ do poziomu mocy czaru, lecz jest używany do sprawdzania Przekleństwa Tzeentcha. Jeśli Twój BG nauczy się umiejętności język tajemny, nie będziesz musial rzucać dodatkową kostką.'
            ],
            [
                'name' => 'Intrygant',
                'description' => 'Bohater jest mistrzem rozgrywek politycznych i intryg. Otrzymuje modyfikator +10 do testów przekonywania związanych z intrygami oraz do Sily Woli podczas testów przeciwko przekonywaniu ze strony innych osób.'
            ],
            [
                'name' => 'Krasnoludzki fach',
                'description' => 'Krasnoludy są urodzonymi rzemieślnikami. Bohater otrzymuje modyfikator +10 do testów rzemiosla: górnictwo, kamieniarstwo, kowalstwo, jubilerstwo, piwowarstwo, platnerstwo i rusznikarstwo.'
            ],
            [
                'name' => 'Krasomówstwo',
                'description' => 'Bohater potrafi przemawiać tak pięknie i przekonywująco, że może poderwać do dzialania cale tlumy. Wykorzystujạc przekonywanie BG może oddzialywać na grupę osób 100 razy licznicjszą niz̀ normalnie. Zdolność krasomówstwo wymaga uprzedniego opanowania zdolności przemawianie.'
            ],
            [
                'name' => 'Krzepki',
                'description' => 'Bohater, który posiada tę zdolność, w czasie wielu przygód nabral niesamowitej krzepy. Może nosić ciężki pancerz lub zbroję plytową bez zmniejszenia Szybkości. Szczególowy opis pancerzy znajdziesz w Rozdziale V: Ekwipunek.'
            ],
            [
                'name' => 'Latanie',
                'description' => 'Postá́ potrafi latać. Zasady dotyczące latania znajdziesz w Rozdziale VI: Walka, obrażenia i ruch.'
            ],
            [
                'name' => 'Lewitacja',
                'description' => 'Postać potrafi unosić siẹ nisko nad ziemią. Zasady dotyczące lewitacji znajdziesz w Rozdziale VI: Walka, obrażenia i ruch.'
            ],
            [
                'name' => 'Łotrzyk',
                'description' => 'Bohater jest blisko zwiạzany ze światem przestẹpczym. Otrzymuje modyfikator +10 do testów plotkowania i przekonywania w kontaktach z przedstawicielami przestẹpczego pólświatka.'
            ],
            [
                'name' => 'Magia czarnoksięska',
                'description' => 'Twój Bohater poznał tajniki jednej z zakazanych tradycji magicznych. Podobnie jak w przypadku magii tajemnej, zglębianie sekretów magii czarnoksięskiej wymagania poświęcenia i wyboru jednej z dostępnych mrocznych tradycji. Każda magia czarnoksięska jest oddzielną zdolnością. Na przyklad magia czarnoksięska (Chaos) różni się od magii czarnoksięskiej (nekromancja). W kolejnych dodatkach znajdziesz informacje o innych rodzajach magii czarnoksięskiej, wlacznic z wyszczególnieniem mocy bogów Chaosu: Nurgla, Slaanesha i Tzeentcha. Znajomość magii czarnoksięskiej pozwala na rzucanie czarów z określonej tradycji. Szezególowe zasady rzucania zaklęć znajdziesz w Rozdziale VII: Magia.'
            ],
            [
                'name' => 'Magia kapłánska',
                'description' => 'Bohater jest tak żarliwym wyznawcą jakiegoś bóstwa, że dzięki jego modlitwom zdarzają się rzeczy nadprzyrodzone. Zgłłębianie tajemnic boga wymaga fanatycznego poświęcenia i zaangażowania. Bohater musi wybrać jednego boga, a w konsekwencji także jedną z magii kapłańskich. Każda magia kapłańska jest oddzielną zdolnością magicznạ, pozwalająç̨̨ na rzucanie czarów z dziedziny określonego bóstwa. Na przyklad magia kaplańska (dziedzina Sigmara) różni się od magii kaplańskiej (dziedzina Ulryka). Każde z większych bóstw w Starym Świecie posiada wlasny kult, który naucza magii kaplańskiej. Są to Świątynie: Mananna, Morra, Myrmidii, Ranalda, Sigmara, Shallyi, Taala i Rhyi, Ulryka oraz Vereny. Znajomość magii kapłańskiej pozwala na rzucanie czarów z określonej dziedziny. Szczególowe zasady rzucania zaklęć znajdziesz w Rozdziale VII: Magia.'
            ],
        ]);
    }
}
