import axios from 'axios';

export interface DbWeapon { id: number; name: string; power: number; add_hero_power: boolean; traits: Record<string, { name: string }> | { name: string }[] }
export interface DbArmor  { id: number; name: string; category: string; armor_points: number | null; locations: { name: string }[] }
export interface DbSkill  { id: number; name: string; characteristic: string }
export interface DbTalent { id: number; name: string; description: string }

export interface NpcCatalogs {
    weapons: DbWeapon[];
    armors: DbArmor[];
    skills: DbSkill[];
    talents: DbTalent[];
}

// Katalogi broni/pancerzy/umiejętności/zdolności są wspólne dla wszystkich NPC —
// pobieramy je raz na sesję strony i cache'ujemy w module, zamiast dobijać się
// o nie przy każdym otwarciu karty NPC.
let cached: NpcCatalogs | null = null;
let inflight: Promise<NpcCatalogs> | null = null;

export const loadNpcCatalogs = (): Promise<NpcCatalogs> => {
    if (cached) return Promise.resolve(cached);
    if (inflight) return inflight;

    inflight = (async (): Promise<NpcCatalogs> => {
        const [wRes, aRes, sRes, tRes] = await Promise.all([
            axios.get('/bronie/get-weapons'),
            axios.get('/opancerzenie/get-armors'),
            axios.get('/umiejetnosci/get-skills'),
            axios.get('/zdolnosci/get-talents'),
        ]);

        const weapons = [
            ...Object.values(wRes.data.ranged ?? {}),
            ...Object.values(wRes.data.cold   ?? {}),
        ] as DbWeapon[];

        const armors = [
            ...Object.values(aRes.data.leather ?? {}),
            ...Object.values(aRes.data.mail    ?? {}),
            ...Object.values(aRes.data.plate   ?? {}),
        ] as DbArmor[];

        cached = {
            weapons,
            armors,
            skills: sRes.data as DbSkill[],
            talents: tRes.data as DbTalent[],
        };
        return cached;
    })();

    return inflight.finally(() => { inflight = null; });
};
