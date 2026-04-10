<template>
    <div class="game-container">
        <v-stage :config="stageConfig" @mousedown="handleStageMouseDown">
            <v-layer>
                <v-rect v-for="n in 20" :key="'h'+n" :config="{ x: 0, y: n*50, width: 2000, height: 1, stroke: '#ccc', strokeWidth: 0.5 }" />

                <v-group
                    v-for="token in tokens"
                    :key="token.id"
                    :config="{ x: token.x, y: token.y, draggable: true }"
                    @dragend="(e) => updateTokenPosition(e, token)"
                >
                    <v-circle :config="{ radius: 25, fill: token.color, stroke: 'black', strokeWidth: 2 }" />
                    <v-text :config="{ text: token.name, fontSize: 12, x: -20, y: 30, fill: 'white' }" />
                </v-group>
            </v-layer>
        </v-stage>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

interface MoveTokenEvent {
    tokenId: number;
    x: number;
    y: number;
}

window.Echo.channel('token-move')
    .listen('.move', (e: MoveTokenEvent) => {
        console.log('Event odebrany:', e);
        const token = tokens.value.find(t => t.id === e.tokenId);
        if (token) {
            token.x = e.x;
            token.y = e.y;
        }
    });

const tokens = ref([]);
const stageConfig = ref({
    width: window.innerWidth,
    height: window.innerHeight,
});

const updateSize = () => {
    stageConfig.value.width = window.innerWidth;
    stageConfig.value.height = window.innerHeight;
};

// Pobierz tokeny z bazy przy starcie
const fetchTokens = async () => {
    const response = await axios.get('/session/tokens');
    tokens.value = response.data;
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
    border: 1px solid #d4af37; /* Złoty akcent pasujący do Twojej strony */
    cursor: pointer;
}
</style>
