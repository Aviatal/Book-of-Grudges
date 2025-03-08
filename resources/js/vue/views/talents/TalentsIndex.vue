<template>
    <div class="container mx-auto p-4">
        <h2 class="text-center text-2xl font-bold text-yellow-500 mb-4">Zdolności</h2>
        <div class="grid grid-cols-2 gap-4">
            <div v-for="talent in talents" :key="talent.id"
                 class="bg-gray-800 border border-gray-600 p-4 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-white">{{ talent.name }}</h3>
                <div>{{ talent.description }}</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "TalentsIndex",
    data() {
        return {
            loading: false,
            talents: [],
        }
    },
    created() {
        this.getTalents()
    },
    methods: {
        getTalents() {
            this.loading = true;

            axios.get('zdolnosci/get-talents')
                .then(response => {
                    this.talents = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.$toast.error('Nie udało się pobrać umiejętności')
                })
                .finally(() => {
                    this.loading = false;
                })
        },
    }
}
</script>
