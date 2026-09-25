<template>
    <button class="btn-blood-fate w-full" :class="{ compact }" @click="spendFatePoint">Wydaj punkt przeznaczenia</button>
</template>
<script setup lang="ts">
import {defineProps} from "vue";
import {useToast} from "vue-toast-notification";
import axios from "axios";
import {getApiErrorMessage} from "../../utils/apiError";

const props = defineProps<{
    heroId: number,
    compact?: boolean
}>()
const toast = useToast();

const spendFatePoint = () => {
    customSwal.fire({
        title: "Czy na pewno chcesz wydać punkt przeznaczenia?",
        text: "Tej operacji nie można cofnąć.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Tak, wydaj",
        cancelButtonText: "Anuluj",
        focusCancel: true
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }
        axios
            .patch('karta-postaci/' + props.heroId + '/spend-fate-point')
            .then(() => {
                customSwal.fire({
                    title: "Udało Ci się oszukać przeznaczenie... Tym razem",
                    text: "Wydano punkt przeznaczenia.",
                });
            })
            .catch(error => {
                toast.error(getApiErrorMessage(error, 'Nie udało się wydać punktu przeznaczenia'))
            })
    });
};
</script>
<style scoped>
.btn-blood-fate {
    font-family: 'Cinzel', 'Times New Roman', serif;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #c91a1a;

    background-color: rgba(20, 10, 10, 0.85); /* Ciemniejsze, nieco czerwonawe tło */
    border: 1px solid #8b0000;
    border-radius: 4px;
    padding: 8px 16px;

    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.6);
    position: relative;
    overflow: hidden;
    white-space: nowrap;
}

.btn-blood-fate.compact {
    font-size: 0.7rem;
    letter-spacing: 0.5px;
    padding: 4px 8px;
}

.btn-blood-fate:hover {
    background-color: #8b0000;
    color: #e3dacc; /* Kolor starego pergaminu / kości */
    border-color: #a50000;
    box-shadow: 0 0 15px rgba(139, 0, 0, 0.6);
}

.btn-blood-fate:active {
    transform: scale(0.98);
    box-shadow: inset 0 3px 5px rgba(0,0,0,0.7);
}

.btn-blood-fate:disabled {
    border-color: #4a1111;
    color: #663333;
    background-color: #1a1a1a;
    cursor: not-allowed;
    box-shadow: none;
}
</style>
