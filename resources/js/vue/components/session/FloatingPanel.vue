<template>
    <div
        ref="panelEl"
        class="fp"
        :style="wrapperStyle"
        @mousedown="bringToFront"
    >
        <!-- ── Narożniki resize (wszystkie 4) ────────────────────────────── -->
        <div v-show="!minimized" class="fp-r fp-r-nw" @mousedown.prevent="e => startResize(e, 'nw')" />
        <div v-show="!minimized" class="fp-r fp-r-ne" @mousedown.prevent="e => startResize(e, 'ne')" />
        <div v-show="!minimized" class="fp-r fp-r-sw" @mousedown.prevent="e => startResize(e, 'sw')" />
        <div v-show="!minimized" class="fp-r fp-r-se" @mousedown.prevent="e => startResize(e, 'se')" />

        <!-- ── Nagłówek / uchwyt do przeciągania ────────────────────────── -->
        <div class="fp-header" @mousedown.prevent="startDrag">
            <span class="fp-title">{{ title }}</span>
            <div class="fp-header-right" @mousedown.stop>
                <slot name="header-actions" />
                <button class="fp-btn" :title="minimized ? 'Rozwiń' : 'Zwiń'" @click="toggleMinimize">
                    {{ minimized ? '▲' : '▼' }}
                </button>
            </div>
        </div>

        <!-- ── Treść ─────────────────────────────────────────────────────── -->
        <div v-show="!minimized" class="fp-body">
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';

// Globalny licznik z-index współdzielony przez wszystkie instancje
let zCounter = 1000;

type ResizeDir = 'nw' | 'ne' | 'sw' | 'se';

const props = defineProps<{
    panelId:         string;
    title:           string;
    defaultPos?:     { x: number; y: number };
    defaultSize?:    { w: number; h: number };
    minWidth?:       number;
    minHeight?:      number;
    startMinimized?: boolean;
}>();

// ── Stan reaktywny ─────────────────────────────────────────────────────────────
const pos       = ref({ x: props.defaultPos?.x  ?? 100, y: props.defaultPos?.y  ?? 100 });
const size      = ref({ w: props.defaultSize?.w ?? 320, h: props.defaultSize?.h ?? 400 });
const minimized = ref(props.startMinimized ?? false);
const zIndex    = ref(zCounter++);
const panelEl   = ref<HTMLElement | null>(null);

// ── Zmienne robocze drag/resize (nie reaktywne — nie potrzebują reactivity) ───
let dragging     = false;
let dragOffX     = 0;
let dragOffY     = 0;

let resizing     = false;
let resDir: ResizeDir = 'se';
let resStartX    = 0;
let resStartY    = 0;
let resStartW    = 0;
let resStartH    = 0;
let resStartPosX = 0;
let resStartPosY = 0;

// ── Computed style ─────────────────────────────────────────────────────────────
const wrapperStyle = computed(() => ({
    left:   `${pos.value.x}px`,
    top:    `${pos.value.y}px`,
    width:  `${size.value.w}px`,
    height: minimized.value ? 'auto' : `${size.value.h}px`,
    zIndex: zIndex.value,
}));

// ── Bring to front ─────────────────────────────────────────────────────────────
const bringToFront = () => { zIndex.value = ++zCounter; };

// ── Minimize ───────────────────────────────────────────────────────────────────
const toggleMinimize = () => { minimized.value = !minimized.value; save(); };

// ── Drag ───────────────────────────────────────────────────────────────────────
const startDrag = (e: MouseEvent) => {
    if ((e.target as HTMLElement).closest('.fp-header-right')) return;
    dragging = true;
    dragOffX = e.clientX - pos.value.x;
    dragOffY = e.clientY - pos.value.y;
    document.addEventListener('mousemove', onMouseMove);
    document.addEventListener('mouseup',   onMouseUp);
};

// ── Resize — wszystkie 4 narożniki ─────────────────────────────────────────────
const startResize = (e: MouseEvent, dir: ResizeDir) => {
    resizing     = true;
    resDir       = dir;
    resStartX    = e.clientX;
    resStartY    = e.clientY;
    resStartW    = size.value.w;
    resStartH    = size.value.h;
    resStartPosX = pos.value.x;
    resStartPosY = pos.value.y;
    document.addEventListener('mousemove', onMouseMove);
    document.addEventListener('mouseup',   onMouseUp);
};

// ── Wspólny handler ruchu myszy ────────────────────────────────────────────────
const onMouseMove = (e: MouseEvent) => {
    if (dragging) {
        pos.value = {
            x: Math.max(0, Math.min(window.innerWidth  - 60,  e.clientX - dragOffX)),
            y: Math.max(0, Math.min(window.innerHeight - 32, e.clientY - dragOffY)),
        };
        return;
    }

    if (!resizing) return;

    const dx   = e.clientX - resStartX;
    const dy   = e.clientY - resStartY;
    const minW = props.minWidth  ?? 220;
    const minH = props.minHeight ?? 120;

    let newW = resStartW;
    let newH = resStartH;
    let newX = resStartPosX;
    let newY = resStartPosY;

    // Zmiana szerokości
    if (resDir === 'se' || resDir === 'ne') {
        newW = Math.max(minW, resStartW + dx);
    } else {
        // sw / nw — rozciąganie w lewo: pozycja X się przesuwa
        newW = Math.max(minW, resStartW - dx);
        newX = resStartPosX + resStartW - newW;
    }

    // Zmiana wysokości
    if (resDir === 'se' || resDir === 'sw') {
        newH = Math.max(minH, resStartH + dy);
    } else {
        // ne / nw — rozciąganie w górę: pozycja Y się przesuwa
        newH = Math.max(minH, resStartH - dy);
        newY = resStartPosY + resStartH - newH;
    }

    size.value = { w: newW, h: newH };
    pos.value  = { x: newX, y: newY };
};

const onMouseUp = () => {
    if (dragging || resizing) save();
    dragging = false;
    resizing = false;
    document.removeEventListener('mousemove', onMouseMove);
    document.removeEventListener('mouseup',   onMouseUp);
};

// ── Trwałość w localStorage ────────────────────────────────────────────────────
const LSKEY = `fp_${props.panelId}`;

const save = () => {
    localStorage.setItem(LSKEY, JSON.stringify({
        x: pos.value.x, y: pos.value.y,
        w: size.value.w, h: size.value.h,
        minimized: minimized.value,
    }));
};

const load = () => {
    try {
        const raw = localStorage.getItem(LSKEY);
        if (!raw) return;
        const d = JSON.parse(raw);
        if (d.x         != null) pos.value.x      = d.x;
        if (d.y         != null) pos.value.y      = d.y;
        if (d.w         != null) size.value.w     = d.w;
        if (d.h         != null) size.value.h     = d.h;
        if (d.minimized != null) minimized.value  = d.minimized;
    } catch { /* nieprawidłowy JSON — ignoruj */ }
};

onMounted(load);

onUnmounted(() => {
    document.removeEventListener('mousemove', onMouseMove);
    document.removeEventListener('mouseup',   onMouseUp);
});
</script>

<style scoped>
.fp {
    position: fixed;
    display: flex;
    flex-direction: column;
    background: rgba(14, 11, 6, 0.97);
    border: 1px solid #5e4128;
    border-radius: 4px;
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.75);
    font-family: ui-serif, Georgia, 'Times New Roman', serif;
    color: #e4d8b4;
    overflow: hidden;
}

/* ── Nagłówek ─────────────────────────────────────────────────────────────── */
.fp-header {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    background: #1c1510;
    border-bottom: 1px solid #5e4128;
    cursor: move;
    user-select: none;
    flex-shrink: 0;
}

.fp-title {
    flex: 1;
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #d4af37;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.fp-header-right {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-shrink: 0;
}

.fp-btn {
    background: none;
    border: none;
    color: #7a5035;
    font-size: 0.72rem;
    cursor: pointer;
    padding: 2px 5px;
    border-radius: 3px;
    line-height: 1;
    transition: color 0.15s;
}
.fp-btn:hover { color: #d4af37; }

/* ── Treść ────────────────────────────────────────────────────────────────── */
.fp-body {
    flex: 1;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    min-height: 0;
}

/* ── Uchwyty resize — 4 narożniki ─────────────────────────────────────────── */
.fp-r {
    position: absolute;
    width: 14px;
    height: 14px;
    z-index: 10;
    opacity: 0.6;
    transition: opacity 0.15s;
}
.fp-r:hover { opacity: 1; }

.fp-r-se {
    bottom: 0; right: 0;
    cursor: se-resize;
    background: linear-gradient(135deg, transparent 50%, #8b5a2b 50%);
    border-radius: 0 0 4px 0;
}
.fp-r-sw {
    bottom: 0; left: 0;
    cursor: sw-resize;
    background: linear-gradient(225deg, transparent 50%, #8b5a2b 50%);
    border-radius: 0 0 0 4px;
}
.fp-r-ne {
    top: 0; right: 0;
    cursor: ne-resize;
    background: linear-gradient(45deg, transparent 50%, #8b5a2b 50%);
    border-radius: 0 4px 0 0;
}
.fp-r-nw {
    top: 0; left: 0;
    cursor: nw-resize;
    background: linear-gradient(315deg, transparent 50%, #8b5a2b 50%);
    border-radius: 4px 0 0 0;
}
</style>
