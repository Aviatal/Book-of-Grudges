<template>
    <div class="container mx-auto p-4 bg-[#121212] min-h-screen text-[#E0E0E0]">
        <div class="mb-12">
            <v-text-field
                v-model="searchValue"
                label="Przeszukaj zakazane arkana..."
                prepend-inner-icon="mdi-magnify"
                variant="outlined"
                color="#7B1113"
                theme="dark"
                class="custom-search"
                clearable
                @input="getSpells"
            ></v-text-field>
        </div>

        <div class="text-center mb-16">
            <h1 class="text-6xl font-serif font-bold text-[#7B1113] tracking-[0.2em] uppercase drop-shadow-[0_5px_15px_rgba(123,17,19,0.4)]">
                KSIĘGA CZARÓW
            </h1>
            <div class="flex justify-center items-center mt-4">
                <div class="h-[1px] w-32 bg-gradient-to-r from-transparent to-[#A27B37]"></div>
                <v-icon color="#A27B37" class="mx-6" size="large">mdi-skull</v-icon>
                <div class="h-[1px] w-32 bg-gradient-to-l from-transparent to-[#A27B37]"></div>
            </div>
        </div>

        <div v-for="(specializations, type) in groupedSpells" :key="type" class="mb-24">

            <div class="relative mb-12 flex justify-center">
                <div class="w-full absolute top-1/2 left-0 h-px bg-[#7B1113]/30"></div>
                <div class="relative bg-[#121212] px-10 py-2 border-2 border-[#7B1113]">
                    <h2 class="text-5xl font-serif font-black text-[#7B1113] uppercase tracking-widest">
                        {{ type }}
                    </h2>
                </div>
            </div>

            <div v-for="(spellsInSpec, specName) in specializations" :key="specName" class="mb-16">

                <div class="flex items-center mb-8 pl-4">
                    <v-icon color="#A27B37" size="small" class="mr-4">mdi-script</v-icon>
                    <h3 v-if="specName && specName !== 'null'" class="text-4xl font-serif text-[#A27B37] italic">
                        {{ specName }}
                    </h3>
                    <h3 v-else class="text-3xl font-serif text-gray-600 italic"> Wiedza Powszechna </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="spell in spellsInSpec" :key="spell.id"
                         class="spell-card bg-[#1A1A1A] border-l-4 border-l-[#7B1113] border-y border-r border-[#333] flex flex-col shadow-2xl">

                        <div class="bg-[#222] p-5 border-b border-[#333] flex justify-between items-center">
                            <h4 class="text-[#E0E0E0] font-bold text-xl uppercase tracking-tight">{{ spell.name }}</h4>
                            <div class="bg-[#7B1113] text-white px-3 py-1 rounded-sm text-sm font-black">
                                SM {{ spell.casting_number }}
                            </div>
                        </div>

                        <div class="p-5 text-xs space-y-3 bg-[#1A1A1A]">
                            <div class="flex justify-between items-center">
                                <span class="text-[#555] uppercase font-bold tracking-tighter">Inkantacja</span>
                                <span class="text-[#CCC]">{{ spell.casting_duration }}</span>
                            </div>
                            <div class="flex justify-between items-start border-t border-[#252525] pt-3">
                                <span class="text-[#555] uppercase font-bold tracking-tighter">Składnik</span>
                                <span class="text-[#A27B37] italic text-right max-w-[150px] leading-none">{{ spell.ingredient || 'Brak' }}</span>
                            </div>
                        </div>

                        <div class="description-box p-5 bg-[#141414] text-base text-[#999] leading-snug italic border-t border-[#252525] flex-grow">
                            {{ spell.description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "SpellsIndex",
    data() {
        return {
            loading: false,
            spells: [],
            searchValue: '',
            debounceTimer: null,
        }
    },
    created() {
        this.getSpells()
    },
    computed: {
        spellsLength() {
            return this.spells.length;
        },
        groupedSpells() {
            return this.spells.reduce((acc, spell) => {
                const type = spell.type || 'Inne';
                const spec = spell.specialization || 'Ogólne';
                if (!acc[type]) acc[type] = {};
                if (!acc[type][spec]) acc[type][spec] = [];
                acc[type][spec].push(spell);
                return acc;
            }, {});
        }
    },
    methods: {
        getSpells() {
            this.loading = true;
            if (this.debounceTimer) clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                axios.get('zaklecia/get-spells?search=' + this.searchValue)
                    .then(response => { this.spells = response.data; })
                    .finally(() => { this.loading = false; })
            }, 300);
        }
    }
}
</script>

<style scoped>
/* Naprawa skakania - stała wysokość opisu */
.description-box {
    min-height: 140px;
    max-height: 140px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #7B1113 #141414;
}

/* Custom Scrollbar dla Chrome/Safari */
.description-box::-webkit-scrollbar {
    width: 3px;
}
.description-box::-webkit-scrollbar-track {
    background: #141414;
}
.description-box::-webkit-scrollbar-thumb {
    background: #7B1113;
}

/* Stylizacja wyszukiwarki */
.custom-search :deep(.v-field) {
    border: 1px solid #333 !important;
    border-radius: 0 !important;
}

.spell-card:hover {
    transform: scale(1.02);
    box-shadow: 0 15px 30px rgba(0,0,0,0.6);
}

.font-serif {
    font-family: 'Crimson Text', 'Georgia', serif;
}

h2 {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}
</style>
