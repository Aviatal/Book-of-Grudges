export interface CharacteristicPivot {
    id: number,
    hero_id: number,
    characteristic_id: number,
    start_value: number,
    advancement: number
}
export interface Characteristic {
    name: string,
    shortName: string,
    type: string,
    pivot: CharacteristicPivot
}
