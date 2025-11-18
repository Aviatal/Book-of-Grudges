export interface Armor {
    name: string,
    category: string,
    price: number,
    loading: number,
    armor_points: number,
    availability: string,
    locations: {
        id: number,
        name: string
        pivot: {
            armor_id: number,
            location_id: number
        }
    }
}
