import {Hero} from "./Hero";

export interface Token {
    id: number,
    name: string,
    x: number,
    y: number,
    color: string,
    hero_id: number,
    hero: Hero
    created_at: string
    updated_at: string
}
