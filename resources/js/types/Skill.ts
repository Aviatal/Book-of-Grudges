export interface SkillPivot {
    hero_id: number,
    skill_id: number,
    additional_skill_name: string,
    first_level: boolean,
    second_level: boolean,
    hurdled: boolean,
}
export interface Skill {
    id: number,
    type: string,
    characteristic: string,
    description: string
    expandable: boolean,
    pivot: SkillPivot
}
