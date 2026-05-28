<template>
    <div class="npc-sheet-editor">
        <button type="button" class="sheet-toggle" @click="open = !open">
            <i :class="open ? 'mdi mdi-chevron-down' : 'mdi mdi-chevron-right'"></i>
            Karta Postaci NPC
            <span class="sheet-hint">opcjonalnie</span>
        </button>

        <div v-if="open" class="sheet-body">
            <!-- Tabs -->
            <div class="sheet-tabs">
                <button
                    v-for="tab in tabs"
                    :key="tab.key"
                    type="button"
                    class="sheet-tab"
                    :class="{ active: activeTab === tab.key }"
                    @click="activeTab = tab.key"
                >{{ tab.label }}</button>
            </div>

            <!-- Cechy -->
            <div v-if="activeTab === 'characteristics'" class="tab-content">
                <p class="tab-hint">Wpisz wartości końcowe (bazowe + modyfikatory).</p>
                <div class="char-grid">
                    <div v-for="key in characteristicKeys" :key="key" class="char-cell">
                        <label class="char-label">{{ key }}</label>
                        <input
                            type="number"
                            min="0"
                            max="999"
                            class="char-input"
                            :value="sheet.characteristics[key] ?? ''"
                            @input="setChar(key, ($event.target as HTMLInputElement).value)"
                            placeholder="—"
                        />
                    </div>
                </div>
            </div>

            <!-- Broń -->
            <div v-if="activeTab === 'weapons'" class="tab-content">
                <div v-if="sheet.weapons.length" class="weapon-rows">
                    <div v-for="(w, i) in sheet.weapons" :key="i" class="weapon-row">
                        <span class="weapon-base-name">{{ weaponName(w.weapon_id) }}</span>
                        <input
                            class="weapon-custom-name"
                            v-model="w.custom_name"
                            placeholder="nazwa własna (opcjonalnie)"
                            title="Opcjonalna nazwa własna, np. Kordelas"
                        />
                        <button type="button" class="chip-remove" @click="removeWeapon(i)">✕</button>
                    </div>
                </div>
                <p v-else class="empty-hint">Brak broni — dodaj poniżej.</p>
                <v-select
                    :options="availableWeapons"
                    :reduce="(w: WeaponOption) => w.id"
                    label="name"
                    placeholder="Dodaj broń z bazy..."
                    :model-value="null"
                    @option:selected="(w: WeaponOption) => addWeapon(w.id)"
                    class="custom-select add-select"
                    :loading="loadingWeapons"
                />
            </div>

            <!-- Pancerz -->
            <div v-if="activeTab === 'armor'" class="tab-content">
                <div v-if="sheet.armor.length" class="chips-list">
                    <div v-for="(a, i) in sheet.armor" :key="i" class="item-chip">
                        <span class="chip-name">{{ armorLabel(a.armor_id) }}</span>
                        <button type="button" class="chip-remove" @click="removeArmor(i)">✕</button>
                    </div>
                </div>
                <p v-else class="empty-hint">Brak pancerza — dodaj poniżej.</p>
                <v-select
                    :options="availableArmors"
                    :reduce="(a: ArmorOption) => a.id"
                    label="name"
                    placeholder="Dodaj pancerz z bazy..."
                    :model-value="null"
                    @option:selected="(a: ArmorOption) => addArmor(a.id)"
                    class="custom-select add-select"
                    :loading="loadingArmors"
                >
                    <template #option="a">{{ a.category }} - {{ a.name }}</template>
                    <template #selected-option="a">{{ a.category }} - {{ a.name }}</template>
                </v-select>
            </div>

            <!-- Umiejętności -->
            <div v-if="activeTab === 'skills'" class="tab-content">
                <p class="tab-hint">Wartości wyliczane automatycznie z uzupełnionych cech.</p>
                <div v-if="sheet.skills.length" class="chips-list">
                    <div v-for="(s, i) in sheet.skills" :key="i" class="item-chip">
                        <span class="chip-name">{{ skillName(s.skill_id) }}</span>
                        <button type="button" class="chip-remove" @click="removeSkill(i)">✕</button>
                    </div>
                </div>
                <p v-else class="empty-hint">Brak umiejętności — dodaj poniżej.</p>
                <v-select
                    :options="availableSkills"
                    :reduce="(s: SkillOption) => s.id"
                    label="name"
                    placeholder="Dodaj umiejętność z bazy..."
                    :model-value="null"
                    @option:selected="(s: SkillOption) => addSkill(s.id)"
                    class="custom-select add-select"
                    :loading="loadingSkills"
                />
            </div>

            <!-- Notatki -->
            <div v-if="activeTab === 'notes'" class="tab-content">
                <textarea
                    class="notes-textarea"
                    v-model="sheet.notes"
                    rows="6"
                    placeholder="Opis, zachowanie, specjalne zdolności..."
                ></textarea>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch, computed, onMounted } from 'vue';
import axios from 'axios';
import { NpcSheet } from '@/types/Token';

interface WeaponOption { id: number; name: string; }
interface ArmorOption  { id: number; name: string; category: string; }
interface SkillOption  { id: number; name: string; }

const props = defineProps<{ modelValue: NpcSheet | null }>();
const emit = defineEmits<{ (e: 'update:modelValue', value: NpcSheet): void }>();

const open      = ref(false);
const activeTab = ref<'characteristics' | 'weapons' | 'armor' | 'skills' | 'notes'>('characteristics');

const tabs = [
    { key: 'characteristics', label: 'Cechy' },
    { key: 'weapons',         label: 'Broń' },
    { key: 'armor',           label: 'Pancerz' },
    { key: 'skills',          label: 'Umiejętności' },
    { key: 'notes',           label: 'Notatki' },
] as const;

const characteristicKeys = ['WW', 'US', 'K', 'Odp', 'Zr', 'Int', 'SW', 'Ogd', 'A', 'Żyw', 'Mag'];

// ---- Lists from DB ----
const allWeapons = ref<WeaponOption[]>([]);
const allArmors  = ref<ArmorOption[]>([]);
const allSkills  = ref<SkillOption[]>([]);
const loadingWeapons = ref(false);
const loadingArmors  = ref(false);
const loadingSkills  = ref(false);

// Filter already-added items out of selects
const availableWeapons = computed(() =>
    allWeapons.value.filter(w => !sheet.weapons.some(sw => sw.weapon_id === w.id))
);
const availableArmors = computed(() =>
    allArmors.value.filter(a => !sheet.armor.some(sa => sa.armor_id === a.id))
);
const availableSkills = computed(() =>
    allSkills.value.filter(s => !sheet.skills.some(ss => ss.skill_id === s.id))
);

// Resolve name / label by ID for chip display
const weaponName = (id: number) => allWeapons.value.find(w => w.id === id)?.name ?? `Broń #${id}`;
const armorLabel = (id: number) => {
    const a = allArmors.value.find(a => a.id === id);
    return a ? `${a.category} - ${a.name}` : `Pancerz #${id}`;
};
const skillName  = (id: number) => allSkills.value.find(s => s.id === id)?.name ?? `Umiejętność #${id}`;

// ---- Sheet state ----
const makeEmptySheet = (): NpcSheet => ({
    characteristics: Object.fromEntries(characteristicKeys.map(k => [k, null])),
    weapons: [],
    armor: [],
    skills: [],
    notes: '',
});

const sheet = reactive<NpcSheet>(
    props.modelValue ? JSON.parse(JSON.stringify(props.modelValue)) : makeEmptySheet()
);
characteristicKeys.forEach(k => { if (!(k in sheet.characteristics)) sheet.characteristics[k] = null; });

watch(sheet, val => emit('update:modelValue', JSON.parse(JSON.stringify(val))), { deep: true });

// ---- Loaders ----
const loadWeapons = async () => {
    loadingWeapons.value = true;
    try {
        const res = await axios.get('/bronie/get-weapons?grouped=true');
        allWeapons.value = res.data as WeaponOption[];
    } catch {} finally { loadingWeapons.value = false; }
};

const loadArmors = async () => {
    loadingArmors.value = true;
    try {
        const res = await axios.get('/opancerzenie/get-armors?grouped=true');
        // endpoint grouped=true zwraca { name, category, id }[]
        allArmors.value = res.data as ArmorOption[];
    } catch {} finally { loadingArmors.value = false; }
};

const loadSkills = async () => {
    loadingSkills.value = true;
    try {
        const res = await axios.get('/umiejetnosci/get-skills');
        allSkills.value = (res.data as any[]).map(s => ({ id: s.id, name: s.name }));
    } catch {} finally { loadingSkills.value = false; }
};

onMounted(() => { loadWeapons(); loadArmors(); loadSkills(); });

// ---- CRUD ----
const setChar = (key: string, raw: string) => {
    const n = parseInt(raw, 10);
    sheet.characteristics[key] = isNaN(n) ? null : n;
};

const addWeapon = (id: number) => { if (!sheet.weapons.some(w => w.weapon_id === id)) sheet.weapons.push({ weapon_id: id }); };
const removeWeapon = (i: number) => sheet.weapons.splice(i, 1);

const addArmor = (id: number) => { if (!sheet.armor.some(a => a.armor_id === id)) sheet.armor.push({ armor_id: id }); };
const removeArmor = (i: number) => sheet.armor.splice(i, 1);

const addSkill = (id: number) => { if (!sheet.skills.some(s => s.skill_id === id)) sheet.skills.push({ skill_id: id }); };
const removeSkill = (i: number) => sheet.skills.splice(i, 1);
</script>

<style scoped>
.npc-sheet-editor { margin-top: 1.5rem; }

.sheet-toggle {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #1b1b18;
    color: #c4a47c;
    border: 1px solid #5e4128;
    padding: 0.6rem 1rem;
    font-size: 0.85rem;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    cursor: pointer;
    transition: border-color 0.2s, color 0.2s;
    text-align: left;
}
.sheet-toggle:hover { border-color: #d4af37; color: #d4af37; }

.sheet-hint {
    margin-left: auto;
    font-size: 0.7rem;
    font-weight: normal;
    text-transform: none;
    color: #706f6c;
    letter-spacing: 0;
}

.sheet-body {
    border: 1px solid #5e4128;
    border-top: none;
    background: #1b1b18;
}

.sheet-tabs { display: flex; border-bottom: 1px solid #5e4128; flex-wrap: wrap; }
.sheet-tab {
    padding: 0.5rem 1rem;
    font-size: 0.78rem;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #706f6c;
    background: none;
    border: none;
    border-right: 1px solid #5e4128;
    cursor: pointer;
    transition: color 0.2s, background 0.2s;
}
.sheet-tab:hover  { color: #c4a47c; background: #2b2a27; }
.sheet-tab.active { color: #d4af37; background: #2b2a27; }

.tab-content { padding: 1rem; }
.tab-hint, .empty-hint { font-size: 0.78rem; color: #706f6c; margin-bottom: 0.75rem; }

/* Characteristics */
.char-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); gap: 0.5rem; }
.char-cell { display: flex; flex-direction: column; align-items: center; gap: 0.2rem; }
.char-label { font-size: 0.7rem; font-weight: bold; color: #c4a47c; text-transform: uppercase; }
.char-input {
    width: 100%;
    text-align: center;
    background: #2b2a27;
    color: #e4d8b4;
    border: 1px solid #8b5a2b;
    padding: 0.35rem 0.25rem;
    border-radius: 2px;
    font-size: 0.9rem;
}
.char-input:focus { outline: none; border-color: #d4af37; }

/* Weapon rows */
.weapon-rows { display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 0.75rem; }
.weapon-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #2b2a27;
    border: 1px solid #3b3a36;
    padding: 0.3rem 0.5rem;
    border-radius: 2px;
}
.weapon-base-name {
    font-size: 0.82rem;
    color: #c4a47c;
    white-space: nowrap;
    flex-shrink: 0;
}
.weapon-custom-name {
    flex: 1;
    min-width: 0;
    background: #1b1b18;
    color: #e4d8b4;
    border: 1px solid #3b3a36;
    padding: 0.2rem 0.4rem;
    border-radius: 2px;
    font-size: 0.78rem;
    font-style: italic;
}
.weapon-custom-name::placeholder { color: #4a4947; font-style: italic; }
.weapon-custom-name:focus { outline: none; border-color: #8b5a2b; }

/* Chip list (armor) */
.chips-list { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 0.75rem; }
.item-chip {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    background: #2b2a27;
    border: 1px solid #5e4128;
    padding: 0.2rem 0.4rem 0.2rem 0.65rem;
    border-radius: 2px;
}
.chip-name { font-size: 0.82rem; color: #c4a47c; }
.chip-remove {
    background: none;
    border: none;
    color: #5e4128;
    cursor: pointer;
    font-size: 0.75rem;
    padding: 0 0.2rem;
    line-height: 1;
    transition: color 0.2s;
}
.chip-remove:hover { color: #e05c5c; }

/* Skill rows */
.skills-rows { display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 0.75rem; }
.skill-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #2b2a27;
    border: 1px solid #3b3a36;
    padding: 0.3rem 0.5rem;
    border-radius: 2px;
}
.skill-row-name { flex: 1; font-size: 0.82rem; color: #c4a47c; min-width: 0; }
.skill-value-input {
    width: 72px;
    flex-shrink: 0;
    background: #1b1b18;
    color: #e4d8b4;
    border: 1px solid #5e4128;
    padding: 0.25rem 0.4rem;
    border-radius: 2px;
    font-size: 0.82rem;
    text-align: center;
}
.skill-value-input:focus { outline: none; border-color: #d4af37; }

/* Add select */
.add-select { margin-top: 0.25rem; }

/* Notes */
.notes-textarea {
    width: 100%;
    background: #2b2a27;
    color: #e4d8b4;
    border: 1px solid #5e4128;
    padding: 0.6rem;
    border-radius: 2px;
    resize: vertical;
    font-size: 0.85rem;
    font-family: inherit;
}
.notes-textarea:focus { outline: none; border-color: #8b5a2b; }

/* v-select dark override */
.custom-select :deep(.vs__dropdown-toggle) {
    background: #1b1b18;
    border: 1px solid #3b3a36;
    border-radius: 2px;
    padding: 0 6px;
    min-height: 34px;
}
.custom-select :deep(.vs__search),
.custom-select :deep(.vs__selected) { color: #e4d8b4; font-size: 0.82rem; margin: 0; padding: 2px 4px; }
.custom-select :deep(.vs__search::placeholder) { color: #5e4128; }
.custom-select :deep(.vs__open-indicator) { fill: #8b5a2b; }
.custom-select :deep(.vs__clear) { fill: #8b5a2b; }
.custom-select :deep(.vs__dropdown-menu) {
    background: #2b2a27;
    border: 1px solid #5e4128;
    border-top: none;
    color: #e4d8b4;
    font-size: 0.82rem;
    max-height: 200px;
}
.custom-select :deep(.vs__dropdown-option) { padding: 6px 10px; color: #c4a47c; }
.custom-select :deep(.vs__dropdown-option--highlight) { background: #5e4128; color: #e4d8b4; }
</style>
