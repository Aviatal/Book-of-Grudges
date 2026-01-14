export interface TalentPivot {
    talent_id: number,
    hero_id: number,
    additional_talent_name: string,
}
export interface Talent {
    id: number,
    name: string,
    description: string,
    pivot: TalentPivot
}
