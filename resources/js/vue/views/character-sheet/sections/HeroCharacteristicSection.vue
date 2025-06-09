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
                        {{ characteristic['SW']?.pivot.start_value }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Int']?.pivot.start_value }}
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
                        {{ characteristic['SW']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Int']?.available_advancement ?? '-' }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Ogd']?.available_advancement ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['WW']?.pivot.start_value + characteristic['WW']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['US']?.pivot.start_value + characteristic['US']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['K']?.pivot.start_value + characteristic['K']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Odp']?.pivot.start_value + characteristic['Odp']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Zr']?.pivot.start_value + characteristic['Zr']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Int']?.pivot.start_value + characteristic['Int']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['SW']?.pivot.start_value + characteristic['SW']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Ogd']?.pivot.start_value + characteristic['Ogd']?.pivot.advancement }}
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
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['A']?.pivot.start_value + characteristic['A']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Zyw']?.pivot.start_value + characteristic['Zyw']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['S']?.pivot.start_value + characteristic['S']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Wt']?.pivot.start_value + characteristic['Wt']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Sz']?.pivot.start_value + characteristic['Sz']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['Mag']?.pivot.start_value + characteristic['Mag']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['PO']?.pivot.start_value + characteristic['PO']?.pivot.advancement }}
                    </td>
                    <td class="border border-[#8b5a2b]">
                        {{ characteristic['PP']?.pivot.start_value + characteristic['PP']?.pivot.advancement }}
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
<style scoped>
td {
    text-align: center;
    vertical-align: middle;
    padding: 1rem;
}
</style>
