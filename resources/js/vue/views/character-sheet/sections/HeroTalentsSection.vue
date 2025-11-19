<template>
    <div class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300">
        <div
            @click="toggleOpen"
            class="cursor-pointer flex justify-between items-center px-3 py-2 rounded-md hover:bg-[#8b5a2b] hover:text-[#2b2a27] transition-colors duration-300"
        >
            <h2 class="font-semibold text-xl text-[#e4d8b4]">Zdolności</h2>
            <svg
                :class="isOpen ? 'rotate-180' : ''"
                xmlns="http://www.w3.org/2000/svg"
                width="24" height="24"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-down text-[#e4d8b4] transition-transform duration-300"
            >
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>

        <transition name="fade">
            <div v-show="isOpen" class="mt-4">
                <add-talent-modal
                    :hero-id="heroId"
                    v-on:talent-added="handleNewTalent"
                ></add-talent-modal>
                <div class="grid grid-cols-2 gap-4">
                    <div v-for="(talent, index) in talents" :key="talent.id"
                         class="bg-gray-800 border border-gray-600 p-4 rounded-lg shadow-lg flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-white">{{ talent.name }}</h3>
                            <div>{{ talent.description }}</div>
                        </div>
                        <button @click="removeTalent(talent, index)"
                                class="mt-4 self-end px-4 py-2 bg-red-600 text-white font-semibold rounded hover:bg-red-700 transition-colors">
                            Usuń
                        </button>
                    </div>
                </div>

            </div>
        </transition>
    </div>
</template>
<script setup lang="ts">
import AddTalentModal from "../../../components/character-sheet/AddTalentModal.vue";
import {ref, defineProps, computed} from "vue";
import {Talent} from "../../../../types/Talent";
import {useToast} from "vue-toast-notification";
import axios from "axios";

const props = defineProps<{
    talentsData: Talent[],
    heroId: number
}>();
const toast = useToast();

const isOpen = ref<boolean>(false);
const talents = computed(() => props.talentsData.sort((a, b) => a.name.localeCompare(b.name)));

const toggleOpen = (): void => {
    isOpen.value = !isOpen.value;
};

const handleNewTalent = (newTalent: Talent): void => {
    props.talentsData.push(newTalent);
};

const removeTalent = (talent: Talent, index: number): void => {
    axios
        .post('karta-postaci/' + props.heroId + '/drop-talent', {talent: talent})
        .then(response => {
            talents.value.splice(index, 1)
            toast.success(response.data.message)
        })
        .catch((error) => {
            console.log(error);
            toast.error('Wystąpił błąd podczas usuwania talentu')
        })
}
</script>
