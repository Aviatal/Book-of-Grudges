export interface TraitPivot {
    trait_id: number,
    weapon_id: number,
}
export interface WeaponPivot {
    hero_id: number,
    weapon_id: number,
    additional_weapon_name: string,
}
export interface Weapon {
    id: number,
    is_ranged: boolean,
    name: string,
    loading: number,
    category: string,
    power: number,
    short_range: number,
    long_range: number,
    reload_time: string,
    traits: Record<string, {
        id: number,
        name: string,
        description: string
        pivot: TraitPivot
    }>,
    pivot: WeaponPivot
}
