
export const professionTables = {
    human: {
        name: 'Człowiek',
        table: [
            { min: 1, max: 100, professionId: 'agitator' },
            { min: 6, max: 10, professionId: 'apprentice_wizard' },
            { min: 11, max: 15, professionId: 'boatman' },
            { min: 16, max: 20, professionId: 'bounty_hunter' },
            { min: 21, max: 25, professionId: 'charcoal_burner' },
            { min: 26, max: 30, professionId: 'entertainer' },
            { min: 31, max: 35, professionId: 'grave_robber' },
            { min: 36, max: 40, professionId: 'guard' },
            { min: 41, max: 45, professionId: 'initiate' },
            { min: 46, max: 50, professionId: 'merchant' },
            { min: 51, max: 55, professionId: 'militiaman' },
            { min: 56, max: 60, professionId: 'noble' },
            { min: 61, max: 65, professionId: 'peasant' },
            { min: 66, max: 70, professionId: 'rat_catcher' },
            { min: 71, max: 75, professionId: 'scholar' },
            { min: 76, max: 80, professionId: 'scribe' },
            { min: 81, max: 85, professionId: 'soldier' },
            { min: 86, max: 90, professionId: 'thief' },
            { min: 91, max: 95, professionId: 'watchman' },
            { min: 96, max: 100, professionId: 'wizard_apprentice' }
        ]
    },
    dwarf: {
        name: 'Krasnolud',
        table: [
            { min: 1, max: 5, professionId: 'agitator' },
            { min: 6, max: 10, professionId: 'apprentice_runesmith' },
            { min: 11, max: 15, professionId: 'boatman' },
            { min: 16, max: 25, professionId: 'bounty_hunter' },
            { min: 26, max: 30, professionId: 'charcoal_burner' },
            { min: 31, max: 35, professionId: 'entertainer' },
            { min: 36, max: 40, professionId: 'grave_robber' },
            { min: 41, max: 50, professionId: 'guard' },
            { min: 51, max: 55, professionId: 'initiate' },
            { min: 56, max: 60, professionId: 'merchant' },
            { min: 61, max: 70, professionId: 'miner' },
            { min: 71, max: 75, professionId: 'militiaman' },
            { min: 76, max: 80, professionId: 'rat_catcher' },
            { min: 81, max: 85, professionId: 'scholar' },
            { min: 86, max: 90, professionId: 'soldier' },
            { min: 91, max: 95, professionId: 'thief' },
            { min: 96, max: 100, professionId: 'warrior' }
        ]
    },
    elf: {
        name: 'Elf',
        table: [
            { min: 1, max: 5, professionId: 'agitator' },
            { min: 6, max: 15, professionId: 'apprentice_wizard' },
            { min: 16, max: 20, professionId: 'boatman' },
            { min: 21, max: 25, professionId: 'bounty_hunter' },
            { min: 26, max: 30, professionId: 'charcoal_burner' },
            { min: 31, max: 40, professionId: 'entertainer' },
            { min: 41, max: 45, professionId: 'grave_robber' },
            { min: 46, max: 50, professionId: 'guard' },
            { min: 51, max: 55, professionId: 'hunter' },
            { min: 56, max: 60, professionId: 'initiate' },
            { min: 61, max: 65, professionId: 'merchant' },
            { min: 66, max: 70, professionId: 'militiaman' },
            { min: 71, max: 75, professionId: 'noble' },
            { min: 76, max: 80, professionId: 'ranger' },
            { min: 81, max: 85, professionId: 'scholar' },
            { min: 86, max: 90, professionId: 'scout' },
            { min: 91, max: 95, professionId: 'thief' },
            { min: 96, max: 100, professionId: 'wizard_apprentice' }
        ]
    },
    halfling: {
        name: 'Niziołek',
        table: [
            { min: 1, max: 5, professionId: 'agitator' },
            { min: 6, max: 10, professionId: 'apprentice_wizard' },
            { min: 11, max: 20, professionId: 'boatman' },
            { min: 21, max: 25, professionId: 'bounty_hunter' },
            { min: 26, max: 30, professionId: 'charcoal_burner' },
            { min: 31, max: 40, professionId: 'entertainer' },
            { min: 41, max: 45, professionId: 'grave_robber' },
            { min: 46, max: 50, professionId: 'guard' },
            { min: 51, max: 55, professionId: 'initiate' },
            { min: 56, max: 65, professionId: 'merchant' },
            { min: 66, max: 70, professionId: 'militiaman' },
            { min: 71, max: 80, professionId: 'peasant' },
            { min: 81, max: 85, professionId: 'rat_catcher' },
            { min: 86, max: 90, professionId: 'scholar' },
            { min: 91, max: 95, professionId: 'thief' },
            { min: 96, max: 100, professionId: 'vagabond' }
        ]
    }
}

export const professions = {
    agitator: {
        id: 'agitator',
        name: 'Agitator',
        description: 'Podżegacz i wywrotowiec, który sieje niepokój wśród ludności swoimi płomiennymi przemowami.',
        class: 'Społeczna',
        characteristics: {
            weaponSkill: 5,
            ballisticSkill: 0,
            strength: 0,
            toughness: 5,
            agility: 10,
            intelligence: 10,
            willpower: 15,
            fellowship: 15
        },
        skills: [
            'Czytanie/Pisanie',
            'Dowodzenie',
            'Plotkarstwo',
            'Przekonywanie',
            'Występy (Oratorstwo)',
            'Znajomość (Prawo)',
            'Znajomość (Polityka)',
            'Znajomość (Reikland)'
        ],
        talents: [
            'Gadanina',
            'Mobilizacja',
            'Odczytywanie intencji',
            'Szybka riosta'
        ],
        equipment: [
            'Ręczna broń',
            'Skórzana kurtka',
            '10 s broń miotana',
            '2d10 srebrnych szylingów'
        ],
        entryProfessions: ['Wszystkie'],
        exitProfessions: [
            'Barykadowiec',
            'Krzykacz publiczny',
            'Polityk',
            'Żebrak'
        ]
    },

    apprentice_wizard: {
        id: 'apprentice_wizard',
        name: 'Uczeń Czarodzieja',
        description: 'Młody adept magii, który dopiero rozpoczyna swoją naukę tajemnych sztuk.',
        class: 'Akademicka',
        characteristics: {
            weaponSkill: 0,
            ballisticSkill: 0,
            strength: 0,
            toughness: 5,
            agility: 5,
            intelligence: 15,
            willpower: 15,
            fellowship: 5
        },
        skills: [
            'Język (Magiczny)',
            'Czytanie/Pisanie',
            'Nasłuchiwanie',
            'Opieka nad zwierzętami',
            'Przekonywanie',
            'Znajomość (Magia)',
            'Znajomość (Historia)',
            'Zmysły'
        ],
        talents: [
            'Aethyryczny dotyku',
            'Instynkt magiczny',
            'Odczytywanie emocji',
            'Szósty zmysł'
        ],
        equipment: [
            'Różdżka',
            'Księga zaklęć',
            'Szata',
            '1k10 mosiężnych penisów'
        ],
        entryProfessions: ['Wszystkie'],
        exitProfessions: [
            'Czarodziej',
            'Uczony',
            'Felczer',
            'Badacz'
        ]
    },

    boatman: {
        id: 'boatman',
        name: 'Przewoźnik',
        description: 'Żeglarz rzeczny, który przewozi ludzi i towary po wodnych szlakach.',
        class: 'Łokciowa',
        characteristics: {
            weaponSkill: 10,
            ballisticSkill: 5,
            strength: 10,
            toughness: 10,
            agility: 15,
            intelligence: 0,
            willpower: 5,
            fellowship: 5
        },
        skills: [
            'Atletyka',
            'Dowodzenie',
            'Nawigacja',
            'Plotkarstwo',
            'Splatanie lin',
            'Znajomość (Reikland)',
            'Znajomość (Rzeki)',
            'Żeglarstwo'
        ],
        talents: [
            'Orientacja',
            'Silny',
            'Równowaga',
            'Wykrzesać płomień'
        ],
        equipment: [
            'Ręczna broń',
            'Łódź',
            'Ubranie skórzane',
            '2d10 srebrnych szylingów'
        ],
        entryProfessions: ['Wszystkie'],
        exitProfessions: [
            'Marynarz',
            'Przemytnik',
            'Rybak',
            'Handlowiec'
        ]
    },

    bounty_hunter: {
        id: 'bounty_hunter',
        name: 'Łowca Nagród',
        description: 'Zawodowiec zajmujący się schwytaniem zbiegłych przestępców za odpowiednią zapłatę.',
        class: 'Strzelecka',
        characteristics: {
            weaponSkill: 10,
            ballisticSkill: 15,
            strength: 0,
            toughness: 10,
            agility: 10,
            intelligence: 10,
            willpower: 5,
            fellowship: 0
        },
        skills: [
            'Atletyka',
            'Czujność',
            'Dowodzenie',
            'Nasłuchiwanie',
            'Plotkarstwo',
            'Skradanie',
            'Tropienie',
            'Znajomość (Prawo)'
        ],
        talents: [
            'Ambidekstra',
            'Szybkie przeładowanie',
            'Strzał precyzyjny',
            'Refleks bojowy'
        ],
        equipment: [
            'Kusza',
            '20 bełtów',
            'Ręczna broń',
            'Skórzana kurtka',
            'Lina (10 jardów)',
            '2d10 srebrnych szylingów'
        ],
        entryProfessions: ['Wszystkie'],
        exitProfessions: [
            'Łowca czarownic',
            'Zwiadowca',
            'Żołnierz',
            'Stróż prawa'
        ]
    },

    // Dodaj więcej profesji według wzoru...
    miner: {
        id: 'miner',
        name: 'Górnik',
        description: 'Wydobywca rud i minerałów z głębin ziemi, znający tajemnice podziemnych tuneli.',
        class: 'Łokciowa',
        characteristics: {
            weaponSkill: 10,
            ballisticSkill: 0,
            strength: 15,
            toughness: 15,
            agility: 5,
            intelligence: 0,
            willpower: 10,
            fellowship: 0
        },
        skills: [
            'Atletyka',
            'Klimatyzacja',
            'Nasłuchiwanie',
            'Nawigacja',
            'Opieka nad zwierzętami',
            'Rzemiosło (Górnictwo)',
            'Znajomość (Geologia)',
            'Znajomość (Góry)'
        ],
        talents: [
            'Górnicze światło',
            'Silny',
            'Wytrzymały',
            'Noktowizja'
        ],
        equipment: [
            'Kilof',
            'Lampa górnicza',
            'Ubranie skórzane',
            '2d10 mosiężnych penisów'
        ],
        entryProfessions: ['Krasnolud', 'Chłop'],
        exitProfessions: [
            'Inżynier',
            'Prospektujący',
            'Rzemieślnik',
            'Handlowiec'
        ]
    }
}

export function rollProfession(race, diceRoll) {
    const raceKey = race.replace(/\s+/g, '_')
    const table = professionTables[raceKey]
    if (!table) {
        throw new Error(`Nie znaleziono tabeli profesji dla rasy: ${race}`)
    }

    const entry = table.table.find(entry =>
        diceRoll >= entry.min && diceRoll <= entry.max
    )

    if (!entry) {
        throw new Error(`Nie znaleziono profesji dla wyniku: ${diceRoll}`)
    }
console.log(entry)
    const profession = professions[entry.professionId]

    if (!profession) {
        throw new Error(`Nie znaleziono danych dla profesji: ${entry.professionId}`)
    }

    return {
        ...profession,
        diceRoll
    }
}
