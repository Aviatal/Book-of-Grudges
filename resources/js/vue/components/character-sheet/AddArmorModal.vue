<template>
    <v-dialog v-model="dialog" max-width="800px">
        <template v-slot:activator="{ on, attrs }">
            <button class="add-button" @click="dialog = true">
                Dodaj zbroję
            </button>
        </template>
        <v-card class="bg-dark-custom border-custom shadow-custom">
            <v-card-title class="text-h5 font-weight-bold gold-text text-center v-card-title">
                Dodaj zbroję
            </v-card-title>
            <template v-if="!isLoading">
                <v-card-text class="v-card-text">
                    <v-row class="mb-5">
                        <v-col cols="12">
                            <v-select
                                v-model="newArmorId"
                                :options="armors"
                                :reduce="armor => armor.id"
                                label="name"
                                placeholder="Wybierz rodzaj zbroi"
                                class="custom-select w-full"
                            >
                                <template v-slot:option="armor">
                                    {{ armor.category }} - {{ armor.name }}
                                </template>
                            </v-select>
                        </v-col>
                    </v-row>
                </v-card-text>
            </template>
            <template v-else>
                <div class="text-center py-8">
                    <v-progress-circular indeterminate color="amber"></v-progress-circular>
                    <p class="mt-4 text-amber-400">Pobieranie zbroi...</p>
                </div>
            </template>
            <v-card-actions class="justify-center">
                <button @click="dialog = false" class="cancel-button">Anuluj</button>
                <button @click="addArmor" class="add-button">Dodaj</button>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script setup lang="ts">
import {defineProps, ref, watch} from 'vue'
import {Armor} from "@/types/Armor";
import {useToast} from "vue-toast-notification";
const props = defineProps<{
    heroId: number;
}>();
const emits = defineEmits<{
    armorAdded: [armor: Armor];
}>();
const toast = useToast();

const dialog = ref<boolean>(false);
const armors = ref<Armor[]>([]);
const isLoading = ref<boolean>(false);
const newArmorId = ref<number | null>(null);

const getArmors = (): void => {
    isLoading.value = true;
    axios
        .get('opancerzenie/get-armors?grouped=true')
        .then(response => {
            armors.value = response.data
        })
        .catch(error => {
            console.log(error)
            toast.error('Wystąpił błąd podczas pobierania zbroi')
        })
        .finally(() => {
            isLoading.value = false
        })
};

const addArmor = (): void => {
    axios
        .post('karta-postaci/' + props.heroId + '/add-armor', {armorId: newArmorId.value})
        .then((response) => {
            dialog.value = false;
            newArmorId.value = null;
            toast.success('Pomyślnie dodano zbroję')
            emits('armorAdded', response.data)
        })
        .catch(error => {
            console.error(error);
            this.$toast.error(error.response.data.message, {duration: 10000})
        });
}
watch(dialog, (newValue) => {
    if (newValue) {
        getArmors();
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

.v-card-title {
    font-size: 1.8rem;
    margin-bottom: 20px;
}

.v-card-text {
    margin: 20px;
}
</style>
