import {Talent} from "./Talent";
export interface Spell {
    id: number,
    talent: Talent,
    type: string,
    specialization: string,
    name: string,
    ingredient: string,
    casting_duration: string,
    effect_duration: string,
    description: string,
    casting_number: string,
}
