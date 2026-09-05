<template>
    <div>
            <div class="mt-4">
                <div class="flex justify-end items-center mb-4">
                    <add-weapon-modal
                        :hero-id="heroId"
                        v-on:weapon-added="handleNewWeapon"
                    ></add-weapon-modal>
                </div>
                <div class="section-label"><span>BROŃ BIAŁA</span><span class="section-label-line"></span></div>
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
                    <template v-slot:item.power="{ item }">
                        <template v-if="item.power === 0">S</template>
                        <template v-else-if="item.add_hero_power">
                            S
                            <template v-if="item.power > 0">+</template><template v-else>-</template>
                            {{ item.power }}
                        </template>
                        <template v-else>{{ item.power }}</template>
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

            <div class="mt-4">
                <div class="section-label"><span>BROŃ STRZELECKA</span><span class="section-label-line"></span></div>
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
                    <template v-slot:item.power="{ item }">
                        <template v-if="item.power === 0">S</template>
                        <template v-else-if="item.add_hero_power">
                            S
                            <template v-if="item.power > 0">+</template><template v-else>-</template>
                            {{ item.power }}
                        </template>
                        <template v-else>{{ item.power }}</template>
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
    </div>
</template>
<script setup lang="ts">
import {computed, defineProps, ref} from "vue";
import AddWeaponModal from "../../../components/character-sheet/AddWeaponModal.vue";
import {useToast} from "vue-toast-notification";
import type {Weapon} from "../../../../types/Weapon";
import type {Characteristic} from "../../../../types/Characteristic";
import type {Talent} from "../../../../types/Talent";
import type {TableHeader} from "../../../../types/general/TableHeader";
import type {Response} from "../../../../types/general/Response";
import axios from "axios";

const props = defineProps<{
    heroId: number,
    characteristicData: Characteristic,
    talentsData: Talent[],
    coldWeaponsData: Weapon[],
    rangedWeaponsData: Weapon[]
}>();
const emits = defineEmits<{
    unequipWeapon: [weapon: any[]];
}>();
const toast = useToast();

const coldWeaponHeaders = ref<TableHeader[]>([
    {title: 'Broń', align: 'start', sortable: false, value: 'name'},
    {title: 'Nazwa', align: 'start', sortable: false, value: 'additional_weapon_name'},
    {title: 'Atak', align: 'start', sortable: false, value: 'attack_bonus'},
    {title: 'Siła', align: 'start', sortable: false, value: 'power'},
    {title: 'Cechy', align: 'start', sortable: false, value: 'traits'},
    {title: 'Opcje', align: 'start', sortable: false, value: 'delete'},
])
const rangedWeaponHeaders = ref<TableHeader[]>([
    {title: 'Broń', align: 'start', sortable: false, value: 'name'},
    {title: 'Nazwa', align: 'start', sortable: false, value: 'additional_weapon_name'},
    {title: 'Atak', align: 'start', sortable: false, value: 'attack_bonus'},
    {title: 'Siła', align: 'start', sortable: false, value: 'power'},
    {title: 'Zasięg', align: 'start', sortable: false, value: 'range'},
    {title: 'Przeład.', align: 'start', sortable: false, value: 'reload_time'},
    {title: 'Cechy', align: 'start', sortable: false, value: 'traits'},
    {title: 'Opcje', align: 'start', sortable: false, value: 'delete'},
])
const coldWeapons = computed(() => props.coldWeaponsData ?? []);
const rangedWeapons = computed(() => props.rangedWeaponsData ?? []);
const heroPower = computed(() => Math.floor((props.characteristicData['K'].pivot.start_value + props.characteristicData['K'].pivot.advancement) / 10) ?? 0);
const hasBrawlTalent = computed(() => props.talentsData.some(talent => talent.name === "Bijatyka") ?? false);
const hasStrongStrikeTalent = computed(() => props.talentsData.some(talent => talent.name === "Silny cios") ?? false);
const hasSharpshooterTalent = computed(() => props.talentsData.some(talent => talent.name === "Strzał precyzyjny") ?? false);

const handleNewWeapon = (newWeapon: Weapon): void => {
    if (!newWeapon.is_ranged) {
        coldWeapons.value.push(newWeapon)
    } else {
        rangedWeapons.value.push(newWeapon)
    }
};

const dropWeapon = (weapon :Weapon, index :number) => {
    if (!confirm('Czy na pewno chcesz usunąć broń?')) {
        return;
    }
    axios
        .post('karta-postaci/' + props.heroId + '/drop-weapon', {weapon: weapon})
        .then((response: Response) => {
            if (!weapon.is_ranged) {
                coldWeapons.value.splice(index, 1)
            } else {
                rangedWeapons.value.splice(index, 1)
            }
            toast.success(response.data.message)
        })
        .catch((error) => {
            console.log(error);
            toast.error('Wystąpił błąd podczas usuwania broni')
        })
};
const unequip = (weapon: Weapon, index: number) => {
    axios
        .post('karta-postaci/' + props.heroId + '/unequip-weapon', {weapon: weapon})
        .then(response => {
            if (!weapon.is_ranged) {
                coldWeapons.value.splice(index, 1)
            } else {
                rangedWeapons.value.splice(index, 1)
            }
            toast.success(response.data.message)
            emits('unequipWeapon', response.data.inventory)
        })
        .catch((error) => {
            console.log(error);
            toast.error('Wystąpił błąd podczas chowania broni do ekwipunku')
        })
};

const updateWeapon = (weapon: Weapon) => {
    axios
        .post('karta-postaci/' + props.heroId + '/edit-weapon', {weapon: weapon})
        .then(response => {
            toast.success(response.data.message)
        })
        .catch((error) => {
            console.log(error);
            toast.error('Wystąpił błąd podczas edytowania broni')
        })
};
const weaponPower = (weapon: Weapon) => {
    let weaponPower = 0;
    if (!weapon.is_ranged) {
        weaponPower = weaponPower + heroPower.value + weapon.power
    } else {
        weaponPower += weapon.power;

    }

    if (
        (hasBrawlTalent && weapon.name === 'Bez broni') ||
        (weapon.name !== 'Bez broni' && !weapon.is_ranged && hasStrongStrikeTalent.value) ||
        (weaponPower > 0 && weapon.is_ranged && hasSharpshooterTalent.value)
    ) {
        weaponPower++;
    }

    return weaponPower
}
</script>
<style scoped>
.section-label {
    display: flex;
    align-items: center;
    gap: 14px;
    margin: 0 0 14px;
}

.section-label span:first-child {
    font-family: var(--font-heading), serif;
    font-size: 11px;
    letter-spacing: .2em;
    color: var(--text-faint);
}

.section-label-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, var(--border-default), transparent);
}

.trait-pill {
    display: inline-block;
    border: 1px solid var(--border-frame);
    background: #221d16;
    color: var(--text-muted-alt);
    padding: 3px 9px;
    font-size: 13px;
    letter-spacing: .03em;
}

.custom-table {
    background-color: var(--bg-inset) !important;
    border: 1px solid var(--border-default) !important;
    color: var(--text-body) !important;
}

.custom-table :deep(table) {
    min-width: 820px;
}

.custom-table .v-data-table thead {
    background-color: var(--bg-panel) !important;
}

.custom-table .v-data-table th {
    background-color: var(--bg-panel) !important;
    color: var(--text-faint) !important;
    font-family: var(--font-heading), serif;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
    padding: 12px 16px !important;
    border-bottom: 2px solid var(--border-accent) !important;
}

.custom-table .v-data-table tbody tr {
    background-color: var(--bg-panel) !important;
}

.custom-table .v-data-table tbody tr:nth-child(even) {
    background-color: var(--bg-inset) !important;
}

.custom-table .v-data-table th:hover {
    background-color: var(--border-accent) !important;
    color: var(--gold-bright) !important;
}

.delete-button {
    background: var(--danger-bg);
    color: var(--danger-text);
    border: 1px solid var(--danger-border);
    padding: 4px 8px;
    border-radius: 2px;
    font-size: 12px;
    font-weight: bold;
    cursor: pointer;
    transition: border-color 0.2s ease, color 0.2s ease;
}

.delete-button:hover {
    border-color: var(--danger-border-hover);
    color: var(--danger-text-hover);
}

.unequip-button {
    background: var(--bg-panel);
    color: var(--text-muted-alt);
    border: 1px solid var(--border-default);
    padding: 4px 8px;
    border-radius: 2px;
    font-size: 12px;
    font-weight: bold;
    cursor: pointer;
    transition: border-color 0.2s ease, color 0.2s ease;
}

.unequip-button:hover {
    border-color: var(--border-accent-hover);
    color: var(--text-body);
}
</style>
