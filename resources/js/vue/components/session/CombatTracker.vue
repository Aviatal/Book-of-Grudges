<template>
    <!-- Panel widoczny dla MG zawsze, dla gracza gdy walka aktywna -->
    <FloatingPanel
        v-if="state || canDraw"
        panel-id="combat"
        title="⚔️ Walka"
        :default-pos="combatDefaultPos"
        :default-size="{ w: 260, h: 520 }"
        :min-width="220"
        :min-height="160"
        start-minimized
    >
        <template #header-actions>
            <span v-if="state" class="combat-round-badge">Runda {{ state.round }}</span>
            <button
                v-if="state && canDraw"
                class="combat-header-end"
                title="Zakończ walkę"
                @click="endCombat"
            >✕</button>
        </template>

        <div class="combat-inner">
        <!-- ── Brak walki (MG może zacząć) ─────────────────────────────── -->
        <div v-if="!state" class="combat-idle">
            <p class="combat-idle-hint">{{ mapTokenIds.length }} tokenów na mapie</p>
            <button
                class="combat-start-btn"
                :disabled="mapTokenIds.length === 0 || isSubmitting"
                @click="startCombat"
            >⚔️ Rozpocznij walkę</button>
        </div>

        <!-- ── Walka w toku ──────────────────────────────────────────────── -->
        <template v-else>

            <!-- Faza inicjatywy (nie wszyscy rzucili) -->
            <div v-if="!allRolled" class="combat-phase-label">🎲 Faza inicjatywy</div>

            <!-- Lista uczestników -->
            <div class="combat-list">
                <div
                    v-for="(p, i) in participants"
                    :key="p.token_id"
                    class="combat-row"
                    :class="{
                        'row-active': allRolled && i === state.current_index,
                        'row-rolled': p.initiative !== null,
                        'row-npc':    p.is_npc,
                    }"
                >
                    <!-- Numer porządkowy (tylko faza walki) -->
                    <span v-if="allRolled" class="combat-pos">#{{ i + 1 }}</span>

                    <!-- Avatar -->
                    <div class="combat-avatar">
                        <img v-if="p.image_url" :src="p.image_url" :alt="p.name" />
                        <span v-else class="combat-avatar-letter">{{ p.name.charAt(0) }}</span>
                    </div>

                    <!-- Nazwa + szczegóły rzutu -->
                    <div class="combat-info">
                        <div class="combat-name-row">
                            <span class="combat-name">{{ p.name }}</span>
                            <span class="combat-type-badge" :class="p.is_npc ? 'badge-npc' : 'badge-hero'">
                                {{ p.is_npc ? 'NPC' : 'G' }}
                            </span>
                        </div>
                        <span v-if="p.initiative !== null" class="combat-init-detail">
                            Zr{{ p.zr }}&nbsp;+&nbsp;[{{ p.roll }}]
                        </span>
                    </div>

                    <!-- Inicjatywa lub akcja rzutu -->
                    <div class="combat-right">
                        <span v-if="p.initiative !== null" class="combat-init-value">
                            {{ p.initiative }}
                        </span>
                        <template v-else>
                            <!-- NPC: przycisk MG -->
                            <button
                                v-if="p.is_npc && canDraw"
                                class="combat-roll-btn btn-npc"
                                :disabled="isSubmitting"
                                title="Rzuć inicjatywę NPC"
                                @click="rollNpcToken(p.token_id)"
                            >🎲</button>
                            <!-- Bohater: gracz rzuca sam -->
                            <button
                                v-else-if="!p.is_npc && p.hero_id === heroId"
                                class="combat-roll-btn btn-hero-self"
                                :disabled="isSubmitting"
                                title="Rzuć swoją inicjatywę"
                                @click="rollHeroInitiative"
                            >🎲 Rzuć</button>
                            <!-- Bohater: MG rzuca zastępczo (inny kolor!) -->
                            <button
                                v-else-if="!p.is_npc && canDraw"
                                class="combat-roll-btn btn-hero-proxy"
                                :disabled="isSubmitting"
                                title="Rzuć inicjatywę za gracza (zastępstwo)"
                                @click="rollHeroAsGm(p.token_id)"
                            >🎲 Zastęp.</button>
                            <!-- Oczekiwanie na gracza -->
                            <span v-else class="combat-pending">⋯</span>
                        </template>
                    </div>

                    <!-- Wskaźnik aktywnej tury -->
                    <span v-if="allRolled && i === state.current_index" class="combat-active-marker">◀</span>
                </div>
            </div>

            <!-- Przycisk: rzuć za NPC (MG, faza inicjatywy) -->
            <div v-if="canDraw && !allRolled && hasUnrolledNpcs" class="combat-gm-bar">
                <button class="combat-gm-btn" :disabled="isSubmitting" @click="rollNpcInitiative">
                    🎲 Rzuć za NPC
                </button>
            </div>

            <!-- Nawigacja tur (MG, faza walki) -->
            <div v-if="allRolled && canDraw" class="combat-nav">
                <button class="combat-nav-btn" title="Poprzednia tura" @click="setTurn('prev')">◀</button>
                <span class="combat-nav-name">{{ participants[state!.current_index]?.name ?? '—' }}</span>
                <button class="combat-nav-btn combat-nav-fwd" title="Następna tura" @click="setTurn('next')">▶</button>
            </div>

            <!-- Info czyja tura (gracz) -->
            <div v-if="allRolled && !canDraw" class="combat-turn-info">
                Tura:&nbsp;<strong>{{ participants[state!.current_index]?.name ?? '—' }}</strong>
            </div>

        </template>
        </div><!-- /combat-inner -->
    </FloatingPanel>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import FloatingPanel from './FloatingPanel.vue';

// Combat tracker: prawy górny róg
const combatDefaultPos = { x: window.innerWidth - 280, y: 10 };

// ── Typy ─────────────────────────────────────────────────────────────────────

interface CombatParticipant {
    token_id:   number;
    name:       string;
    image_url:  string | null;
    color:      string;
    is_npc:     boolean;
    hero_id:    number | null;
    zr:         number;
    initiative: number | null;
    roll:       number | null;
}

interface CombatState {
    active:        boolean;
    round:         number;
    current_index: number;
    participants:  CombatParticipant[];
}

// ── Props ─────────────────────────────────────────────────────────────────────

const props = defineProps<{
    mapTokenIds:          number[];
    heroId:               number;
    // Blade przekazuje PHP true jako "1" — akceptujemy number i boolean
    hasDrawingPermission: boolean | number;
}>();

// ── Stan reaktywny ───────────────────────────────────────────────────────────

const state        = ref<CombatState | null>(null);
const isSubmitting = ref(false);

// ── Computed ─────────────────────────────────────────────────────────────────

/** Blade może przekazać "1" zamiast true — normalizujemy do boolean */
const canDraw = computed(() => Boolean(props.hasDrawingPermission));

/** Bezpieczna lista uczestników (guard na wypadek niespójnego cache) */
const participants = computed<CombatParticipant[]>(() =>
    state.value?.participants ?? []
);

/** Wszyscy rzucili na inicjatywę → wchodzimy w fazę walki */
const allRolled = computed(() =>
    participants.value.length > 0 &&
    participants.value.every(p => p.initiative !== null)
);

/** Są NPC, które jeszcze nie mają inicjatywy */
const hasUnrolledNpcs = computed(() =>
    participants.value.some(p => p.is_npc && p.initiative === null)
);

// ── Metody API ────────────────────────────────────────────────────────────────

const startCombat = async (): Promise<void> => {
    if (isSubmitting.value || props.mapTokenIds.length === 0) return;
    isSubmitting.value = true;
    try {
        const { data } = await axios.post<CombatState>('/session/combat/start', {
            token_ids: props.mapTokenIds,
        });
        state.value = data;
    } catch (e) {
        console.error('Błąd startu walki', e);
    } finally {
        isSubmitting.value = false;
    }
};

const rollHeroInitiative = async (): Promise<void> => {
    if (isSubmitting.value) return;
    isSubmitting.value = true;
    try {
        const { data } = await axios.post<CombatState>('/session/combat/roll-hero');
        state.value = data;
    } catch (e) {
        console.error('Błąd rzutu na inicjatywę bohatera', e);
    } finally {
        isSubmitting.value = false;
    }
};

const rollNpcInitiative = async (): Promise<void> => {
    if (isSubmitting.value) return;
    isSubmitting.value = true;
    try {
        const { data } = await axios.post<CombatState>('/session/combat/roll-npc');
        state.value = data;
    } catch (e) {
        console.error('Błąd rzutu na inicjatywę NPC', e);
    } finally {
        isSubmitting.value = false;
    }
};

const rollHeroAsGm = async (tokenId: number): Promise<void> => {
    if (isSubmitting.value) return;
    isSubmitting.value = true;
    try {
        const { data } = await axios.post<CombatState>('/session/combat/roll-hero-proxy', { token_id: tokenId });
        state.value = data;
    } catch (e) {
        console.error('Błąd rzutu inicjatywy za bohatera', e);
    } finally {
        isSubmitting.value = false;
    }
};

const rollNpcToken = async (tokenId: number): Promise<void> => {
    if (isSubmitting.value) return;
    isSubmitting.value = true;
    try {
        const { data } = await axios.post<CombatState>('/session/combat/roll-npc', { token_id: tokenId });
        state.value = data;
    } catch (e) {
        console.error('Błąd rzutu na inicjatywę NPC', e);
    } finally {
        isSubmitting.value = false;
    }
};

const setTurn = async (direction: 'next' | 'prev'): Promise<void> => {
    if (isSubmitting.value) return;
    isSubmitting.value = true;
    try {
        const { data } = await axios.patch<CombatState>('/session/combat/turn', { direction });
        state.value = data;
    } catch (e) {
        console.error('Błąd zmiany tury', e);
    } finally {
        isSubmitting.value = false;
    }
};

const endCombat = async (): Promise<void> => {
    if (isSubmitting.value) return;
    isSubmitting.value = true;
    try {
        await axios.delete('/session/combat');
        state.value = null;
    } catch (e) {
        console.error('Błąd zakończenia walki', e);
    } finally {
        isSubmitting.value = false;
    }
};

// ── Lifecycle ─────────────────────────────────────────────────────────────────

onMounted(async () => {
    // Załaduj istniejący stan (np. po odświeżeniu strony)
    try {
        const { data } = await axios.get<CombatState | null>('/session/combat');
        // axios może zwrócić {} dla JSON null — sprawdzamy flagę active
        state.value = data?.active === true ? data : null;
    } catch (e) {
        console.error('Błąd ładowania stanu walki', e);
    }

    // Nasłuchuj na zmiany w czasie rzeczywistym
    window.Echo.channel('combat')
        .listen('.combat', (e: { type: string; state: CombatState | null }) => {
            if (e.type === 'ended') {
                state.value = null;
            } else {
                state.value = e.state;
            }
        });
});

onUnmounted(() => {
    window.Echo.leaveChannel('combat');
});
</script>

<style scoped>
/* ── Elementy nagłówka (renderowane w slocie FloatingPanel) ─────────────────── */

.combat-round-badge {
    background: #3b2a10;
    border: 1px solid #8b5a2b;
    border-radius: 10px;
    padding: 1px 8px;
    font-size: 0.68rem;
    font-weight: 700;
    color: #d4af37;
    letter-spacing: 0.05em;
}

.combat-header-end {
    background: none;
    border: none;
    color: #7a5035;
    font-size: 0.85rem;
    cursor: pointer;
    padding: 2px 5px;
    border-radius: 3px;
    line-height: 1;
    transition: color 0.15s;
}
.combat-header-end:hover { color: #e74c3c; }

/* ── Brak walki ─────────────────────────────────────────────────────────────── */
.combat-idle {
    padding: 14px 12px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: center;
}

.combat-idle-hint {
    font-size: 0.72rem;
    color: #7a6a4a;
    text-align: center;
    margin: 0;
}

.combat-start-btn {
    width: 100%;
    background: #1c1208;
    border: 1px solid #d4af37;
    color: #d4af37;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    font-family: inherit;
    transition: background 0.15s;
}
.combat-start-btn:hover:not(:disabled) { background: #2c1e0c; }
.combat-start-btn:disabled { opacity: 0.45; cursor: not-allowed; }

/* Wewnętrzny kontener — scroll w obrębie FloatingPanel */
.combat-inner {
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow-y: auto;
    font-family: ui-serif, Georgia, 'Times New Roman', serif;
    color: #e4d8b4;
}
.combat-inner::-webkit-scrollbar { width: 4px; }
.combat-inner::-webkit-scrollbar-track { background: transparent; }
.combat-inner::-webkit-scrollbar-thumb { background: #3b2a10; border-radius: 2px; }

/* ── Faza inicjatywy — etykieta ────────────────────────────────────────────── */
.combat-phase-label {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #8b5a2b;
    padding: 5px 10px 3px;
    border-bottom: 1px solid #2a1f10;
}

/* ── Lista uczestników ──────────────────────────────────────────────────────── */
.combat-list {
    display: flex;
    flex-direction: column;
}

.combat-row {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 8px 6px 10px;
    border-bottom: 1px solid #1e1710;
    border-left: 3px solid transparent;
    transition: border-color 0.15s, background 0.15s;
}

.combat-row:last-child { border-bottom: none; }

.row-active {
    border-left-color: #d4af37;
    background: rgba(212, 175, 55, 0.07);
}

.row-npc .combat-name { color: #c47a5a; }

.row-rolled .combat-init-detail { opacity: 1; }

/* Numer porządkowy */
.combat-pos {
    font-size: 0.62rem;
    color: #7a6a4a;
    min-width: 22px;
    font-weight: 700;
    font-family: monospace;
}
.row-active .combat-pos { color: #d4af37; }

/* Avatar */
.combat-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid #3b2a10;
    background: #1b1510;
    display: flex;
    align-items: center;
    justify-content: center;
}
.combat-avatar img { width: 100%; height: 100%; object-fit: cover; }
.combat-avatar-letter {
    font-size: 0.85rem;
    font-weight: 700;
    color: #c4a47c;
}
.row-active .combat-avatar { border-color: #d4af37; }

/* Informacje o uczestniku */
.combat-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 1px;
}

.combat-name-row {
    display: flex;
    align-items: center;
    gap: 4px;
    min-width: 0;
}

.combat-name {
    font-size: 0.8rem;
    font-weight: 700;
    color: #e4d8b4;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    min-width: 0;
}
.row-active .combat-name { color: #fae484; }

/* Odznaki NPC / Gracz */
.combat-type-badge {
    flex-shrink: 0;
    font-size: 0.55rem;
    font-weight: 800;
    letter-spacing: 0.04em;
    padding: 1px 4px;
    border-radius: 2px;
    line-height: 1.4;
}
.badge-npc  { background: #3d1a10; color: #c47a5a; border: 1px solid #5e2a18; }
.badge-hero { background: #0e2035; color: #7ab3d4; border: 1px solid #1a4060; }

.combat-init-detail {
    font-size: 0.6rem;
    color: #7a6a4a;
    font-family: monospace;
}

/* Prawa kolumna: inicjatywa / przycisk / oczekiwanie */
.combat-right {
    flex-shrink: 0;
    display: flex;
    align-items: center;
}

.combat-init-value {
    font-size: 1.05rem;
    font-weight: 800;
    color: #d4af37;
    font-family: monospace;
    min-width: 24px;
    text-align: right;
}
.row-active .combat-init-value { color: #fae484; font-size: 1.15rem; }

.combat-roll-btn {
    font-size: 0.68rem;
    padding: 3px 6px;
    border-radius: 3px;
    cursor: pointer;
    font-family: inherit;
    border: 1px solid;
    transition: background 0.12s;
    white-space: nowrap;
}
.combat-roll-btn:disabled { opacity: 0.45; cursor: not-allowed; }

/* NPC — złoto/brąz */
.btn-npc {
    background: #1c1208;
    border-color: #8b5a2b;
    color: #d4af37;
}
.btn-npc:hover:not(:disabled) { background: #2c1e0c; }

/* Gracz rzuca sam — zielony */
.btn-hero-self {
    background: #0d2010;
    border-color: #2d6b3a;
    color: #6dbf7d;
}
.btn-hero-self:hover:not(:disabled) { background: #142e1a; }

/* MG zastępczo za gracza — niebieski, wyraźnie inny */
.btn-hero-proxy {
    background: #0e1e30;
    border-color: #1a5080;
    color: #7ab3d4;
}
.btn-hero-proxy:hover:not(:disabled) { background: #162a40; }

.combat-pending {
    font-size: 1rem;
    color: #4a3a24;
    min-width: 20px;
    text-align: center;
}

/* Wskaźnik aktywnej tury */
.combat-active-marker {
    font-size: 0.7rem;
    color: #d4af37;
    margin-left: 2px;
    animation: pulse-marker 1s ease-in-out infinite alternate;
}

@keyframes pulse-marker {
    from { opacity: 0.5; }
    to   { opacity: 1; }
}

/* ── Przyciski MG (inicjatywa) ──────────────────────────────────────────────── */
.combat-gm-bar {
    padding: 6px 8px;
    border-top: 1px solid #2a1f10;
}

.combat-gm-btn {
    width: 100%;
    background: #1a1208;
    border: 1px solid #8b5a2b;
    color: #c4a47c;
    padding: 6px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.78rem;
    font-weight: 700;
    font-family: inherit;
    transition: background 0.12s;
}
.combat-gm-btn:hover:not(:disabled) { background: #241a0c; }
.combat-gm-btn:disabled { opacity: 0.45; cursor: not-allowed; }

/* ── Nawigacja tur ──────────────────────────────────────────────────────────── */
.combat-nav {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 8px;
    border-top: 1px solid #2a1f10;
    background: #100d08;
}

.combat-nav-btn {
    background: #1c1510;
    border: 1px solid #5e4128;
    color: #c4a47c;
    font-size: 0.9rem;
    width: 30px;
    height: 30px;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: background 0.12s, border-color 0.12s;
}
.combat-nav-btn:hover { background: #2c2010; border-color: #d4af37; color: #d4af37; }

.combat-nav-fwd {
    background: #1c1208;
    border-color: #8b5a2b;
    color: #d4af37;
}
.combat-nav-fwd:hover { background: #2c1e0c; border-color: #d4af37; }

.combat-nav-name {
    flex: 1;
    text-align: center;
    font-size: 0.78rem;
    font-weight: 700;
    color: #d4af37;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ── Info dla gracza ─────────────────────────────────────────────────────────── */
.combat-turn-info {
    padding: 8px 10px;
    border-top: 1px solid #2a1f10;
    font-size: 0.78rem;
    color: #c4a47c;
    text-align: center;
    background: #100d08;
}
.combat-turn-info strong { color: #d4af37; }

</style>
