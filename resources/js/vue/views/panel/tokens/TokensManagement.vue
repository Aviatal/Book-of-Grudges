<template>
    <div class="tokens-table-container">
        <div class="flex justify-between items-center mb-6 border-b-2 border-[#8b5a2b] pb-4">
            <h2 class="text-3xl font-bold text-[#d4af37] tracking-widest uppercase font-serif">
                <i class="mdi mdi-format-list-bulleted-type mr-2"></i> Rejestr Artefaktów i Tokenów
            </h2>
            <button class="warhammer-btn">
                <i class="mdi mdi-plus"></i> Nowy Wpis
            </button>
        </div>

        <div class="overflow-x-auto border-2 border-[#8b5a2b] shadow-2xl">
            <table class="w-full text-left border-collapse">
                <thead>
                <tr class="bg-[#2b2a27] border-b-2 border-[#8b5a2b]">
                    <th class="warhammer-th">ID</th>
                    <th class="warhammer-th">Nazwa</th>
                    <th class="warhammer-th text-center">Kolor</th>
                    <th class="warhammer-th">Pozycja</th>
                    <th class="warhammer-th">Postać</th>
                    <th class="warhammer-th text-right">Akcje</th>
                </tr>
                </thead>
                <tbody class="bg-[#3b3a36]">
                <tr
                    v-for="token in tokens"
                    :key="token.id"
                    class="grudge-row border-b border-[#5e4128] hover:bg-[#4b4a46] transition-colors"
                >
                    <td class="warhammer-td font-mono opacity-60">#{{ token.id }}</td>
                    <td class="warhammer-td font-bold text-[#d4af37]">{{ token.name }}</td>
                    <td class="warhammer-td text-center">
                        <div
                            class="w-6 h-6 mx-auto rounded-sm border border-black shadow-inner"
                            :style="{ backgroundColor: token.color }"
                            :title="token.color"
                        ></div>
                    </td>
                    <td class="warhammer-td font-mono">
                        <span class="text-[#8b5a2b]">X:</span> {{ token.x }}
                        <span class="text-[#8b5a2b] ml-2">Y:</span> {{ token.y }}
                    </td>
                    <td class="warhammer-td italic text-[#c4a47c]">
                        {{ token.hero.name }} <template v-if="token.hero.user">({{ token.hero.user.name}})</template>
                    </td>
                    <td class="warhammer-td text-right">
                        <div class="flex justify-end gap-3">
                            <button class="icon-btn hover:text-[#d4af37]" title="Edytuj">
                                <i class="mdi mdi-feather"></i>
                            </button>
                            <button class="icon-btn hover:text-red-600" title="Usuń">
                                <i class="mdi mdi-close-thick"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="tokens.length === 0">
                    <td colspan="7" class="py-10 text-center text-[#8b5a2b] uppercase tracking-widest italic">
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

const tokens = ref<Token[]>([]);

const fetchTokens = async () => {
    try {
        const response = await axios.get('/panel/tokens/get-tokens');
        tokens.value = response.data;
    } catch (error) {
        console.error("Błąd podczas pobierania:", error);
    }
}

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleString('pl-PL', {
        day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit'
    });
}

onMounted(() => {
    fetchTokens();
})
</script>

<style scoped>
.warhammer-th {
    padding: 1rem;
    color: #d4af37;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.1em;
    font-weight: 700;
}

.warhammer-td {
    padding: 0.75rem 1rem;
    color: #e4d8b4;
    vertical-align: middle;
}

.grudge-row:nth-child(even) {
    background-color: rgba(0, 0, 0, 0.1);
}

.warhammer-btn {
    background-color: #8b5a2b;
    color: #e4d8b4;
    padding: 0.5rem 1rem;
    border: 1px solid #5e4128;
    text-transform: uppercase;
    font-size: 0.75rem;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 2px 2px 0px rgba(0,0,0,1);
}

.warhammer-btn:hover {
    background-color: #d4af37;
    color: #1b1b18;
}

.icon-btn {
    background: none;
    border: none;
    font-size: 1.2rem;
    color: #8b5a2b;
    cursor: pointer;
    transition: all 0.2s;
}

/* Custom scrollbar dla tabeli */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}
.overflow-x-auto::-webkit-scrollbar-track {
    background: #2b2a27;
}
.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #8b5a2b;
    border-radius: 4px;
}
</style>
