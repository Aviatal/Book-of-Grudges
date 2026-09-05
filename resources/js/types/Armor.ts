export interface ArmorLocation {
    id: number,
    name: string
    pivot: {
        armor_id: number,
        location_id: number
    }
}

export interface Armor {
    name: string,
    category: string,
    price: number,
    loading: number,
    armor_points: number,
    availability: string,
    locations: ArmorLocation[]
}
