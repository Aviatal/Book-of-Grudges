<template>
    <div class="container mx-auto p-4">
        <h2 class="text-center text-2xl font-bold text-yellow-500 mb-4">Opancerzenie skórzane</h2>
        <div class="grid grid-cols-2 gap-4">
            <div v-for="armor in leatherArmors" :key="armor.name"
                 class="bg-gray-800 border border-gray-600 p-4 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-white">{{ armor.name }}</h3>
                <div><b>Cena: </b>{{$calculatePrice(armor.price) }}</div>
                <div><b>Obciążenie: </b>{{ armor.loading ?? '-' }}</div>
                <div><b>Kategoria: </b>{{ armor.category }}</div>
                <div><b>Punkty pancerza: </b>{{ armor.armor_points }}</div>
                <div>
                    <b>Chronione lokacje: </b>
                    <span v-if="armor.locations.length < 1">-</span>
                    <div v-else class="flex flex-wrap gap-2 mt-1">
                        <span v-for="(location, index) in armor.locations" :key="index" class="bg-red-600 text-white px-2 py-1 rounded-md">
                            {{ location.name }}
                        </span>
                    </div>
                </div>
                <div><b>Dostępność: </b>{{ armor.availability ?? '-' }}</div>
            </div>
        </div>

        <h2 class="text-center text-2xl font-bold text-yellow-500 my-4">Opancerzenie Kolcze</h2>
        <div class="grid grid-cols-2 gap-4">
            <div v-for="armor in mailArmors" :key="armor.name"
                 class="bg-gray-800 border border-gray-600 p-4 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-white">{{ armor.name }}</h3>
                <div><b>Cena: </b>{{$calculatePrice(armor.price) }}</div>
                <div><b>Obciążenie: </b>{{ armor.loading ?? '-' }}</div>
                <div><b>Kategoria: </b>{{ armor.category }}</div>
                <div><b>Punkty pancerza: </b>{{ armor.armor_points }}</div>
                <div>
                    <b>Chronione lokacje: </b>
                    <span v-if="armor.locations.length < 1">-</span>
                    <div v-else class="flex flex-wrap gap-2 mt-1">
                        <span v-for="(location, index) in armor.locations" :key="index" class="bg-red-600 text-white px-2 py-1 rounded-md">
                            {{ location.name }}
                        </span>
                    </div>
                </div>
                <div><b>Dostępność: </b>{{ armor.availability ?? '-' }}</div>
            </div>
        </div>

        <h2 class="text-center text-2xl font-bold text-yellow-500 my-4">Opancerzenie płytowe</h2>
        <div class="grid grid-cols-2 gap-4">
            <div v-for="armor in plateArmors" :key="armor.name"
                 class="bg-gray-800 border border-gray-600 p-4 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-white">{{ armor.name }}</h3>
                <div><b>Cena: </b>{{$calculatePrice(armor.price) }}</div>
                <div><b>Obciążenie: </b>{{ armor.loading ?? '-' }}</div>
                <div><b>Kategoria: </b>{{ armor.category }}</div>
                <div><b>Punkty pancerza: </b>{{ armor.armor_points }}</div>
                <div>
                    <b>Chronione lokacje: </b>
                    <span v-if="armor.locations.length < 1">-</span>
                    <div v-else class="flex flex-wrap gap-2 mt-1">
                        <span v-for="(location, index) in armor.locations" :key="index" class="bg-red-600 text-white px-2 py-1 rounded-md">
                            {{ location.name }}
                        </span>
                    </div>
                </div>
                <div><b>Dostępność: </b>{{ armor.availability ?? '-' }}</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ArmorsIndex",
    data() {
        return {
            loading: false,
            leatherArmors: [],
            mailArmors: [],
            plateArmors: [],
        }
    },
    created() {
        this.getArmors()
    },
    methods: {
        getArmors() {
            this.loading = true;

            axios.get('opanczerzenie/get-armors')
                .then(response => {
                    this.leatherArmors = response.data.leather;
                    this.mailArmors = response.data.mail;
                    this.plateArmors = response.data.plate;
                })
                .catch(error => {
                    console.log(error);
                    this.$toast.error('Nie udało się pobrać opancerzeń')
                })
                .finally(() => {
                    this.loading = false;
                })
        },
    }
}
</script>
