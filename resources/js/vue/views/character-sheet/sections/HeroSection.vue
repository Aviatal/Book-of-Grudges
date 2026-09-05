<template>
    <div>
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
                    <div class="select-wrapper">
                        <select
                            v-model="hero.race"
                            class="race-select w-full"
                            @change="updateHero('race')"
                        >
                            <option value="Krasnolud">Krasnolud</option>
                            <option value="Niziołek">Niziołek</option>
                            <option value="Człowiek">Człowiek</option>
                            <option value="Elf">Elf</option>
                        </select>
                    </div>
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
                    <div class="text-center font-heading text-sm font-semibold my-1" style="color: var(--gold)">
                        Punkty Szczęścia
                    </div>

                    <v-rating
                        v-model="hero.fortune_points"
                        :length="hero.fortune_points"
                        color="grey-darken-3"
                        active-color="red-darken-4"
                        empty-icon="mdi-skull"
                        full-icon="mdi-skull"
                        hover
                        disabled
                    ></v-rating>
                </v-col>

                <v-col cols="12">
                    <div class="text-center font-heading text-lg font-semibold my-1" style="color: var(--gold)">Majątek</div>
                </v-col>
                <v-col cols="12" sm="4" lg="4">
                    <v-text-field
                        v-model="hero.gold_crowns"
                        label="Złote Korony"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHero('gold_crowns')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="4" lg="4">
                    <v-text-field
                        v-model="hero.silver_shillings"
                        label="Srebrne szylingi"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHero('silver_shillings')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="4" lg="4">
                    <v-text-field
                        v-model="hero.brass_pennies"
                        label="Miedziane pensy"
                        class="custom-input w-full"
                        variant="filled"
                        @change="updateHero('brass_pennies')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12">
                    <div class="text-center font-heading text-lg font-semibold my-1" style="color: var(--gold)">Profesje</div>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <label class="font-heading" style="display: block; font-size: 10px; letter-spacing: .18em; color: var(--text-faint); margin-bottom: 6px">POPRZEDNIA PROFESJA</label>
                    <v-select
                        v-model="hero.previous_profession_id"
                        :options="professions"
                        :reduce="profession => profession.id"
                        placeholder="Poprzednia profesja"
                        label="text"
                        class="custom-select w-full"
                        @blur="updateHero('previous_profession_id')"
                        @close="updateHero('previous_profession_id')"
                    ></v-select>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <label class="font-heading" style="display: block; font-size: 10px; letter-spacing: .18em; color: var(--text-faint); margin-bottom: 6px">OBECNA PROFESJA</label>
                    <v-select
                        v-model="hero.current_profession_id"
                        :options="professions"
                        :reduce="profession => profession.id"
                        placeholder="Obecna profesja"
                        label="text"
                        class="custom-select w-full"
                        @blur="updateHero('current_profession_id')"
                        @close="updateHero('current_profession_id')"
                    ></v-select>
                </v-col>

                <v-col cols="12">
                    <div class="text-center font-heading text-lg font-semibold my-1" style="color: var(--gold)">Punkty doświadczenia</div>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="hero.current_experience"
                        label="Obecne PD"
                        class="custom-input w-full"
                        variant="filled"
                        disabled
                        @change="updateHero('current_experience')"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" lg="6">
                    <v-text-field
                        v-model="hero.all_experience"
                        label="Wszystkie PD"
                        class="custom-input w-full"
                        variant="filled"
                        disabled
                        @change="updateHero('all_experience')"
                    ></v-text-field>
                </v-col>
            </v-row>
    </div>
</template>

<script setup lang="ts">
import {defineEmits, defineProps} from "vue";
import {onMounted, ref} from "vue";
import axios from "axios";
import {CharacteristicPivot, Hero} from "../../../../types/Hero";
import {useToast} from "vue-toast-notification";


const props = defineProps<{
    hero: Hero
}>();

const emits = defineEmits<{
    updateCharacteristics: [characteristic: CharacteristicPivot]
}>();

const professions = ref<any[]>([]);
const toast = useToast();

onMounted(() => {
    getProfessions();
})

const updateHero = async(field: string): Promise<void> => {
    axios.post('karta-postaci/' + props.hero.id + '/update-hero', {
        field: field,
        value: props.hero[field]
    })
        .then((response) => {
            toast.success(response.data.message)
            if (field === 'current_profession_id') {
                emits('updateCharacteristics', response.data.characteristic)
            }
        })
        .catch((error) => {
            toast.error('Wystąpił błąd podczas aktualizacji bohatera: ' + error.response.data.message)
        })
}
const getProfessions = async(): Promise<void> => {
    axios.get('professions/get-professions')
        .then(response => {
            professions.value = response.data;
        })
        .catch(() => {
            toast.error('Wystąpił błąd podczas pobierania profesji');
        });
}

</script>
<style scoped>
.select-wrapper {
    position: relative;
    width: 100%;
}

.race-select {
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

.race-select:focus {
    outline: none;
    border-color: var(--border-accent-hover);
    box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, .2);
}
</style>
