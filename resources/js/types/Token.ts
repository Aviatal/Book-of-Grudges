import {Hero} from "./Hero";

export interface NpcWeapon {
    weapon_id: number;
    custom_name?: string;  // opcjonalna nazwa własna, np. "Kordelas"
}

export interface NpcArmorPiece {
    armor_id: number;
}

export interface NpcSkill {
    skill_id: number;
    // wartość = sheet.characteristics[skill.characteristic] — brak osobnego pola
}

export interface NpcSheet {
    characteristics: Record<string, number | null>;
    weapons: NpcWeapon[];
    armor: NpcArmorPiece[];
    skills: NpcSkill[];
    notes: string;
}

export interface Token {
    id: number,
    name: string,
    x: number,
    y: number,
    scale: number,
    color: string,
    image: string | null | Blob | File,
    image_url: string,
    hero_id: number | null,
    on_map: boolean,
    sheet: NpcSheet | null,
    hero: Hero,
    created_at: string,
    updated_at: string
}
