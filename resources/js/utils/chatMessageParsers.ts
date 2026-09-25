export interface SkillTestResult {
    skill: string;
    characteristic: string;
    characteristic_value: number;
    effective_value: number;
    modifier: number;
    half: boolean;
    roll: number;
    passed: boolean;
    fumble: boolean;
    levels: number;
    // Obecny (null dla cechy) tylko w rzutach, które serwer potrafi powtórzyć punktem szczęścia
    skill_id?: number | null;
    // Powtórka rzutu za punkt szczęścia
    fortune_reroll?: boolean;
}

export interface DiceRollPayload {
    notation: string;
    count: number;
    sides: number;
    results: number[];
    total: number;
}

export const parseSkillTest = (text: string): SkillTestResult | null => {
    try {
        return JSON.parse(text) as SkillTestResult;
    } catch (e) {
        console.error('Failed to parse skill test message', e);
        return null;
    }
};

export const parseDiceRoll = (text: string): DiceRollPayload | null => {
    try {
        return JSON.parse(text) as DiceRollPayload;
    } catch {
        return null;
    }
};

export const parseRoll = (text: string) => {
    const match = text.match(/Zr \((\d+)\) \+ k10 \[(\d+)\] = (\d+)/);
    return match
        ? { zr: match[1], dice: match[2], total: match[3] }
        : { zr: '?', dice: '?', total: '?' };
};

export const formatDate = (isoString: string) => {
    const date = new Date(isoString);

    return new Intl.DateTimeFormat('pl-PL', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    }).format(date);
};

export const pluralizeLevels = (count: number): string => {
    if (count === 1) return 'poziom';
    if (count >= 2 && count <= 4) return 'poziomy';
    return 'poziomów';
};
