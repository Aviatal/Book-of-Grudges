<template>
    <div class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300">
        <div
            @click="toggleOpen"
            class="cursor-pointer flex justify-between items-center px-3 py-2 rounded-md hover:bg-[#8b5a2b] hover:text-[#2b2a27] transition-colors duration-300"
        >
            <h2 class="font-semibold text-xl text-[#e4d8b4]">Cechy</h2>
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

        <div v-show="isOpen" class="mt-4">
            <div class="text-center text-[#e4d8b4] text-lg font-semibold mt-6 mb-2">Cechy podstawowe</div>
            <table class="w-full table-auto border-collapse">
                <thead>
                <tr>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">WW</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">US</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">K</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">Odp</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">Zr</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">Int</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">SW</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">Ogd</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['WW']?.pivot.start_value }}

                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['US']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['K']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Odp']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Zr']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Int']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['SW']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Ogd']?.pivot.start_value }}
                    </td>
                </tr>
                <tr>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['WW']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['US']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['K']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Odp']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Zr']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Int']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['SW']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Ogd']?.available_advancement ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('WW')"
                        title="Kliknij, aby rozwinąć umiejętność"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer text-center">
                            {{ characteristic['WW']?.pivot.start_value + characteristic['WW']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('US')"
                        title="Kliknij, aby rozwinąć umiejętność"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer text-center">
                            {{ characteristic['US']?.pivot.start_value + characteristic['US']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('K')"
                        title="Kliknij, aby rozwinąć umiejętność"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer text-center">
                            {{ characteristic['K']?.pivot.start_value + characteristic['K']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('Odp')"
                        title="Kliknij, aby rozwinąć umiejętność"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer text-center">
                            {{ characteristic['Odp']?.pivot.start_value + characteristic['Odp']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('Zr')"
                        title="Kliknij, aby rozwinąć umiejętność"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer text-center">
                            {{ characteristic['Zr']?.pivot.start_value + characteristic['Zr']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('Int')"
                        title="Kliknij, aby rozwinąć umiejętność"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer text-center">
                            {{ characteristic['Int']?.pivot.start_value + characteristic['Int']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('SW')"
                        title="Kliknij, aby rozwinąć umiejętność"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer text-center">
                            {{ characteristic['SW']?.pivot.start_value + characteristic['SW']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('Ogd')"
                        title="Kliknij, aby rozwinąć umiejętność"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer text-center">
                            {{ characteristic['Ogd']?.pivot.start_value + characteristic['Ogd']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="text-center text-[#e4d8b4] text-lg font-semibold mt-6 mb-2">Cechy drugorzędne</div>
            <table class="w-full table-auto border-collapse mt-4">
                <thead>
                <tr>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">A</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">Żyw</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">S</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">Wt</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">Sz</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">Mag</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">PO</th>
                    <th class="border border-[#8b5a2b] px-2 py-1 text-[#e4d8b4]">PP</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['A']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Zyw']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['S']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Wt']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Sz']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Mag']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['PO']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['PP']?.pivot.start_value }}
                    </td>
                </tr>
                <tr>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['A']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Zyw']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['S']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Wt']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Sz']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Mag']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['PO']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['PP']?.available_advancement ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('A')"
                        title="Kliknij, aby rozwinąć umiejętność"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer text-center">
                            {{ characteristic['A']?.pivot.start_value + characteristic['A']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('Zyw')"
                        title="Kliknij, aby rozwinąć umiejętność"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer text-center">
                            {{ characteristic['Zyw']?.pivot.start_value + characteristic['Zyw']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ getRelatedCharacteristicValue('K') }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ getRelatedCharacteristicValue('Odp') }}
                    </td>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('Sz')"
                        title="Kliknij, aby rozwinąć umiejętność"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer text-center">
                            {{ characteristic['Sz']?.pivot.start_value + characteristic['Sz']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('Mag')"
                        title="Kliknij, aby rozwinąć umiejętność"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer text-center">
                            {{ characteristic['Mag']?.pivot.start_value + characteristic['Mag']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('PO')"
                        title="Kliknij, aby rozwinąć umiejętność"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer text-center">
                            {{ characteristic['PO']?.pivot.start_value + characteristic['PO']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                    <td
                        class="border border-[#8b5a2b] rounded-sm p-0 relative group shadow-sm hover:shadow-md"
                        @click="developCharacteristic('PP')"
                        title="Kliknij, aby zwiększyć wartość"
                    >
                        <div class="w-full h-full p-2 hover:cursor-pointer  text-center transition-colors duration-200 ease-in-out">
                            {{ characteristic['PP']?.pivot.start_value + characteristic['PP']?.pivot.advancement }}
                            <span class="absolute top-1 right-1 bg-[#8b5a2b] text-white px-1 text-s rounded-bl">+</span>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script setup lang="ts">
import {ref, defineProps, defineEmits, computed} from "vue";
import axios from "axios";
import {useToast} from "vue-toast-notification";
import {CharacteristicPivot} from "../../../../types/Hero";

const props = defineProps<{
    characteristicData: Object
    heroId: number
}>();

const emits = defineEmits<{
    addCharacteristics: [characteristicName: string, characteristic: CharacteristicPivot, changeCurrentWounds: number, spentExperience: number];
}>();


const isOpen = ref<boolean>(false);
const characteristic = computed(() => props.characteristicData ?? {});
const toast = useToast();

const toggleOpen = (): void => {
    isOpen.value = !isOpen.value;
}
const developCharacteristic = (characteristicName: string): void => {
    let titleText = 'Czy na pewno chcesz rozwinąć umiejętność?';
    let subtitleText = 'Będzie Cię to kosztowało punkty doświadczenia!';
    if (characteristicName === 'PP') {
        titleText = 'Czy na pewno zasłużyłeś na uzyskanie Punktu Przeznaczenia, miernoto?'
        subtitleText = 'Czytaj: "Czy Mistrz gry przyznał Ci Punkt Przeznaczenia?"'
    } else if (characteristicName === 'PO') {
        titleText = 'Ktoś tu powoli szaleje! Co zrobiłeś nie tak, że chcesz dodać sobie Punkt Obłędu'
        subtitleText = 'Uważaj! Ludzie zwykle dziwnie patrzą na kogoś z trzecim okiem na środku czoła'
    }
    customSwal
        .fire({
            title: titleText,
            text: subtitleText,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Tak!",
            cancelButtonText: 'Nie.',
            width: '30%'
        })
        .then((result) => {
            if (result.isConfirmed) {
                axios.post('karta-postaci/' + props.heroId + '/update-characteristic', {
                    characteristic: characteristic.value[characteristicName]
                })
                    .then((response) => {
                        toast.success(response.data.message)
                        characteristic.value[characteristicName].pivot.advancement += response.data.developedValue;
                        emits(
                            'addCharacteristics',
                            characteristicName,
                            characteristic.value[characteristicName].pivot,
                            response.data.changeCurrentWounds,
                            response.data.spentExperience,
                        )
                        customSwal.fire({title: "Rozwinięto!", icon: "success", theme: 'dark'});
                    })
                    .catch((error) => {
                        console.log(error)
                        customSwal.fire({title: error.response.data.message, icon: "error", theme: 'dark'});
                    })
            }
        });
};

const getRelatedCharacteristicValue = (basedOn: string) => {
    return Math.floor((characteristic.value[basedOn]?.pivot.start_value + (characteristic.value[basedOn]?.pivot.advancement ?? 0)) / 10)
}
</script>
<style scoped>
td {
    text-align: center;
    vertical-align: middle;
    padding: 1rem;
}
</style>
