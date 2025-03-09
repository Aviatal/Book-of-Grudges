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
                            v-model="characteristic['WW'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['US'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['K'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Odp'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Zr'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['SW'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Int'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Ogd'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['WW'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['US'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['K'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Odp'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Zr'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['SW'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Int'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Ogd'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field

                            v-model="characteristic['WW'].current_value"

                            class="custom-input w-full"

                            variant="filled"

                            @change="updateHero" hide-details></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field

                            v-model="characteristic['US'].current_value"

                            class="custom-input w-full"

                            variant="filled"

                            @change="updateHero" hide-details></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field

                            v-model="characteristic['K'].current_value"

                            class="custom-input w-full"

                            variant="filled"

                            @change="updateHero" hide-details></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field

                            v-model="characteristic['Odp'].current_value"

                            class="custom-input w-full"

                            variant="filled"

                            @change="updateHero" hide-details></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field

                            v-model="characteristic['Zr'].current_value"

                            class="custom-input w-full"

                            variant="filled"

                            @change="updateHero" hide-details></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field

                            v-model="characteristic['Int'].current_value"

                            class="custom-input w-full"

                            variant="filled"

                            @change="updateHero" hide-details></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field

                            v-model="characteristic['SW'].current_value"

                            class="custom-input w-full"

                            variant="filled"

                            @change="updateHero" hide-details></v-text-field>

                    </td>
                    <td class="border border-[#8b5a2b]">

                        <v-text-field

                            v-model="characteristic['Ogd'].current_value"

                            class="custom-input w-full"

                            variant="filled"

                            @change="updateHero" hide-details></v-text-field>

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
                            v-model="characteristic['A'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Zyw'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['S'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Wt'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Sz'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Mag'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['PO'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['PP'].start_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['A'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Zyw'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['S'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Wt'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Sz'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Mag'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['PO'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['PP'].advancement"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['A'].current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Zyw'].current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['S'].current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Wt'].current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Sz'].current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['Mag'].current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['PO'].current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
                    </td>
                    <td class="border border-[#8b5a2b]">
                        <v-text-field
                            v-model="characteristic['PP'].current_value"
                            class="custom-input w-full"
                            variant="filled"
                            @change="updateHero" hide-details></v-text-field>
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
            characteristic: this.characteristicData,
            isOpen: false
        };
    },
    methods: {
        toggleOpen() {
            this.isOpen = !this.isOpen;
        },
        updateHero() {
            this.$emit('update-hero-characteristic', this.characteristic);
        },
    }
};
</script>
