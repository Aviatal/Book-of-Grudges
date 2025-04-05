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
                <div class="flex justify-end items-center mb-4">
                    <add-weapon-modal
                        :hero-id="heroId"
                        v-on:weapon-added="handleNewWeapon"
                    ></add-weapon-modal>
                </div>
                <div class="weapon-category-header">Broń Biała</div>
                <v-data-table
                    :headers="coldWeaponHeaders"
                    :items="coldWeapons"
                    class="custom-table"
                    items-per-page="-1"
                    hide-default-footer
                    no-data-text="Nie posiadasz broni białej"
                >
                    <template v-slot:item.additional_weapon_name="{ item }">
                        <v-text-field
                            v-model="item.pivot.additional_weapon_name"
                            class="custom-input w-full"
                            variant="filled"
                            hide-details
                            @update="updateWeapon(item)"
                            @blur="updateWeapon(item)"
                        >
                        </v-text-field>
                    </template>
                    <template v-slot:item.traits="{ item }">
                        <span v-for="(trait, index) in item.traits" :key="index" class="trait-pill">
                            {{ trait.name }}<span v-if="index < item.traits.length - 1">, </span>
                        </span>
                    </template>
                    <template v-slot:item.attack_bonus="{ item }">
                        {{ weaponPower(item) }}
                    </template>

                    <template v-slot:item.delete="{ item, index }">
                        <v-row no-gutters class="n-3">
                            <v-col cols="12">
                                <v-btn @click="dropWeapon(item, index)" block class="delete-button">
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
            </div>
        </transition>

        <transition name="fade">
            <div v-show="isOpen" class="mt-4">
                <div class="weapon-category-header">Broń Strzelecka</div>
                <v-data-table
                    :headers="rangedWeaponHeaders"
                    :items="rangedWeapons"
                    key="id"
                    class="custom-table"
                    items-per-page="-1"
                    hide-default-footer
                    no-data-text="Nie posiadasz broni strzeleckiej"
                >
                    <template v-slot:item.attack_bonus="{ item }">
                        {{ weaponPower(item) }}
                    </template>
                    <template v-slot:item.additional_weapon_name="{ item }">
                        <v-text-field
                            v-model="item.pivot.additional_weapon_name"
                            class="custom-input w-full"
                            variant="filled"
                            hide-details
                            @update="updateWeapon(item)"
                            @blur="updateWeapon(item)"
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

                    <template v-slot:item.delete="{ item, index }">
                        <v-row no-gutters>
                            <v-col cols="12">
                                <v-btn @click="dropWeapon(item, index)" block class="delete-button">
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
            </div>
        </transition>
    </div>
</template>

<script>
import addWeaponModal from "../../../components/character-sheet/AddWeaponModal.vue";
import AddInventoryModal from "../../../components/character-sheet/AddInventoryModal.vue";

export default {
    props: {
        heroId: Number,
        characteristicData: Array,
        talentsData: Array,
        coldWeaponsData: Object,
        rangedWeaponsData: Object
    },
    components: {AddInventoryModal, addWeaponModal},
    data() {
        return {
            isOpen: false,

            coldWeaponHeaders: [
                {title: 'Broń', align: 'start', sortable: false, value: 'name'},
                {title: 'Nazwa', align: 'start', sortable: false, value: 'additional_weapon_name'},
                {title: 'Atak', align: 'start', sortable: false, value: 'attack_bonus'},
                {title: 'Siła', align: 'start', sortable: false, value: 'power'},
                {title: 'Cechy', align: 'start', sortable: false, value: 'traits'},
                {title: 'Opcje', align: 'start', sortable: false, value: 'delete'},
            ],
            rangedWeaponHeaders: [
                {title: 'Broń', align: 'start', sortable: false, value: 'name'},
                {title: 'Nazwa', align: 'start', sortable: false, value: 'additional_weapon_name'},
                {title: 'Atak', align: 'start', sortable: false, value: 'attack_bonus'},
                {title: 'Siła', align: 'start', sortable: false, value: 'power'},
                {title: 'Zasięg', align: 'start', sortable: false, value: 'range'},
                {title: 'Przeład.', align: 'start', sortable: false, value: 'reload_time'},
                {title: 'Cechy', align: 'start', sortable: false, value: 'traits'},
                {title: 'Opcje', align: 'start', sortable: false, value: 'delete'},
            ],
        };
    },
    computed: {
        coldWeapons() {
            return this.coldWeaponsData;
        },
        rangedWeapons() {
            return this.rangedWeaponsData;
        },
        heroPower() {
            return Math.max(this.characteristicData['S'].pivot.current_value, this.characteristicData['S'].pivot.start_value)
        },
        hasBrawlTalent() {
            return this.talentsData.some(talent => talent.name === "Bijatyka");
        },
        hasStrongStrikeTalent() {
            return this.talentsData.some(talent => talent.name === "Silny cios");
        },
        hasSharpshooterTalent() {
            return this.talentsData.some(talent => talent.name === "Strzał precyzyjny");
        },
    },
    methods: {
        toggleOpen() {
            this.isOpen = !this.isOpen;
        },
        handleNewWeapon(newWeapon) {
            if (!newWeapon.is_ranged) {
                this.coldWeapons.push(newWeapon)
            } else {
                this.rangedWeapons.push(newWeapon)
            }
        },
        dropWeapon(weapon, index) {
            if (!confirm('Czy na pewno chcesz usunąć broń?')) {
                return;
            }
            axios.post('karta-postaci/' + this.heroId + '/drop-weapon', {weapon: weapon})
                .then(response => {
                    if (!weapon.is_ranged) {
                        this.coldWeapons.splice(index, 1)
                    } else {
                        this.rangedWeapons.splice(index, 1)
                    }
                    this.$toast.success(response.data.message)
                })
                .catch((error) => {
                    console.log(error);
                    this.$toast.error('Wystąpił błąd podczas usuwania broni')
                })
        },
        unequip(weapon, index) {
            axios.post('karta-postaci/' + this.heroId + '/unequip-weapon', {weapon: weapon})
                .then(response => {
                    if (!weapon.is_ranged) {
                        this.coldWeapons.splice(index, 1)
                    } else {
                        this.rangedWeapons.splice(index, 1)
                    }
                    this.$toast.success(response.data.message)
                    this.$emit('unequip-weapon', response.data.inventory)
                })
                .catch((error) => {
                    console.log(error);
                    this.$toast.error('Wystąpił błąd podczas chowania broni do ekwipunku')
                })
        },
        updateWeapon(weapon) {
            axios.post('karta-postaci/' + this.heroId + '/edit-weapon', {weapon: weapon})
                .then(response => {
                    this.$toast.success(response.data.message)
                })
                .catch((error) => {
                    console.log(error);
                    this.$toast.error('Wystąpił błąd podczas edytowania broni')
                })
        },
        weaponPower(weapon) {
            let weaponPower = 0;

            if (weapon.power === 'S') {
                weaponPower += this.heroPower
            } else if (weapon.power.startsWith('S') && weapon.power.length > 1) {
                const calculatedWeaponPower = this.heroPower + parseInt(weapon.power.slice(1))
                weaponPower += calculatedWeaponPower;
            } else if (!isNaN(parseInt(weapon.power))) {
                weaponPower += weapon.power;
            }

            if (
                (this.hasBrawlTalent && weapon.name === 'Bez broni') ||
                (weapon.name !== 'Bez broni' && weapon.is_ranged === 0 && this.hasStrongStrikeTalent) ||
                (weaponPower > 0 && weapon.is_ranged === 1 && this.hasSharpshooterTalent)
            ) {
                weaponPower++;
            }

            return weaponPower
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
