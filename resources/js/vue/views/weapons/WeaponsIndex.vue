<template>
    <div class="container mx-auto p-4">
        <h2 class="text-center text-2xl font-bold text-yellow-500 mb-4">Broń biała</h2>
        <div class="grid grid-cols-2 gap-4">
            <div v-for="weapon in coldWeapons" :key="weapon.name"
                 class="bg-gray-800 border border-gray-600 p-4 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-white">{{ weapon.name }}</h3>
                <div><b>Cena: </b>{{ $calculatePrice(weapon.price) }}</div>
                <div><b>Obciążenie: </b>{{ weapon.loading ?? '-' }}</div>
                <div><b>Kategoria: </b>{{ weapon.category }}</div>
                <div><b>Siła broni: </b>{{ weapon.power }}</div>
                <div>
                    <b>Cechy oręża: </b>
                    <span v-if="weapon.traits.length < 1">-</span>
                    <div v-else class="flex flex-wrap gap-2 mt-1">
                        <span v-for="(trait, index) in weapon.traits" :key="index" class="bg-red-600 text-white px-2 py-1 rounded-md">
                            {{ trait.name }}
                        </span>
                    </div>
                </div>
                <div><b>Dostępność: </b>{{ weapon.availability ?? '-' }}</div>
            </div>
        </div>

        <h2 class="text-center text-2xl font-bold text-yellow-500 mt-8 mb-4">Broń strzelecka</h2>
        <div class="grid grid-cols-2 gap-4">
            <div v-for="weapon in rangedWeapons" :key="weapon.name"
                 class="bg-gray-800 border border-gray-600 p-4 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-white">{{ weapon.name }}</h3>
                <div><b>Cena: </b>{{ calculateWeaponPrice(weapon.price) }}</div>
                <div><b>Obciążenie: </b>{{ weapon.loading ?? '-' }}</div>
                <div><b>Kategoria: </b>{{ weapon.category }}</div>
                <div><b>Siła broni: </b>{{ weapon.power }}</div>
                <div><b>Zasięg: </b>{{ weapon.short_range }}/{{ weapon.long_range }}</div>
                <div><b>Przeładowanie: </b>{{ weapon.reload_time }}</div>
                <div>
                    <b>Cechy oręża: </b>
                    <span v-if="weapon.traits.length < 1">-</span>
                    <div v-else class="flex flex-wrap gap-2 mt-1">
                        <span v-for="(trait, index) in weapon.traits" :key="index" class="bg-red-600 text-white px-2 py-1 rounded-md">
                            {{ trait.name }}
                        </span>
                    </div>
                </div>
                <div><b>Dostępność: </b>{{ weapon.availability }}</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "WeaponsIndex",
    data() {
        return {
            loading: false,
            coldWeapons: [],
            rangedWeapons: []
        }
    },
    created() {
        this.getWeapons()
    },
    methods: {
        getWeapons() {
            this.loading = true;

            axios.get('bronie/get-weapons')
                .then(response => {
                    this.coldWeapons = response.data.cold;
                    this.rangedWeapons = response.data.ranged;
                })
                .catch(error => {
                    console.log(error);
                    this.$toast.error('Nie udało się pobrać broni')
                })
                .finally(() => {
                    this.loading = false;
                })
        },
    }
}
</script>
