// Pełne nazwy cech dla podanych w bazie short_name (patrz HeroCharacteristicSeeder).
const CHARACTERISTIC_NAMES: Record<string, string> = {
    WW: 'Walka Wręcz',
    US: 'Umiejętności strzeleckie',
    K: 'Krzepa',
    Odp: 'Odporność',
    Zr: 'Zręczność',
    Int: 'Inteligencja',
    SW: 'Siła woli',
    Ogd: 'Ogłada',
    A: 'Ataki',
    Żyw: 'Żywotność',
    S: 'Siła',
    Wt: 'Wytrzymałość',
    Sz: 'Szybkość',
    Mag: 'Magia',
    PO: 'Punkty obłędu',
    PP: 'Punkty przeznaczenia',
};

export function characteristicFullName(shortName: string): string {
    return CHARACTERISTIC_NAMES[shortName] ?? shortName;
}

export function pluralizeSkillsCount(count: number): string {
    // W polskiej odmianie "umiejętność" ma tę samą formę mnogą dla 2+ i dla 5+, więc
    // jedyny wyjątek to liczba pojedyncza.
    return count === 1 ? 'umiejętność' : 'umiejętności';
}
