<template>
    <div>
            <div class="mt-4">
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
                        <span v-for="(location, index) in item.locations" :key="index" class="location-pill">
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
                        <span class="*:max-h-[90dvh] *:!w-auto" style="color: var(--border-accent-hover)" v-html="heroSvgContent"></span>
                    </div>
                    <div class="absolute inset-0 flex justify-between px-20">
                        <div class="flex flex-col justify-between h-full pl-4 py-8">
                            <div class="flex flex-col items-center">
                                <div class="mb-1 text-center" style="color: var(--text-body)">Głowa (01-15)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" :value="headArmorPoints" disabled>
                            </div>


                            <div class="flex flex-col items-center">
                                <div class="mb-1 text-center" style="color: var(--text-body)">Prawa ręka (16-35)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" :value="armsArmorPoints" disabled>
                            </div>

                            <div class="flex flex-col items-center">
                                <div class="mb-1 text-center" style="color: var(--text-body)">Prawa noga (81-90)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" :value="legsArmorPoints" disabled>
                            </div>
                        </div>

                        <div class="flex flex-col justify-between h-full pr-4 py-8">
                            <div class="flex flex-col items-center">
                                <div class="mb-1 text-center" style="color: var(--text-body)">Korpus (56-80)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" :value="torsoArmorPoints" disabled>
                            </div>

                            <div class="flex flex-col items-center">
                                <div class="mb-1 text-center" style="color: var(--text-body)">Lewa ręka (36-55)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" :value="armsArmorPoints" disabled>
                            </div>

                            <div class="flex flex-col items-center">
                                <div class="mb-1 text-center" style="color: var(--text-body)">Lewa noga (91-00)</div>
                                <input type="text" maxlength="1" class="armor-input text-center" :value="legsArmorPoints" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
.location-pill {
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

.custom-table .v-data-table thead {
    background-color: var(--bg-panel) !important;
}

.custom-table .v-data-table th {
    background-color: var(--bg-panel) !important;
    color: var(--text-faint) !important;
    font-family: var(--font-heading);
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

.armor-input {
    width: 40px;
    height: 40px;
    text-align: center;
    background-color: var(--bg-inset);
    color: var(--text-body);
    border: 1px solid var(--border-default);
    border-radius: 2px;
    font-size: 16px;
    font-family: var(--font-body);
}

.armor-input:focus {
    border-color: var(--border-accent-hover);
    outline: none;
    box-shadow: 0 0 0 2px rgba(212, 175, 55, .2);
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
