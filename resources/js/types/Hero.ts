export interface CharacteristicPivot {
    advancement: number;
    characteristic_id: number;
    hero_id: number;
    value: number;
}

export interface Hero {
    id: number;
    description: string;
    current_experience: number;
    all_experience: number;
    current_wounds: number;
    characteristic: Record<string, {
        name: string,
        shortName: string,
        type: string,
        available_advancement: number,
        pivot: CharacteristicPivot
    }>;
    talents: object[];
    skills: object[];
    armors: object[];
    inventory: object[];
    cold_weapons: object[];
    ranged_weapons: object[];
    [key: string]: any;
}
