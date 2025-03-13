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
                fill="none" stroke="currentColor"
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
                        <v-text-field
                            v-model="characteristic['WW'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('WW')"
                            hide-details
                        ></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['US'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('US')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['K'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('K')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Odp'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Odp')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Zr'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Zr')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['SW'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('SW')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Int'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Int')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Ogd'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Ogd')"
                            hide-details
                        ></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['WW'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('WW')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['US'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('US')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['K'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('K')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Odp'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Odp')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Zr'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['SW'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('SW')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Int'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Int')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Ogd'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Ogd')"
                            hide-details
                        ></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field
                            v-model="characteristic['WW'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('WW')"
                            hide-details
                        ></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field
                            v-model="characteristic['US'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('US')"
                            hide-details
                        ></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field
                            v-model="characteristic['K'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('K')"
                            hide-details
                        ></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field
                            v-model="characteristic['Odp'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Odp')"
                            hide-details
                        ></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field
                            v-model="characteristic['Zr'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Zr')"
                            hide-details
                        ></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field
                            v-model="characteristic['Int'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Int')"
                            hide-details
                        ></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field
                            v-model="characteristic['SW'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('SW')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field
                            v-model="characteristic['Ogd'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Ogd')"
                            hide-details
                        ></v-text-field>
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
                        <v-text-field
                            v-model="characteristic['A'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('A')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Zyw'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Zyw')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['S'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('S')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Wt'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Wt')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Sz'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Sz')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Mag'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Mag')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['PO'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('PO')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['PP'].pivot.start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('PP')"
                            hide-details
                        ></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['A'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('A')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Zyw'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Zyw')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['S'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('S')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Wt'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Wt')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Sz'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Sz')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Mag'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Mag')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['PO'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('PO')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['PP'].pivot.advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('PP')"
                            hide-details
                        ></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['A'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('A')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Zyw'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Zyw')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['S'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('S')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Wt'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Wt')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Sz'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Sz')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Mag'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('Mag')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['PO'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('PO')"
                            hide-details
                        ></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['PP'].pivot.current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateCharacteristic('PP')"
                            hide-details
                        ></v-text-field>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        characteristicData: Object
    },
    data() {
        return {
            isOpen: false,
            heroId: Object.values(this.characteristicData)[0].pivot.hero_id
        };
    },
    computed: {
        characteristic() {
            return this.characteristicData;
        }
    },
    methods: {
        toggleOpen() {
            this.isOpen = !this.isOpen;
        },
        updateCharacteristic(characteristicName) {
            axios.post('karta-postaci/' + this.heroId + '/update-characteristic', {
                characteristic: this.characteristic[characteristicName]
            })
                .then((response) => {
                    this.$toast.success(response.data.message)
                    this.$emit(
                        'add-characteristic',
                        characteristicName,
                        this.characteristic[characteristicName].pivot,
                        response.data.changeCurrentWounds
                    )
                })
                .catch((error) => {
                    console.log(error)
                    this.$toast.error('Wystąpił błąd podczas aktualizacji cechy: ' + error.data.message);
                })
        },
    }
};
</script>
