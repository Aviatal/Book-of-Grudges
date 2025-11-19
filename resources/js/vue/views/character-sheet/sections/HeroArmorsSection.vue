<template>
    <div class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300">
        <div
            @click="toggleOpen"
            class="cursor-pointer flex justify-between items-center px-3 py-2 rounded-md hover:bg-[#8b5a2b] hover:text-[#2b2a27] transition-colors duration-300"
        >
            <h2 class="font-semibold text-xl text-[#e4d8b4]">Pancerz</h2>
            <svg
                :class="isOpen ? 'rotate-180' : ''"
                xmlns="http://www.w3.org/2000/svg"
                width="24" height="24"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-down text-[#e4d8b4] transition-transform duration-300"
            >
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>

        <transition name="fade">
            <div v-show="isOpen" class="mt-4">
                <div class="flex justify-end items-center mb-4">
                    <add-armor-modal
                        :hero-id="heroId"
                        v-on:armor-added="handleNewArmor"
                    ></add-armor-modal>
                </div>
                <v-data-table
                    :headers="armorHeaders"
                    :items="armors"
                    class="custom-table mb-6"
                    key="id"
                    items-per-page="-1"
                    hide-default-footer
                    no-data-text="Nie posiadasz pancerza"
                >
                    <template v-slot:item.locations="{ item }">
                        <span v-if="item.locations.length < 1">-</span>
                        <div v-else class="flex flex-wrap gap-2 mt-1">
                        <span v-for="(location, index) in item.locations" :key="index" class="bg-red-600 text-white px-2 py-1 rounded-md">
                            {{ location.name }}
                        </span>
                        </div>
                    </template>
                    <template v-slot:item.options="{ item, index }">
                        <v-row no-gutters>
                            <v-col cols="12">
                                <v-btn @click="dropArmor(item, index)" block class="delete-button">
                                    Usuń
                                </v-btn>
                            </v-col>
                            <v-col cols="12">
                                <v-btn @click="unequip(item, index)" block class="unequip-button">
                                    Schowaj
                                </v-btn>
                            </v-col>
                        </v-row>
                    </template>
                </v-data-table>

                <div class="relative">
                    <div class="w-full grid place-items-center">
                        <span class="text-[#8b5a2b] *:max-h-[90dvh] *:!w-auto" v-html="heroSvgContent"></span>
                    </div>
                    <div class="absolute inset-0 flex justify-between px-20">
                        <div class="flex flex-col justify-between h-full pl-4 py-8">
                            <div class="flex flex-col items-center">
                                <div class="text-[#e4d8b4] mb-1 text-center">Głowa (01-15)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" :value="headArmorPoints" disabled>
                            </div>


                            <div class="flex flex-col items-center">
                                <div class="text-[#e4d8b4] mb-1 text-center">Prawa ręka (16-35)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" :value="armsArmorPoints" disabled>
                            </div>

                            <div class="flex flex-col items-center">
                                <div class="text-[#e4d8b4] mb-1 text-center">Prawa noga (81-90)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" :value="legsArmorPoints" disabled>
                            </div>
                        </div>

                        <div class="flex flex-col justify-between h-full pr-4 py-8">
                            <div class="flex flex-col items-center">
                                <div class="text-[#e4d8b4] mb-1 text-center">Korpus (56-80)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" :value="torsoArmorPoints" disabled>
                            </div>

                            <div class="flex flex-col items-center">
                                <div class="text-[#e4d8b4] mb-1 text-center">Lewa ręka (36-55)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" :value="armsArmorPoints" disabled>
                            </div>

                            <div class="flex flex-col items-center">
                                <div class="text-[#e4d8b4] mb-1 text-center">Lewa noga (91-00)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" :value="legsArmorPoints" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
<script setup lang="ts">
import AddArmorModal from "../../../components/character-sheet/AddArmorModal.vue";
import {Armor} from "../../../../types/Armor";
import {TableHeader} from "../../../../types/general/TableHeader";
import {computed, ref} from "vue";
import heroSvg from '@/assets/images/hero.svg?raw';
import {useToast} from "vue-toast-notification";

const props = defineProps<{
    armorsData: Armor[],
    heroId: Number,
}>();
const emits = defineEmits<{
    unequipArmor: [armor: Armor[]]
}>();
const toast = useToast();

const isOpen = ref<boolean>(false);
const heroSvgContent = heroSvg;
const armorPoints = ref<{ [key: string]: number }>({
    head: 0,
    arms: 0,
    torso: 0,
    legs: 0,
});
const locationsMap = ref<{ [key: string]: string }>({
    head: 'Głowa',
    arms: 'Ręce',
    torso: 'Korpus',
    legs: 'Nogi',
});
const armorHeaders = ref<TableHeader[]>([
    {title: 'Nazwa', align: 'start', sortable: true, value: 'name'},
    {title: 'Typ pancerza', align: 'start', sortable: true, value: 'category'},
    {title: 'Obc.', align: 'start', sortable: true, value: 'loading'},
    {title: 'Lokacja ciała', align: 'start', sortable: true, value: 'locations'},
    {title: 'Punkty zbroi', align: 'start', sortable: true, value: 'armor_points'},
    {title: 'Opcje', align: 'start', sortable: true, value: 'options'},
]);

const armors = computed(() => props.armorsData ?? []);
const headArmorPoints = computed(() => calculateArmorPoints('head'));
const torsoArmorPoints = computed(() => calculateArmorPoints('torso'));
const armsArmorPoints = computed(() => calculateArmorPoints('arms'));
const legsArmorPoints = computed(() => calculateArmorPoints('legs'));

const toggleOpen = () : void => {
    isOpen.value = !isOpen.value;
};
const calculateArmorPoints = (location: string) :number => {
    return armors.value.reduce((currentValue, armor) => {
        for (let item of armor.locations) {
            if (item.name === locationsMap.value[location]) {
                return currentValue + parseInt(armor.armor_points);
            }
        }
        return currentValue;
    }, 0)
};

const dropArmor = (armor: Armor, index: number) => {
    if (!confirm('Czy na pewno chcesz usunąć zbroję?')) {
        return;
    }
    axios
        .post('karta-postaci/' + props.heroId + '/drop-armor', {armor: armor})
        .then(response => {
            armors.value.splice(index, 1)
            toast.success(response.data.message)
        })
        .catch((error) => {
            console.log(error);
            toast.error('Wystąpił błąd podczas usuwania zbroi')
        })
};

const unequip = (armor: Armor, index: number) => {
    axios
        .post('karta-postaci/' + props.heroId + '/unequip-armor', {armor: armor})
        .then(response => {
            armors.value.splice(index, 1)
            toast.success(response.data.message)
            emits('unequipArmor', response.data.inventory)
        })
        .catch((error) => {
            console.log(error);
            toast.error('Wystąpił błąd podczas chowania zbroi do ekwipunku')
        })
};

const handleNewArmor = (newArmor: Armor) => {
    armors.value.push(newArmor);
};

</script>

<style scoped>
.custom-table {
    background-color: #2b2a27 !important;
    border: 1px solid #8b5a2b !important;
    color: #e4d8b4 !important;
}

.custom-table .v-data-table thead {
    background-color: #8b5a2b !important;
}

.custom-table .v-data-table th {
    background-color: #8b5a2b !important;
    color: #2b2a27 !important;
    font-weight: bold;
    text-transform: uppercase;
    padding: 12px 16px !important;
    border-bottom: 2px solid #704214 !important;
}

.custom-table .v-data-table tbody tr {
    background-color: #3b3a36 !important;
}

.custom-table .v-data-table tbody tr:nth-child(even) {
    background-color: #2b2a27 !important;
}

.custom-table .v-data-table th:hover {
    background-color: #704214 !important;
    color: #f5e0b7 !important;
}

.armor-input {
    width: 40px;
    height: 40px;
    text-align: center;
    background-color: #2b2a27;
    color: #e4d8b4;
    border: 1px solid #8b5a2b;
    border-radius: 4px;
    font-size: 16px;
}

.armor-input:focus {
    border-color: #e4d8b4;
    outline: none;
    box-shadow: 0 0 0 2px rgba(228, 216, 180, 0.3);
}

.delete-button {
    background-color: #ff4d4d;
    color: white;
    border: none;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.delete-button:hover {
    background-color: #ff1a1a;
}

.delete-button:active {
    background-color: #e60000;
}

.unequip-button {
    background-color: #d4af37;
    color: white;
    border: none;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.unequip-button:hover {
    background-color: #b99932;
}

.unequip-button:active {
    background-color: #8d7525;
}
</style>
