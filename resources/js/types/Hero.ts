export interface CharacteristicPivot {
    value: number;
    [key: string]: any;
}

export interface Hero {
    id: number;
    description: string;
    current_experience: number;
    all_experience: number;
    current_wounds: number;
    characteristic: Record<string, { pivot: CharacteristicPivot }>;
    talents: object[];
    skills: object[];
    armors: object[];
    inventory: object[];
    cold_weapons: object[];
    ranged_weapons: object[];
    [key: string]: any;
}
