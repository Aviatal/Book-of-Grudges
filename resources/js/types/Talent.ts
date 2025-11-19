export interface TalentPivot {
    talent_id: number,
    hero_id: number
}
export interface Talent {
    id: number,
    name: string,
    description: string,
    pivot: TalentPivot
}
