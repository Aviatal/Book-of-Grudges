import {Hero} from "./Hero";

export interface Token {
    id: number,
    name: string,
    x: number,
    y: number,
    color: string,
    image: string | null | Blob | File,
    image_url: string,
    hero_id: number | null,
    hero: Hero,
    created_at: string,
    updated_at: string
}
