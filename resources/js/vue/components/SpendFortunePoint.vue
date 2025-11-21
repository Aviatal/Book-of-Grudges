<template>
    <button class="btn-grim-dark w-100" @click="spendFp">Wydaj punkt szczęścia</button>
</template>
<script setup lang="ts">
import {defineProps} from "vue";
import {useToast} from "vue-toast-notification";
import { emitter } from '../../emitter'
import axios from "axios";

const props = defineProps<{
    heroId: number
}>();
const toast = useToast();

const spendFp = () => {
    axios
        .patch('karta-postaci/' + props.heroId + '/spend-fortune-point')
        .then(() => {
            customSwal.fire({
                title: "Wydałeś punkt szczęścia, było warto?",
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Jeszcze jak",
                denyButtonText: `Ani trochę`
            }).then((result) => {
                let worthIt = false;
                if (result.isConfirmed) {
                    worthIt = true;
                }
                axios
                    .post('karta-postaci/' + props.heroId + '/log-fortune-point-satisfaction', {satisfied: worthIt})
                    .then(() => {
                        if (worthIt) {
                            toast.success('Sigmar Ci sprzyjaja!')
                        } else {
                            toast.warning('Sigmar tak chciał')
                        }
                    })
                emitter.emit('luck-spent')
            });

        })
        .catch(error => {
            toast.error(error.response.data.message)
        })
};
</script>
<style scoped>
.btn-grim-dark {
    font-family: 'Cinzel', 'Times New Roman', serif;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #d4af37;

    background-color: rgba(30, 30, 30, 0.8);
    border: 1px solid #d4af37;
    border-radius: 4px;
    padding: 8px 16px;
    min-width: 200px;

    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
    position: relative;
    overflow: hidden;
}

.btn-grim-dark:hover {
    background-color: #d4af37;
    color: #1a1a1a;
    box-shadow: 0 0 15px rgba(212, 175, 55, 0.4);
}

.btn-grim-dark:active {
    transform: scale(0.98);
    box-shadow: inset 0 3px 5px rgba(0,0,0,0.5);
}

.btn-grim-dark:disabled {
    border-color: #555;
    color: #777;
    background-color: #222;
    cursor: not-allowed;
    box-shadow: none;
}
</style>
