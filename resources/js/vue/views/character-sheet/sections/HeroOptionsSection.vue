<template>
    <div>
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
</template>

<script setup lang="ts">
import {defineProps} from "vue";
import axios from "axios";
import {useToast} from "vue-toast-notification";

const props = defineProps<{
    heroId: number
}>();

const toast = useToast();

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
    background: var(--danger-bg-alt);
    color: var(--danger-text);
    border: 1px solid var(--danger-border);
    padding: 0.8rem 1.5rem;
    border-radius: 2px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: border-color 0.2s ease, color 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: var(--font-heading), serif;
    letter-spacing: .06em;
}

.delete-hero-btn:hover {
    border-color: var(--danger-border-hover);
    color: var(--danger-text-hover);
}

.btn-icon {
    font-size: 1.2rem;
}
</style>
