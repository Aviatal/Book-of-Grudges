<template>
    <div>
            <div class="mt-4">
                <div class="flex justify-end items-center mb-4">
                    <add-inventory-modal
                        :hero-id="props.heroId"
                        v-on:item-added="handleNewItem"
                    ></add-inventory-modal>
                </div>
                <v-data-table
                    :headers="headers"
                    :items="inventory"
                    class="custom-table"
                    items-per-page="-1"
                    hide-default-footer
                    no-data-text="Nie posiadasz żadnych przedmiotów"
                >
                    <template v-slot:item.name="{ item, index }">
                        <v-text-field
                            v-model="item.name"
                            @change="editItem(item)"
                        ></v-text-field>
                    </template>

                    <template v-slot:item.description="{ item }">
                        <v-text-field
                            v-model="item.description"
                            @change="editItem(item)"
                        ></v-text-field>
                    </template>
                    <template v-slot:item.delete="{ item, index }">
                        <div class="flex items-center justify-center h-full w-full">
                            <button @click="removeItem(item, index)" class="delete-button">
                                Usuń
                            </button>
                        </div>
                    </template>
                </v-data-table>
            </div>
    </div>
</template>
<script setup lang="ts">
import AddInventoryModal from "../../../components/character-sheet/AddInventoryModal.vue";
import {ref, defineProps, computed} from "vue";
import {Inventory} from "../../../../types/Inventory";
import {TableHeader} from "../../../../types/general/TableHeader";
import axios from "axios";
import {useToast} from "vue-toast-notification";

const props = defineProps<{
    heroId: number,
    inventoryData: Inventory[]
}>();
const toast = useToast();

const headers = ref<TableHeader[]>([
    {title: 'Przedmiot', align: 'start', sortable: true, value: 'name'},
    {title: 'Obc.', align: 'start', sortable: true, value: 'loading'},
    {title: 'Opis', align: 'start', sortable: true, value: 'description'},
    {title: 'Usuń', align: 'start', sortable: true, value: 'delete'},
]);

const inventory = computed(() => props.inventoryData ?? []);

const handleNewItem = (newItem: Inventory) => {
    inventory.value.push(newItem);
};
const removeItem = (item: Inventory, index: number) => {
    axios
        .post('karta-postaci/' + props.heroId + '/drop-item-from-inventory', {item: item})
        .then(response => {
            inventory.value.splice(index, 1)
            toast.success(response.data.message)
        })
        .catch((error) => {
            console.log(error);
            toast.error('Wystąpił błąd podczas usuwania przedmiotu')
        })
};
const editItem = (item: Inventory) => {
    if (item.name === '') {
        toast.error('Nazwa przedmiotu nie może być pusta')
        return;
    }
    axios
        .post('karta-postaci/' + props.heroId + '/edit-inventory-item', {item: item})
        .then(response => {
            $toast.success(response.data.message)
        })
        .catch((error) => {
            console.log(error);
            $toast.error('Wystąpił błąd podczas usuwania przedmiotu')
        })
}
</script>

<style scoped>
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

.delete-button {
    background: var(--danger-bg);
    color: var(--danger-text);
    border: 1px solid var(--danger-border);
    padding: 5px 14px;
    border-radius: 2px;
    font-size: 13px;
    font-weight: bold;
    cursor: pointer;
    transition: border-color 0.2s ease, color 0.2s ease;
}

.delete-button:hover {
    border-color: var(--danger-border-hover);
    color: var(--danger-text-hover);
}
</style>
