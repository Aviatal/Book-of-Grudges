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
                fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-down text-[#e4d8b4] transition-transform duration-300"
            >
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>

        <transition name="fade">
            <div v-show="isOpen" class="mt-4">
                <v-data-table
                    :headers="armorHeaders"
                    :items="armors"
                    class="custom-table mb-6"
                    hide-default-footer
                    no-data-text="Nie posiadasz broni pancerza"
                >
                    <template v-slot:item.locations="{ item }">
                        <span v-if="item.locations.length < 1">-</span>
                        <div v-else class="flex flex-wrap gap-2 mt-1">
                        <span v-for="(location, index) in item.locations" :key="index" class="bg-red-600 text-white px-2 py-1 rounded-md">
                            {{ location.name }}
                        </span>
                        </div>
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
                                <input type="text" maxlength="1" class="armor-input text-center" v-model="armorPoints.head" disabled>
                            </div>


                            <div class="flex flex-col items-center">
                                <div class="text-[#e4d8b4] mb-1 text-center">Prawa ręka (16-35)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" v-model="armorPoints.arms" disabled>
                            </div>

                            <div class="flex flex-col items-center">
                                <div class="text-[#e4d8b4] mb-1 text-center">Prawa noga (81-90)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" v-model="armorPoints.legs" disabled>
                            </div>
                        </div>

                        <div class="flex flex-col justify-between h-full pr-4 py-8">
                            <div class="flex flex-col items-center">
                                <div class="text-[#e4d8b4] mb-1 text-center">Korpus (56-80)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" v-model="armorPoints.torso" disabled>
                            </div>

                            <div class="flex flex-col items-center">
                                <div class="text-[#e4d8b4] mb-1 text-center">Lewa ręka (36-55)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" v-model="armorPoints.arms" disabled>
                            </div>

                            <div class="flex flex-col items-center">
                                <div class="text-[#e4d8b4] mb-1 text-center">Lewa noga (91-00)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" v-model="armorPoints.legs" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import heroSvg from '@/assets/images/hero.svg?raw';
export default {
    props: {
        armorsData: Object,
    },
    data() {
        return {
            isOpen: false,
            armors: this.armorsData,
            heroSvgContent: heroSvg,
            armorPoints: {
                head: 0,
                arms: 0,
                torso: 0,
                legs: 0
            },

            locationsMap: {
                head: 'Głowa',
                arms: 'Ręce',
                torso: 'Korpus',
                legs: 'Nogi',
            },

            armorHeaders: [
                {title: 'Nazwa', align: 'start', sortable: true, value: 'name'},
                {title: 'Typ pancerza', align: 'start', sortable: true, value: 'category'},
                {title: 'Obc.', align: 'start', sortable: true, value: 'loading'},
                {title: 'Lokacja ciała', align: 'start', sortable: true, value: 'locations'},
                {title: 'Punkty zbroi', align: 'start', sortable: true, value: 'armor_points'},
            ],
        };
    },
    created() {
        ['head', 'arms', 'torso', 'legs'].forEach((location) => {
            this.calculateArmorPoints(location)
        })
    },
    methods: {
        toggleOpen() {
            this.isOpen = !this.isOpen;
        },
        calculateArmorPoints(location) {
            this.armorPoints[location] = this.armorsData.reduce((currentValue, armor) => {
                for (let item of armor.locations) {
                    if (item.name === this.locationsMap[location]) {
                        return currentValue + parseInt(armor.armor_points);
                    }
                }
                return currentValue;
            }, 0)
        }
    }
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
</style>
