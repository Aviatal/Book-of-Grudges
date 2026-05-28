<template>
    <div class="npc-popup-overlay" @click.self="$emit('close')">
        <div class="npc-popup">
            <div class="popup-header">
                <div class="popup-title">
                    <div
                        class="popup-avatar"
                        :style="token.image_url ? `background-image: url(${token.image_url})` : ''"
                    >
                        <span v-if="!token.image_url">{{ token.name.charAt(0) }}</span>
                    </div>
                    <span>{{ token.name }}</span>
                </div>
                <button class="popup-close" @click="$emit('close')">✕</button>
            </div>

            <div v-if="loading" class="loading-state">
                <i class="mdi mdi-loading mdi-spin"></i> Ładowanie...
            </div>

            <div v-else-if="!sheet" class="no-sheet">
                <i class="mdi mdi-file-question-outline"></i>
                Brak karty postaci dla tego tokenu.
            </div>

            <template v-else>
                <!-- Characteristics -->
                <section class="popup-section">
                    <h4 class="section-title">Cechy</h4>
                    <div class="char-grid">
                        <div
                            v-for="key in charKeys"
                            :key="key"
                            class="char-cell"
                            :class="{
                                empty:    sheet.characteristics[key] == null,
                                rollable: canRoll && sheet.characteristics[key] != null,
                            }"
                            @click="canRoll && sheet.characteristics[key] != null && rollTest(key, key)"
                        >
                            <span class="char-key">{{ key }}</span>
                            <span class="char-val">{{ sheet.characteristics[key] ?? '—' }}</span>
                        </div>
                    </div>
                </section>

                <!-- Weapons -->
                <section v-if="sheet.weapons.length" class="popup-section">
                    <h4 class="section-title">Broń</h4>
                    <table class="popup-table">
                        <thead>
                            <tr>
                                <th>Nazwa</th>
                                <th>Obrażenia</th>
                                <th>Cechy</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(w, i) in resolvedWeapons" :key="i">
                                <td>
                                    {{ w.name }}
                                    <span v-if="w.baseName" class="base-name">({{ w.baseName }})</span>
                                </td>
                                <td class="damage-cell">
                                    <span class="dmg-formula">{{ w.formula }}</span>
                                    <span class="dmg-value">= {{ w.damage }}</span>
                                </td>
                                <td class="traits-cell">{{ w.traits || '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <!-- Armor -->
                <section v-if="sheet.armor.length" class="popup-section">
                    <h4 class="section-title">Pancerz</h4>
                    <table class="popup-table">
                        <thead>
                            <tr>
                                <th>Zbroja</th>
                                <th>PP</th>
                                <th>Lokacje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(a, i) in resolvedArmors" :key="i">
                                <td>{{ a.label }}</td>
                                <td class="mono">{{ a.armor_points ?? '—' }}</td>
                                <td class="traits-cell">{{ a.locations }}</td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <!-- Skills -->
                <section v-if="sheet.skills.length" class="popup-section">
                    <h4 class="section-title">Umiejętności</h4>
                    <div class="skills-list">
                        <div
                            v-for="(s, i) in resolvedSkills"
                            :key="i"
                            class="skill-chip"
                            :class="{ rollable: canRoll && !!s.characteristic }"
                            @click="canRoll && s.characteristic && rollTest(s.characteristic, s.name)"
                        >
                            <span class="skill-name">{{ s.name }}</span>
                            <span v-if="s.characteristic" class="skill-char">{{ s.characteristic }}</span>
                            <span v-if="s.value != null" class="skill-value">{{ s.value }}</span>
                        </div>
                    </div>
                </section>

                <!-- Notes -->
                <section v-if="sheet.notes" class="popup-section">
                    <h4 class="section-title">Notatki</h4>
                    <p class="notes-text">{{ sheet.notes }}</p>
                </section>

                <!-- Roll bar (tylko MG) -->
                <section v-if="canRoll" class="popup-section roll-bar">
                    <h4 class="section-title">Test cechy / umiejętności</h4>
                    <div class="roll-controls">
                        <label class="roll-control-label">
                            <span>Modyfikator</span>
                            <div class="modifier-stepper">
                                <button @click="modifier = Math.max(-40, modifier - 10)">−</button>
                                <span class="modifier-val" :class="{ positive: modifier > 0, negative: modifier < 0 }">
                                    {{ modifier > 0 ? '+' : '' }}{{ modifier }}
                                </span>
                                <button @click="modifier = Math.min(40, modifier + 10)">+</button>
                            </div>
                        </label>
                        <label class="half-label">
                            <input type="checkbox" v-model="half" />
                            <span>½ wartości</span>
                        </label>
                        <span v-if="rolledLabel" class="roll-feedback">✓ {{ rolledLabel }}</span>
                    </div>
                    <p class="roll-hint">Kliknij cechę lub umiejętność, by rzucić test</p>
                </section>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, onMounted } from 'vue';
import axios from 'axios';
import { Token, NpcSheet } from '@/types/Token';

const props = defineProps<{ token: Token; canRoll?: boolean }>();
defineEmits<{ (e: 'close'): void }>();

const sheet     = computed<NpcSheet | null>(() => props.token.sheet ?? null);
const loading   = ref(true);
const modifier  = ref(0);
const half      = ref(false);
const isRolling = ref(false);
const rolledLabel = ref<string | null>(null);

const rollTest = async (characteristic: string, label: string): Promise<void> => {
    if (isRolling.value || !props.canRoll) return;
    isRolling.value = true;
    rolledLabel.value = null;
    try {
        await axios.post(`/session/tokens/${props.token.id}/roll`, {
            characteristic,
            label,
            modifier: modifier.value,
            half: half.value,
        });
        rolledLabel.value = label;
        setTimeout(() => { rolledLabel.value = null; }, 2000);
    } catch (e) {
        console.error('Błąd testu cechy NPC', e);
    } finally {
        isRolling.value = false;
    }
};

const charKeys = ['WW', 'US', 'K', 'Odp', 'Zr', 'Int', 'SW', 'Ogd', 'A', 'Żyw', 'Mag'];

// ---- Raw data from DB ----
interface DbWeapon { id: number; name: string; power: number; add_hero_power: boolean; traits: Record<string, { name: string }> | { name: string }[] }
interface DbArmor  { id: number; name: string; category: string; armor_points: number | null; locations: { name: string }[] }
interface DbSkill  { id: number; name: string; characteristic: string }

const dbWeapons = ref<DbWeapon[]>([]);
const dbArmors  = ref<DbArmor[]>([]);
const dbSkills  = ref<DbSkill[]>([]);

const sb = computed<number>(() => {
    const k = sheet.value?.characteristics?.['K'];
    return k != null ? Math.floor(k / 10) : 0;
});

// ---- Resolved lists for display ----
const resolvedWeapons = computed(() =>
    (sheet.value?.weapons ?? []).map(w => {
        const db = dbWeapons.value.find(d => d.id === w.weapon_id);
        if (!db) return { name: `Broń #${w.weapon_id}`, baseName: '', formula: '?', damage: '?', traits: '' };

        const traitNames = Array.isArray(db.traits)
            ? db.traits.map((t: any) => t.name).join(', ')
            : Object.values(db.traits ?? {}).map((t: any) => t.name).join(', ');

        const formula = db.add_hero_power
            ? (db.power > 0 ? `SB+${db.power}` : db.power < 0 ? `SB${db.power}` : 'SB')
            : String(db.power);

        const damage = db.add_hero_power ? sb.value + db.power : db.power;

        const name     = w.custom_name?.trim() || db.name;
        const baseName = w.custom_name?.trim() ? db.name : '';

        return { name, baseName, formula, damage, traits: traitNames };
    })
);

const resolvedArmors = computed(() =>
    (sheet.value?.armor ?? []).map(a => {
        const db = dbArmors.value.find(d => d.id === a.armor_id);
        if (!db) return { label: `Pancerz #${a.armor_id}`, armor_points: null, locations: '' };
        const locations = db.locations?.map(l => l.name).join(', ') ?? '';
        return { label: `${db.category} - ${db.name}`, armor_points: db.armor_points, locations };
    })
);

const resolvedSkills = computed(() =>
    (sheet.value?.skills ?? []).map(s => {
        const db = dbSkills.value.find(d => d.id === s.skill_id);
        const char = db?.characteristic ?? '';
        const value = char ? (sheet.value?.characteristics?.[char] ?? null) : null;
        return { name: db?.name ?? `Umiejętność #${s.skill_id}`, characteristic: char, value };
    })
);

// ---- Load ----
onMounted(async () => {
    try {
        const [wRes, aRes, sRes] = await Promise.all([
            axios.get('/bronie/get-weapons'),
            axios.get('/opancerzenie/get-armors'),
            axios.get('/umiejetnosci/get-skills'),
        ]);

        dbWeapons.value = [
            ...Object.values(wRes.data.ranged ?? {}),
            ...Object.values(wRes.data.cold   ?? {}),
        ] as DbWeapon[];

        dbArmors.value = [
            ...Object.values(aRes.data.leather ?? {}),
            ...Object.values(aRes.data.mail    ?? {}),
            ...Object.values(aRes.data.plate   ?? {}),
        ] as DbArmor[];

        dbSkills.value = sRes.data as DbSkill[];
    } finally {
        loading.value = false;
    }
});
</script>

<style scoped>
.npc-popup-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.65);
    z-index: 10010;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.npc-popup {
    background: #1f1e1b;
    border: 2px solid #8b5a2b;
    border-radius: 2px;
    width: 100%;
    max-width: 560px;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 8px 32px rgba(0,0,0,0.8);
    font-family: ui-serif, Georgia, 'Times New Roman', serif;
    color: #e4d8b4;
}

.popup-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.85rem 1rem;
    border-bottom: 1px solid #5e4128;
    background: #2b2a27;
    position: sticky;
    top: 0;
    z-index: 1;
}

.popup-title {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 1.1rem;
    font-weight: bold;
    color: #d4af37;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.popup-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: #3b3a36;
    background-size: cover;
    background-position: center;
    border: 1px solid #8b5a2b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: bold;
    color: #c4a47c;
    flex-shrink: 0;
}

.popup-close {
    background: none;
    border: none;
    color: #8b5a2b;
    font-size: 1.1rem;
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    transition: color 0.2s;
}
.popup-close:hover { color: #e4d8b4; }

.loading-state, .no-sheet {
    padding: 2rem;
    text-align: center;
    color: #706f6c;
    font-size: 0.9rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}
.loading-state i, .no-sheet i { font-size: 1.8rem; }

.popup-section {
    padding: 0.85rem 1rem;
    border-bottom: 1px solid #2b2a27;
}
.popup-section:last-child { border-bottom: none; }

.section-title {
    font-size: 0.72rem;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #8b5a2b;
    margin-bottom: 0.6rem;
}

/* Characteristics */
.char-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(52px, 1fr)); gap: 0.4rem; }
.char-cell {
    background: #2b2a27;
    border: 1px solid #3b3a36;
    border-radius: 2px;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.3rem 0.2rem;
}
.char-cell.empty { opacity: 0.35; }
.char-key { font-size: 0.65rem; font-weight: bold; color: #8b5a2b; text-transform: uppercase; }
.char-val { font-size: 1rem; font-weight: bold; color: #e4d8b4; line-height: 1.2; }

/* Tables */
.popup-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
.popup-table th {
    text-align: left;
    color: #8b5a2b;
    font-size: 0.68rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 0.2rem 0.4rem;
    border-bottom: 1px solid #3b3a36;
}
.popup-table td { padding: 0.3rem 0.4rem; border-bottom: 1px solid #2b2a27; }
.popup-table tr:last-child td { border-bottom: none; }
.mono { font-variant-numeric: tabular-nums; }
.traits-cell { color: #c4a47c; font-size: 0.78rem; }
.base-name { color: #706f6c; font-size: 0.75rem; margin-left: 0.25rem; }

.damage-cell { white-space: nowrap; }
.dmg-formula { color: #c4a47c; font-size: 0.8rem; }
.dmg-value { margin-left: 0.4rem; font-weight: bold; color: #d4af37; }

/* Skills */
.skills-list { display: flex; flex-wrap: wrap; gap: 0.4rem; }
.skill-chip {
    background: #2b2a27;
    border: 1px solid #5e4128;
    border-radius: 2px;
    display: flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.55rem;
}
.skill-name { font-size: 0.8rem; color: #c4a47c; }
.skill-char { font-size: 0.68rem; color: #8b5a2b; font-weight: bold; }
.skill-value { font-size: 0.82rem; font-weight: bold; color: #d4af37; }

/* Notes */
.notes-text { font-size: 0.85rem; color: #c4a47c; white-space: pre-wrap; line-height: 1.5; }

/* Rollable — hover state dla klikalnych elementów */
.char-cell.rollable { cursor: pointer; transition: border-color 0.15s, background 0.15s; }
.char-cell.rollable:hover { border-color: #d4af37; background: #3a3820; }
.skill-chip.rollable { cursor: pointer; transition: border-color 0.15s, background 0.15s; }
.skill-chip.rollable:hover { border-color: #d4af37; background: #3a3820; }

/* Roll bar */
.roll-bar { background: #191810; }

.roll-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 0.4rem;
}

.roll-control-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.78rem;
    color: #8b5a2b;
}

.modifier-stepper {
    display: flex;
    align-items: center;
    gap: 0;
    border: 1px solid #5e4128;
    border-radius: 3px;
    overflow: hidden;
}
.modifier-stepper button {
    background: #2b2a27;
    border: none;
    color: #c4a47c;
    font-size: 1rem;
    width: 24px;
    height: 26px;
    cursor: pointer;
    line-height: 1;
    transition: background 0.1s;
}
.modifier-stepper button:hover { background: #3b3a30; color: #d4af37; }
.modifier-val {
    min-width: 36px;
    text-align: center;
    font-size: 0.82rem;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
    color: #e4d8b4;
    padding: 0 4px;
    background: #1f1e1b;
}
.modifier-val.positive { color: #7dbf7d; }
.modifier-val.negative { color: #c47a5a; }

.half-label {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.78rem;
    color: #8b5a2b;
    cursor: pointer;
    user-select: none;
}
.half-label input { accent-color: #d4af37; cursor: pointer; }

.roll-feedback {
    font-size: 0.75rem;
    color: #7dbf7d;
    font-weight: 700;
    animation: fade-in 0.2s ease;
}
@keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }

.roll-hint {
    font-size: 0.68rem;
    color: #5e4128;
    margin: 0;
    font-style: italic;
}
</style>
