<template>
    <v-dialog v-model="dialog" max-width="800px">
        <template v-slot:activator="{ on, attrs }">
            <button class="button-panel-custom button-orange for-vue" @click="dialog = true">
                Dodaj broń
            </button>
        </template>
        <v-card class="bg-dark-custom border-custom shadow-custom">
            <v-card-title class="text-h5 font-weight-bold gold-text text-center v-card-title">
                Dodaj Broń
            </v-card-title>
            <template v-if="!isLoading">
                <v-card-text class="v-card-text">
                    <v-row class="mb-5">
                        <v-col cols="12">
                            <v-select
                                v-model="newWeapon.weaponId"
                                :options="weapons"
                                :reduce="weapon => weapon.id"
                                label="name"
                                placeholder="Wybierz rodzaj broni"
                                class="custom-select w-full"
                            ></v-select>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                v-model="newWeapon.additionalWeaponName"
                                label="Dodatkowa nazwa broni (np. kordelas dla broni jednoręcznej)"
                                class="custom-input"
                                variant="filled"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
            </template>
            <template v-else>
                <div class="text-center py-8">
                    <v-progress-circular indeterminate color="amber"></v-progress-circular>
                    <p class="mt-4 text-amber-400">Ładowanie broni...</p>
                </div>
            </template>
            <v-card-actions class="justify-center">
                <button @click="dialog = false" class="cancel-button">Anuluj</button>
                <button @click="addWeapon" class="add-button">Dodaj</button>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script setup lang="ts">
import {defineProps, ref, watch} from 'vue'
import {useToast} from "vue-toast-notification";
import {Weapon} from "../../../types/Weapon";

const toast = useToast();
const props = defineProps<{
    heroId: number;
}>();
const emits = defineEmits<{
    weaponAdded: [weapon: Weapon];
}>();
interface NewWeapon {
    weaponId: number | null;
    additionalWeaponName: string;
}


const dialog = ref<boolean>(false);
const weapons = ref<any[]>([]);
const isLoading = ref<boolean>(false);
const newWeapon = ref<NewWeapon>({
    weaponId: null,
    additionalWeaponName: ''
});

const getWeapons = (): void => {
    isLoading.value = true;
    axios
        .get('bronie/get-weapons?grouped=true')
        .then(response => {
            weapons.value = response.data
        })
        .catch(error => {
            console.log(error)
            toast.error('Wystąpił błąd podczas pobierania broni')
        })
        .finally(() => {
            isLoading.value = false
        })
};
const addWeapon = (): void => {
    axios
        .post('karta-postaci/' + props.heroId + '/add-weapon', newWeapon.value)
        .then((response) => {
            dialog.value = false;
            newWeapon.value = {weaponId: null, additionalWeaponName: ''};
            toast.success('Pomyślnie dodano broń')
            emits('weaponAdded', response.data)
        })
        .catch(error => {
            console.error(error);
            toast.error(error.response.data.message, {duration: 10000})
        });
};

watch(dialog, (newValue) => {
    if (newValue) {
        getWeapons();
    }
});
</script>
<style scoped>
.bg-dark-custom {
    background-color: #2a2926;
    border-radius: 12px;
}

.border-custom {
    border: 2px solid #c09f80;
}

.shadow-custom {
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
}

.gold-text {
    color: #d4b38a;
}

.custom-input ::v-deep .v-input__control {
    background-color: #3b3a36;
    border: 1px solid #c09f80;
    color: #fff;
    border-radius: 6px;
}

.custom-input ::v-deep {
    color: #c09f80;
}

.v-card-title {
    font-size: 1.8rem;
    margin-bottom: 20px;
}

.v-card-text {
    margin: 20px;
}

.button-panel-custom {
    background-color: #c09f80;
    color: #1c1c1c;
    font-size: 1.2rem;
    font-weight: bold;
    padding: 0.8rem;
    margin: 0.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.button-panel-custom:hover {
    background-color: #d4b38a;
    transform: scale(1.1);
}

.button-panel-custom:active {
    transform: scale(0.95);
}
</style>
