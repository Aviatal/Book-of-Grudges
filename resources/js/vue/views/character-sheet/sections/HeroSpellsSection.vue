<template>
    <div>
        <div v-if="spellsData.length === 0" class="text-center py-6 italic" style="color: var(--text-faint)">
            Bohater nie zna jeszcze żadnych arkana...
        </div>

        <div v-else v-for="(specializations, type) in groupedSpells" :key="type" class="mb-10">

            <div class="flex items-center mb-6">
                <div class="h-8 w-2 mr-3 shadow-md" style="background: var(--magic-accent)"></div>
                <h3 class="text-3xl font-serif font-black uppercase italic flex-grow pb-1" style="color: var(--text-body); border-bottom: 1px solid var(--magic-accent)">
                    {{ type }}
                </h3>
            </div>

            <div v-for="(spellsInSpec, specName) in specializations" :key="specName" class="mb-8 pl-4">

                <h4 v-if="specName && specName !== 'null'" class="text-xl font-serif font-bold uppercase mb-4 flex items-center" style="color: var(--gold-muted)">
                    <span class="w-4 h-[1px] mr-2" style="background: var(--gold-muted)"></span>
                    {{ specName }}
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-for="spell in spellsInSpec" :key="spell.id" class="spell-card">

                        <div class="p-3 flex justify-between items-center relative overflow-hidden" style="background: var(--bg-panel); border-bottom: 1px solid var(--border-subtle)">
                            <div class="absolute top-0 left-0 w-1 h-full" style="background: var(--magic-accent)"></div>
                            <h5 class="font-bold text-base uppercase tracking-tight pl-2" style="color: var(--text-body)">{{ spell.name }}</h5>
                            <div class="px-2 py-0.5 rounded-sm text-[10px] font-black" style="background: var(--magic-accent); color: var(--text-body)" title="Wymagany poziom mocy">
                                WPM {{ spell.casting_number }}
                            </div>
                        </div>

                        <div class="p-3 text-[12px] space-y-1" style="background: var(--bg-inset)">
                            <div class="flex justify-between">
                                <span class="uppercase font-bold" style="color: var(--text-faint)">Czas</span>
                                <span style="color: var(--text-muted-alt)">{{ spell.casting_duration }}</span>
                            </div>
                            <div class="flex justify-between pt-1" style="border-top: 1px solid var(--border-subtle)">
                                <span class="uppercase font-bold" style="color: var(--text-faint)">Składnik</span>
                                <span class="italic text-right max-w-[120px]" style="color: var(--gold-muted)">{{ spell.ingredient || 'Brak' }}</span>
                            </div>
                        </div>

                        <div class="description-box p-3 text-xs leading-tight italic flex-grow" style="background: var(--bg-inset-alt); color: var(--text-faint-alt); border-top: 1px solid var(--border-subtle)">
                            {{ spell.description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {defineProps, computed} from "vue";
import {Spell} from "../../../../types/Spell";

const props = defineProps<{
    spellsData: Spell[],
    heroId: number
}>();

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
</script>

<style scoped>
.font-serif {
    font-family: 'Crimson Text', 'Georgia', serif;
}

.spell-card {
    display: flex;
    flex-direction: column;
    border: 1px solid var(--border-default);
    box-shadow: 0 18px 40px -28px #000;
    transition: all 0.3s;
}

.spell-card:hover {
    border-color: var(--magic-accent);
    transform: translateY(-3px);
}

/* Naprawa "skakania" - stała wysokość opisu */
.description-box {
    min-height: 100px;
    max-height: 100px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: var(--magic-accent) var(--bg-inset-alt);
}

.description-box::-webkit-scrollbar {
    width: 2px;
}
.description-box::-webkit-scrollbar-thumb {
    background: var(--magic-accent);
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
