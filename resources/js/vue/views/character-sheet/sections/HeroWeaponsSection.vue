<template>
    <div class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300">
        <div
            @click="toggleOpen"
            class="cursor-pointer flex justify-between items-center px-3 py-2 rounded-md hover:bg-[#8b5a2b] hover:text-[#2b2a27] transition-colors duration-300"
        >
            <h2 class="font-semibold text-xl text-[#e4d8b4]">Broń</h2>
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
                <div class="weapon-category-header">Broń Biała</div>
                <v-data-table
                    :headers="coldWeaponHeaders"
                    :items="coldWeapons"
                    class="custom-table"
                    hide-default-footer
                    no-data-text="Nie posiadasz broni białej"
                >
                    <template v-slot:item.additional_weapon_name="{ item }">
                        <v-text-field
                            v-model="item.pivot.additional_weapon_name"
                            class="custom-input w-full"
                            variant="filled"
                            hide-details
                        >
                        </v-text-field>
                    </template>
                    <template v-slot:item.traits="{ item }">
                        <span v-for="(trait, index) in item.traits" :key="index" class="trait-pill">
                            {{ trait.name }}<span v-if="index < item.traits.length - 1">, </span>
                        </span>
                    </template>
                </v-data-table>
            </div>
        </transition>

        <transition name="fade">
            <div v-show="isOpen" class="mt-4">
                <div class="weapon-category-header">Broń Strzelecka</div>
                <v-data-table
                    :headers="rangedWeaponHeaders"
                    :items="rangedWeapons"
                    class="custom-table"
                    hide-default-footer
                    no-data-text="Nie posiadasz broni strzeleckiej"
                >
                    <template v-slot:item.additional_weapon_name="{ item }">
                        <v-text-field
                            v-model="item.pivot.additional_weapon_name"
                            class="custom-input w-full"
                            variant="filled"
                            hide-details
                        >
                        </v-text-field>
                    </template>
                    <template v-slot:item.range="{ item }">
                        {{ item.short_range }}/{{ item.long_range }}
                    </template>

                    <template v-slot:item.traits="{ item }">
                        <span v-for="(trait, index) in item.traits" :key="index" class="trait-pill">
                            {{ trait.name }}<span v-if="index < item.traits.length - 1">, </span>
                        </span>
                    </template>
                </v-data-table>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        coldWeaponsData: Object,
        rangedWeaponsData: Object
    },
    data() {
        return {
            isOpen: false,
            coldWeapons: this.coldWeaponsData,
            rangedWeapons: this.rangedWeaponsData,

            coldWeaponHeaders: [
                {title: 'Broń', align: 'start', sortable: true, value: 'name'},
                {title: 'Nazwa', align: 'start', sortable: true, value: 'additional_weapon_name'},
                {title: 'Obc.', align: 'start', sortable: true, value: 'loading'},
                {title: 'Kategoria', align: 'start', sortable: true, value: 'category'},
                {title: 'Siła broni', align: 'start', sortable: true, value: 'power'},
                {title: 'Cechy', align: 'start', sortable: false, value: 'traits'},
            ],
            rangedWeaponHeaders: [
                {title: 'Broń', align: 'start', sortable: true, value: 'name'},
                {title: 'Nazwa', align: 'start', sortable: true, value: 'additional_weapon_name'},
                {title: 'Obc.', align: 'start', sortable: true, value: 'loading'},
                {title: 'Kategoria', align: 'start', sortable: true, value: 'category'},
                {title: 'Siła broni', align: 'start', sortable: true, value: 'power'},
                {title: 'Zasięg', align: 'start', sortable: true, value: 'range'},
                {title: 'Przeład.', align: 'start', sortable: true, value: 'reload_time'},
                {title: 'Cechy', align: 'start', sortable: false, value: 'traits'},
            ],
        };
    },
    methods: {
        toggleOpen() {
            this.isOpen = !this.isOpen;
        },
        updateHeroDescription() {
            this.$emit('update-hero-weapons');
        },
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

.weapon-category-header {
    background-color: #5a3e1b;
    color: #f5e0b7;
    font-size: 1.5rem;
    font-weight: bold;
    text-align: center;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 10px;
    border: 2px solid #8b5a2b;
}

</style>
