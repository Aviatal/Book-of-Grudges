<template>
    <div class="container mx-auto p-4">
        <div>
            <v-text-field
                v-model="searchValue"
                placeholder="Wyszukaj umiejętność"
                @input="getSkills"
            ></v-text-field>
        </div>
        <h2 class="text-center text-2xl font-bold text-yellow-500 mb-4">Umiejętności</h2>
        <div v-show="skillsLength > 0" class="grid grid-cols-2 gap-4" key="skills">
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
            searchValue: '',
            debounceTimer: null,
        }
    },
    created() {
        this.getSkills()
    },
    computed: {
        skillsLength() {
            return this.skills.length;
        }
    },
    methods: {
        getSkills() {
            console.log('getSkills');
            if (this.debounceTimer) {
                clearTimeout(this.debounceTimer);
            }

            this.debounceTimer = setTimeout(() => {
                axios.get('umiejetnosci/get-skills?search=' + this.searchValue)
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
            }, 50)
        },
    }
}
</script>
