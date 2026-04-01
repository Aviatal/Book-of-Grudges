<template>
    <div class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300">
        <div
            @click="toggleOpen"
            class="cursor-pointer flex justify-between items-center px-3 py-2 rounded-md hover:bg-[#8b5a2b] hover:text-[#2b2a27] transition-colors duration-300 border-b border-[#8b5a2b]/30 pb-4"
        >
            <div class="flex items-center">
                <h2 class="font-semibold text-xl text-[#e4d8b4]">Zaklęcia</h2>
            </div>
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
            <div v-show="isOpen" class="mt-6">

                <div v-if="spellsData.length === 0" class="text-center py-6 text-[#e4d8b4]/50 italic">
                    Bohater nie zna jeszcze żadnych arkana...
                </div>

                <div v-else v-for="(specializations, type) in groupedSpells" :key="type" class="mb-10">

                    <div class="flex items-center mb-6">
                        <div class="bg-[#7B1113] h-8 w-2 mr-3 shadow-md"></div>
                        <h3 class="text-3xl font-serif font-black text-[#e4d8b4] uppercase italic border-b border-[#7B1113] flex-grow pb-1">
                            {{ type }}
                        </h3>
                    </div>

                    <div v-for="(spellsInSpec, specName) in specializations" :key="specName" class="mb-8 pl-4">

                        <h4 v-if="specName && specName !== 'null'" class="text-xl font-serif text-[#A27B37] font-bold uppercase mb-4 flex items-center">
                            <span class="w-4 h-[1px] bg-[#A27B37] mr-2"></span>
                            {{ specName }}
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="spell in spellsInSpec" :key="spell.id"
                                 class="spell-card bg-[#1A1A1A] border border-[#2a2a2a] flex flex-col shadow-xl hover:border-[#7B1113] transition-all duration-300">

                                <div class="bg-[#222] p-3 border-b border-[#333] flex justify-between items-center relative overflow-hidden">
                                    <div class="absolute top-0 left-0 w-1 h-full bg-[#7B1113]"></div>
                                    <h5 class="text-[#E0E0E0] font-bold text-base uppercase tracking-tight pl-2">{{ spell.name }}</h5>
                                    <div class="bg-[#7B1113] text-white px-2 py-0.5 rounded-sm text-[10px] font-black" title="Wymagany poziom mocy">
                                        WPM {{ spell.casting_number }}
                                    </div>
                                </div>

                                <div class="p-3 text-[12px] space-y-1 bg-[#1A1A1A]">
                                    <div class="flex justify-between">
                                        <span class="text-[#555] uppercase font-bold">Czas</span>
                                        <span class="text-[#CCC]">{{ spell.casting_duration }}</span>
                                    </div>
                                    <div class="flex justify-between border-t border-[#252525] pt-1">
                                        <span class="text-[#555] uppercase font-bold">Składnik</span>
                                        <span class="text-[#A27B37] italic text-right max-w-[120px]">{{ spell.ingredient || 'Brak' }}</span>
                                    </div>
                                </div>

                                <div class="description-box p-3 bg-[#141414] text-xs text-[#999] leading-tight italic border-t border-[#252525] flex-grow">
                                    {{ spell.description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </transition>
    </div>
</template>

<script setup lang="ts">
import {ref, defineProps, computed} from "vue";
import {Spell} from "../../../../types/Spell";

const props = defineProps<{
    spellsData: Spell[],
    heroId: number
}>();

const isOpen = ref<boolean>(false);

const groupedSpells = computed(() => {
    return props.spellsData.reduce((acc, spell) => {
        const type = spell.type || 'Inne';
        const spec = spell.specialization || 'Ogólne';

        if (!acc[type]) acc[type] = {};
        if (!acc[type][spec]) acc[type][spec] = [];

        acc[type][spec].push(spell);
        return acc;
    }, {} as Record<string, Record<string, Spell[]>>);
});

const toggleOpen = (): void => {
    isOpen.value = !isOpen.value;
};
</script>

<style scoped>
.font-serif {
    font-family: 'Crimson Text', 'Georgia', serif;
}

/* Naprawa "skakania" - stała wysokość opisu */
.description-box {
    min-height: 100px;
    max-height: 100px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #7B1113 #141414;
}

.description-box::-webkit-scrollbar {
    width: 2px;
}
.description-box::-webkit-scrollbar-thumb {
    background: #7B1113;
}

.spell-card:hover {
    transform: translateY(-3px);
    background-color: #222;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
