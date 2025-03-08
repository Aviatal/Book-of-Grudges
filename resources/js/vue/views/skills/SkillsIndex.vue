<template>
    <div class="container mx-auto p-4">
        <h2 class="text-center text-2xl font-bold text-yellow-500 mb-4">Umiejętności</h2>
        <div class="grid grid-cols-2 gap-4">
            <div v-for="skill in skills" :key="skill.id"
                 class="bg-gray-800 border border-gray-600 p-4 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-white">{{ skill.name }}</h3>
                <div><b>Typ: </b>{{ skill.type }}</div>
                <div><b>Cecha: </b>{{ skill.characteristic }}</div>
                <div><b>Opis: </b>{{ skill.description }}</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SkillsIndex",
    data() {
        return {
            loading: false,
            skills: [],
        }
    },
    created() {
        this.getSkills()
    },
    methods: {
        getSkills() {
            this.loading = true;

            axios.get('umiejetnosci/get-skills')
                .then(response => {
                    this.skills = response.data;
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
