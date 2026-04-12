<template>
    <div class="tokens-table-container">
        <div class="flex justify-between items-center mb-6 border-b-2 border-[#8b5a2b] pb-4">
            <h2 class="text-3xl font-bold text-[#d4af37] tracking-widest uppercase font-serif">
                <i class="mdi mdi-format-list-bulleted-type mr-2"></i> Rejestr Artefaktów i Tokenów
            </h2>
            <button class="warhammer-btn">
                <a href="/panel/tokens/create" title="Dodaj token">
                    <i class="mdi mdi-plus"></i> Nowy token
                </a>
            </button>
        </div>

        <div class="overflow-x-auto border-2 border-[#8b5a2b] shadow-2xl bg-[#2b2a27]">
            <table class="w-full text-left border-collapse">
                <thead>
                <tr class="bg-[#1e1d1b] border-b-2 border-[#8b5a2b]">
                    <th class="warhammer-th w-16">ID</th>
                    <th class="warhammer-th">Nazwa</th>
                    <th class="warhammer-th text-center">Widok</th>
                    <th class="warhammer-th">Pozycja</th>
                    <th class="warhammer-th">Postać</th>
                    <th class="warhammer-th text-right">Akcje</th>
                </tr>
                </thead>
                <tbody class="bg-[#3b3a36]">
                <tr
                    v-for="token in tokens"
                    :key="token.id"
                    class="grudge-row border-b border-[#5e4128] hover:bg-[#4b4a46] transition-all duration-300"
                >
                    <td class="warhammer-td font-mono opacity-60">#{{ token.id }}</td>
                    <td class="warhammer-td font-bold text-[#d4af37] text-lg">{{ token.name }}</td>

                    <td class="warhammer-td text-center">
                        <div class="token-wrapper">
                            <div
                                class="token-preview-circ"
                                :style="{
                                        backgroundImage: `url(${token.image_url})`,
                                    }"
                            >
                                <i v-if="!token.image_url" class="mdi mdi-help text-3xl text-[#5e4128]"></i>
                            </div>
                        </div>
                    </td>

                    <td class="warhammer-td font-mono">
                        <span class="text-[#8b5a2b] font-bold">X:</span> {{ token.x }}
                        <span class="text-[#8b5a2b] font-bold ml-3">Y:</span> {{ token.y }}
                    </td>
                    <td class="warhammer-td italic text-[#c4a47c]">
                        <div class="flex flex-col">
                            <span>{{ token.hero.name }}</span>
                            <span v-if="token.hero.user" class="text-xs opacity-70">({{ token.hero.user.name }})</span>
                        </div>
                    </td>
                    <td class="warhammer-td text-right">
                        <div class="flex justify-end gap-4">
                            <a :href="'/panel/tokens/'+ token.id +'/edit'" class="icon-btn edit-btn" title="Edytuj">
                                <i class="mdi mdi-feather"></i>
                            </a>
                            <button @click="deleteToken(token.id)" class="icon-btn delete-btn hover:text-red-600" title="Usuń">
                                <i class="mdi mdi-close-thick"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr v-if="tokens.length === 0">
                    <td colspan="6" class="py-16 text-center text-[#8b5a2b] uppercase tracking-widest italic opacity-50">
                        <i class="mdi mdi-book-open-variant text-4xl block mb-2"></i>
                        Księgi milczą o takich tokenach...
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";
import axios from "axios";
import { Token } from "../../../../types/Token";
import {useToast} from "vue-toast-notification";

const tokens = ref<Token[]>([]);
const toast = useToast();

const fetchTokens = async () => {
    try {
        const response = await axios.get('/panel/tokens/get-tokens');
        tokens.value = response.data;
    } catch (error) {
        console.error("Błąd podczas pobierania:", error);
    }
}

const deleteToken = async (tokenId: number) => {
    await axios.delete(`/panel/tokens/${tokenId}/delete`)
        .then(() => {
            toast.success('Udało się usunąć token!');
            fetchTokens();
        })
        .catch((error) => {
            toast.error(error.response.data.error)
        })
}

onMounted(() => {
    fetchTokens();
})
</script>

<style scoped>
.warhammer-th {
    padding: 1.25rem 1rem;
    color: #d4af37;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.15em;
    font-weight: 800;
    background: rgba(0,0,0,0.2);
}

.warhammer-td {
    padding: 1rem;
    color: #e4d8b4;
    vertical-align: middle;
}

.grudge-row:nth-child(even) {
    background-color: rgba(0, 0, 0, 0.15);
}

/* Kontener ułatwiający centrowanie */
.token-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Stylizacja samego tokena */
.token-preview-circ {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-size: cover;
    background-position: center;
    border: 3px solid #8b5a2b; /* Brązowa rama */
    outline: 1px solid #d4af37; /* Cienka złota obwódka */
    box-shadow:
        0 4px 8px rgba(0,0,0,0.8),
        inset 0 0 15px rgba(0,0,0,0.6);
    transition: transform 0.2s ease-in-out, border-color 0.2s;
    cursor: zoom-in;
}

.token-preview-circ:hover {
    transform: scale(1.1);
    border-color: #d4af37;
    box-shadow: 0 0 15px rgba(212, 175, 55, 0.4);
}

.warhammer-btn {
    background: linear-gradient(145deg, #8b5a2b, #5e4128);
    color: #e4d8b4;
    padding: 0.6rem 1.2rem;
    border: 1px solid #d4af37;
    text-transform: uppercase;
    font-size: 0.8rem;
    font-weight: bold;
    letter-spacing: 1px;
    cursor: pointer;
    box-shadow: 3px 3px 0px rgba(0,0,0,0.5);
    transition: all 0.2s;
}

.warhammer-btn:hover {
    transform: translate(-1px, -1px);
    box-shadow: 4px 4px 0px rgba(0,0,0,0.8);
    filter: brightness(1.2);
}

.icon-btn {
    background: none;
    border: none;
    font-size: 1.4rem;
    color: #8b5a2b;
    cursor: pointer;
    transition: transform 0.2s, color 0.2s;
}

.edit-btn:hover { color: #d4af37; transform: rotate(-10deg); }
.delete-btn:hover { color: #ff4444; transform: scale(1.2); }

.overflow-x-auto::-webkit-scrollbar { height: 8px; }
.overflow-x-auto::-webkit-scrollbar-track { background: #1e1d1b; }
.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #8b5a2b;
    border: 2px solid #1e1d1b;
}
</style>
