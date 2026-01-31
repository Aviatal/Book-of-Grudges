<template>
    <div class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300 mt-4">
        <div
            @click="toggleOpen"
            class="cursor-pointer flex justify-between items-center px-3 py-2 rounded-md hover:bg-[#8b5a2b] hover:text-[#42413b] transition-colors duration-300"
        >
            <h2 class="font-semibold text-xl text-[#e4d8b4]">Opcje</h2>
            <svg
                :class="isOpen ? 'rotate-180' : ''"
                xmlns="http://www.w3.org/2000/svg"
                width="24" height="24"
                viewBox="0 0 24 24"
                fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-down text-[#e4d8b4] transition-transform duration-300"
            >
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>

        <div v-show="isOpen" class="mt-4">
            <v-row>
                <v-col cols="12">
                    <div class="flex justify-center">
                        <button
                            class="delete-hero-btn"
                            @click="deleteHero"
                        >
                            <span class="btn-icon">💀</span>
                            Usuń postać
                        </button>
                    </div>
                </v-col>
            </v-row>
        </div>
    </div>
</template>

<script setup lang="ts">
import {defineProps, ref} from "vue";
import axios from "axios";
import {useToast} from "vue-toast-notification";

const props = defineProps<{
    heroId: number
}>();

const isOpen = ref<boolean>(false);
const toast = useToast();

const toggleOpen = (): void => {
    isOpen.value = !isOpen.value;
};

const deleteHero = async (): Promise<void> => {
    if (!confirm('Czy na pewno chcesz usunąć tę postać? Operacji nie można cofnąć.')) {
        return;
    }

    axios.delete('karta-postaci/' + props.heroId + '/delete-hero')
        .then(response => {
            toast.success(response.data.message);
            window.location.href = '/';
        })
        .catch(error => {
            toast.error('Wystąpił błąd podczas usuwania bohatera: ' + error.response.data.message);
        });
}
</script>

<style scoped>
.delete-hero-btn {
    background: linear-gradient(145deg, #8b0000 0%, #b22222 100%);
    color: #e4d8b4;
    border: 1px solid #5a0000;
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: 'Cinzel', serif;
    box-shadow: 0 4px 10px rgba(139, 0, 0, 0.3);
}

.delete-hero-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(139, 0, 0, 0.5);
    background: linear-gradient(145deg, #a50000 0%, #cd5c5c 100%);
}

.btn-icon {
    font-size: 1.2rem;
}
</style>
