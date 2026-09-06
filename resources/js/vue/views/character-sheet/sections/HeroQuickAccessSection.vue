<template>
    <div class="qa">
        <!-- ── Żywotność ─────────────────────────────────────────────────── -->
        <div class="qa-block">
            <div class="qa-block__label"><span>ŻYWOTNOŚĆ</span><span class="qa-block__line"></span></div>
            <div class="qa-health" :class="`qa-health--${healthState}`">
                <button class="qa-health__step" title="Odejmij" @click="bump(-1)">−</button>
                <div class="qa-health__readout">
                    <input
                        v-model.number="editingWounds"
                        type="number"
                        class="qa-health__input"
                        inputmode="numeric"
                        @input="debouncedCommit"
                        @change="commit"
                        @blur="commit"
                    >
                    <span class="qa-health__max">/ {{ maxWounds || '—' }}</span>
                </div>
                <button class="qa-health__step" title="Dodaj" @click="bump(1)">+</button>
            </div>
            <div class="qa-health__bar">
                <div class="qa-health__bar-fill" :style="{ width: healthPercent + '%' }"></div>
            </div>
        </div>

        <!-- ── Majątek ───────────────────────────────────────────────────── -->
        <div class="qa-block">
            <div class="qa-block__label"><span>MAJĄTEK</span><span class="qa-block__line"></span></div>
            <div class="qa-coins">
                <div v-for="coin in coins" :key="coin.key" class="qa-coin">
                    <span class="qa-coin__disc" :class="`qa-coin__disc--${coin.key}`">{{ coin.symbol }}</span>
                    <span class="qa-coin__amount">{{ coin.amount }}</span>
                    <span class="qa-coin__name">{{ coin.name }}</span>
                </div>
            </div>
        </div>

        <!-- ── Broń ──────────────────────────────────────────────────────── -->
        <div class="qa-block">
            <div class="qa-block__label"><span>BROŃ</span><span class="qa-block__line"></span></div>
            <div v-if="weapons.length" class="qa-weapons">
                <div v-for="weapon in weapons" :key="weapon.key" class="qa-weapon">
                    <span class="qa-weapon__name">
                        {{ weapon.displayName }}
                        <span v-if="weapon.ranged" class="qa-weapon__tag">strzelecka</span>
                    </span>
                    <span class="qa-weapon__attack">
                        <span class="qa-weapon__attack-label">Atak</span>
                        <span class="qa-weapon__attack-value">{{ weapon.attack }}</span>
                    </span>
                </div>
            </div>
            <p v-else class="qa-empty">Brak wyposażonej broni.</p>
        </div>

        <!-- ── Pancerz ───────────────────────────────────────────────────── -->
        <div class="qa-block">
            <div class="qa-block__label"><span>PUNKTY PANCERZA</span><span class="qa-block__line"></span></div>
            <div class="qa-armor">
                <span class="qa-armor__figure" v-html="heroSvgContent"></span>
                <span
                    v-for="marker in armorMarkers"
                    :key="marker.key"
                    class="qa-armor__marker"
                    :class="[`qa-armor__marker--${marker.key}`, { 'qa-armor__marker--zero': marker.points === 0 }]"
                >
                    <span class="qa-armor__marker-points">{{ marker.points }}</span>
                    <span class="qa-armor__marker-name">{{ marker.name }}</span>
                </span>
            </div>
        </div>

        <!-- ── Wyszukiwarka umiejętności ─────────────────────────────────── -->
        <div class="qa-block">
            <div class="qa-block__label"><span>UMIEJĘTNOŚCI</span><span class="qa-block__line"></span></div>
            <input
                v-model="query"
                type="text"
                class="qa-search"
                placeholder="Wpisz nazwę umiejętności…"
            >
            <p v-if="!query.trim()" class="qa-hint">Wpisz nazwę, aby sprawdzić, czy postać posiada daną umiejętność.</p>
            <div v-else-if="filteredSkills.length" class="qa-skills">
                <div
                    v-for="skill in filteredSkills"
                    :key="skill.key"
                    class="qa-skill"
                    :class="{ 'qa-skill--owned': skill.hurdled }"
                >
                    <span class="qa-skill__mark">{{ skill.hurdled ? '✓' : '✗' }}</span>
                    <span class="qa-skill__name">
                        {{ skill.name }}
                        <span v-if="skill.additional" class="qa-skill__spec">({{ skill.additional }})</span>
                    </span>
                    <span v-if="skill.levels" class="qa-skill__levels">{{ skill.levels }}</span>
                    <span class="qa-skill__char">{{ skill.characteristic || '—' }}</span>
                    <span class="qa-skill__roll">{{ skill.roll }}</span>
                </div>
            </div>
            <p v-else class="qa-empty">Brak umiejętności pasujących do „{{ query }}".</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, watch, onBeforeUnmount } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toast-notification';
import heroSvg from '@/assets/images/hero.svg?raw';
import type { Hero } from '../../../../types/Hero';

interface HeroSkill {
    id: number;
    name: string;
    characteristic: string | null;
    pivot: {
        additional_skill_name: string | null;
        hurdled: boolean | number;
        first_level: boolean | number;
        second_level: boolean | number;
    };
}

interface HeroWeapon {
    id: number;
    name: string;
    power: number;
    is_ranged: boolean;
    add_hero_power?: boolean;
    pivot: { additional_weapon_name: string | null };
}

const props = defineProps<{
    hero: Hero;
}>();

const toast = useToast();

// ── Żywotność ────────────────────────────────────────────────────────────────
const characteristicTotal = (code: string): number => {
    const stat = props.hero.characteristic?.[code];
    return stat ? stat.pivot.start_value + stat.pivot.advancement : 0;
};

const maxWounds = computed<number>(() => characteristicTotal('Żyw'));

const clampWounds = (value: number): number => {
    if (Number.isNaN(value)) {
        return props.hero.current_wounds ?? 0;
    }
    const rounded = Math.round(value);
    return rounded < 0 ? 0 : rounded;
};

const editingWounds = ref<number>(clampWounds(props.hero.current_wounds ?? 0));

// Zakładka „Bohater" i „Szybki dostęp" współdzielą ten sam obiekt hero —
// utrzymujemy lokalne pole w zgodzie, gdy wartość zmieni się gdzie indziej.
watch(() => props.hero.current_wounds, (value) => {
    const normalized = clampWounds(value ?? 0);
    if (normalized !== editingWounds.value) {
        editingWounds.value = normalized;
    }
});

const healthPercent = computed<number>(() => {
    if (!maxWounds.value) {
        return editingWounds.value > 0 ? 100 : 0;
    }
    return Math.max(0, Math.min(100, Math.round((editingWounds.value / maxWounds.value) * 100)));
});

const healthState = computed<'ok' | 'warn' | 'danger'>(() => {
    if (healthPercent.value > 50) return 'ok';
    if (healthPercent.value > 25) return 'warn';
    return 'danger';
});

let saveTimer: ReturnType<typeof setTimeout> | null = null;

const persistWounds = (value: number): void => {
    axios.post('karta-postaci/' + props.hero.id + '/update-hero', {
        field: 'current_wounds',
        value,
    })
        .then((response) => {
            props.hero.current_wounds = value;
            toast.success(response.data.message);
        })
        .catch((error) => {
            console.error('Błąd aktualizacji żywotności', error);
            toast.error('Wystąpił błąd podczas aktualizacji żywotności');
            editingWounds.value = clampWounds(props.hero.current_wounds ?? 0);
        });
};

const commit = (allowEmpty = true): void => {
    if (saveTimer) {
        clearTimeout(saveTimer);
        saveTimer = null;
    }
    const raw = editingWounds.value;
    // Nie zapisujemy w trakcie czyszczenia pola — dopiero po opuszczeniu (blur).
    if (!allowEmpty && (raw === null || (raw as unknown as string) === '')) {
        return;
    }
    const value = clampWounds(Number(raw));
    editingWounds.value = value;
    if (value === (props.hero.current_wounds ?? 0)) {
        return;
    }
    persistWounds(value);
};

const debouncedCommit = (): void => {
    if (saveTimer) {
        clearTimeout(saveTimer);
    }
    saveTimer = setTimeout(() => commit(false), 400);
};

const bump = (delta: number): void => {
    editingWounds.value = clampWounds((editingWounds.value ?? 0) + delta);
    commit();
};

onBeforeUnmount(() => {
    if (saveTimer) {
        clearTimeout(saveTimer);
    }
});

// ── Majątek ──────────────────────────────────────────────────────────────────
const coins = computed(() => [
    { key: 'gold',   symbol: 'GK', name: 'Złote Korony',    amount: Number(props.hero.gold_crowns ?? 0) },
    { key: 'silver', symbol: 'SS', name: 'Srebrne Szylingi', amount: Number(props.hero.silver_shillings ?? 0) },
    { key: 'copper', symbol: 'MP', name: 'Mosiężne Pensy',   amount: Number(props.hero.brass_pennies ?? 0) },
]);

// ── Broń ─────────────────────────────────────────────────────────────────────
const talents = computed<{ name: string }[]>(() => (props.hero.talents as { name: string }[]) ?? []);
const heroPower = computed<number>(() => Math.floor(characteristicTotal('K') / 10));
const hasStrongStrikeTalent = computed<boolean>(() => talents.value.some((t) => t.name === 'Silny cios'));
const hasSharpshooterTalent = computed<boolean>(() => talents.value.some((t) => t.name === 'Strzał precyzyjny'));

// Odwzorowanie kolumny „Atak" z HeroWeaponsSection.vue (weaponPower).
const weaponAttack = (weapon: HeroWeapon): number => {
    let attack = weapon.is_ranged ? weapon.power : heroPower.value + weapon.power;

    if (
        weapon.name === 'Bez broni' ||
        (weapon.name !== 'Bez broni' && !weapon.is_ranged && hasStrongStrikeTalent.value) ||
        (attack > 0 && weapon.is_ranged && hasSharpshooterTalent.value)
    ) {
        attack++;
    }

    return attack;
};

const weapons = computed(() => {
    const cold = (props.hero.cold_weapons as HeroWeapon[]) ?? [];
    const ranged = (props.hero.ranged_weapons as HeroWeapon[]) ?? [];
    return [...cold, ...ranged].map((weapon, index) => ({
        key: `${weapon.is_ranged ? 'r' : 'c'}-${weapon.id}-${index}`,
        ranged: weapon.is_ranged,
        displayName: weapon.pivot?.additional_weapon_name?.trim() || weapon.name,
        attack: weaponAttack(weapon),
    }));
});

// ── Pancerz ──────────────────────────────────────────────────────────────────
const heroSvgContent = heroSvg;
const locationLabels: Record<string, string> = {
    head: 'Głowa',
    arms: 'Ręce',
    torso: 'Korpus',
    legs: 'Nogi',
};

const armorPointsAt = (location: keyof typeof locationLabels): number => {
    const armors = (props.hero.armors as { armor_points: string | number; locations: { name: string }[] }[]) ?? [];
    return armors.reduce((total, armor) => {
        const covers = (armor.locations ?? []).some((loc) => loc.name === locationLabels[location]);
        return covers ? total + Number.parseInt(String(armor.armor_points), 10) : total;
    }, 0);
};

const armorMarkers = computed(() => [
    { key: 'head',  name: 'Głowa',  points: armorPointsAt('head') },
    { key: 'arms',  name: 'Ręce',   points: armorPointsAt('arms') },
    { key: 'torso', name: 'Korpus', points: armorPointsAt('torso') },
    { key: 'legs',  name: 'Nogi',   points: armorPointsAt('legs') },
]);

// ── Umiejętności ─────────────────────────────────────────────────────────────
const query = ref<string>('');

const normalize = (value: string): string => value.toLocaleLowerCase('pl-PL');

const skillRows = computed(() => {
    const skills = (props.hero.skills as HeroSkill[]) ?? [];
    return skills.map((skill, index) => {
        const hurdled = Boolean(skill.pivot?.hurdled);
        const base = characteristicTotal(skill.characteristic ?? '');
        const hasChar = Boolean(skill.characteristic && props.hero.characteristic?.[skill.characteristic]);
        const first = Boolean(skill.pivot?.first_level);
        const second = Boolean(skill.pivot?.second_level);
        const roll = hasChar
            ? (hurdled ? base + (first ? 10 : 0) + (second ? 20 : 0) : Math.floor(base / 2))
            : '—';
        const levels = [first ? '+10' : null, second ? '+20' : null].filter(Boolean).join(' ');
        return {
            key: `${skill.id}-${index}`,
            name: skill.name,
            additional: skill.pivot?.additional_skill_name?.trim() || '',
            characteristic: skill.characteristic ?? '',
            hurdled,
            roll,
            levels,
        };
    });
});

const filteredSkills = computed(() => {
    const term = normalize(query.value.trim());
    if (!term) {
        return [];
    }
    return skillRows.value
        .filter((skill) => normalize(`${skill.name} ${skill.additional}`).includes(term))
        .sort((a, b) => {
            if (a.hurdled !== b.hurdled) {
                return a.hurdled ? -1 : 1;
            }
            return a.name.localeCompare(b.name, 'pl-PL');
        });
});
</script>

<style scoped>
.qa {
    display: flex;
    flex-direction: column;
    gap: 26px;
}

.qa-block__label {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 14px;
}

.qa-block__label span:first-child {
    font-family: var(--font-heading), serif;
    font-size: 11px;
    letter-spacing: .2em;
    color: var(--text-faint);
}

.qa-block__line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, var(--border-default), transparent);
}

.qa-empty,
.qa-hint {
    margin: 0;
    font-size: 14px;
    font-style: italic;
    color: var(--text-faint);
}

.qa-hint {
    margin-bottom: 12px;
}

/* ── Żywotność ───────────────────────────────────────────────────────────── */
.qa-health {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 18px;
    border: 1px solid var(--border-default);
    background: linear-gradient(#221d17, #171310);
    padding: 16px;
}

.qa-health__step {
    width: 40px;
    height: 40px;
    border: 1px solid var(--border-frame);
    background: var(--bg-inset-alt);
    color: var(--gold-muted);
    font-size: 22px;
    line-height: 1;
    cursor: pointer;
    font-family: var(--font-body), serif;
    transition: border-color .15s, color .15s, background .15s;
}

.qa-health__step:hover {
    border-color: var(--gold);
    color: var(--gold-bright);
    background: #241d14;
}

.qa-health__readout {
    display: flex;
    align-items: baseline;
    gap: 8px;
}

.qa-health__input {
    width: 96px;
    text-align: center;
    background: transparent;
    border: none;
    border-bottom: 1px solid var(--border-frame);
    color: var(--text-body);
    font-size: 44px;
    line-height: 1.1;
    font-family: var(--font-body), serif;
    -moz-appearance: textfield;
}

.qa-health__input::-webkit-outer-spin-button,
.qa-health__input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.qa-health__input:focus {
    outline: none;
    border-bottom-color: var(--gold);
}

.qa-health__max {
    font-size: 20px;
    color: var(--text-faint);
}

.qa-health__bar {
    margin-top: 10px;
    height: 6px;
    background: var(--bg-inset);
    border: 1px solid var(--border-subtle);
    overflow: hidden;
}

.qa-health__bar-fill {
    height: 100%;
    transition: width .2s ease, background .2s ease;
}

.qa-health--ok .qa-health__bar-fill { background: #6f9a4e; }
.qa-health--warn .qa-health__bar-fill { background: var(--gold-muted); }
.qa-health--danger .qa-health__bar-fill { background: var(--danger-border-hover); }
.qa-health--danger .qa-health__input { color: var(--danger-text); }

/* ── Majątek ─────────────────────────────────────────────────────────────── */
.qa-coins {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}

.qa-coin {
    display: grid;
    grid-template-columns: auto 1fr;
    grid-template-rows: auto auto;
    column-gap: 12px;
    align-items: center;
    border: 1px solid var(--border-default);
    background: linear-gradient(#221d17, #171310);
    padding: 12px 14px;
}

.qa-coin__disc {
    grid-row: 1 / 3;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-heading), serif;
    font-size: 12px;
    font-weight: 700;
    color: #2a2015;
    box-shadow: inset 0 0 0 2px rgba(0, 0, 0, .25), 0 2px 6px rgba(0, 0, 0, .5);
}

.qa-coin__disc--gold   { background: radial-gradient(circle at 35% 30%, #f7e08c, #d4af37 60%, #a97f22); }
.qa-coin__disc--silver { background: radial-gradient(circle at 35% 30%, #f2f4f7, #c9ccd1 60%, #8f949c); }
.qa-coin__disc--copper { background: radial-gradient(circle at 35% 30%, #e6a06b, #b87333 60%, #8a4f22); }

.qa-coin__amount {
    font-size: 26px;
    color: var(--text-body);
    line-height: 1.1;
}

.qa-coin__name {
    font-size: 12px;
    letter-spacing: .04em;
    color: var(--text-faint);
}

/* ── Broń ────────────────────────────────────────────────────────────────── */
.qa-weapons {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.qa-weapon {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    border: 1px solid var(--border-default);
    background: linear-gradient(#221d17, #171310);
    padding: 10px 14px;
}

.qa-weapon__name {
    font-size: 16px;
    color: var(--text-body);
}

.qa-weapon__tag {
    margin-left: 8px;
    font-size: 11px;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--text-faint);
    border: 1px solid var(--border-frame);
    padding: 1px 6px;
}

.qa-weapon__attack {
    display: flex;
    align-items: baseline;
    gap: 8px;
    flex: none;
}

.qa-weapon__attack-label {
    font-family: var(--font-heading), serif;
    font-size: 10px;
    letter-spacing: .16em;
    color: var(--gold-muted);
}

.qa-weapon__attack-value {
    font-size: 26px;
    color: var(--gold-bright);
    line-height: 1;
    font-variant-numeric: tabular-nums;
}

/* ── Pancerz ─────────────────────────────────────────────────────────────── */
.qa-armor {
    position: relative;
    display: flex;
    justify-content: center;
    padding: 8px 0;
}

.qa-armor__figure {
    color: var(--border-accent-hover);
    display: block;
}

.qa-armor__figure :deep(svg) {
    max-height: 280px;
    width: auto;
}

.qa-armor__marker {
    position: absolute;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.qa-armor__marker-points {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-inset);
    border: 1px solid var(--gold-muted);
    color: var(--gold-bright);
    font-size: 15px;
    font-variant-numeric: tabular-nums;
}

.qa-armor__marker--zero .qa-armor__marker-points {
    border-color: var(--border-frame);
    color: var(--text-faint);
}

.qa-armor__marker-name {
    font-size: 10px;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--text-faint);
    background: rgba(15, 12, 9, .82);
    padding: 0 4px;
}

.qa-armor__marker--head  { top: 0; left: calc(50% - 96px); }
.qa-armor__marker--arms  { top: 33%; right: calc(50% - 96px); }
.qa-armor__marker--torso { top: 33%; left: calc(50% - 96px); }
.qa-armor__marker--legs  { bottom: 0; left: calc(50% - 96px); }

/* ── Umiejętności ────────────────────────────────────────────────────────── */
.qa-search {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 12px;
    background: var(--bg-inset);
    border: 1px solid var(--border-default);
    color: var(--text-body);
    font-family: var(--font-body), serif;
    font-size: 15px;
}

.qa-search:focus {
    outline: none;
    border-color: var(--border-accent-hover);
    box-shadow: 0 0 0 2px rgba(212, 175, 55, .18);
}

.qa-skills {
    display: flex;
    flex-direction: column;
    gap: 4px;
    max-height: 320px;
    overflow-y: auto;
}

.qa-skill {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 7px 10px;
    border: 1px solid var(--border-subtle);
    background: var(--bg-inset);
    opacity: .55;
}

.qa-skill--owned {
    opacity: 1;
    border-color: var(--border-default);
    background: linear-gradient(#221d17, #171310);
}

.qa-skill__mark {
    flex: none;
    width: 18px;
    text-align: center;
    font-size: 14px;
    color: var(--danger-text);
}

.qa-skill--owned .qa-skill__mark {
    color: #7bab55;
}

.qa-skill__name {
    flex: 1;
    font-size: 15px;
    color: var(--text-body);
}

.qa-skill__spec {
    color: var(--text-faint);
}

.qa-skill__levels {
    flex: none;
    font-size: 12px;
    color: var(--gold-muted);
    font-variant-numeric: tabular-nums;
}

.qa-skill__char {
    flex: none;
    width: 42px;
    text-align: right;
    font-family: var(--font-heading), serif;
    font-size: 12px;
    letter-spacing: .1em;
    color: var(--gold-muted);
}

.qa-skill__roll {
    flex: none;
    width: 34px;
    text-align: right;
    font-size: 17px;
    color: var(--text-body);
    font-variant-numeric: tabular-nums;
}

@media (max-width: 640px) {
    .qa-health__input { font-size: 36px; width: 78px; }
    .qa-armor__figure :deep(svg) { max-height: 220px; }
    .qa-armor__marker--head,
    .qa-armor__marker--torso,
    .qa-armor__marker--legs { left: calc(50% - 74px); }
    .qa-armor__marker--arms { right: calc(50% - 74px); }
}
</style>
