<template>
    <div class="hp-overlay" @click.self="$emit('close')">
        <div class="hp-popup">

            <!-- ── Nagłówek ──────────────────────────────────────────────── -->
            <div class="hp-header">
                <div class="hp-title">
                    <div class="hp-avatar" :style="token.image_url ? `background-image:url(${token.image_url})` : ''">
                        <span v-if="!token.image_url">{{ token.name.charAt(0) }}</span>
                    </div>
                    <div>
                        <div class="hp-name">{{ token.name }}</div>
                        <div class="hp-subtitle">{{ isGm ? 'Zastępstwo MG' : 'Twoja postać' }}</div>
                    </div>
                </div>
                <button class="hp-close" @click="$emit('close')">✕</button>
            </div>

            <!-- ── Ładowanie ─────────────────────────────────────────────── -->
            <div v-if="loading" class="hp-loading">
                <i class="mdi mdi-loading mdi-spin"></i> Ładowanie...
            </div>

            <template v-else-if="heroData">

                <!-- ── Cechy ─────────────────────────────────────────────── -->
                <section class="hp-section">
                    <h4 class="hp-section-title">Cechy</h4>
                    <div class="hp-char-grid">
                        <div
                            v-for="key in charKeys"
                            :key="key"
                            class="hp-char-cell"
                            :class="{
                                empty:    heroData.characteristics[key] == null,
                                rollable: heroData.characteristics[key] != null,
                            }"
                            @click="heroData.characteristics[key] != null && roll(key, key)"
                        >
                            <span class="hp-char-key">{{ key }}</span>
                            <span class="hp-char-val">{{ heroData.characteristics[key] ?? '—' }}</span>
                        </div>
                    </div>
                </section>

                <!-- ── Umiejętności ──────────────────────────────────────── -->
                <section v-if="heroData.skills.length" class="hp-section">
                    <h4 class="hp-section-title">Umiejętności</h4>
                    <div class="hp-skills-list">
                        <div
                            v-for="s in heroData.skills"
                            :key="s.id"
                            class="hp-skill-chip"
                            :class="{ rollable: !!s.characteristic, hurdled: s.hurdled }"
                            @click="s.characteristic && roll(s.characteristic, s.name)"
                        >
                            <span class="hp-skill-name">{{ s.name }}</span>
                            <span v-if="s.characteristic" class="hp-skill-char">{{ s.characteristic }}</span>
                            <span v-if="s.value != null" class="hp-skill-val">{{ s.value }}</span>
                        </div>
                    </div>
                </section>

                <!-- ── Pasek rzutu ────────────────────────────────────────── -->
                <section class="hp-section hp-roll-bar">
                    <h4 class="hp-section-title">{{ isGm ? 'Rzuć za gracza' : 'Rzuć test' }}</h4>
                    <div class="hp-roll-controls">
                        <label class="hp-control-label">
                            <span>Modyfikator</span>
                            <div class="hp-stepper">
                                <button @click="modifier = Math.max(-40, modifier - 10)">−</button>
                                <span class="hp-mod-val" :class="{ pos: modifier > 0, neg: modifier < 0 }">
                                    {{ modifier > 0 ? '+' : '' }}{{ modifier }}
                                </span>
                                <button @click="modifier = Math.min(40, modifier + 10)">+</button>
                            </div>
                        </label>
                        <label class="hp-half-label">
                            <input type="checkbox" v-model="half" />
                            <span>½ wartości</span>
                        </label>
                        <span v-if="rolledLabel" class="hp-feedback">✓ {{ rolledLabel }}</span>
                        <span v-if="isRolling" class="hp-feedback rolling">🎲</span>
                    </div>
                    <p class="hp-hint">Kliknij cechę lub umiejętność, by rzucić test</p>
                </section>

            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Token } from '@/types/Token';

const props = defineProps<{ token: Token; isGm?: boolean }>();
defineEmits<{ (e: 'close'): void }>();

// ── Typy ─────────────────────────────────────────────────────────────────────

interface HeroSkill {
    id: number;
    name: string;
    characteristic: string;
    value: number | null;
    hurdled: boolean;
}

interface HeroProxyData {
    id: number;
    name: string;
    characteristics: Record<string, number | null>;
    skills: HeroSkill[];
}

// ── Stan ─────────────────────────────────────────────────────────────────────

const loading    = ref(true);
const heroData   = ref<HeroProxyData | null>(null);
const modifier   = ref(0);
const half       = ref(false);
const isRolling  = ref(false);
const rolledLabel = ref<string | null>(null);

const charKeys = ['WW', 'US', 'K', 'Odp', 'Zr', 'Int', 'SW', 'Ogd', 'A', 'Żyw', 'Mag'];

// ── Rzut ─────────────────────────────────────────────────────────────────────

const roll = async (characteristic: string, label: string): Promise<void> => {
    if (isRolling.value || !heroData.value) return;
    isRolling.value  = true;
    rolledLabel.value = null;
    try {
        await axios.post(`/session/heroes/${heroData.value.id}/roll`, {
            characteristic,
            label,
            modifier: modifier.value,
            half: half.value,
        });
        rolledLabel.value = label;
        setTimeout(() => { rolledLabel.value = null; }, 2000);
    } catch (e) {
        console.error('Błąd rzutu za bohatera', e);
    } finally {
        isRolling.value = false;
    }
};

// ── Lifecycle ─────────────────────────────────────────────────────────────────

onMounted(async () => {
    try {
        const { data } = await axios.get<HeroProxyData>(
            `/session/heroes/${props.token.hero_id}/proxy`
        );
        heroData.value = data;
    } catch (e) {
        console.error('Błąd ładowania danych bohatera', e);
    } finally {
        loading.value = false;
    }
});
</script>

<style scoped>
.hp-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.65);
    z-index: 10020;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.hp-popup {
    background: #1f1e1b;
    border: 2px solid #8b5a2b;
    border-radius: 4px;
    width: 100%;
    max-width: 540px;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 8px 32px rgba(0,0,0,0.8);
    font-family: ui-serif, Georgia, 'Times New Roman', serif;
    color: #e4d8b4;
}

/* ── Nagłówek ─────────────────────────────────────────────────────────────── */
.hp-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.8rem 1rem;
    border-bottom: 1px solid #5e4128;
    background: #2b2a27;
    position: sticky;
    top: 0;
    z-index: 1;
}

.hp-title {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.hp-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background-color: #3b3a36;
    background-size: cover;
    background-position: center;
    border: 2px solid #d4af37;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem; font-weight: bold; color: #c4a47c;
    flex-shrink: 0;
}

.hp-name {
    font-size: 1rem;
    font-weight: bold;
    color: #d4af37;
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

.hp-subtitle {
    font-size: 0.65rem;
    color: #8b5a2b;
    font-style: italic;
    letter-spacing: 0.05em;
}

.hp-close {
    background: none; border: none;
    color: #8b5a2b; font-size: 1.1rem;
    cursor: pointer; padding: 0.25rem 0.5rem;
    transition: color 0.2s;
}
.hp-close:hover { color: #e4d8b4; }

/* ── Loading ──────────────────────────────────────────────────────────────── */
.hp-loading {
    padding: 2rem;
    text-align: center;
    color: #706f6c;
    font-size: 0.9rem;
}

/* ── Sekcje ───────────────────────────────────────────────────────────────── */
.hp-section {
    padding: 0.8rem 1rem;
    border-bottom: 1px solid #2b2a27;
}
.hp-section:last-child { border-bottom: none; }

.hp-section-title {
    font-size: 0.68rem;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: #8b5a2b;
    margin-bottom: 0.55rem;
}

/* ── Cechy ────────────────────────────────────────────────────────────────── */
.hp-char-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
    gap: 0.35rem;
}

.hp-char-cell {
    background: #2b2a27;
    border: 1px solid #3b3a36;
    border-radius: 2px;
    display: flex; flex-direction: column;
    align-items: center;
    padding: 0.3rem 0.2rem;
    transition: border-color 0.15s, background 0.15s;
}
.hp-char-cell.empty { opacity: 0.3; }
.hp-char-cell.rollable { cursor: pointer; }
.hp-char-cell.rollable:hover { border-color: #d4af37; background: #3a3820; }

.hp-char-key { font-size: 0.62rem; font-weight: bold; color: #8b5a2b; text-transform: uppercase; }
.hp-char-val { font-size: 1rem; font-weight: bold; color: #e4d8b4; line-height: 1.2; }

/* ── Umiejętności ─────────────────────────────────────────────────────────── */
.hp-skills-list { display: flex; flex-wrap: wrap; gap: 0.35rem; }

.hp-skill-chip {
    background: #2b2a27;
    border: 1px solid #3b3a36;
    border-radius: 2px;
    display: flex; align-items: center; gap: 0.3rem;
    padding: 0.2rem 0.5rem;
    transition: border-color 0.15s, background 0.15s;
}
.hp-skill-chip.rollable { cursor: pointer; }
.hp-skill-chip.rollable:hover { border-color: #d4af37; background: #3a3820; }
.hp-skill-chip.hurdled { border-color: #5e4128; }

.hp-skill-name { font-size: 0.78rem; color: #c4a47c; }
.hp-skill-char { font-size: 0.65rem; color: #8b5a2b; font-weight: bold; }
.hp-skill-val  { font-size: 0.8rem; font-weight: bold; color: #d4af37; }

/* ── Pasek rzutu ──────────────────────────────────────────────────────────── */
.hp-roll-bar { background: #191810; }

.hp-roll-controls {
    display: flex; align-items: center;
    gap: 1rem; flex-wrap: wrap;
    margin-bottom: 0.4rem;
}

.hp-control-label {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.75rem; color: #8b5a2b;
}

.hp-stepper {
    display: flex; align-items: center;
    border: 1px solid #5e4128; border-radius: 3px; overflow: hidden;
}
.hp-stepper button {
    background: #2b2a27; border: none;
    color: #c4a47c; font-size: 1rem;
    width: 24px; height: 26px;
    cursor: pointer; transition: background 0.1s;
}
.hp-stepper button:hover { background: #3b3a30; color: #d4af37; }

.hp-mod-val {
    min-width: 36px; text-align: center;
    font-size: 0.82rem; font-weight: 700;
    font-variant-numeric: tabular-nums;
    color: #e4d8b4; padding: 0 4px;
    background: #1f1e1b;
}
.hp-mod-val.pos { color: #7dbf7d; }
.hp-mod-val.neg { color: #c47a5a; }

.hp-half-label {
    display: flex; align-items: center; gap: 0.35rem;
    font-size: 0.75rem; color: #8b5a2b;
    cursor: pointer; user-select: none;
}
.hp-half-label input { accent-color: #d4af37; cursor: pointer; }

.hp-feedback {
    font-size: 0.75rem; color: #7dbf7d;
    font-weight: 700;
}
.hp-feedback.rolling { color: #d4af37; }

.hp-hint {
    font-size: 0.65rem; color: #5e4128;
    margin: 0; font-style: italic;
}

/* ── Scrollbar ────────────────────────────────────────────────────────────── */
.hp-popup::-webkit-scrollbar { width: 4px; }
.hp-popup::-webkit-scrollbar-track { background: transparent; }
.hp-popup::-webkit-scrollbar-thumb { background: #3b2a10; border-radius: 2px; }
</style>
