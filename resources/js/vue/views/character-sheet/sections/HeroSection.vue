<template>
    <div class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300">
        <div @click="toggleOpen" class="cursor-pointer flex justify-between items-center hover:bg-[#8b5a2b] hover:text-[#2b2a27] rounded-md transition-colors duration-300">
            <h2 class="font-semibold text-xl text-[#e4d8b4]">Bohater</h2>
            <svg :class="isOpen ? 'transform rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down text-[#e4d8b4]">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>
        <div v-show="isOpen" class="mt-4">
            <v-text-field
                v-model="hero.name"
                label="Imię"
                @change="updateHero"
            >
            </v-text-field>
            <v-select
                label="Rasa"
                v-model="hero.race"
                :items="['Krasnolud', 'Niziołek', 'Człowiek', 'Elf']"
                @blur="updateHero"
            ></v-select>
            <v-select
                label="Poprzednia profesja"
                v-model="hero.previous_profession_id"
                :items="professions"
                item-title="text"
                item-value="id"
                @blur="updateHero"
            ></v-select>
            <v-select
                label="Poprzednia profesja"
                v-model="hero.current_profession_id"
                :items="professions"
                item-title="text"
                item-value="id"
                item-color="#FFF"
                @blur="updateHero"
            ></v-select>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        heroData: Object
    },
    data() {
        return {
            hero: this.heroData,
            professions: [],

            isOpen: false
        };
    },
    created() {
        this.getProfessions();
    },
    methods: {
        toggleOpen() {
            this.isOpen = !this.isOpen;
        },
        updateHero() {
            this.$emit('update-hero', this.hero);
        },
        getProfessions() {
            axios.get('professions/get-professions')
                .then(response => {
                    this.professions = response.data
                    console.log(this.professions)
                })
                .catch(error => {
                    this.$toast.error('Wystąpił błąd podczas pobierania profesji')
                })
        }
    }
};
</script>
