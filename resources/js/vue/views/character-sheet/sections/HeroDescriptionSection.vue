<template>
    <div>
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
</template>
<script setup lang="ts">
import {defineProps} from "vue";
import {useToast} from "vue-toast-notification";
import axios from "axios";

const props = defineProps<{
    heroDescriptions: Object
}>();
const toast = useToast();

const updateDescription = async (field: string): Promise<void> => {
    axios.post('karta-postaci/' + props.heroDescriptions.hero_id + '/update-description', {
        field: field,
        value: props.heroDescriptions[field]
    })
        .then((response) => {
            toast.success(response.data.message)
        })
        .catch((error) => {
            toast.error('Wystąpił błąd podczas aktualizacji bohatera: ' + error.data.message)
        })
}
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
    font-family: var(--font-body);
    border: 1px solid var(--border-default);
    border-radius: 2px;
    background-color: var(--bg-inset);
    color: var(--text-body);
    cursor: pointer;
}

.select-wrapper::after {
    content: '\25BC';
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    pointer-events: none;
    color: var(--text-faint);
}

.custom-select:focus {
    outline: none;
    border-color: var(--border-accent-hover);
    box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, .2);
}
</style>
