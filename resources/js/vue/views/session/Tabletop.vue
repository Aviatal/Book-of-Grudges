<template>
    <div class="game-container">
        <div class="toolbar shadow-lg">
            <div class="tool-group">
                <button :class="{ active: activeTool === 'select' }" @click="activeTool = 'select'">🏹 Tokeny</button>
                <button :class="{ active: activeTool === 'select-draw' }" @click="activeTool = 'select-draw'">🛠️ Edytuj</button>
            </div>
            <div class="tool-group">
                <button :class="{ active: activeTool === 'pen' }" @click="activeTool = 'pen'">✏️ Pisak</button>
                <button :class="{ active: activeTool === 'rect' }" @click="activeTool = 'rect'">⬜ Prostokąt</button>
                <button :class="{ active: activeTool === 'eraser' }" @click="activeTool = 'eraser'">🧹 Gumka</button>
            </div>
            <button :class="{ active: activeTool === 'ping' }" @click="activeTool = 'ping'">📍 Ping</button>
            <div v-if="activeTool === 'ping'" class="color-picker">
                <div
                    v-for="color in colors"
                    :key="color.value"
                    class="color-dot"
                    :style="{ backgroundColor: color.value, border: pingColor === color.value ? '2px solid white' : 'none' }"
                    @click="pingColor = color.value"
                ></div>
            </div>
        </div>

        <v-stage
            :config="stageConfig"
            @mousedown="handleStageMouseDown"
            @mousemove="handleStageMouseMove"
            @mouseup="handleStageMouseUp"
        >
            <v-layer ref="drawLayer">
                <template v-for="draw in drawings" :key="draw.id">
                    <v-line
                        v-if="draw.type === 'pen'"
                        :config="{
                            ...draw,
                            draggable: activeTool === 'select-draw'
                        }"
                        @click="(e) => handleShapeClick(e, draw.id)"
                        @dragend="(e) => handleTransformEnd(e, draw)"
                    />
                    <v-rect
                        v-if="draw.type === 'rect'"
                        :config="{
                            ...draw,
                            draggable: activeTool === 'select-draw'
                        }"
                        @click="(e) => handleShapeClick(e, draw.id)"
                        @transformend="(e) => handleTransformEnd(e, draw)"
                        @dragend="(e) => handleTransformEnd(e, draw)"
                    />
                </template>

                <v-transformer
                    ref="transformerNode"
                    :config="{
                        visible: activeTool === 'select-draw' && selectedShapeId !== null,
                        enabledAnchors: [ 'top-center', 'top-left', 'middle-left', 'bottom-left', 'bottom-center', 'bottom-right', 'middle-right', 'top-right' ]
                    }"
                />
            </v-layer>

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

            <v-layer>
                <PingItem
                    v-for="ping in pings"
                    :key="ping.id"
                    :x="ping.x"
                    :y="ping.y"
                    :color="ping.color"
                />
            </v-layer>
        </v-stage>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import {Token} from "@/types/Token";
import {DrawingData} from "@/types/DrawingData";
import PingItem from '../../components/session/PingItem.vue';

const props = defineProps<{
    heroId: number
}>();

interface MoveTokenEvent {
    id: number;
    x: number;
    y: number;
}
interface DrawingEditEvent {
    drawingId: number;
    data: DrawingData
}

interface PingData {
    id: number
    x: number,
    y: number,
    color: number,
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
window.Echo.channel('drawings')
    .listen('.drawing-update', (e: DrawingEditEvent) => {
        let drawing = drawings.value.find(d => d.id === e.drawingId);
        if (drawing) {
            Object.assign(drawing, e.data);
        }
    })
    .listen('.drawing-create', (e: {data: DrawingData}) => {
        console.log('Received drawing create event:', e);
        drawings.value.push(e.data);
    })
    .listen('.drawing-delete', (e: { drawingId: number}) => {
        drawings.value = drawings.value.filter(d => d.id !== e.drawingId);
    })
    .listen('.ping', (e: { newPing: PingData }) => {
        const newPing = {
            id: Date.now(),
            x: e.newPing.x,
            y: e.newPing.y,
            color: pingColor.value
        };

        createPing(newPing);
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
const activeTool = ref<'select' | 'pen' | 'rect' | 'circle' | 'select-draw' | 'eraser' | 'ping'>('select');
const drawings = ref<any[]>([]);
const isDrawing = ref(false);
const history = ref<any[]>([]);
const selectedShapeId = ref<number | null>(null);
const pingColor = ref('#00a1ff');
const pings = ref<any[]>([]);

const colors = [
    { name: 'Niebieski', value: '#00a1ff' },
    { name: 'Czerwony', value: '#ff4d4d' },
    { name: 'Zielony', value: '#2ecc71' },
    { name: 'Złoty', value: '#d4af37' }
];

const transformerNode = ref();
const drawLayer = ref();

const fetchDrawings = async () => {
    const { data } = await axios.get('/session/drawings');
    drawings.value = data.map(d => ({
        id: d.id,
        type: d.type,
        ...d.data // Rozpakowujemy właściwości x, y, points itp.
    }));
};

const handleShapeClick = (e: any, shapeId: number) => {
    // 1. Logika GUMKI
    if (activeTool.value === 'eraser') {
        drawings.value = drawings.value.filter(d => d.id !== shapeId);
        selectedShapeId.value = null;
        transformerNode.value.getNode().nodes([]);
        axios.delete(`/session/drawings/${shapeId}`);
        return;
    }

    // 2. Logika EDYCJI (Transformer)
    if (activeTool.value === 'select-draw') {
        selectedShapeId.value = shapeId;
        const selectedNode = e.target; // Element, w który kliknęliśmy

        // Podpinamy transformer pod węzeł
        transformerNode.value.getNode().nodes([selectedNode]);
        transformerNode.value.getNode().getLayer().batchDraw();
    }
};

// Po zakończeniu skalowania/przesuwania
const handleTransformEnd = async (e: any, draw: any) => {
    const node = e.target;
    draw.x = node.x();
    draw.y = node.y();
    draw.scaleX = node.scaleX();
    draw.scaleY = node.scaleY();
    draw.rotation = node.rotation();

    const updatedData = {
        id: draw.id,
        data: draw,
    };

    await axios.patch(`/session/drawings/${node.id()}`, updatedData);
};

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

const handleSelectionStart = (e: any) => {
    // Jeśli klikniemy w tło (pusty obszar)
    if (e.target === e.target.getStage()) {
        // Resetujemy zaznaczenie rysunku
        selectedShapeId.value = null;
        if (transformerNode.value) {
            transformerNode.value.getNode().nodes([]);
        }

        // Twoja stara logika zaznaczania prostokątem (tylko dla narzędzia select)
        if (activeTool.value === 'select') {
            const pos = e.target.getStage().getPointerPosition();
            selectionBox.value = { x: pos.x, y: pos.y, width: 0, height: 0, visible: true };
            selectedIds.value = [];
        }
    }
};
const handleSelectionMove = (e: any) => {
    if (!selectionBox.value.visible) return;

    const pos = e.target.getStage().getPointerPosition();
    selectionBox.value.width = pos.x - selectionBox.value.x;
    selectionBox.value.height = pos.y - selectionBox.value.y;
};

const handleSelectionEnd = () => {
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

const handleStageMouseDown = (e: any) => {
    if (activeTool.value === 'select-draw' || activeTool.value === 'eraser') {
        return;
    }
    if (activeTool.value === 'select') {
        handleSelectionStart(e); // Przenieś tam starą logikę handleStageMouseDown
        return;
    }

    const pos = e.target.getStage().getPointerPosition();

    if (activeTool.value === 'pen') {
        isDrawing.value = true;
        drawings.value.push({
            id: Date.now(), // Tymczasowe ID
            type: 'pen',
            points: [pos.x, pos.y],
            stroke: '#ff0000',
            strokeWidth: 3,
            tension: 0.5, // Wygładzanie linii
            lineCap: 'round',
            lineJoin: 'round'
        });
    } else if (activeTool.value === 'rect') {
        isDrawing.value = true;
        drawings.value.push({
            id: Date.now(),
            type: 'rect',
            x: pos.x,
            y: pos.y,
            width: 0,
            height: 0,
            stroke: '#ff0000',
            strokeWidth: 2
        });
    } else if (activeTool.value === 'ping') {
        const newPing = {
            id: Date.now(),
            x: pos.x,
            y: pos.y,
            color: pingColor.value
        };

        createPing(newPing);
        axios.post('/session/ping', newPing);
        return;
    }
};

const createPing = (pingData: any) => {
    pings.value.push(pingData);

    setTimeout(() => {
        pings.value = pings.value.filter(p => p.id !== pingData.id);
    }, 3000);
};
const handleStageMouseMove = (e: any) => {
    if (!isDrawing.value || activeTool.value === 'select') {
        handleSelectionMove(e); // Stara logika selection box
        return;
    }

    const pos = e.target.getStage().getPointerPosition();
    const lastShape = drawings.value[drawings.value.length - 1];

    if (activeTool.value === 'pen') {
        lastShape.points = lastShape.points.concat([pos.x, pos.y]);
    } else if (activeTool.value === 'rect') {
        lastShape.width = pos.x - lastShape.x;
        lastShape.height = pos.y - lastShape.y;
    }
};

const handleStageMouseUp = async () => {
    if (isDrawing.value) {
        isDrawing.value = false;
        const lastShape = drawings.value[drawings.value.length - 1];
        const { data } = await axios.post('/session/drawings/store', {
            id: lastShape.id,
            type: lastShape.type,
            data: lastShape
        });
    }
    handleSelectionEnd();
};

onMounted(() => {
    fetchDrawings();
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
.toolbar {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 10001;
    display: flex;
    gap: 10px;
    background: rgba(0,0,0,0.7);
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #d4af37;
}
button { background: #333; color: white; border: 1px solid #555; padding: 5px 10px; cursor: pointer; }
button.active { background: #d4af37; color: black; }
</style>
