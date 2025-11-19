<template>
    <v-dialog v-model="dialog" max-width="800px">
        <template v-slot:activator="{ on, attrs }">
            <button class="button-panel-custom button-orange for-vue" @click="dialog = true">
                Dodaj przedmiot
            </button>
        </template>
        <v-card class="bg-dark-custom border-custom shadow-custom">
            <v-card-title class="text-h5 font-weight-bold gold-text text-center v-card-title">
                Dodaj Przedmiot
            </v-card-title>
            <v-card-text class="v-card-text">
                <v-row class="mb-4">
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="newItem.name"
                            label="Nazwa"
                            :rules="nameRules"
                            class="custom-input"
                            variant="filled"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="newItem.loading"
                            label="Obciążenie"
                            class="custom-input"
                            variant="filled"
                            type="number"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-text-field
                            v-model="newItem.description"
                            label="Opis"
                            class="custom-input"
                            variant="filled"
                        ></v-text-field>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions class="justify-center">
                <button @click="dialog = false" class="cancel-button">Anuluj</button>
                <button @click="addItem" :disabled="!isFormValid" class="add-button">Dodaj</button>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script setup lang="ts">
import {ref, defineProps, computed} from 'vue';

interface newInventoryItem {
    name: string,
    loading: number,
    description: string
}

const props = defineProps<{
    heroId: number;
}>();
const emits = defineEmits<{
    itemAdded: [item: newInventoryItem];
}>();


const dialog = ref<boolean>(false);
const newItem = ref<newInventoryItem>({
    name: '',
    loading: 0,
    description: ''
});
const nameRules = ref<any[]>([
    v => !!v || 'Nazwa przedmiotu jest wymagana',
    v => (v && v.length >= 3) || 'Nazwa musi mieć co najmniej 3 znaki',
]);

const isFormValid = computed(() => {
    return nameRules.value.every(rule => rule(newItem.value.name) === true)
});

const addItem = () => {
    if (isFormValid.value) {
        axios
            .post('karta-postaci/' + props.heroId + '/add-inventory-item', newItem.value)
            .then((response) => {
                dialog.value = false;
                newItem.value = { name: '', loading: 0, description: '' };
                emits('itemAdded', response.data)
            })
            .catch(error => {
                console.error(error);
                toast.error('Nie udało się dodać przedmiotu')
            });
    }
}
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

.custom-input ::v-deep  {
    color: #c09f80;
}

.cancel-button {
    background-color: transparent;
    color: #999;
    border: 1px solid #999;
    padding: 10px 20px;
    font-weight: bold;
    border-radius: 6px;
    transition: 0.3s;
}

.cancel-button:hover {
    color: #c09f80;
    border-color: #c09f80;
}

.add-button {
    background-color: #c09f80;
    color: #1c1c1c;
    padding: 10px 20px;
    font-weight: bold;
    border-radius: 6px;
    transition: 0.3s;
}

.add-button:hover {
    background-color: #d4b38a;
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
