<template>
    <div class="cb-wrap">

        <!-- Pasek inicjatywy (lepi się do górnej krawędzi pod toolbar) -->
        <div v-if="combatState" class="cb-topbar">
            <span class="cb-topbar-label">⚔️ Runda {{ combatState.round }}</span>

            <div class="cb-init-strip">
                <div
                    v-for="(p, i) in combatState.participants"
                    :key="p.token_id"
                    class="cb-chip"
                    :class="{
                        'chip-active': allRolled && i === combatState.current_index,
                        'chip-npc':    p.is_npc,
                    }"
                >
                    <img v-if="p.image_url" :src="p.image_url" class="chip-avatar" />
                    <span v-else class="chip-letter">{{ p.name.charAt(0) }}</span>
                    <span class="chip-name">{{ p.name }}</span>
                    <span v-if="p.initiative !== null" class="chip-init">{{ p.initiative }}</span>
                </div>
            </div>

            <span v-if="allRolled" class="cb-topbar-turn">
                Tura: <strong>{{ combatState.participants[combatState.current_index]?.name ?? '—' }}</strong>
            </span>
            <span v-else class="cb-topbar-turn cb-turn-phase">🎲 Faza inicjatywy</span>
        </div>

        <!-- Plansza -->
        <div class="cb-board" ref="boardRef" @mousedown.self="() => {}">
            <div
                v-for="p in combatState?.participants ?? []"
                :key="p.token_id"
                class="cb-token"
                :class="{
                    'token-active':    allRolled && isActive(p.token_id),
                    'token-npc':       p.is_npc,
                    'token-dragging':  draggingId === p.token_id,
                    'token-draggable': canDragToken(p),
                }"
                :style="tokenStyle(p.token_id)"
                @mousedown.stop="onTokenMouseDown($event, p)"
                @dragstart.prevent
                @click.stop="onTokenClick(p.token_id)"
            >
                <div class="cb-avatar-wrap">
                    <div class="cb-avatar">
                        <img v-if="p.image_url" :src="p.image_url" :alt="p.name" />
                        <span v-else class="cb-avatar-letter">{{ p.name.charAt(0) }}</span>
                    </div>
                    <div v-if="p.initiative !== null" class="cb-init-badge">{{ p.initiative }}</div>
                    <div v-if="allRolled && isActive(p.token_id)" class="cb-active-ring" />
                </div>
                <div class="cb-token-name">{{ p.name }}</div>
            </div>

            <div v-if="!combatState" class="cb-empty">
                Brak aktywnej walki
            </div>
        </div>

    </div>

    <!-- Popupy kart postaci -->
    <NpcSheetPopup
        v-if="selectedNpcToken && canDraw"
        :token="selectedNpcToken"
        :can-roll="true"
        @close="selectedNpcToken = null"
    />
    <HeroProxyPopup
        v-if="selectedHeroToken"
        :token="selectedHeroToken"
        :is-gm="canDraw"
        @close="selectedHeroToken = null"
    />
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { Token } from '@/types/Token';
import NpcSheetPopup from './NpcSheetPopup.vue';
import HeroProxyPopup from './HeroProxyPopup.vue';

interface CombatParticipant {
    token_id:   number;
    name:       string;
    image_url:  string | null;
    color:      string;
    is_npc:     boolean;
    hero_id:    number | null;
    initiative: number | null;
}

interface CombatState {
    active:        boolean;
    round:         number;
    current_index: number;
    participants:  CombatParticipant[];
}

interface BoardPosition {
    token_id: number;
    x: number;
    y: number;
}

const props = defineProps<{
    canDraw: boolean;
    tokens:  Token[];
    heroId:  number;
}>();

// ── Stan reaktywny ────────────────────────────────────────────────────────────

const boardRef          = ref<HTMLElement | null>(null);
const combatState       = ref<CombatState | null>(null);
const positions         = ref<BoardPosition[]>([]);
const draggingId        = ref<number | null>(null);
const dragOffset        = ref({ x: 0, y: 0 });
const saveTimer         = ref<ReturnType<typeof setTimeout> | null>(null);
const selectedNpcToken  = ref<Token | null>(null);
const selectedHeroToken = ref<Token | null>(null);
// Czy podczas mousedown token był przesuwany (odróżnia drag od click)
let wasDragged = false;

// ── Computed ──────────────────────────────────────────────────────────────────

const allRolled = computed(() => {
    const pts = combatState.value?.participants ?? [];
    return pts.length > 0 && pts.every(p => p.initiative !== null);
});

const isActive = (tokenId: number): boolean => {
    if (!combatState.value) return false;
    const idx = combatState.value.participants.findIndex(p => p.token_id === tokenId);
    return allRolled.value && idx === combatState.value.current_index;
};

// ── Pozycje tokenów ───────────────────────────────────────────────────────────

const getPosition = (tokenId: number): BoardPosition => {
    return positions.value.find(p => p.token_id === tokenId) ?? defaultPosition(tokenId);
};

const defaultPosition = (tokenId: number): BoardPosition => {
    const pts = combatState.value?.participants ?? [];
    const idx = pts.findIndex(p => p.token_id === tokenId);
    const total = pts.length;
    const cols  = Math.max(1, Math.ceil(Math.sqrt(total)));
    const col   = idx % cols;
    const row   = Math.floor(idx / cols);
    const rows  = Math.ceil(total / cols);
    const padX  = 12;
    const padY  = 15;
    const stepX = cols  > 1 ? (100 - padX * 2) / (cols - 1) : 0;
    const stepY = rows  > 1 ? (100 - padY * 2) / (rows - 1) : 0;
    return {
        token_id: tokenId,
        x: total === 1 ? 50 : padX + col * stepX,
        y: total === 1 ? 50 : padY + row * stepY,
    };
};

const tokenStyle = (tokenId: number) => {
    const pos = getPosition(tokenId);
    return { left: `${pos.x}%`, top: `${pos.y}%` };
};

// ── Uprawnienia do przeciągania ───────────────────────────────────────────────

const canDragToken = (p: CombatParticipant): boolean => {
    if (props.canDraw) return true;
    if (!p.is_npc && p.hero_id === props.heroId) return true;
    return false;
};

// ── Drag & Drop — document-level listenery ────────────────────────────────────

// Pozycja myszy przy mousedown — pozwala odróżnić klik od drag
let dragStartX = 0;
let dragStartY = 0;
const DRAG_THRESHOLD = 5; // px

const onTokenMouseDown = (e: MouseEvent, p: CombatParticipant): void => {
    if (!canDragToken(p)) return;
    if (!boardRef.value) return;

    e.preventDefault(); // zapobiega zaznaczaniu tekstu podczas drag
    wasDragged = false;
    dragStartX = e.clientX;
    dragStartY = e.clientY;

    const board = boardRef.value.getBoundingClientRect();
    const pos   = getPosition(p.token_id);

    dragOffset.value = {
        x: e.clientX - (board.left + (pos.x / 100) * board.width),
        y: e.clientY - (board.top  + (pos.y / 100) * board.height),
    };
    draggingId.value = p.token_id;

    document.addEventListener('mousemove', onDocMouseMove);
    document.addEventListener('mouseup',   onDocMouseUp);
};

const onDocMouseMove = (e: MouseEvent): void => {
    if (draggingId.value === null || !boardRef.value) return;
    e.preventDefault();

    // Uznaj za drag dopiero po przekroczeniu progu
    if (!wasDragged) {
        const dx = Math.abs(e.clientX - dragStartX);
        const dy = Math.abs(e.clientY - dragStartY);
        if (dx < DRAG_THRESHOLD && dy < DRAG_THRESHOLD) return;
        wasDragged = true;
    }

    const board = boardRef.value.getBoundingClientRect();
    const updated: BoardPosition = {
        token_id: draggingId.value,
        x: Math.max(2, Math.min(98, ((e.clientX - dragOffset.value.x - board.left) / board.width)  * 100)),
        y: Math.max(2, Math.min(98, ((e.clientY - dragOffset.value.y - board.top)  / board.height) * 100)),
    };

    const idx = positions.value.findIndex(p => p.token_id === draggingId.value);
    if (idx >= 0) positions.value[idx] = updated;
    else           positions.value.push(updated);
};

const onDocMouseUp = (e: MouseEvent): void => {
    e.preventDefault();
    document.removeEventListener('mousemove', onDocMouseMove);
    document.removeEventListener('mouseup',   onDocMouseUp);

    const wasActuallyDragged = wasDragged;
    draggingId.value = null;

    if (wasActuallyDragged) scheduleSave();
};

// ── Kliknięcie w token (otwiera kartę postaci) ────────────────────────────────

const onTokenClick = (tokenId: number): void => {
    if (wasDragged) {
        wasDragged = false; // reset na następny klik
        return;
    }

    const token = props.tokens.find(t => t.id === tokenId);
    if (!token) return;

    if (token.hero_id) {
        selectedHeroToken.value = token;
    } else if (props.canDraw) {
        selectedNpcToken.value = token;
    }
};

// ── Zapis + broadcast ─────────────────────────────────────────────────────────

const scheduleSave = (): void => {
    if (saveTimer.value) clearTimeout(saveTimer.value);
    saveTimer.value = setTimeout(savePositions, 300);
};

const savePositions = async (): Promise<void> => {
    if (!combatState.value) return;
    const all = combatState.value.participants.map(p => getPosition(p.token_id));
    try {
        await axios.post('/session/combat/board', { positions: all });
    } catch (e) {
        console.error('Błąd zapisu pozycji planszy', e);
    }
};

// ── Lifecycle ─────────────────────────────────────────────────────────────────

onMounted(async () => {
    try {
        const [stateRes, boardRes] = await Promise.all([
            axios.get<CombatState | null>('/session/combat'),
            axios.get<BoardPosition[]>('/session/combat/board'),
        ]);
        combatState.value = stateRes.data?.active === true ? stateRes.data : null;
        if (Array.isArray(boardRes.data) && boardRes.data.length > 0) {
            positions.value = boardRes.data;
        }
    } catch (e) {
        console.error('Błąd ładowania stanu walki / planszy', e);
    }

    window.Echo.channel('combat')
        .listen('.combat', (e: { type: string; state: any }) => {
            if (e.type === 'ended') {
                combatState.value = null;
            } else if (e.type === 'board-updated') {
                if (Array.isArray(e.state?.positions)) {
                    positions.value = e.state.positions;
                }
            } else if (e.type === 'board-visibility') {
                // Widoczność planszy obsługuje Tabletop.vue
            } else {
                combatState.value = e.state;
            }
        });
});

onUnmounted(() => {
    if (saveTimer.value) clearTimeout(saveTimer.value);
    document.removeEventListener('mousemove', onDocMouseMove);
    document.removeEventListener('mouseup',   onDocMouseUp);
    // Kanał combat jest współdzielony z CombatTracker — nie opuszczamy go tutaj
});
</script>

<style scoped>
/* ── Kontener główny ────────────────────────────────────────────────────────── */
.cb-wrap {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    background:
        repeating-linear-gradient(0deg,  transparent, transparent 59px, rgba(255,255,255,0.025) 59px, rgba(255,255,255,0.025) 60px),
        repeating-linear-gradient(90deg, transparent, transparent 59px, rgba(255,255,255,0.025) 59px, rgba(255,255,255,0.025) 60px),
        radial-gradient(ellipse at 50% 40%, rgba(50,30,10,0.4) 0%, transparent 70%),
        #0c0a06;
    user-select: none;
}

/* ── Pasek inicjatywy (góra) ─────────────────────────────────────────────────── */
.cb-topbar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 14px;
    background: rgba(10, 8, 4, 0.85);
    border-bottom: 1px solid #2a1f10;
    flex-shrink: 0;
    /* Uwzględniamy toolbar nad spodem — toolbar ma ok. 42px, ale używamy margin-top by nie przykryć */
    margin-top: 44px;
    backdrop-filter: blur(4px);
    z-index: 1;
}

.cb-topbar-label {
    font-family: ui-serif, Georgia, serif;
    font-size: 0.78rem;
    font-weight: 700;
    color: #d4af37;
    flex-shrink: 0;
    letter-spacing: 0.05em;
}

.cb-init-strip {
    display: flex;
    align-items: center;
    gap: 5px;
    flex: 1;
    overflow-x: auto;
    padding: 0 2px;
}
.cb-init-strip::-webkit-scrollbar { height: 3px; }
.cb-init-strip::-webkit-scrollbar-thumb { background: #3b2a10; border-radius: 2px; }

.cb-chip {
    display: flex;
    align-items: center;
    gap: 4px;
    background: #1a140c;
    border: 1px solid #3b2a10;
    border-radius: 20px;
    padding: 2px 8px 2px 3px;
    flex-shrink: 0;
    transition: border-color 0.15s;
}
.chip-active { border-color: #d4af37; background: rgba(212,175,55,0.1); }
.chip-npc .chip-name { color: #c47a5a; }
.chip-active.chip-npc .chip-name { color: #f0a070; }

.chip-avatar {
    width: 20px; height: 20px;
    border-radius: 50%; object-fit: cover;
}
.chip-letter {
    width: 20px; height: 20px;
    border-radius: 50%; background: #2a1f10;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.65rem; font-weight: 700; color: #c4a47c;
}
.chip-name {
    font-size: 0.65rem; font-weight: 700; color: #c4a47c;
    max-width: 70px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.chip-active .chip-name { color: #fae484; }
.chip-init {
    font-size: 0.68rem; font-weight: 800; color: #d4af37;
    font-family: monospace; margin-left: 2px;
}

.cb-topbar-turn {
    font-family: ui-serif, Georgia, serif;
    font-size: 0.75rem; color: #c4a47c; flex-shrink: 0;
}
.cb-topbar-turn strong { color: #d4af37; }
.cb-turn-phase { color: #6a5a3a; font-style: italic; }

/* ── Plansza ─────────────────────────────────────────────────────────────────── */
.cb-board {
    flex: 1;
    position: relative;
    overflow: hidden;
}

.cb-empty {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: ui-serif, Georgia, serif;
    font-size: 1rem;
    color: #3a2f1a;
}

/* ── Token ───────────────────────────────────────────────────────────────────── */
.cb-token {
    position: absolute;
    transform: translate(-50%, -50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    z-index: 2;
}

.cb-token:not(.token-dragging) {
    transition: left 0.08s ease, top 0.08s ease;
}

.cb-token.token-draggable { cursor: grab; }
.cb-token.token-dragging  {
    cursor: grabbing;
    z-index: 10;
    filter: drop-shadow(0 0 14px rgba(212,175,55,0.7));
}

.cb-avatar-wrap {
    position: relative;
}

.cb-avatar {
    width: 72px; height: 72px;
    border-radius: 50%; overflow: hidden;
    border: 3px solid #3b2a10;
    background: #1b1510;
    display: flex; align-items: center; justify-content: center;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.cb-avatar img { width: 100%; height: 100%; object-fit: cover; }
.cb-avatar-letter { font-size: 1.5rem; font-weight: 700; color: #c4a47c; }

.token-npc .cb-avatar  { border-color: #5e2a18; }
.token-active .cb-avatar {
    border-color: #d4af37;
    box-shadow: 0 0 0 3px rgba(212,175,55,0.3), 0 0 20px rgba(212,175,55,0.4);
}

.cb-init-badge {
    position: absolute;
    top: -6px; right: -6px;
    background: #d4af37; color: #0d0b07;
    font-size: 0.62rem; font-weight: 900; font-family: monospace;
    border-radius: 8px; padding: 1px 5px; line-height: 1.4;
    border: 1px solid #8b5a2b;
}

.cb-active-ring {
    position: absolute;
    inset: -7px; border-radius: 50%;
    border: 2px solid #d4af37;
    animation: pulse-ring 1.2s ease-in-out infinite;
    pointer-events: none;
}

@keyframes pulse-ring {
    0%   { transform: scale(0.92); opacity: 0.9; }
    50%  { transform: scale(1.06); opacity: 0.4; }
    100% { transform: scale(0.92); opacity: 0.9; }
}

.cb-token-name {
    font-family: ui-serif, Georgia, serif;
    font-size: 0.72rem; font-weight: 700; color: #e4d8b4;
    text-align: center; max-width: 90px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    text-shadow: 0 1px 5px #000, 0 0 10px #000;
    pointer-events: none;
}
.token-active .cb-token-name { color: #fae484; }
.token-npc .cb-token-name    { color: #c47a5a; }
.token-active.token-npc .cb-token-name { color: #f0a070; }
</style>
