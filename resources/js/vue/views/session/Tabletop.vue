<template>
    <div class="game-container">
        <v-stage
            :config="stageConfig"
            @mousedown="handleStageMouseDown"
            @mousemove="handleStageMouseMove"
            @mouseup="handleStageMouseUp"
        >
            <v-layer>
                <v-group
                    v-for="token in tokens"
                    :key="token.id"
                    :config="{
                        x: token.x,
                        y: token.y,
                        draggable: props.heroId === token.hero_id
                    }"
                    @dragmove="(e) => handleGroupDragMove(e, token)"
                    @dragend="handleGroupDragEnd"
                    @click="selectedIds = [token.id]"
                >
                    <v-circle v-if="selectedIds.includes(token.id)" :config="{
                        radius: 55,
                        fill: '#d4af37',
                        opacity: 0.4
                    }" />

                    <v-image v-if="loadedImages[token.id]" :config="{
                        image: loadedImages[token.id],
                        width: 100,
                        height: 100,
                        x: -50,
                        y: -50,
                        clipFunc: (ctx) => {
                            // Centrujemy koło tnące (50, 50 to środek obrazka 100x100)
                            ctx.arc(50, 50, 50, 0, Math.PI * 2, false);
                        }
                    }" />

                    <v-circle :config="{
                        radius: 50,
                        stroke: selectedIds.includes(token.id) ? '#00ff00' : (props.heroId === token.hero_id ? '#d4af37' : 'black'),
                        strokeWidth: selectedIds.includes(token.id) ? 5 : 3
                    }" />

                    <v-text :config="{
                        text: token.name,
                        fontSize: 12,
                        x: -50,
                        y: 60,
                        width: 100,
                        align: 'center',
                        fill: 'white',
                        fontStyle: 'bold',
                        shadowColor: 'black',
                        shadowBlur: 5
                    }" />
                </v-group>

                <v-rect v-if="selectionBox.visible" :config="{
                    x: selectionBox.x,
                    y: selectionBox.y,
                    width: selectionBox.width,
                    height: selectionBox.height,
                    fill: 'rgba(0, 161, 255, 0.3)',
                    stroke: '#00a1ff',
                    strokeWidth: 1
                }" />
            </v-layer>
        </v-stage>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import {Token} from "@/types/Token";

const props = defineProps<{
    heroId: number
}>();

interface MoveTokenEvent {
    id: number;
    x: number;
    y: number;
}
window.Echo.channel('token-move')
    .listen('.move', (e: MoveTokenEvent) => {
        moveToken(e.id, e.x, e.y);
    })
    .listen('.batch-move', (e: { tokens: MoveTokenEvent[] }) => {
        console.log('Received batch move event:', e);
        e.tokens.forEach(token => {
            moveToken(token.id, token.x, token.y);
        })
    });

const moveToken = (tokenId: number, x: number, y: number) => {
    const token = tokens.value.find(t => t.id === tokenId);
    if (token) {
        token.x = x;
        token.y = y;
    }
}

const tokens = ref<Token[]>([]);
const loadedImages = ref<Record<number, HTMLImageElement>>({});

const stageConfig = ref({
    width: window.innerWidth,
    height: window.innerHeight,
});

const loadImage = (token: Token) => {
    if (!token.image) return;

    const img = new window.Image();
    img.src = token.image_url;
    img.onload = () => {
        loadedImages.value[token.id] = img;
    };
};

const updateSize = () => {
    stageConfig.value.width = window.innerWidth;
    stageConfig.value.height = window.innerHeight;
};

// Pobierz tokeny z bazy przy starcie
const fetchTokens = async () => {
    const response = await axios.get('/session/tokens');
    tokens.value = response.data;

    tokens.value.forEach(token => {
        loadImage(token);
    });
};

// Aktualizacja pozycji po przeciągnięciu
const updateTokenPosition = async (event, token) => {
    const { x, y } = event.target.attrs;

    token.x = x;
    token.y = y;

    try {
        await axios.patch(`/session/tokens/${token.id}/move`, { x, y });
        console.log(`Token ${token.name} zapisany na pozycji: ${x}, ${y}`);
    } catch (error) {
        console.error("Błąd zapisu:", error);
    }
};
const selectedIds = ref<number[]>([]);
const selectionBox = ref({
    x: 0,
    y: 0,
    width: 0,
    height: 0,
    visible: false
});

// Funkcja pomocnicza do sprawdzania, czy token jest wewnątrz prostokąta
const isInside = (token: Token, box: any) => {
    // Obliczamy granice prostokąta (obsługuje przeciąganie w każdą stronę)
    const x1 = Math.min(box.x, box.x + box.width);
    const x2 = Math.max(box.x, box.x + box.width);
    const y1 = Math.min(box.y, box.y + box.height);
    const y2 = Math.max(box.y, box.y + box.height);

    return token.x >= x1 && token.x <= x2 && token.y >= y1 && token.y <= y2;
};

const handleStageMouseDown = (e: any) => {
    // Jeśli klikniemy w pusty obszar sceny
    if (e.target === e.target.getStage()) {
        const pos = e.target.getStage().getPointerPosition();
        selectionBox.value = { x: pos.x, y: pos.y, width: 0, height: 0, visible: true };
        selectedIds.value = []; // Reset zaznaczenia
    }
};

const handleStageMouseMove = (e: any) => {
    if (!selectionBox.value.visible) return;

    const pos = e.target.getStage().getPointerPosition();
    selectionBox.value.width = pos.x - selectionBox.value.x;
    selectionBox.value.height = pos.y - selectionBox.value.y;
};

const handleStageMouseUp = () => {
    if (!selectionBox.value.visible) return;

    // Zaznaczamy tylko tokeny, które należą do gracza (zgodnie z Twoją logiką props.heroId)
    const newlySelected = tokens.value
        .filter(t => isInside(t, selectionBox.value) && t.hero_id === props.heroId)
        .map(t => t.id);

    selectedIds.value = newlySelected;
    selectionBox.value.visible = false;
};
const handleGroupDragMove = (e: any, draggedToken: Token) => {
    // Jeśli przesuwany token nie jest zaznaczony, zaznacz go (single selection)
    if (!selectedIds.value.includes(draggedToken.id)) {
        selectedIds.value = [draggedToken.id];
        return;
    }

    // Obliczamy o ile przesunął się aktualny token
    const { x, y } = e.target.attrs;
    const dx = x - draggedToken.x;
    const dy = y - draggedToken.y;

    // Przesuwamy wszystkie INNE zaznaczone tokeny o tę samą różnicę
    tokens.value.forEach(t => {
        if (selectedIds.value.includes(t.id) && t.id !== draggedToken.id) {
            t.x += dx;
            t.y += dy;
        }
    });

    // Aktualizujemy pozycję "lidera" (draggedToken jest reaktywny, więc to zaktualizuje widok)
    draggedToken.x = x;
    draggedToken.y = y;
};

const handleGroupDragEnd = async () => {
    const movedTokens = tokens.value
        .filter(t => selectedIds.value.includes(t.id))
        .map(t => ({
            id: t.id,
            name: t.name,
            hero_id: t.hero_id,
            image: t.image,
            x: t.x,
            y: t.y
        }));

    if (movedTokens.length === 0) return;

    try {
        await axios.patch('/session/tokens/bulk-move', {
            tokens: movedTokens
        });

        console.log(`Zaktualizowano grupę: ${movedTokens.length} tokenów.`);
    } catch (error) {
        console.error("Błąd zapisu grupowego:", error);
    }
};

onMounted(() => {
    fetchTokens();
    window.addEventListener('resize', updateSize);
});
onUnmounted(() => {
    window.removeEventListener('resize', updateSize);
    window.Echo.leaveChannel('token-move');
});
</script>

<style scoped>
.game-container {
    position: fixed; /* Wyrywa element z normalnego układu */
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: #1a1a1a; /* Jeszcze ciemniejszy, bitewny kolor */
    z-index: 9999; /* Musi być wyżej niż Twój header "Book of Grudges" */
    overflow: hidden;
}

/* Dodatek: styl dla przycisku wyjścia, jeśli będziesz go chciał */
.exit-button {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 10000;
    padding: 10px;
    background: #444;
    color: white;
    cursor: pointer;
}
</style>
