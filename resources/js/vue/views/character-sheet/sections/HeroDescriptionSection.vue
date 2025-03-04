<template>
    <div class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300">
        <div
            @click="toggleOpen"
            class="cursor-pointer flex justify-between items-center px-3 py-2 rounded-md hover:bg-[#8b5a2b] hover:text-[#2b2a27] transition-colors duration-300"
        >
            <h2 class="font-semibold text-xl text-[#e4d8b4]">Opis bohatera</h2>
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
                        v-model="heroDescriptions.age"
                        label="Wiek"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHeroDescription"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-select
                        label="Rasa"
                        v-model="heroDescriptions.gender"
                        :items="['M', 'K']"
                        class="custom-select w-full"
                        variant="filled"
                        @blur="updateHeroDescription"
                    ></v-select>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.eye_color"
                        label="Kolor oczu"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHeroDescription"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.hair_color"
                        label="Kolor włosów"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHeroDescription"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.star_sign"
                        label="Znak gwiezdny"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHeroDescription"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.weight"
                        label="Waga"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHeroDescription"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.height"
                        label="Wzrost"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHeroDescription"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.siblings"
                        label="Rodzeństwo"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHeroDescription"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.place_of_birth"
                        label="Miejsce urodzenia"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHeroDescription"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.distinguishing_signs"
                        label="Znaki szczególne"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHeroDescription"
                    ></v-text-field>
                </v-col>
            </v-row>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        descriptionData: Object
    },
    data() {
        return {
            heroDescriptions: this.descriptionData,

            isOpen: false
        };
    },
    created() {
        console.log(this.descriptionData)
    },
    methods: {
        toggleOpen() {
            this.isOpen = !this.isOpen;
        },
        updateHeroDescription() {
            this.$emit('update-hero-descriptions', this.heroDescriptions);
        },
    }
};
</script>
