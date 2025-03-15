<template>
    <div class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300">
        <div
            @click="toggleOpen"
            class="cursor-pointer flex justify-between items-center px-3 py-2 rounded-md hover:bg-[#8b5a2b] hover:text-[#42413b] transition-colors duration-300"
        >
            <h2 class="font-semibold text-xl text-[#e4d8b4]">Bohater</h2>
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
                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="hero.name"
                        label="Imię"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHero('name')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-select
                        label="Rasa"
                        v-model="hero.race"
                        :options="['Krasnolud', 'Niziołek', 'Człowiek', 'Elf']"
                        class="custom-select w-full"
                        variant="filled"
                        @blur="updateHero('race')"
                    ></v-select>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="hero.current_wounds"
                        label="Aktualna żywotność"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHero('current_wounds')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="hero.fortune_points"
                        label="Punkty Szczęścia"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHero('fortune_points')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="hero.gold_crowns"
                        label="Złote Korony"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHero('gold_crowns')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="hero.silver_shillings"
                        label="Srebrne szylingi"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHero('silver_shillings')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="hero.brass_pennies"
                        label="Miedziane pensy"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHero('brass_pennies')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-select
                        v-model="hero.previous_profession_id"
                        :options="professions"
                        :reduce="profession => profession.id"
                        label="text"
                        class="custom-select w-full"
                        @blur="updateHero('previous_profession_id')"
                    ></v-select>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-select
                        v-model="hero.current_profession_id"
                        :options="professions"
                        :reduce="profession => profession.id"
                        label="text"
                        class="custom-select w-full"
                        @blur="updateHero('current_profession_id')"
                    ></v-select>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="hero.current_experience"
                        label="Obecne PD"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHero('current_experience')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="hero.all_experience"
                        label="Wszystkie PD"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHero('all_experience')"
                    ></v-text-field>
                </v-col>
            </v-row>
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
            professions: [],

            isOpen: false
        };
    },
    created() {
        this.getProfessions();
    },
    computed: {
        hero() {
            return this.heroData
        }
    },
    methods: {
        toggleOpen() {
            this.isOpen = !this.isOpen;
        },
        updateHero(field) {
            axios.post('karta-postaci/' + this.hero.id + '/update-hero', {
                field: field, value: this.hero[field]
            })
                .then((response) => {
                    this.$toast.success(response.data.message)
                })
                .catch((error) => {
                    this.$toast.error('Wystąpił błąd podczas aktualizacji bohatera: ' + error.data.message)
                })
        },
        getProfessions() {
            axios.get('professions/get-professions')
                .then(response => {
                    this.professions = response.data;
                })
                .catch(() => {
                    this.$toast.error('Wystąpił błąd podczas pobierania profesji');
                });
        }
    }
};
</script>
