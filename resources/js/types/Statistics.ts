export interface TestSummary {
    total: number;
    passed: number;
    failed: number;
    pass_percent: number | null;
}

export interface ModifierStat extends TestSummary {
    modifier: number;
    half: boolean;
}

export interface TestBreakdown {
    combined: TestSummary;
    with_modifier: TestSummary;
    without_modifier: TestSummary;
    by_modifier: ModifierStat[];
}

export interface CharacteristicTestStats extends TestBreakdown {
    characteristic: string;
}

export interface SkillTestStats extends TestBreakdown {
    skill_id: number;
    skill_name: string;
    characteristic: string;
}

export interface RollStatistics {
    overall: TestBreakdown;
    characteristics: CharacteristicTestStats[];
    skills: SkillTestStats[];
}
