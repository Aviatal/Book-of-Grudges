<template>
    <div class="chat-actions">
        <button class="roll-btn" @click="toggleSkillPicker" :disabled="disabled" :class="{ active: showSkillPicker }">
            🎯 Test umiejętności
        </button>
        <button class="roll-btn" @click="showDicePicker = !showDicePicker; if(showDicePicker) showSkillPicker = false" :disabled="disabled" :class="{ active: showDicePicker }">
            🎲 Rzut kośćmi
        </button>
    </div>

    <div v-if="showDicePicker" class="dice-picker">
        <div class="dice-picker-count">
            <span class="dice-picker-label">Liczba kostek</span>
            <div class="dice-count-btns">
                <button
                    v-for="n in [1,2,3,4,5]"
                    :key="n"
                    class="dice-count-btn"
                    :class="{ active: diceCount === n }"
                    @click="diceCount = n"
                >{{ n }}</button>
            </div>
        </div>
        <div class="dice-picker-dice">
            <button
                v-for="sides in [4,6,8,10,12,20,100]"
                :key="sides"
                class="dice-type-btn"
                :disabled="disabled"
                @click="rollDice(sides)"
            >k{{ sides }}</button>
        </div>
    </div>

    <div v-if="showSkillPicker" class="skill-picker">
        <div class="skill-picker-modifiers">
            <button
                v-for="mod in MODIFIERS"
                :key="mod"
                class="mod-btn"
                :class="{ 'mod-active': skillModifier === mod, 'mod-neg': mod < 0, 'mod-pos': mod > 0, 'mod-zero': mod === 0 }"
                @click="skillModifier = mod"
            >{{ mod > 0 ? '+' + mod : mod }}</button>
        </div>
        <div class="skill-picker-options">
            <button
                class="half-btn"
                :class="{ 'half-active': skillHalf }"
                @click="skillHalf = !skillHalf"
            >½ Połowa cechy</button>
        </div>
        <!-- Cechy — bezpośredni rzut -->
        <div v-if="characteristics.length" class="char-roll-section">
            <div class="char-roll-label">Cechy</div>
            <div class="char-roll-grid">
                <button
                    v-for="entry in characteristics"
                    :key="entry.key"
                    class="char-roll-btn"
                    :disabled="disabled"
                    @click="rollCharacteristic(entry.key)"
                >
                    <span class="char-roll-key">{{ entry.key }}</span>
                    <span class="char-roll-val">{{ entry.val }}</span>
                </button>
            </div>
        </div>

        <input
            v-model="skillSearch"
            class="skill-picker-search"
            placeholder="Szukaj umiejętności..."
            type="text"
        />
        <div class="skill-picker-list">
            <template v-if="filteredSkills.length">
                <div v-if="filteredSkills.some(s => s.is_purchased)" class="skill-group-label">Wykupione</div>
                <button
                    v-for="skill in filteredSkills.filter(s => s.is_purchased)"
                    :key="skill.id"
                    class="skill-item skill-item-purchased"
                    @click="rollSkill(skill.id)"
                >
                    <span class="skill-item-name">{{ skill.additional_name ?? skill.name }}</span>
                    <span class="skill-item-char">{{ skill.characteristic }} {{ skill.characteristic_value }}</span>
                </button>
                <div v-if="filteredSkills.some(s => !s.is_purchased)" class="skill-group-label">Pozostałe</div>
                <button
                    v-for="skill in filteredSkills.filter(s => !s.is_purchased)"
                    :key="skill.id"
                    class="skill-item"
                    @click="rollSkill(skill.id)"
                >
                    <span class="skill-item-name">{{ skill.name }}</span>
                    <span class="skill-item-char">{{ skill.characteristic }} {{ skill.characteristic_value }}</span>
                </button>
            </template>
            <div v-else-if="isLoadingSkills" class="skill-picker-info">Ładowanie...</div>
            <div v-else class="skill-picker-info">Brak wyników</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import type { SkillOption } from '../../../composables/useHeroSkills';

const props = defineProps<{
    skills: SkillOption[];
    characteristics: { key: string; val: number }[];
    isLoadingSkills: boolean;
    disabled?: boolean;
}>();

const emit = defineEmits<{
    'roll-characteristic': [characteristic: string, modifier: number, half: boolean];
    'roll-skill': [skillId: number, modifier: number, half: boolean];
    'roll-dice': [count: number, sides: number];
    'ensure-skills-loaded': [];
}>();

const showSkillPicker = ref(false);
const showDicePicker = ref(false);
const diceCount = ref(1);
const skillSearch = ref('');
const skillModifier = ref(0);
const skillHalf = ref(false);

const MODIFIERS = [-40, -30, -20, -10, 0, 10, 20, 30, 40];

const filteredSkills = computed(() => {
    const q = skillSearch.value.trim().toLowerCase();
    return props.skills.filter(s =>
        !q || s.name.toLowerCase().includes(q) || (s.additional_name ?? '').toLowerCase().includes(q)
    );
});

const toggleSkillPicker = () => {
    showSkillPicker.value = !showSkillPicker.value;
    if (showSkillPicker.value) {
        showDicePicker.value = false;
        emit('ensure-skills-loaded');
    }
};

const rollCharacteristic = (characteristic: string) => {
    emit('roll-characteristic', characteristic, skillModifier.value, skillHalf.value);
    showSkillPicker.value = false;
    skillModifier.value = 0;
    skillHalf.value = false;
};

const rollSkill = (skillId: number) => {
    emit('roll-skill', skillId, skillModifier.value, skillHalf.value);
    showSkillPicker.value = false;
    skillModifier.value = 0;
    skillHalf.value = false;
};

const rollDice = (sides: number) => {
    emit('roll-dice', diceCount.value, sides);
    showDicePicker.value = false;
};
</script>

<style scoped>
.chat-actions {
    padding: 5px 10px 8px;
    background: #111;
    display: flex;
    gap: 5px;
}

.roll-btn {
    background: #2a2a1a;
    border: 1px solid #d4af37;
    color: #d4af37;
    padding: 4px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: background 0.15s;
}

.roll-btn:hover:not(:disabled) { background: #3a3a1a; }
.roll-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.roll-btn.active { background: #3a3a00; border-color: #ffdf00; }

/* Skill picker */
.skill-picker {
    background: #0d0d0d;
    border-top: 1px solid #333;
    display: flex;
    flex-direction: column;
    /* Wcześniej sztywne 260px robiło listę umiejętności ciasną i trudną do klikania —
       zwłaszcza gdy cechy zawijały się na kilka linii i zjadały resztę miejsca. */
    max-height: min(58vh, 460px);
    min-height: 0;
}

.skill-picker-modifiers {
    display: flex;
    gap: 3px;
    padding: 7px 7px 0;
    flex-wrap: wrap;
}

.mod-btn {
    flex: 1;
    min-width: 34px;
    padding: 3px 2px;
    border-radius: 3px;
    border: 1px solid #2a2a2a;
    background: #141414;
    color: #777;
    font-size: 0.7rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.1s;
    text-align: center;
}

.mod-btn:hover { border-color: #555; color: #ccc; background: #1e1e1e; }
.mod-neg { color: #c0392b; }
.mod-pos { color: #27ae60; }
.mod-zero { color: #777; }
.mod-active { border-color: #d4af37 !important; background: #1a1500 !important; color: #d4af37 !important; box-shadow: 0 0 6px rgba(212,175,55,0.3); }

.skill-picker-options {
    padding: 5px 7px 3px;
    display: flex;
    gap: 5px;
}

.half-btn {
    background: #141414;
    border: 1px solid #2a2a2a;
    color: #777;
    padding: 4px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.78rem;
    transition: all 0.12s;
    width: 100%;
}

.half-btn:hover { border-color: #555; color: #ccc; }
.half-active { border-color: #7b68ee !important; color: #9d91f0 !important; background: #0e0d1a !important; }

/* ── Sekcja cech w pickerze ── */
.char-roll-section {
    padding: 6px 8px 2px;
    border-bottom: 1px solid #2a2a2a;
}

.char-roll-label {
    font-size: 0.6rem;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #8b5a2b;
    margin-bottom: 5px;
}

.char-roll-grid {
    display: flex;
    flex-wrap: nowrap;
    gap: 4px;
    overflow-x: auto;
    padding-bottom: 2px;
    scrollbar-width: thin;
}

.char-roll-grid::-webkit-scrollbar { height: 4px; }
.char-roll-grid::-webkit-scrollbar-thumb { background: #3b3a36; border-radius: 2px; }

.char-roll-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #1c1510;
    border: 1px solid #3b3a36;
    border-radius: 3px;
    padding: 5px 8px;
    cursor: pointer;
    transition: border-color 0.12s, background 0.12s;
    min-width: 40px;
    flex: 0 0 auto;
}
.char-roll-btn:hover:not(:disabled) { border-color: #d4af37; background: #2c1e0c; }
.char-roll-btn:disabled { opacity: 0.5; cursor: not-allowed; }

.char-roll-key {
    font-size: 0.6rem;
    font-weight: 800;
    color: #8b5a2b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.char-roll-val {
    font-size: 0.88rem;
    font-weight: 700;
    color: #d4af37;
    font-variant-numeric: tabular-nums;
}

.skill-picker-search {
    margin: 8px;
    background: #1a1a1a;
    border: 1px solid #444;
    color: white;
    padding: 5px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
    outline: none;
}

.skill-picker-search:focus { border-color: #d4af37; }

.skill-picker-list {
    overflow-y: auto;
    flex: 1;
    padding: 0 6px 6px;
}

.skill-group-label {
    font-size: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #555;
    padding: 6px 4px 2px;
}

.skill-item {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #141414;
    border: 1px solid #2a2a2a;
    color: #aaa;
    padding: 9px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.85rem;
    margin-bottom: 4px;
    text-align: left;
    transition: border-color 0.12s, background 0.12s;
}

.skill-item:hover {
    background: #1e1e1e;
    border-color: #555;
    color: #ddd;
}

.skill-item-purchased {
    border-color: rgba(212, 175, 55, 0.4);
    color: #e8d68a;
    background: #16130a;
}

.skill-item-purchased:hover {
    background: #201c0e;
    border-color: #d4af37;
}

.skill-item-name { flex: 1; }

.skill-item-char {
    font-size: 0.7rem;
    color: #666;
    margin-left: 6px;
    white-space: nowrap;
    font-family: monospace;
}

.skill-item-purchased .skill-item-char { color: #a08030; }

.skill-picker-info {
    color: #555;
    font-size: 0.8rem;
    text-align: center;
    padding: 12px;
}

/* ── Dice picker ── */
.dice-picker {
    background: #0d0d0d;
    border-top: 1px solid #333;
    padding: 8px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.dice-picker-label {
    font-size: 0.6rem;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #8b5a2b;
}

.dice-picker-count {
    display: flex;
    align-items: center;
    gap: 8px;
}

.dice-count-btns {
    display: flex;
    gap: 3px;
}

.dice-count-btn {
    min-width: 28px;
    padding: 3px 6px;
    border-radius: 3px;
    border: 1px solid #2a2a2a;
    background: #141414;
    color: #777;
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.1s;
}

.dice-count-btn:hover { border-color: #555; color: #ccc; }
.dice-count-btn.active { border-color: #d4af37; background: #1a1500; color: #d4af37; }

.dice-picker-dice {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.dice-type-btn {
    flex: 1;
    min-width: 40px;
    padding: 6px 4px;
    border-radius: 4px;
    border: 1px solid #3b3a36;
    background: #1c1510;
    color: #d4af37;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    transition: border-color 0.12s, background 0.12s;
    text-align: center;
}

.dice-type-btn:hover:not(:disabled) { border-color: #d4af37; background: #2c1e0c; }
.dice-type-btn:disabled { opacity: 0.5; cursor: not-allowed; }
</style>
