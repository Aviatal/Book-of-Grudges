<template>
    <div class="game-container">
        <div v-if="hasDrawingPermission" class="toolbar shadow-lg">
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

        <div class="chat-container shadow-lg" :class="{ 'chat-minimized': isChatMinimized }">
            <div class="chat-header" @click="isChatMinimized = !isChatMinimized">
                <span>📜 Komunikaty sesji</span>
                <button class="minimize-btn">{{ isChatMinimized ? '▲' : '▼' }}</button>
            </div>

            <div v-if="!isChatMinimized" class="chat-messages" ref="messageContainer">
                <template v-for="msg in messages" :key="msg.id">
                    <div v-if="msg.type === 'roll'" class="message-roll-card">
                        <div class="roll-card-header">
                            <span class="roll-card-icon">🎲</span>
                            <span class="roll-card-type">INICJATYWA</span>
                            <span class="roll-card-time">{{ formatDate(msg.created_at) }}</span>
                        </div>
                        <div class="roll-card-author">{{ msg.author_name }}</div>
                        <div class="roll-card-breakdown">
                            <div class="roll-die">
                                <span class="roll-die-value">{{ parseRoll(msg.text).zr }}</span>
                                <span class="roll-die-label">Zręczność</span>
                            </div>
                            <span class="roll-op">+</span>
                            <div class="roll-die roll-die-d10">
                                <span class="roll-die-value">{{ parseRoll(msg.text).dice }}</span>
                                <span class="roll-die-label">k10</span>
                            </div>
                            <span class="roll-op">=</span>
                            <div class="roll-die roll-die-total">
                                <span class="roll-die-value">{{ parseRoll(msg.text).total }}</span>
                                <span class="roll-die-label">Wynik</span>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="msg.type === 'skill_test'" class="message-skill-card" :class="parseSkillTest(msg.text)?.passed ? 'skill-passed' : 'skill-failed'">
                        <div class="skill-card-header">
                            <span class="skill-card-icon">🎯</span>
                            <span class="skill-card-type">TEST UMIEJĘTNOŚCI</span>
                            <span class="skill-card-time">{{ formatDate(msg.created_at) }}</span>
                        </div>
                        <div class="skill-card-author">{{ msg.author_name }}</div>
                        <div class="skill-card-name">
                            {{ parseSkillTest(msg.text)?.skill }}
                            <span class="skill-card-char">({{ parseSkillTest(msg.text)?.characteristic }})</span>
                        </div>
                        <div class="skill-card-effective" v-if="parseSkillTest(msg.text)?.half || parseSkillTest(msg.text)?.modifier !== 0">
                            <span class="eff-base">{{ parseSkillTest(msg.text)?.characteristic_value }}</span>
                            <span class="eff-op" v-if="parseSkillTest(msg.text)?.half">÷2</span>
                            <span class="eff-op" v-if="parseSkillTest(msg.text)?.modifier !== 0">
                                {{ parseSkillTest(msg.text)?.modifier > 0 ? '+' + parseSkillTest(msg.text)?.modifier : parseSkillTest(msg.text)?.modifier }}
                            </span>
                            <span class="eff-sep">=</span>
                            <span class="eff-result">{{ parseSkillTest(msg.text)?.effective_value }}</span>
                        </div>
                        <div class="skill-card-breakdown">
                            <div class="skill-stat">
                                <span class="skill-stat-value">{{ parseSkillTest(msg.text)?.effective_value ?? parseSkillTest(msg.text)?.characteristic_value }}</span>
                                <span class="skill-stat-label">Próg</span>
                            </div>
                            <span class="skill-vs">vs</span>
                            <div class="skill-stat">
                                <span class="skill-stat-value">{{ parseSkillTest(msg.text)?.roll }}</span>
                                <span class="skill-stat-label">k100</span>
                            </div>
                            <div class="skill-verdict" :class="parseSkillTest(msg.text)?.passed ? 'skill-verdict-pass' : 'skill-verdict-fail'">
                                {{ parseSkillTest(msg.text)?.passed ? '✓ ZDANY' : '✗ NIEZDANY' }}
                            </div>
                        </div>
                    </div>
                    <div v-else class="message">
                        <span class="msg-author">[{{ msg.author_name }}]</span>
                        <span class="msg-content">{{ msg.text }}</span>
                        <span class="msg-time">{{ formatDate(msg.created_at) }}</span>
                    </div>
                </template>
            </div>

            <div v-if="isRolling && !isChatMinimized" class="dice-overlay">
                <div class="dice-overlay-die">🎲</div>
                <div class="dice-overlay-label">Rzut na inicjatywę...</div>
            </div>

            <div v-if="!isChatMinimized" class="chat-input-area">
                <input
                    v-model="newMessage"
                    @keyup.enter="sendMessage"
                    placeholder="Napisz wiadomość..."
                    type="text"
                />
                <button @click="sendMessage">➤</button>
            </div>
            <div v-if="!isChatMinimized" class="chat-actions">
                <button class="roll-btn" @click="rollInitiative" :disabled="isRolling">
                    🎲 Inicjatywa
                </button>
                <button class="roll-btn" @click="toggleSkillPicker" :disabled="isRollingSkill" :class="{ active: showSkillPicker }">
                    🎯 Test umiejętności
                </button>
            </div>

            <div v-if="showSkillPicker && !isChatMinimized" class="skill-picker">
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
                            draggable: activeTool === 'select-draw' && hasDrawingPermission
                        }"
                        @click="(e) => handleShapeClick(e, draw.id)"
                        @dragend="(e) => handleTransformEnd(e, draw)"
                    />
                    <v-rect
                        v-if="draw.type === 'rect'"
                        :config="{
                            ...draw,
                            draggable: activeTool === 'select-draw' && hasDrawingPermission
                        }"
                        @click="(e) => handleShapeClick(e, draw.id)"
                        @transformend="(e) => handleTransformEnd(e, draw)"
                        @dragend="(e) => handleTransformEnd(e, draw)"
                    />
                </template>

                <v-transformer
                    ref="transformerNode"
                    :config="{
                        visible: activeTool === 'select-draw' && selectedShapeId !== null && hasDrawingPermission,
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
                        draggable: hasDrawingPermission
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
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import {Token} from "@/types/Token";
import {DrawingData} from "@/types/DrawingData";
import {Message} from "@/types/Message";
import PingItem from '../../components/session/PingItem.vue';

const props = defineProps<{
    heroId: number,
    hasDrawingPermission: boolean
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
// todo:: Webhooks
window.Echo.channel('session-chat')
    .listen('.message-sent', (e: any) => {
        messages.value.push(e.message);
        scrollToBottom();
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
interface SkillTestResult {
    skill: string;
    characteristic: string;
    characteristic_value: number;
    effective_value: number;
    modifier: number;
    half: boolean;
    roll: number;
    passed: boolean;
}

interface SkillOption {
    id: number;
    name: string;
    type: string;
    characteristic: string;
    characteristic_value: number;
    is_purchased: boolean;
    additional_name: string | null;
}

const messages = ref<Message[]>([]);
const newMessage = ref('');
const isChatMinimized = ref(false);
const isRolling = ref(false);
const isRollingSkill = ref(false);
const showSkillPicker = ref(false);
const skillSearch = ref('');
const skillModifier = ref(0);
const skillHalf = ref(false);
const skills = ref<SkillOption[]>([]);
const isLoadingSkills = ref(false);
const MODIFIERS = [-40, -30, -20, -10, 0, 10, 20, 30, 40];
const messageContainer = ref<HTMLElement | null>(null);

const filteredSkills = computed(() => {
    const q = skillSearch.value.trim().toLowerCase();
    return skills.value.filter(s =>
        !q || s.name.toLowerCase().includes(q) || (s.additional_name ?? '').toLowerCase().includes(q)
    );
});

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

const fetchTokens = async () => {
    const response = await axios.get('/session/tokens');
    tokens.value = response.data;

    tokens.value.forEach(token => {
        loadImage(token);
    });
};

const fetchMessages = async () => {
    const { data } = await axios.get('/session/chat');
    messages.value = data;
    scrollToBottom();
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

const sendMessage = async () => {
    if (newMessage.value.trim() === '') return;

    try {
        const { data } = await axios.post('/session/chat/send', {
            text: newMessage.value
        });

        newMessage.value = '';
        scrollToBottom();
    } catch (error) {
        console.error("Błąd wysyłania wiadomości");
    }
};

const playDiceSound = () => {
    const AudioCtx = window.AudioContext || (window as any).webkitAudioContext;
    if (!AudioCtx) return;

    const ctx = new AudioCtx();
    const sampleRate = ctx.sampleRate;

    // Kilka "klaknięć" kostką o stół z malejącą głośnością
    const clacks = [0, 0.09, 0.17, 0.27, 0.36];
    const totalDuration = 0.55;
    const bufferSize = Math.floor(sampleRate * totalDuration);
    const buffer = ctx.createBuffer(1, bufferSize, sampleRate);
    const data = buffer.getChannelData(0);

    clacks.forEach((clackTime, idx) => {
        const start = Math.floor(clackTime * sampleRate);
        const clackLen = Math.floor(0.045 * sampleRate);
        const volume = 1 - idx * 0.15;
        for (let i = 0; i < clackLen && start + i < bufferSize; i++) {
            const env = Math.exp(-i / (clackLen * 0.25));
            data[start + i] += (Math.random() * 2 - 1) * env * volume;
        }
    });

    const source = ctx.createBufferSource();
    source.buffer = buffer;

    const filter = ctx.createBiquadFilter();
    filter.type = 'bandpass';
    filter.frequency.value = 1800;
    filter.Q.value = 0.8;

    const gain = ctx.createGain();
    gain.gain.value = 0.65;

    source.connect(filter);
    filter.connect(gain);
    gain.connect(ctx.destination);
    source.start();
    source.onended = () => ctx.close();
};

const parseSkillTest = (text: string): SkillTestResult | null => {
    try {
        return JSON.parse(text) as SkillTestResult;
    } catch (e) {
        console.error('Failed to parse skill test message', e);
        return null;
    }
};

const toggleSkillPicker = async () => {
    showSkillPicker.value = !showSkillPicker.value;
    if (showSkillPicker.value && skills.value.length === 0) {
        isLoadingSkills.value = true;
        try {
            const { data } = await axios.get<SkillOption[]>('/session/chat/skills');
            skills.value = data;
        } catch (e) {
            console.error('Błąd pobierania umiejętności', e);
        } finally {
            isLoadingSkills.value = false;
        }
    }
};

const rollSkill = async (skillId: number) => {
    if (isRollingSkill.value) return;
    isRollingSkill.value = true;
    showSkillPicker.value = false;
    playDiceSound();
    try {
        await axios.post('/session/chat/roll-skill', {
            skill_id: skillId,
            modifier: skillModifier.value,
            half: skillHalf.value,
        });
        scrollToBottom();
    } catch (e) {
        console.error('Błąd testu umiejętności', e);
    } finally {
        skillModifier.value = 0;
        skillHalf.value = false;
        isRollingSkill.value = false;
    }
};

const parseRoll = (text: string) => {
    const match = text.match(/Zr \((\d+)\) \+ k10 \[(\d+)\] = (\d+)/);
    return match
        ? { zr: match[1], dice: match[2], total: match[3] }
        : { zr: '?', dice: '?', total: '?' };
};

const rollInitiative = async () => {
    if (isRolling.value) return;
    isRolling.value = true;
    playDiceSound();
    try {
        const { data } = await axios.post('/session/chat/roll-initiative');
        scrollToBottom();
    } catch (error) {
        console.error('Błąd rzutu na inicjatywę');
    } finally {
        isRolling.value = false;
    }
};

const scrollToBottom = () => {
    setTimeout(() => {
        if (messageContainer.value) {
            messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
        }
    }, 50);
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

const formatDate = (isoString: string) => {
    const date = new Date(isoString);

    return new Intl.DateTimeFormat('pl-PL', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    }).format(date);
};

onMounted(() => {
    fetchDrawings();
    fetchTokens();
    fetchMessages();
    window.addEventListener('resize', updateSize);
});
onUnmounted(() => {
    window.removeEventListener('resize', updateSize);
    window.Echo.leaveChannel('token-move');
    window.Echo.leaveChannel('session-chat');
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

.chat-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 350px;
    height: 900px;
    background: rgba(26, 26, 26, 0.9);
    border: 1px solid #d4af37;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    z-index: 10002;
    font-family: 'Crimson Text', serif;
    overflow: hidden;
}

.chat-minimized { height: 40px; }

.chat-header {
    padding: 8px 15px;
    background: #2a2a2a;
    border-bottom: 1px solid #d4af37;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    color: #d4af37;
    font-weight: bold;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.message { font-size: 0.95rem; line-height: 1.2; border-bottom: 1px solid #333; padding-bottom: 2px; }
.msg-author { font-weight: bold; margin-right: 5px; }
.msg-content { color: #ccc; word-break: break-word; display: block; }
.msg-time { font-size: 0.7rem; color: #666; float: right; }

/* Roll card */
.message-roll-card {
    border: 1px solid #d4af37;
    border-radius: 6px;
    background: linear-gradient(135deg, #1a1500 0%, #0f0f0f 100%);
    padding: 8px 10px;
    margin: 4px 0;
    box-shadow: 0 0 12px rgba(212, 175, 55, 0.15), inset 0 0 20px rgba(0,0,0,0.4);
}

.roll-card-header {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
}

.roll-card-icon { font-size: 1rem; }

.roll-card-type {
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 2px;
    color: #d4af37;
    text-transform: uppercase;
    flex: 1;
}

.roll-card-time {
    font-size: 0.65rem;
    color: #555;
}

.roll-card-author {
    font-size: 0.8rem;
    color: #aaa;
    margin-bottom: 8px;
    font-style: italic;
}

.roll-card-breakdown {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.roll-op {
    color: #888;
    font-size: 1.1rem;
    font-weight: bold;
}

.roll-die {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #1e1e1e;
    border: 1px solid #444;
    border-radius: 5px;
    padding: 4px 10px;
    min-width: 48px;
}

.roll-die-d10 {
    border-color: #d4af37;
    background: #1a1500;
}

.roll-die-total {
    border: 2px solid #d4af37;
    background: #d4af37;
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
    min-width: 54px;
}

.roll-die-value {
    font-size: 1.3rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}

.roll-die-total .roll-die-value {
    color: #1a1a1a;
    font-size: 1.5rem;
}

.roll-die-label {
    font-size: 0.55rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 2px;
}

.roll-die-total .roll-die-label { color: #5a4a00; }

/* Dice overlay */
.dice-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.82);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    border-radius: inherit;
    z-index: 20;
}

.dice-overlay-die {
    font-size: 3rem;
    animation: diceSpin 0.6s ease-in-out infinite alternate;
    filter: drop-shadow(0 0 12px rgba(212, 175, 55, 0.8));
}

.dice-overlay-label {
    color: #d4af37;
    font-size: 0.85rem;
    letter-spacing: 1px;
    text-transform: uppercase;
}

@keyframes diceSpin {
    from { transform: rotate(-20deg) scale(0.9); }
    to   { transform: rotate(20deg)  scale(1.1); }
}

.chat-input-area {
    padding: 10px;
    display: flex;
    gap: 5px;
    background: #111;
}

.chat-input-area input {
    flex: 1;
    background: #222;
    border: 1px solid #444;
    color: white;
    padding: 5px;
    border-radius: 4px;
}

.chat-input-area button {
    background: #d4af37;
    border: none;
    color: black;
    padding: 0 10px;
    cursor: pointer;
    border-radius: 4px;
}

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
    max-height: 260px;
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
    padding: 5px 8px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.82rem;
    margin-bottom: 2px;
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

/* Skill test card */
.message-skill-card {
    border-radius: 6px;
    padding: 8px 10px;
    margin: 4px 0;
    border: 1px solid #333;
    background: #0f0f0f;
}

.skill-passed { border-color: #2e7d32; background: linear-gradient(135deg, #071209 0%, #0f0f0f 100%); }
.skill-failed  { border-color: #7f1d1d; background: linear-gradient(135deg, #120707 0%, #0f0f0f 100%); }

.skill-card-header {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 3px;
}

.skill-card-icon { font-size: 0.9rem; }

.skill-card-type {
    font-size: 0.6rem;
    font-weight: 800;
    letter-spacing: 2px;
    color: #888;
    text-transform: uppercase;
    flex: 1;
}

.skill-card-time { font-size: 0.65rem; color: #555; }

.skill-card-author { font-size: 0.78rem; color: #888; font-style: italic; margin-bottom: 5px; }

.skill-card-name {
    font-size: 0.9rem;
    font-weight: 700;
    color: #ddd;
    margin-bottom: 7px;
}

.skill-card-char { font-size: 0.75rem; color: #666; font-weight: normal; }

.skill-card-effective {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.78rem;
    margin-bottom: 6px;
    color: #888;
}

.eff-base { color: #aaa; font-weight: 700; }
.eff-op { color: #9d91f0; font-weight: 700; }
.eff-sep { color: #555; }
.eff-result { color: #d4af37; font-weight: 800; font-size: 0.88rem; }

.skill-card-breakdown {
    display: flex;
    align-items: center;
    gap: 8px;
}

.skill-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 5px;
    padding: 4px 10px;
    min-width: 44px;
}

.skill-stat-value {
    font-size: 1.2rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}

.skill-stat-label {
    font-size: 0.55rem;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 1px;
}

.skill-vs { color: #555; font-size: 0.8rem; font-weight: bold; }

.skill-verdict {
    flex: 1;
    text-align: right;
    font-size: 0.9rem;
    font-weight: 800;
    letter-spacing: 1px;
}

.skill-verdict-pass { color: #4caf50; text-shadow: 0 0 8px rgba(76, 175, 80, 0.4); }
.skill-verdict-fail { color: #f44336; text-shadow: 0 0 8px rgba(244, 67, 54, 0.4); }
</style>
