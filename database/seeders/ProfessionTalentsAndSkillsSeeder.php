<?php

namespace Database\Seeders;

use App\Models\Profession;
use App\Models\Skill;
use App\Models\Talent;
use DB;
use Illuminate\Database\Seeder;

class ProfessionTalentsAndSkillsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $professions = Profession::all()->pluck('id', 'name')->toArray();
        $talents = Talent::all()
            ->keyBy('name');
        $skills = Skill::all()
            ->keyBy('name');

        $careers_data = [
            'Akolita' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Leczenie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Nauka'], 'additional_name' => 'astronomia'],
                        ['skill_id' => $skills['Nauka'], 'additional_name' => 'historia']
                    ],
                    [['skill_id' => $skills['Nauka'], 'additional_name' => 'teologia']],
                    [['skill_id' => $skills['Przekonywanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'klasyczny']],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'staroświatowy']],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Bardzo silny'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybki refleks'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Charyzmatyczny'], 'additional_name' => null],
                        ['talent_id' => $talents['Urodzony wojownik'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Przemawianie'], 'additional_name' => null]],
                ]
            ],

            'Banita' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium']
                    ],
                    [
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'złodziei']
                    ],
                    [
                        ['skill_id' => $skills['Powożenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Jeździectwo'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Skradanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Ukrywanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wspinaczka'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Zastawianie pułapek'], 'additional_name' => null],
                        ['skill_id' => $skills['Pływanie'], 'additional_name' => null]
                    ],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Strzał mierzony'], 'additional_name' => null],
                        ['talent_id' => $talents['Ogłuszanie'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Wędrowiec'], 'additional_name' => null],
                        ['talent_id' => $talents['Łotrzyk'], 'additional_name' => null]
                    ],
                ]
            ],

            'Berserker z Norski' => [
                'skills' => [
                    [['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'gawędziarstwo']],
                    [['skill_id' => $skills['Mocna głowa'], 'additional_name' => null]],
                    [['skill_id' => $skills['Pływanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wiedza'], 'additional_name' => 'Norska']],
                    [['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'norski']],
                ],
                'talents' => [
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'dwuręczna']],
                    [['talent_id' => $talents['Groźny'], 'additional_name' => null]],
                    [['talent_id' => $talents['Szał bojowy'], 'additional_name' => null]],
                    [['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null]],
                ]
            ],

            'Chłop' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Hazard'], 'additional_name' => null],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'taniec'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'śpiew']
                    ],
                    [
                        ['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null],
                        ['skill_id' => $skills['Przekonywanie'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Oswajanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'gotowanie']
                    ],
                    [
                        ['skill_id' => $skills['Powożenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'wyrób łuków']
                    ],
                    [
                        ['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'uprawa ziemi']
                    ],
                    [
                        ['skill_id' => $skills['Tresura'], 'additional_name' => null],
                        ['skill_id' => $skills['Pływanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Ukrywanie się'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wioślarstwo'], 'additional_name' => null],
                        ['skill_id' => $skills['Zastawianie pułapek'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Wspinaczka'], 'additional_name' => null],
                        ['skill_id' => $skills['Skradanie się'], 'additional_name' => null]
                    ],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Chodu!'], 'additional_name' => null],
                        ['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'proca']
                    ],
                    [
                        ['talent_id' => $talents['Twardziel'], 'additional_name' => null],
                        ['talent_id' => $talents['Wędrowiec'], 'additional_name' => null]
                    ],
                ]
            ],

            'Ciura obozowa' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null],
                        ['skill_id' => $skills['Powożenie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Przekonywanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Wycena'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'gotowanie'],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'handel'],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'kartografia'],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'kowalstwo'],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'krawiectwo'],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'płatnerstwo'],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'rusznikarstwo'],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'wyrób łuków'],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'zielarstwo'],
                    ],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Targowanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'bretoński'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'kislevski'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'tileański']
                    ],
                    [['skill_id' => $skills['Zwinne palce'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Chodu!'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Odporność na choroby'], 'additional_name' => null],
                        ['talent_id' => $talents['Obieżyświat'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Twardziel'], 'additional_name' => null],
                        ['talent_id' => $talents['Charyzmatyczny'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Żyłka handlowa'], 'additional_name' => null],
                        ['talent_id' => $talents['Bijatyka'], 'additional_name' => null]
                    ],
                ]
            ],

            'Cyrkowiec' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'Akrobatyka'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'aktorstwo'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'błaznowanie'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'gawędziarstwo'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'komedianctwo'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'mimika'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'muzykalność'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'śpiew'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'wróżenie z dłoni'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'żonglerka'],
                    ],
                    [
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'Akrobatyka'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'aktorstwo'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'błaznowanie'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'gawędziarstwo'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'komedianctwo'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'mimika'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'muzykalność'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'śpiew'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'wróżenie z dłoni'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'żonglerka'],
                    ],
                    [
                        ['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null],
                        ['skill_id' => $skills['Pływanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Przekonywanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium']],
                    [
                        ['skill_id' => $skills['Wycena'], 'additional_name' => null],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'staroświatowy']],
                    [
                        ['skill_id' => $skills['Brzuchomówstwo'], 'additional_name' => null],
                        ['skill_id' => $skills['Gadanina'], 'additional_name' => null],
                        ['skill_id' => $skills['Hipnoza'], 'additional_name' => null],
                        ['skill_id' => $skills['Jeździectwo'], 'additional_name' => null],
                        ['skill_id' => $skills['Oswajanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Tresura'], 'additional_name' => null],
                        ['skill_id' => $skills['Wspinaczka'], 'additional_name' => null],
                        ['skill_id' => $skills['Zwinne palce'], 'additional_name' => null],
                    ],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Bardzo silny'], 'additional_name' => null],
                        ['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'rzucana'],
                        ['talent_id' => $talents['Naśladowca'], 'additional_name' => null],
                        ['talent_id' => $talents['Przemawianie'], 'additional_name' => null],
                        ['talent_id' => $talents['Strzał mierzony'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybki refleks'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null],
                        ['talent_id' => $talents['Woltyżerka'], 'additional_name' => null],
                        ['talent_id' => $talents['Zapasy'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Bardzo silny'], 'additional_name' => null],
                        ['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'rzucana'],
                        ['talent_id' => $talents['Naśladowca'], 'additional_name' => null],
                        ['talent_id' => $talents['Przemawianie'], 'additional_name' => null],
                        ['talent_id' => $talents['Strzał mierzony'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybki refleks'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null],
                        ['talent_id' => $talents['Woltyżerka'], 'additional_name' => null],
                        ['talent_id' => $talents['Zapasy'], 'additional_name' => null]
                    ],
                ]
            ],

            'Cyrulik' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Leczenie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Powożenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Pływanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Przekonywanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'aptekarstwo']],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Targowanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'bretoński'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'staroświatowy'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'tileański']
                    ],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Charyzmatyczny'], 'additional_name' => null],
                        ['talent_id' => $talents['Niezwykle odporny'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Chirurgia'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Odporność na choroby'], 'additional_name' => null],
                        ['talent_id' => $talents['Błyskotliwość'], 'additional_name' => null]
                    ],
                ]
            ],

            'Fanatyk' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Nauka'], 'additional_name' => 'teologia']],
                    [['skill_id' => $skills['Przekonywanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium']],
                    [['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'korbacze']],
                    [
                        ['talent_id' => $talents['Opanowanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Bardzo silny'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Przemawianie'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Twardziel'], 'additional_name' => null],
                        ['talent_id' => $talents['Charyzmatyczny'], 'additional_name' => null]
                    ],
                ]
            ],

            'Flisak' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Mocna głowa'], 'additional_name' => null],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Nawigacja'], 'additional_name' => null]],
                    [['skill_id' => $skills['Pływanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Sekretny język'], 'additional_name' => 'łowców'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'kislevski']
                    ],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Kislev']
                    ],
                    [['skill_id' => $skills['Wioślarstwo'], 'additional_name' => null]],
                    [['skill_id' => $skills['Żeglarstwo'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Obieżyświat'], 'additional_name' => null]],
                    [['talent_id' => $talents['Wyczucie kierunku'], 'additional_name' => null]],
                ]
            ],

            'Giermek' => [
                'skills' => [
                    [['skill_id' => $skills['Jeździectwo'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Nauka'], 'additional_name' => 'genealogia/heraldyka'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Bretonia']
                    ],
                    [['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Przekonywanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Tresura'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'bretoński'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'staroświatowy']
                    ],
                ],
                'talents' => [
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'kawaleryjska']],
                    [['talent_id' => $talents['Etykieta'], 'additional_name' => null]],
                    [['talent_id' => $talents['Silny cios'], 'additional_name' => null]],
                ]
            ],

            'Gladiator' => [
                'skills' => [
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Bardzo silny'], 'additional_name' => null],
                        ['talent_id' => $talents['Odporność psychiczna'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'dwuręczna']],
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'korbacze']],
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'parująca']],
                    [
                        ['talent_id' => $talents['Rozbrajanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Zapasy'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Silny cios'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null],
                        ['talent_id' => $talents['Morderczy atak'], 'additional_name' => null]
                    ],
                ]
            ],

            'Goniec' => [
                'skills' => [
                    [['skill_id' => $skills['Nawigacja'], 'additional_name' => null]],
                    [['skill_id' => $skills['Pływanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'zwiadowców']],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Bardzo szybki'], 'additional_name' => null],
                        ['talent_id' => $talents['Szósty zmysł'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Niezwykle odporny'], 'additional_name' => null],
                        ['talent_id' => $talents['Bardzo silny'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Błyskawiczne przeładowanie'], 'additional_name' => null]],
                    [['talent_id' => $talents['Chodu!'], 'additional_name' => null]],
                    [['talent_id' => $talents['Wyczucie kierunku'], 'additional_name' => null]],
                ]
            ],

            'Górnik' => [
                'skills' => [
                    [['skill_id' => $skills['Nawigacja'], 'additional_name' => null]],
                    [['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'górnictwo'],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'górnictwo odkrywkowe']
                    ],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Ukrywanie się'], 'additional_name' => null],
                        ['skill_id' => $skills['Powożenie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Wspinaczka'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wycena'], 'additional_name' => null],
                        ['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]
                    ],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Niezwykle odporny'], 'additional_name' => null],
                        ['talent_id' => $talents['Urodzony wojownik'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'dwuręczna']],
                    [['talent_id' => $talents['Wyczucie kierunku'], 'additional_name' => null]],
                ]
            ],

            'Guślarz' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Leczenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Hipnoza'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null],
                        ['skill_id' => $skills['Targowanie'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Oswajanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'aptekarstwo']
                    ],
                    [
                        ['skill_id' => $skills['Przekonywanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Splatanie magii'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wykrywanie magii'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Gusła'], 'additional_name' => null]],
                    [['talent_id' => $talents['Magia prosta'], 'additional_name' => 'gusła']],
                ]
            ],

            'Hiena cmentarna' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Otwieranie zamków'], 'additional_name' => null],
                        ['skill_id' => $skills['Skradanie się'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Ukrywanie się'], 'additional_name' => null],
                        ['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium'],
                        ['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'złodziei']
                    ],
                    [['skill_id' => $skills['Wspinaczka'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wycena'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'citharin'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'khazalid'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'klasyczny']
                    ],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Szczęście'], 'additional_name' => null],
                        ['talent_id' => $talents['Szósty zmysł'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Wykrywanie pułapek'], 'additional_name' => null],
                        ['talent_id' => $talents['Grotołaz'], 'additional_name' => null]
                    ],
                ]
            ],

            'Kanciarz' => [
                'skills' => [
                    [['skill_id' => $skills['Gadanina'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Hazard'], 'additional_name' => null],
                        ['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'złodziei']
                    ],
                    [
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'aktorstwo'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'gawędziarstwo']
                    ],
                    [
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Targowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Przekonywanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Sekretny język'], 'additional_name' => 'złodziejski']
                    ],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wycena'], 'additional_name' => null]],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'staroświatowy']],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Chodu!'], 'additional_name' => null],
                        ['talent_id' => $talents['Łotrzyk'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Przemawianie'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Szczęście'], 'additional_name' => null],
                        ['talent_id' => $talents['Szósty zmysł'], 'additional_name' => null]
                    ],
                ]
            ],

            'Kozak kislevski' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Hazard'], 'additional_name' => null],
                        ['skill_id' => $skills['Targowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Mocna głowa'], 'additional_name' => null]],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wiedza'], 'additional_name' => 'Kislev']],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'kislevski']],
                ],
                'talents' => [
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'dwuręczna']],
                    [['talent_id' => $talents['Morderczy atak'], 'additional_name' => null]],
                ]
            ],

            'Leśnik' => [
                'skills' => [
                    [['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'łowców']],
                    [['skill_id' => $skills['Sekretny język'], 'additional_name' => 'łowców']],
                    [['skill_id' => $skills['Skradanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Tropienie'], 'additional_name' => null],
                        ['skill_id' => $skills['Zastawianie pułapek'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Ukrywanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wspinaczka'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Bardzo szybki'], 'additional_name' => null],
                        ['talent_id' => $talents['Niezwykle odporny'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'dwuręczna']],
                    [['talent_id' => $talents['Wędrowiec'], 'additional_name' => null]],
                ]
            ],

            'Łowca' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Pływanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'łowców']],
                    [
                        ['skill_id' => $skills['Skradanie się'], 'additional_name' => null],
                        ['skill_id' => $skills['Zastawianie pułapek'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [['skill_id' => $skills['Tropienie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Ukrywanie się'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Błyskawiczne przeładowanie'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Strzelec wyborowy'], 'additional_name' => null],
                        ['talent_id' => $talents['Wędrowiec'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Szybki refleks'], 'additional_name' => null],
                        ['talent_id' => $talents['Niezwykle odporny'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Twardziel'], 'additional_name' => null],
                        ['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'długi łuk']
                    ],
                ]
            ],

            'Łowca nagród' => [
                'skills' => [
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Skradanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [['skill_id' => $skills['Śledzenie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Tropienie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'unieruchamiająca']],
                    [
                        ['talent_id' => $talents['Strzał mierzony'], 'additional_name' => null],
                        ['talent_id' => $talents['Silny cios'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Strzelec wyborowy'], 'additional_name' => null],
                        ['talent_id' => $talents['Ogłuszanie'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Wędrowiec'], 'additional_name' => null]],
                ]
            ],

            'Mieszczanin' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Powożenie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Targowanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium'],
                        ['skill_id' => $skills['Mocna głowa'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Wycena'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'bretoński'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'kislevski'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'tileański']
                    ],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'staroświatowy']],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Błyskotliwość'], 'additional_name' => null],
                        ['talent_id' => $talents['Charyzmatyczny'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Żyłka handlowa'], 'additional_name' => null]],
                ]
            ],

            'Mytnik' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Targowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wycena'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'bretoński'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'kislevski'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'tileański']
                    ],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Szybki refleks'], 'additional_name' => null],
                        ['talent_id' => $talents['Strzelec wyborowy'], 'additional_name' => null]
                    ],
                ]
            ],

            'Najemnik' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null],
                        ['skill_id' => $skills['Hazard'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Targowanie'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Powożenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Jeździectwo'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Sekretny język'], 'additional_name' => 'bitewny']],
                    [
                        ['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null],
                        ['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Bretonia'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Kislev'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Tilea']
                    ],
                    [
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'tileański'],
                        ['skill_id' => $skills['Pływanie'], 'additional_name' => null]
                    ],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Błyskawiczne przeładowanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Silny cios'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Rozbrajanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Strzał mierzony'], 'additional_name' => null],
                        ['talent_id' => $talents['Ogłuszanie'], 'additional_name' => null]
                    ],
                ]
            ],

            'Ochotnik' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Hazard'], 'additional_name' => null],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Powożenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Pływanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'dowolne']],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'dwuręczna'],
                        ['talent_id' => $talents['Błyskawiczne przeładowanie'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Silny cios'], 'additional_name' => null]],
                ]
            ],

            'Ochroniarz' => [
                'skills' => [
                    [['skill_id' => $skills['Leczenie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Bardzo silny'], 'additional_name' => null],
                        ['talent_id' => $talents['Niezwykle odporny'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Bijatyka'], 'additional_name' => null]],
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'parująca']],
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'rzucana']],
                    [['talent_id' => $talents['Ogłuszanie'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Rozbrajanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null]
                    ],
                ]
            ],

            'Oprych' => [
                'skills' => [
                    [['skill_id' => $skills['Hazard'], 'additional_name' => null]],
                    [['skill_id' => $skills['Mocna głowa'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sekretny język'], 'additional_name' => 'złodziejski']],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Morderczy atak'], 'additional_name' => null],
                        ['talent_id' => $talents['Zapasy'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Odporność na trucizny'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Ogłuszanie'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Opanowanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybki refleks'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Rozbrajanie'], 'additional_name' => null]],
                ]
            ],

            'Paź' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Zwinne palce'], 'additional_name' => null] // Zgodnie z opisem profesji sługi, ale tu w Pazi u jest "czytanie i pisanie" jako pewnik w tabeli
                    ],
                    [['skill_id' => $skills['Gadanina'], 'additional_name' => null]],
                    [['skill_id' => $skills['Nauka'], 'additional_name' => 'genealogia/heraldyka']],
                    [
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'bretoński'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'staroświatowy']
                    ],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Targowanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wycena'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Etykieta'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Opanowanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Charyzmatyczny'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Żyłka handlowa'], 'additional_name' => null],
                        ['talent_id' => $talents['Obieżyświat'], 'additional_name' => null]
                    ],
                ]
            ],

            'Podżegacz' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Nauka'], 'additional_name' => 'historia'],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Nauka'], 'additional_name' => 'prawo'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium']
                    ],
                    [['skill_id' => $skills['Przekonywanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Ukrywanie się'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'bretoński'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'tileański']
                    ],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'staroświatowy']],
                ],
                'talents' => [
                    [['talent_id' => $talents['Chodu!'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Opanowanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Bijatyka'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Przemawianie'], 'additional_name' => null]],
                ]
            ],

            'Porywacz zwłok' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Targowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Powożenie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'złodziei']],
                    [['skill_id' => $skills['Skradanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wspinaczka'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Chodu!'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Łotrzyk'], 'additional_name' => null],
                        ['talent_id' => $talents['Odporność psychiczna'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Odporność na choroby'], 'additional_name' => null]],
                ]
            ],

            'Posłaniec' => [
                'skills' => [
                    [['skill_id' => $skills['Jeździectwo'], 'additional_name' => null]],
                    [['skill_id' => $skills['Nawigacja'], 'additional_name' => null]],
                    [['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null]],
                    [['skill_id' => $skills['Pływanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'zwiadowców']],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Jałowa Kraina'],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'staroświatowy']],
                ],
                'talents' => [
                    [['talent_id' => $talents['Obieżyświat'], 'additional_name' => null]],
                    [['talent_id' => $talents['Wyczucie kierunku'], 'additional_name' => null]],
                ]
            ],

            'Przemytnik' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Sekretny język'], 'additional_name' => 'złodziejski']
                    ],
                    [['skill_id' => $skills['Pływanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Powożenie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Skradanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Targowanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wioślarstwo'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wycena'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'bretoński'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'kislevski'],
                        ['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'złodziei']
                    ],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Żyłka handlowa'], 'additional_name' => null],
                        ['talent_id' => $talents['Łotrzyk'], 'additional_name' => null]
                    ],
                ]
            ],

            'Przepatrywacz' => [
                'skills' => [
                    [['skill_id' => $skills['Jeździectwo'], 'additional_name' => null]],
                    [['skill_id' => $skills['Nawigacja'], 'additional_name' => null]],
                    [['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null]],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Skradanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [['skill_id' => $skills['Tropienie'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'unieruchamiająca']],
                    [
                        ['talent_id' => $talents['Opanowanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Bardzo silny'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Wyczucie kierunku'], 'additional_name' => null]],
                ]
            ],

            'Przewoźnik' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Pływanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Przekonywanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Targowanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium']],
                    [['skill_id' => $skills['Wioślarstwo'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wycena'], 'additional_name' => null],
                        ['skill_id' => $skills['Sekretny język'], 'additional_name' => 'łowców']
                    ],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'palna'],
                        ['talent_id' => $talents['Bijatyka'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Strzelec wyborowy'], 'additional_name' => null],
                        ['talent_id' => $talents['Charyzmatyczny'], 'additional_name' => null]
                    ],
                ]
            ],

            'Rybak' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Mocna głowa'], 'additional_name' => null],
                        ['skill_id' => $skills['Targowanie'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Nawigacja'], 'additional_name' => null],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'handel']
                    ],
                    [['skill_id' => $skills['Pływanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Jałowa Kraina']
                    ],
                    [['skill_id' => $skills['Wioślarstwo'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'staroświatowy'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'norski']
                    ],
                    [['skill_id' => $skills['Żeglarstwo'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Twardziel'], 'additional_name' => null],
                        ['talent_id' => $talents['Błyskotliwość'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Wyczucie kierunku'], 'additional_name' => null],
                        ['talent_id' => $talents['Bijatyka'], 'additional_name' => null]
                    ],
                ]
            ],

            'Rzecznik rodu' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Pływanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Przekonywanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'handel']],
                    [['skill_id' => $skills['Sekretny język'], 'additional_name' => 'gildii']],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Targowanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Jałowa Kraina']
                    ],
                    [['skill_id' => $skills['Wycena'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Obieżyświat'], 'additional_name' => null],
                        ['talent_id' => $talents['Żyłka handlowa'], 'additional_name' => null]
                    ],
                ]
            ],

            'Rzemieślnik' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Powożenie'], 'additional_name' => null]],
                    // "Rzemiosło (dowolne dwa)"
                    [
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'dowolne'],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'dowolne']
                    ],
                    [['skill_id' => $skills['Sekretny język'], 'additional_name' => 'gildii']],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Targowanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wycena'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Żyłka handlowa'], 'additional_name' => null],
                        ['talent_id' => $talents['Błyskotliwość'], 'additional_name' => null]
                    ],
                ]
            ],

            'Rzezimieszek' => [
                'skills' => [
                    [['skill_id' => $skills['Jeździectwo'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Targowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Bijatyka'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Groźny'], 'additional_name' => null],
                        ['talent_id' => $talents['Charyzmatyczny'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Morderczy atak'], 'additional_name' => null]],
                    [['talent_id' => $talents['Ogłuszanie'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Rozbrajanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Silny cios'], 'additional_name' => null]],
                ]
            ],

            'Skryba' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Nauka'], 'additional_name' => 'dowolna']],
                    [['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'kaligrafia']],
                    [['skill_id' => $skills['Sekretny język'], 'additional_name' => 'gildii']],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium'],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'bretoński']],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'klasyczny']],
                    [
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'staroświatowy'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'tileański']
                    ],
                ],
                'talents' => [
                    [['talent_id' => $talents['Poliglota'], 'additional_name' => null]],
                ]
            ],

            'Sługa' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Zwinne palce'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Gadanina'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'gotowanie']
                    ],
                    [['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Powożenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Targowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Wycena'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Niezwykle odporny'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybki refleks'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Czuły słuch'], 'additional_name' => null],
                        ['talent_id' => $talents['Chodu!'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Etykieta'], 'additional_name' => null],
                        ['talent_id' => $talents['Twardziel'], 'additional_name' => null]
                    ],
                ]
            ],

            'Strażnik' => [
                'skills' => [
                    [['skill_id' => $skills['Nauka'], 'additional_name' => 'prawo']],
                    [['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Tropienie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Ogłuszanie'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Opanowanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Błyskotliwość'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Rozbrajanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Bijatyka'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Silny cios'], 'additional_name' => null]],
                ]
            ],

            'Strażnik dróg' => [
                'skills' => [
                    [['skill_id' => $skills['Jeździectwo'], 'additional_name' => null]],
                    [['skill_id' => $skills['Nawigacja'], 'additional_name' => null]],
                    [['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null]],
                    [['skill_id' => $skills['Powożenie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Tropienie'], 'additional_name' => null],
                        ['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'zwiadowców']
                    ],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium'],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                ],
                'talents' => [
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'palna']],
                    [
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null],
                        ['talent_id' => $talents['Błyskawiczne przeładowanie'], 'additional_name' => null]
                    ],
                ]
            ],

            'Strażnik pól' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Nauka'], 'additional_name' => 'nekromancja'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium']
                    ],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Skradanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [['skill_id' => $skills['Tropienie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Ukrywanie się'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Bardzo szybki'], 'additional_name' => null],
                        ['talent_id' => $talents['Błyskotliwość'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Strzał precyzyjny'], 'additional_name' => null],
                        ['talent_id' => $talents['Błyskawiczne przeładowanie'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Wędrowiec'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null]
                    ],
                ]
            ],

            'Strażnik więzienny' => [
                'skills' => [
                    [['skill_id' => $skills['Dowodzenie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Leczenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Zwinne palce'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Mocna głowa'], 'additional_name' => null]],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'unieruchamiająca']],
                    [['talent_id' => $talents['Odporność na choroby'], 'additional_name' => null]],
                    [['talent_id' => $talents['Odporność na trucizny'], 'additional_name' => null]],
                    [['talent_id' => $talents['Zapasy'], 'additional_name' => null]],
                ]
            ],

            'Szczurołap' => [
                'skills' => [
                    [['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null]],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Skradanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Tresura'], 'additional_name' => null]],
                    [['skill_id' => $skills['Ukrywanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Zastawianie pułapek'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'proca']],
                    [['talent_id' => $talents['Grotołaz'], 'additional_name' => null]],
                    [['talent_id' => $talents['Odporność na choroby'], 'additional_name' => null]],
                    [['talent_id' => $talents['Odporność na trucizny'], 'additional_name' => null]],
                ]
            ],

            'Szermierz estalijski' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Nauka'], 'additional_name' => 'anatomia']],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wiedza'], 'additional_name' => 'Estalia']],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'estalijski']],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Brawura'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybki refleks'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'szermiercza']],
                    [['talent_id' => $talents['Silny cios'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null],
                        ['talent_id' => $talents['Morderczy atak'], 'additional_name' => null]
                    ],
                ]
            ],

            'Szlachcic' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Gadanina'], 'additional_name' => null],
                        ['skill_id' => $skills['Dowodzenie'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Hazard'], 'additional_name' => null],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Jeździectwo'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Mocna głowa'], 'additional_name' => null],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'muzykalność']
                    ],
                    [['skill_id' => $skills['Przekonywanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium']],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'staroświatowy']],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Błyskotliwość'], 'additional_name' => null],
                        ['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'szermiercza']
                    ],
                    [
                        ['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'parująca'],
                        ['talent_id' => $talents['Intrygant'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Etykieta'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Szczęście'], 'additional_name' => null],
                        ['talent_id' => $talents['Przemawianie'], 'additional_name' => null]
                    ],
                ]
            ],

            'Śmieciarz' => [
                'skills' => [
                    [['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null]],
                    [['skill_id' => $skills['Powożenie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Przekonywanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Targowanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium']],
                    [['skill_id' => $skills['Wycena'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Opanowanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Łotrzyk'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Twardziel'], 'additional_name' => null],
                        ['talent_id' => $talents['Odporność na choroby'], 'additional_name' => null]
                    ],
                ]
            ],

            'Tarczownik' => [
                'skills' => [
                    [['skill_id' => $skills['Nawigacja'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Śledzenie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wspinaczka'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Czuły słuch'], 'additional_name' => null],
                        ['talent_id' => $talents['Opanowanie'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Morderczy atak'], 'additional_name' => null]],
                    [['talent_id' => $talents['Ogłuszanie'], 'additional_name' => null]],
                    [['talent_id' => $talents['Silny cios'], 'additional_name' => null]],
                    [['talent_id' => $talents['Wyczucie kierunku'], 'additional_name' => null]],
                ]
            ],

            'Uczeń czarodzieja' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Język tajemny'], 'additional_name' => 'magiczny']],
                    [['skill_id' => $skills['Nauka'], 'additional_name' => 'magia']],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Splatanie magii'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wykrywanie magii'], 'additional_name' => null]],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'klasyczny']],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Błyskotliwość'], 'additional_name' => null],
                        ['talent_id' => $talents['Niezwykle odporny'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Magia prosta'], 'additional_name' => 'tajemna']],
                    [
                        ['talent_id' => $talents['Zmysł magii'], 'additional_name' => null],
                        ['talent_id' => $talents['Dotyk mocy'], 'additional_name' => null]
                    ],
                ]
            ],

            'Węglarz' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Powożenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'łowców']],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [['skill_id' => $skills['Targowanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium'],
                        ['skill_id' => $skills['Ukrywanie się'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Wspinaczka'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Błyskotliwość'], 'additional_name' => null],
                        ['talent_id' => $talents['Bardzo silny'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Chodu!'], 'additional_name' => null]],
                ]
            ],

            'Włóczykij' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'gawędziarstwo'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'śpiew'],
                        ['skill_id' => $skills['Kuglarstwo'], 'additional_name' => 'taniec'],
                        ['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'łowców'],
                        ['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'złodziei']
                    ],
                    [
                        ['skill_id' => $skills['Leczenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Nawigacja'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Sekretny język'], 'additional_name' => 'łowców'],
                        ['skill_id' => $skills['Sekretny język'], 'additional_name' => 'złodziejski']
                    ],
                    [['skill_id' => $skills['Skradanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Targowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Pływanie'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Bretonia'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Estalia'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Kislev'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Tilea']
                    ],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Bardzo szybki'], 'additional_name' => null],
                        ['talent_id' => $talents['Wędrowiec'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Obieżyświat'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Strzelec wyborowy'], 'additional_name' => null],
                        ['talent_id' => $talents['Wyczucie kierunku'], 'additional_name' => null]
                    ],
                ]
            ],

            'Wojownik klanowy' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Leczenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Skradanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sztuka przetrwania'], 'additional_name' => null]],
                    [['skill_id' => $skills['Tropienie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Ukrywanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wspinaczka'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Błyskawiczne przeładowanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Urodzony wojownik'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Strzelec wyborowy'], 'additional_name' => null],
                        ['talent_id' => $talents['Wędrowiec'], 'additional_name' => null]
                    ],
                ]
            ],

            'Woźnica' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Leczenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Jeździectwo'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Nawigacja'], 'additional_name' => null]],
                    [['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Targowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Powożenie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'łowców']],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'bretoński'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'kislevski'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'tileański']
                    ],
                ],
                'talents' => [
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'palna']],
                    [
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null],
                        ['talent_id' => $talents['Obieżyświat'], 'additional_name' => null]
                    ],
                ]
            ],

            'Zabójca trolli' => [
                'skills' => [
                    [['skill_id' => $skills['Mocna głowa'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Bijatyka'], 'additional_name' => null]],
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'dwuręczna']],
                    [
                        ['talent_id' => $talents['Rozbrajanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Silny cios'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Szybki refleks'], 'additional_name' => null],
                        ['talent_id' => $talents['Niezwykle odporny'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Twardziel'], 'additional_name' => null]],
                ]
            ],

            'Zarządca' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Dowodzenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Nawigacja'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Jeździectwo'], 'additional_name' => null]],
                    [['skill_id' => $skills['Nauka'], 'additional_name' => 'prawo']],
                    [
                        ['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Przekonywanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Zastraszanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium']
                    ],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Etykieta'], 'additional_name' => null],
                        ['talent_id' => $talents['Geniusz arytmetyczny'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Przemawianie'], 'additional_name' => null]],
                ]
            ],

            'Złodziej' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Zwinne palce'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Hazard'], 'additional_name' => null],
                        ['skill_id' => $skills['Otwieranie zamków'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Przekonywanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Wspinaczka'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Sekretny język'], 'additional_name' => 'złodziejski'],
                        ['skill_id' => $skills['Sekretne znaki'], 'additional_name' => 'złodziei']
                    ],
                    [['skill_id' => $skills['Skradanie się'], 'additional_name' => null]],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Ukrywanie się'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wycena'], 'additional_name' => null],
                        ['skill_id' => $skills['Charakteryzacja'], 'additional_name' => null]
                    ],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Geniusz arytmetyczny'], 'additional_name' => null],
                        ['talent_id' => $talents['Wykrywanie pułapek'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Ulicznik'], 'additional_name' => null],
                        ['talent_id' => $talents['Łotrzyk'], 'additional_name' => null]
                    ],
                ]
            ],

            'Żak' => [
                'skills' => [
                    [['skill_id' => $skills['Czytanie i pisanie'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Leczenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Przeszukiwanie'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Nauka'], 'additional_name' => 'dowolna']],
                    [
                        ['skill_id' => $skills['Nauka'], 'additional_name' => 'dowolna'], // druga nauka
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Przekonywanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Mocna głowa'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'klasyczny']],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'staroświatowy']],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Błyskotliwość'], 'additional_name' => null],
                        ['talent_id' => $talents['Charyzmatyczny'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Etykieta'], 'additional_name' => null],
                        ['talent_id' => $talents['Poliglota'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Obieżyświat'], 'additional_name' => null],
                        ['talent_id' => $talents['Geniusz arytmetyczny'], 'additional_name' => null]
                    ],
                ]
            ],

            'Żeglarz' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Mocna głowa'], 'additional_name' => null],
                        ['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Pływanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Bretonia'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Norska'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Tilea'],
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Jałowa Kraina']
                    ],
                    [['skill_id' => $skills['Wioślarstwo'], 'additional_name' => null]],
                    [['skill_id' => $skills['Wspinaczka'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'bretoński'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'norski'],
                        ['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'tileański']
                    ],
                    [['skill_id' => $skills['Żeglarstwo'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Obieżyświat'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Silny cios'], 'additional_name' => null],
                        ['talent_id' => $talents['Brawura'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Twardziel'], 'additional_name' => null],
                        ['talent_id' => $talents['Bijatyka'], 'additional_name' => null]
                    ],
                ]
            ],

            'Żołnierz' => [
                'skills' => [
                    [
                        ['skill_id' => $skills['Hazard'], 'additional_name' => null],
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Opieka nad zwierzętami'], 'additional_name' => null],
                        ['skill_id' => $skills['Leczenie'], 'additional_name' => null]
                    ],
                    [
                        ['skill_id' => $skills['Powożenie'], 'additional_name' => null],
                        ['skill_id' => $skills['Jeździectwo'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium'],
                        ['skill_id' => $skills['Spostrzegawczość'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]],
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'palna'],
                        ['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'dwuręczna']
                    ],
                    [
                        ['talent_id' => $talents['Morderczy atak'], 'additional_name' => null],
                        ['talent_id' => $talents['Błyskawiczne przeładowanie'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Ogłuszanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Strzał precyzyjny'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Rozbrajanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null]
                    ],
                    [
                        ['talent_id' => $talents['Strzał mierzony'], 'additional_name' => null],
                        ['talent_id' => $talents['Silny cios'], 'additional_name' => null]
                    ],
                ]
            ],

            'Żołnierz okrętowy' => [
                'skills' => [
                    [['skill_id' => $skills['Mocna głowa'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Plotkowanie'], 'additional_name' => null],
                        ['skill_id' => $skills['Sekretny język'], 'additional_name' => 'bitewny']
                    ],
                    [['skill_id' => $skills['Pływanie'], 'additional_name' => null]],
                    [['skill_id' => $skills['Unik'], 'additional_name' => null]],
                    [
                        ['skill_id' => $skills['Wiedza'], 'additional_name' => 'Jałowa Kraina'],
                        ['skill_id' => $skills['Hazard'], 'additional_name' => null]
                    ],
                    [['skill_id' => $skills['Wioślarstwo'], 'additional_name' => null]],
                    [['skill_id' => $skills['Zastraszanie'], 'additional_name' => null]],
                ],
                'talents' => [
                    [['talent_id' => $talents['Ogłuszanie'], 'additional_name' => null]],
                    [
                        ['talent_id' => $talents['Rozbrajanie'], 'additional_name' => null],
                        ['talent_id' => $talents['Szybkie wyciągnięcie'], 'additional_name' => null]
                    ],
                    [['talent_id' => $talents['Silny cios'], 'additional_name' => null]],
                ]
            ],
        ];
        $insertData = [];
        foreach ($careers_data as $professionName => $profession) {
            foreach ($profession['skills'] as $groupId =>$skillGroups) {
                foreach ($skillGroups as $skill) {
                    $insertData['skills'][] = [
                        'profession_id' => $professions[$professionName],
                        'group_id' => $groupId,
                        'skill_id' => $skill['skill_id']['id'],
                        'additional_name' => $skill['additional_name'] ?? null,
                    ];
                }
            }
            foreach ($profession['talents'] as $groupId => $talentGroups) {
                foreach ($talentGroups as $talent) {
                    $insertData['talents'][] = [
                        'profession_id' => $professions[$professionName],
                        'group_id' => $groupId,
                        'talent_id' => $talent['talent_id']['id'],
                        'additional_name' => $talent['additional_name'] ?? null,
                    ];
                }
            }
        }
        DB::table('profession_skills')->truncate();
        DB::table('profession_talents')->truncate();
        DB::table('profession_skills')->insert($insertData['skills']);
        DB::table('profession_talents')->insert($insertData['talents']);
    }
}
