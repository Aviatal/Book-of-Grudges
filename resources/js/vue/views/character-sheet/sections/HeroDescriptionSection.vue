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
                        @change="updateDescription('age')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <div class="select-wrapper">
                        <select
                            v-model="heroDescriptions.gender"
                            class="custom-select w-full"
                            @change="updateDescription('gender')"
                        >
                            <option value="K">Kobieta</option>
                            <option value="M">Mężczyzna</option>
                        </select>
                    </div>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.eye_color"
                        label="Kolor oczu"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateDescription('eye_color')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.hair_color"
                        label="Kolor włosów"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateDescription('hair_color')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.star_sign"
                        label="Znak gwiezdny"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateDescription('star_sign')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.weight"
                        label="Waga"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateDescription('weight')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.height"
                        label="Wzrost"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateDescription('height')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.siblings"
                        label="Rodzeństwo"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateDescription('siblings')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.place_of_birth"
                        label="Miejsce urodzenia"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateDescription('place_of_birth')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="heroDescriptions.distinguishing_signs"
                        label="Znaki szczególne"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateDescription('distinguishing_signs')"
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
            isOpen: false
        };
    },
    computed: {
        heroDescriptions() {
            return this.descriptionData;
        }
    },
    methods: {
        toggleOpen() {
            this.isOpen = !this.isOpen;
        },
        updateDescription(field) {
            axios.post('karta-postaci/' + this.heroDescriptions.hero_id + '/update-description', {
                field: field, value: this.heroDescriptions[field]
            })
                .then((response) => {
                    this.$toast.success(response.data.message)
                })
                .catch((error) => {
                    this.$toast.error('Wystąpił błąd podczas aktualizacji bohatera: ' + error.data.message)
                })
        },
    },
};
</script>
<style scoped>
.select-wrapper {
    position: relative;
    width: 100%;
}

.custom-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    width: 100%;
    padding: 15px 15px;
    font-size: 16px;
    border: 1px solid #807a69;
    border-radius: 4px;
    background-color: #42413b;
    cursor: pointer;
}

.select-wrapper::after {
    content: '\25BC';
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    pointer-events: none;
}

.custom-select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}
</style>
