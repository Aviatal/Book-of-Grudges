<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Skill::insert([
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Brzuchóstwo',
                'characteristic' => 'Ogd',
                'description' => 'Bohater potrafi mówić bez otwierania ust. Osoby uważnie obserwujące Bohatera korzystającego z tej umiejętności mogą wykonać test spostrzegawczości przeciwko testowi brzuchomówstwa BG, żeby wykryć sztuczkę Bohatera.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Charakteryzacja',
                'characteristic' => 'Ogd',
                'description' => 'Wykorzystanie tej umiejętności pozwala Bohaterowi maskować jego prawdziwy wygląd i udawać kogoś innego. Często potrzebne są dodatkowe rekwizyty, właściwe ubranie lub peruka. Dzięki tej umiejętności Bohater može przebrać się za przedstawiciela innej rasy, osobę płci przeciwnej, a nawet kogoś sławnego i znanego w całym kraju, choć tego rodzaju charakteryzacja jest znacznie trudniejsza. Przeciwko charakteryzacji często wykorzystuje się test spostrzegawczości przeciwnika.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Czytanie i pisanie',
                'characteristic' => 'Int',
                'description' => 'Bohater potrafi czytać i pisać w dowolnym języku, którym umie się posługiwać. W większości przypadków czytania i pisania nie trzeba testować. Mistrz Gry może jednak zdecydować, że test umiejętności jest potrzebny przy odcyfrowywaniu rękopisu spisanego w starożytnym języku, lub zawierającym niezrozumiałe wyrażenia albo archaiczne słownictwo.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Czytanie z warg',
                'characteristic' => 'Int',
                'description' => 'Dzięki tej umiejętności Bohater może zrozumieć rozmowy prowadzone poza zasięgiem jego słuchu lub gdy rozmowa jest zagłuszana przez jakieś odgłosy. Musi widzieć usta obserwowanej osoby, jak również znać język, w którym prowadzona jest rozmowa.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Dowodzenie',
                'characteristic' => 'Ogd',
                'description' => 'Korzystający z tej umiejętności Bohater cieszy się posłuchem u podwładnych. Po udanym teście umiejętności podwładni dokładnie wypełniają jego polecenia. Nieudany test powoduje, że rozkaz zostaje wypełniony błędnie lub też nie zostaje wykonany w ogóle (zależnie od decyzji Mistrza Gry). Dowodzenie nie ma wpływu na zachowanie osób postronnych, umożliwia posłuszeństwo osób podlegających władzy Bohatera.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Gadanina',
                'characteristic' => 'Ogd',
                'description' => 'Bohaterowie posiadający tę umiejętność mogą próbować zagadać osobę, zasypując ją potokiem słów. Korzystający z tej umiejętności Bohater zazwyczaj nie próbuje na nikogo wpływać (do tego służy przekonywanie), chce po prostu zyskać na czasie. Po udanym teście umiejętności gadanina ofiara ma prawo do testu Siły Woli, który określa, czy zorientowała się w tym, co się naprawdę dzieje. Nieudany test oznacza, że zagadana osoba nic nie robi przez całą rundę, zastanawiając się, czy ma do czynienia z osobnikiem pijanym czy zwykłym idiotą, a może jedno i drugie. Gadanina nie przynosi rezultatu, jeśli ofiara bierze udział w walce lub stoi w obliczu ewidentnego zagrożenia życia. Bohater może próbować zagadać kilka osób (jedna osoba za każde 10 punktów jego Ogłady), pod warunkiem, że wszystkie te osoby rozumieją język, którym się posługuje.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Hazard',
                'characteristic' => 'Int',
                'description' => 'Umiejętność hazard zwiększa szansę Bohatera na wygraną w grach losowych, takich jak karty lub kości. Każda uczestnicząca w grze postać wpłaca stawkę, a potem wszyscy grający jednocześnie wykonują test hazardu. Najniższy wynik rzutu (oczywiście, przy udanym teście) oznacza wygranie puli,'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Hipnoza',
                'characteristic' => 'SW',
                'description' => 'Używając hipnozy Bohater może wprowadzić inną osobę w trans. Uwaga hipnotyzowanej osoby musi być przez minutę skupiona na jednej rzeczy (często wykorzystuje się jakąś błyskotkę na łańcuszku lub zapaloną świecę). Potem należy wykonać test umiejętności. Osoby próbujące opierać się hipnozie mogą wykonać test Siły Woli. Po wprowadzeniu osoby w trans, Bohater może jej zadać jedno pytanie za każde 10 punktów swojej Siły Woli. Osoba udziela odpowiedzi szczerze, zgodnie ze swoją wiedzą. Jeśli głęboko w coś wierzy, to udzieli takiej właśnie informacji. Po udzieleniu odpowiedzi na ostatnie pytanie osoba wychodzi z transu.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Jeździectwo',
                'characteristic' => 'Zr',
                'description' => 'Bohater potrafi jeździć konno lub na innych wierzchowcach. Zwykle jeżdżenie w normalnych warunkach nie wymaga wykonywania testu umiejętności. Jednakże może on być konieczny w przypadku jazdy galopem, wyścigu, wskakiwania na konia w biegu, itp.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Język tajemny',
                'characteristic' => 'Int',
                'description' => 'Dzięki tej umiejętności Bohater może rzucać zaklęcia. Znajomość języka tajemnego jest konieczna przy używaniu magicznych formuł. W odróżnieniu od innych języków, język tajemny nie jest używany w codziennych rozmowach, a wyłącznie do manipulowania mocą magiczną. Wszystkie magiczne pergaminy i księgi zapisane są w określonym języku tajemnym. Istnieje wiele takich języków. Najczęściej używane to: demoniczny, magiczny i tajemny elfi.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Kuglarstwo',
                'characteristic' => 'Ogd',
                'description' => 'Wykorzystywane jest do zabawiania publiczności. Podobnie jak w przypadku Nauki i Wiedzy, nazwa Kuglarstwo określa kategorię oddzielnych umiejętności.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Lecznie',
                'characteristic' => 'Int',
                'description' => 'Dzięki tej umiejętności Bohater może zapewnić opiekę medyczną rannej osobie. Udany test leczenia przywraca 1k10 punktów Żywotności w przypadku osoby lekko rannej lub 1 punkt Żywotności w przypadku osoby ciężko rannej. Ranna osoba może być leczona tylko raz podczas sytuacji krytycznej (bitwa, zasadzka, pułapka, upadek, itp.), która spowodowała utratę punktów Żywotności, lub zaraz po niej. Test leczenia można ponowić następnego dnia, jak również każdego kolejnego dnia'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Mocna głowa',
                'characteristic' => 'Int',
                'description' => 'Ta umiejętność zwiększa odporność Bohatera na alkohol. Doświadczeni poszukiwacze przygód potrafią sporo wypić i jednocześnie zachować względną trzeźwość. Test umiejętności wykonuje się po każdej porcji wypitego alkoholu.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Nawigacja',
                'characteristic' => 'Int',
                'description' => ' Umiejętność ta wykorzystywana jest do orientowania się na lądzie i na wodzie. W zależności od wiedzy i możliwości, Bohater może nawigować według mapy albo gwiazd lub posługując się wrodzonym wyczuciem kierunku. Dzięki tej umiejętności może również ocenić długość podróży, biorąc pod uwagę topografię okolicy, porę roku i pogodę. W normalnych warunkach, utrzymanie stałego kursu wymaga jednego udanego testu dziennie.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Opieka nad zwierzętami',
                'characteristic' => 'Int',
                'description' => 'Umiejętność ta wykorzystywana jest podczas doglądania zwierząt domowych i hodowlanych (konie, woły, świnie, psy, itd.). Codzienne czynności i karmienie zwierząt nie wymagają testu umiejętności. Może być jednak potrzebny przy próbie wykrycia objawów choroby lub zastosowaniu specjalnych zabiegów (na przykład zaplatanie grzywy, czesanie, przygotowanie konia do parady wojskowej, itp.).'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Oswajanie',
                'characteristic' => 'Ogd',
                'description' => 'Wyorzystywanie tej umiejętności umożliwia oswajanie zwierząt. Zwierzęta domowe i hodowlane zawsze zachowują się przyjaźnie wobec Bohatera, który posiada tę umiejętność. Zwierzęta dzikie lub tresowane (na przykład psy gończe lub bojowe) mogą dać się oswoić przy udanym teście umiejętności. MG może przydzielić modyfikatory przy próbie oswajania zwierząt wyjątkowo agresywnych lub wiernych innej osobie. Umiejętność ta nie działa na potwory.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Plotkowanie',
                'characteristic' => 'Ogd',
                'description' => ' Wykorzystanie tej umiejętności pozwala na zbieranie informacji w czasie zwykłej rozmowy. Obejmuje wymianę najświeższych nowin, plotek o ważnych osobach oraz ogólnych informacji o wydarzeniach w okolicy.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Pływanie',
                'characteristic' => 'K',
                'description' => 'Bohater umie pływać oraz nurkować. Pływanie w spokojnej wodzie nie wymaga testu. Test może być potrzebny przy próbie nurkowania, pływania w wartkim lub zdradliwym nurcie lub podczas próby przepłynięcia dłuższego dystansu. W czasie pływania Szybkość Bohatera spada o połowę.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Powożenie',
                'characteristic' => 'K',
                'description' => 'Bohater potrafi kierować wozem, powozem, a nawet rydwanem. Powożenie w normalnych warunkach nie wymaga testu umiejętności. Test może być potrzebny w przypadku jazdy w trudnym terenie, z dużą prędkością lub przy wykonywaniu niebezpiecznych manewrów.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Przekonywanie',
                'characteristic' => 'Ogd',
                'description' => 'Ta umiejętność pozwala Bohaterowi wpływać na zachowanie innych osób. Może przekonywująco kłamać, blefować, a nawet skutecznie żebrać. Przekonywanie wykorzystuje się też podczas prób uwodzenia. W przypadku zastosowania tej umiejętności w celu nakłonienia kogoś do zrobienia czegoś niezwykłego lub niebezpiecznego, MG może pozwolić nakłanianej postaci na test Siły Woli. Bohater może próbować przekonać kilka osób (jedna osoba za każde 10pkt jego Ogłady), pod warunkiem że wszystkie te osoby rozumieją język, którym posługuje się bohater.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Przeszukiwanie',
                'characteristic' => 'Int',
                'description' => 'Ta umiejętność jest wykorzystywana przy przeszukiwaniu obszaru lub pomieszczenia, w nadziei znalezienia wskazówek, ukrytych przejść, skarbów lub pułapek. Dokładne przeszukanie pomieszczenia lub niewielkiego obszaru wymaga jednego udanego testu umiejętności.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Rzemiosło',
                'characteristic' => 'Int',
                'description' => 'Bohater jest fachowcem w jednej z dziedzin rzemiosła. Ta umiejętność obejmuje także te dziedziny, które formalnie nie są określone jako rzemiosło, ale wymagają posiadania wyuczonej wiedzy i odpowiednich narzędzi. Każda umiejętność Rzemiosło dotyczy odrębnej dziedziny.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Sekretne znaki',
                'characteristic' => 'Int',
                'description' => 'ohater potrafi odczytywać lub zapisywać zaszyfrowane wiadomości. Na obszarze Imperium stosuje się wiele systemów znaków. Sekretne znaki są zazwyczaj prostymi komunikatami używanymi głównie w celu ostrzeżenia, oznakowania obiektu, wskazania szlaku lub miejsca o szczególnym znaczeniu. Odczytanie lub zapisanie krótkiej, prostej wiadomości nie wymaga testu umiejętności. W przypadku bardziej skomplikowanych zapisów lub gdy fragmenty znaku są podniszczone albo zatarte, MG może nakazać wykonanie testu umiejętności z odpowiednimi modyfikatorami trudności. '
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Sekretny język',
                'characteristic' => 'Int',
                'description' => 'Znajomość sekretnego język pozwala na potajemne porozumiewanie się z przedstawicielami tej samej profesji lub grupy społecznej. Sekretne języki przypominają raczej uproszczony szyfr, a nie powszechnie używane formy porozumiewania się. Wykorzystując znaki, mowę ciała i słowa kodowe wplatane w zwykłą wypowiedź, Bohater może przekazać dodatkowe znaczenie wypowiadanych słów lub większą ilość informacji w krótkim czasie. W normalnych warunkach, gdy wszyscy rozmawiający znają dany sekretny język, test umiejętności nie jest potrzebny, aczkolwiek może być wymagany w niesprzyjających warunkach (na przykład na głośnej ulicy lub w czasie bitwy).'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Skradanie się',
                'characteristic' => 'Zr',
                'description' => 'Umiejętność ta umożliwia Bohaterowi ciche poruszanie się w prawie każdym terenie. Skradając się, Bohater może wykonywać najwyżej jedną akcję „ruch” w rundzie. Test skradania się jest często wykonywany w przeciwstawnym teście przeciwko Spostrzegawczości przeciwnika'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Splatanie magii',
                'characteristic' => 'SW',
                'description' => 'Wykorzystywanie tej umiejętności ułatwia Bohaterowi kontrolowanie Wiatrów Magii. Każde rzucenie zaklęcia wymaga manipulacji nimi, jednak splatanie magii wykorzystuje się wtedy, gdy wymagana jest większa kontrola nad czarem lub jego precyzyjne przygotowanie. '
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Spostrzegawczość',
                'characteristic' => 'Int',
                'description' => 'BG, który posiada tę umiejętność, dokładniej obserwuje otoczenie, często zauważając szczegóły przeoczone przez innych. Dzięki temu ma większe szanse na zauważenie pułapki, zapadni lub ukrytego przejścia. Spostrzegawczość jest używana głównie do ustalania tego co Bohater widzi, choć obejmuje także pozostałe zmysły. Może być więc użyta do określania doznań smakowych, zapachowych, słuchowych i dotykowych. Spostrzegawczość bywa często stosowana w przeciwstawnych testach umiejętnościom takim jak Charakteryzacja, Skradanie się, i Ukrywanie się. Udany test umiejętności pozwala Bohaterowi na określenie liczebności, odległości, wielkości obserwowanego obiektu, itp. Nieudany test może oznaczać uzyskanie niedokładnych informacji.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Sztuka przetrwania',
                'characteristic' => 'Int',
                'description' => 'Umiejętność ta może zapewnić przeżycie w dziczy. Obejmuje znajomość technik łowienia ryb, polowania, oprawiania zwierzyny, rozpalania ognia, znajdowania pożywienia, konstruowania szałasów, itp.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Śledzenie',
                'characteristic' => 'Zr',
                'description' => 'Wykorzystując tę umiejętność, bohater może podążać za kimś, samemu pozostając niezauważonym. Test śledzenia jest często wykorzystywany w przeciwstawnym teście umiejętności przeciwko Spostrzegawczości przeciwnika.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Targowanie',
                'characteristic' => 'Ogd',
                'description' => 'Umiejętność ta umożliwia negocjowanie cen towarów i usług. W przypadku towarów codziennego użytku wystarczy zwykły test umiejętności. Jeśli bohater targuje się o cenny przedmiot, MG może zarządzić przeciwstawny test Targowania (z ewentualnymi modyfikacjami trudności).'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Torturowanie',
                'characteristic' => 'Ogd',
                'description' => 'Dzięki zastosowaniu rozmaitych działań i środków przymusu Bohater potrafi wydobyć interesujące go informacje od osoby niechętnej do współpracy. Umiejętność obejmuje zarówno psychiczne znęcanie się, jak i fizyczne tortury. Ofiara może opierać się torturom, wykonując test Siły Woli.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Tresura',
                'characteristic' => 'Ogd',
                'description' => 'Bohater potrafi uczyć zwierzęta wykonywania różnych sztuczek i słuchania prostych poleceń. Zwykle tresurze poddaje się psy, konie i sokoły, choć MG może pozwolić na tresowanie bardziej niezwykłych zwierząt. Wyuczenie zwierzęcia zajmuje sporo czasu. Test umiejętności należy wykonać raz na tydzień tresury. Nauczenie prostej sztuczki wymaga pojedynczego, udanego testu, średnio trudna sztuczka wymaga trzech udanych testów umiejętności, natomiast bardzo trudna – dziesięciu.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Ukrywanie się',
                'characteristic' => 'Zr',
                'description' => 'Wykorzystanie tej umiejętności umożliwia bohaterowi ukrywanie się w niemal dowolnym terenie, pod warunkiem, że istnieje realna szansa schowania się za jakimś obiektem (mur, drzewo, budynek, itp.). Przy próbie ukrycia się na otwartej, pustej przestrzeni (na przykład na środku ulicy) test umiejętności automatycznie jest nieudany. Ukrywanie się bywa często wykorzystywane przeciwko testowi Spostrzegawczości przeciwnika.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Tropienie',
                'characteristic' => 'Int',
                'description' => 'Bohater potrafi wyszukiwać ślady zwierząt, a także ludzi i innych stworzeń. Podążanie wyraźnym tropem nie wymaga testu umiejętności i nie spowalnia tempa poruszania się. Test umiejętności może być jednak potrzebny w trudnych warunkach terenu lub pogody. Odpowiedni poziom skuteczności może dostarczyć dodatkowych informacji (o liczebności grupy, odległości od tropionego stworzenia, a nawet jego cechach osobniczych).'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Unik',
                'characteristic' => 'Zr',
                'description' => 'Wykorzystanie tej umiejętności umożliwia bohaterowi uniknięcie ataku podczas walki wręcz. Unik można stosować najwyżej raz na rundę.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Wiedza',
                'characteristic' => 'Int',
                'description' => 'Umiejętność ta zapewnia wiedzę o zwyczajach, strukturze władzy, najważniejszych dostojnikach, obyczajach ludowych oraz przesądach mieszkańców danej krainy, członków danej grupy społecznej lub przedstawicieli danej rasy. Widza nie jest równoznaczna ze studiami naukowymi (to oddaje umiejętność Nauka), lecz zapewnia jedynie podstawowe informacje, jakie bohater zdobył w czasie podróży po świecie. Podobnie jak w przypadku umiejętności Nauka, Wiedza to szersza kategoria obejmująca różne, oddzielnie zdobywane konkretne umiejętności'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Warzenie trucizn',
                'characteristic' => 'Int',
                'description' => 'Bohater potrafi przyrządzać rozmaite trucizny pochodzenia zwierzęcego lub roślinnego, a także trucizny uzyskiwane alchemicznie.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Wioślarstwo',
                'characteristic' => 'K',
                'description' => 'Bohater potrafi sterować tratwami, barkami i innymi łodziami wiosłowymi. Utrzymanie kursu w normalnych warunkach nie wymagają testu umiejętności. MG może uznać za stosowne wykonanie testu w przypadku kiepskiej pogody, wysokiej fali, pokonywania progów rzecznych lub omijania mielizn.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Wspinaczka',
                'characteristic' => 'K',
                'description' => 'Twój bohater potrafi wspinać się na drzewa, mury, skalne ściany i inne pionowe przeszkody. W normalnych warunkach test umiejętności wykonuje się raz na rundę. Wspinanie się w czasie walki wymaga poświęcenia akcji podwójnej. Udany test oznacza, że bohater wspiął się na wysokość równą połowie jego Szybkości, mierzonej w metrach.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Wycena',
                'characteristic' => 'Int',
                'description' => 'Bohater potrafi szacować wartość rzeczy codziennego użytku, jak również przedmiotów wartościowych, takich jak biżuteria, klejnoty i dzieła sztuki. Udany test umiejętności pozwala określić rynkową wartość przedmiotu. Ponieważ nieudany test umiejętności może spowodować błędne oszacowanie wartości przedmiotu, MG powinien wykonać rzut w tajemnicy i zależnie od wyniku poinformować gracza o ustalonej w ten sposób wartości przedmiotu.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Wykrywanie magii',
                'characteristic' => 'SW',
                'description' => 'Umiejętność tak umożliwia bohaterowi wykrywanie subtelnych zawirowań, jakie towarzyszą magicznej aurze. Czarodzieje opisują to jako szósty, siódmy i ósmy zmysł człowieka. Wśród chłopstwa to zjawisko jest znane pod nazwą „wiedźmi wzrok”. Udany test umiejętności pozwala określić, czy przedmiot, postać lub obszar pozostaje pod wpływem czaru. Wykorzystując tę umiejętność, czarodziej może ustalić siłę Wiatrów Magii w najbliższej okolicy.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Zastawianie pułapek',
                'characteristic' => 'Zr',
                'description' => 'Bohater potrafi konstruować różnego rodzaju pułapki na zwierzęta. W Imperium używa się pułapek unieruchamiających, jak też uśmiercających złapane zwierzę. Za każdą założoną pułapkę wykonuje się jeden test umiejętności na dzień. Udany test oznacza, że w pułapkę złapało się zwierzę.'
            ],
            [
                'type' => 'PODSTAWOWA',
                'name' => 'Zastraszanie',
                'characteristic' => 'K',
                'description' => 'Dzięki tej umiejętności bohater może zastraszać lub zmuszać do uległości inne osoby. Ofiary, które nie chcą ugiąć się przed groźbami, mogą wykonać test SW. Reakcja postaci zależy całkowicie od decyzji MG, który bierze pod uwagę jej osobowość oraz wynik testu Zastraszania. W niektórych sytuacjach (szantaż, itp.), MG może uznać, że bardziej odpowiednią cechą do testu Zastraszania może być Ogłada.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Znajomość języka',
                'characteristic' => 'Int',
                'description' => 'Ta umiejętność umożliwia bohaterowi porozumiewanie się w obcym języku. Większość języków Starego Świata wywodzi się ze wspólnego starożytnego narzecza, choć w ciągu wielu wieków różnice między poszczególnymi dialektami doprowadziły do powstania odrębnych języków. W normalnych warunkach, gdy wszyscy rozmawiający znają dany język, test umiejętności nie jest potrzebny. Test może być potrzebny w przypadku prób naśladowania regionalnych akcentów lub gdy bohater gracza próbuje przekonać słuchających, że język obcy jest jego ojczystym językiem.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Zwinne palce',
                'characteristic' => 'Zr',
                'description' => 'Dzięki tej użytecznej umiejętności bohater potrafi ukryć w dłoni małe przedmioty lub wykonywać sztuczki z kartami i monetami. Zwinne palce przydają się również przy ukradkowym sięganiu do cudzych sakiewek. Test zwinnych palców jest często wykonywany w przeciwstawnym teście umiejętności przeciwko Spostrzegawczości przeciwnika.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Żeglarstwo',
                'characteristic' => 'Zr',
                'description' => 'Dzięki tej umiejętności bohater potrafi sterować statkami żaglowymi. Dodatkowo bohater dysponuje wiedzą o budowie okrętów, różnych rodzajach żagli, a także umiejętnością przewidywania pogody na morzu. Żeglowanie po spokojnych wodach nie wymaga testu umiejętności. Trudne warunki pogodowe, wysokie fale lub wykonywanie manewrów w czasie bitwy mogą wymagać testu umiejętności o odpowiednim stopniu trudności.'
            ],
            [
                'type' => 'ZAAWANSOWANA',
                'name' => 'Nauka',
                'characteristic' => 'Int',
                'description' => 'Ta umiejętność wykorzystywana jest do zapamiętywania ważniejszych informacji i liczb, jak też (gdy Bohater posiada materiały pomocnicze i odpowiednie zasoby) do badań naukowych. Wymaga intensywnych studiów, lecz zapewnia znacznie szerszą i jednocześnie bardziej szczegółową znajomość problemu niż w przypadku wiedzy ogólnej. Nauka nie jest pojedynczą umiejętnością, lecz kategorią obejmującą różne, odrębnie traktowane umiejętności. Każda z nich musi zostać wykupiona oddzielnie. Musisz wydać 100 PD za każdą opanowaną naukę (w nawiasie wpisujesz nazwę wyuczonej specjalności). Na przykład umiejętność nauka (teologia) jest odrębną umiejętnością niż nauka (historia).'
            ]
        ]);
    }
}
