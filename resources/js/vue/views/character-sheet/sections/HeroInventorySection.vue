<template>
    <div class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300">
        <div
            @click="toggleOpen"
            class="cursor-pointer flex justify-between items-center px-3 py-2 rounded-md hover:bg-[#8b5a2b] hover:text-[#2b2a27] transition-colors duration-300"
        >
            <h2 class="font-semibold text-xl text-[#e4d8b4]">Ekwipunek</h2>
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
                    <add-inventory-modal
                        :hero-id="heroData.id"
                        v-on:item-added="handleNewItem"
                    ></add-inventory-modal>
                </div>
                <v-data-table
                    :headers="headers"
                    :items="inventory"
                    class="custom-table"
                    hide-default-footer
                    no-data-text="Nie posiadasz żadnych przedmiotów"
                >
                    <template v-slot:item.delete="{ item, index }">
                        <button @click="removeItem(item, index)"
                                class="mt-4 self-end px-4 py-2 bg-red-600 text-white font-semibold rounded hover:bg-red-700 transition-colors">
                            Usuń
                        </button>
                    </template>
                </v-data-table>
            </div>

        </transition>
    </div>
</template>

<script>
import AddInventoryModal from "../../../components/character-sheet/AddInventoryModal.vue";

export default {
    components: {AddInventoryModal},
    props: {
        heroData: Object
    },
    data() {
        return {
            isOpen: false,

            headers: [
                {title: 'Przedmiot', align: 'start', sortable: true, value: 'name'},
                {title: 'Obc.', align: 'start', sortable: true, value: 'loading'},
                {title: 'Opis', align: 'start', sortable: true, value: 'description'},
                {title: 'Usuń', align: 'start', sortable: true, value: 'delete'},
            ],
        };
    },
    computed: {
        inventory() {
            return this.heroData.inventory || [];
        }
    },
    methods: {
        toggleOpen() {
            this.isOpen = !this.isOpen;
        },
        handleNewItem(newItem) {
            this.inventory.push(newItem);
        },
        removeItem(item, index) {
            axios.post('karta-postaci/' + this.heroData.id + '/drop-item-from-inventory', {item: item})
                .then(response => {
                    this.inventory.splice(index, 1)
                    this.$toast.success(response.data.message)
                })
                .catch((error) => {
                    console.log(error);
                    this.$toast.error('Wystąpił błąd podczas usuwania przedmiotu')
                })
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
</style>
