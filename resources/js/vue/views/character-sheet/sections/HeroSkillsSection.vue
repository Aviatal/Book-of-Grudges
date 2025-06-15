<template>
    <div class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300">
        <div
            @click="toggleOpen('main')"
            class="cursor-pointer flex justify-between items-center px-3 py-2 rounded-md hover:bg-[#8b5a2b] hover:text-[#2b2a27] transition-colors duration-300"
        >
            <h2 class="font-semibold text-xl text-[#e4d8b4]">Umiejętności</h2>
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
                <div
                    class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300">

                    <div
                        @click="toggleOpen('hurdled')"
                        class="cursor-pointer flex justify-between items-center mt-3 px-3 py-2 rounded-md hover:bg-[#8b5a2b] hover:text-[#2b2a27] transition-colors duration-300"
                    >
                        <h2 class="font-semibold text-xl text-[#e4d8b4]">Wykupione</h2>
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
                        <div v-show="isHurdledOpen" class="mt-4">
                            <v-data-table
                                :headers="hurdledSkillsHeaders"
                                :items="hurdledSkills"
                                class="custom-table"
                                hide-default-footer
                                items-per-page="-1"
                                no-data-text="Nie posiadasz wykupionych umiejętności"
                            >
                                <template v-slot:item.hurdled="{ item }">
                                    <input type="checkbox" checked @change="updateSkill(item ,'hurdled', 0, true)">
                                </template>
                                <template v-slot:item.test="{ item }">
                                    {{ characteristic[item.characteristic].pivot.current_value }}
                                </template>
                                <template v-slot:item.additional_skill_name="{ item }">
                                    <v-text-field
                                        v-model="item.pivot.additional_skill_name"
                                        class="custom-input w-full"
                                        variant="filled"
                                        hide-details
                                        @change="updateSkill(item ,'additional_skill_name', item.pivot.additional_skill_name, true)"
                                    >
                                    </v-text-field>
                                </template>
                                <template v-slot:item.first_level="{ item }">
                                    <input :checked="isLevelChecked('first_level', item)" type="checkbox"
                                           @change="updateSkill(item, 'first_level', !isLevelChecked('first_level', item), true)">
                                </template>
                                <template v-slot:item.second_level="{ item }">
                                    <input :checked="isLevelChecked('second_level', item)" type="checkbox"
                                           @change="updateSkill(item, 'second_level', !isLevelChecked('second_level', item), true)">
                                </template>
                            </v-data-table>
                        </div>
                    </transition>
                </div>

                <div
                    class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300">

                    <div
                        @click="toggleOpen('notHurdled')"
                        class="cursor-pointer flex justify-between items-center px-3 py-2 rounded-md hover:bg-[#8b5a2b] hover:text-[#2b2a27] transition-colors duration-300"
                    >
                        <h2 class="font-semibold text-xl text-[#e4d8b4]">Niewykupione</h2>
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
                        <div v-show="isNotHurdledOpen" class="mt-4">
                            <v-data-table
                                :headers="notHurdledSkillsHeaders"
                                :items="notHurdledSkills"
                                class="custom-table"
                                hide-default-footer
                                items-per-page="-1"
                                no-data-text="Jesteś wcieleniem Sigmara! Posiadasz wszystkie umiejętności!"
                            >
                                <template v-slot:item.test="{ item }">
                                    {{ Math.floor(characteristic[item.characteristic].pivot.current_value / 2) }}
                                </template>
                                <template v-slot:item.add="{ item }">
                                    <button @click="updateSkill(item ,'hurdled', 1, !item.expandable)" block class="simple-button">
                                        Wykup
                                    </button>
                                </template>
                            </v-data-table>
                        </div>
                    </transition>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        skillsData: Array,
        characteristicData: Object,
        heroId: Number
    },
    data() {
        return {
            isOpen: false,
            isHurdledOpen: true,
            isNotHurdledOpen: false,

            hurdledSkillsHeaders: [
                {title: 'Umiejętność', align: 'start', sortable: true, value: 'name'},
                {title: 'Cecha', align: 'start', sortable: true, value: 'characteristic'},
                {title: 'Rzut', align: 'start', sortable: true, value: 'test'},
                {title: 'Dodatkowe informacje', align: 'start', sortable: true, value: 'additional_skill_name'},
                {title: 'Wykupione', align: 'start', sortable: true, value: 'hurdled'},
                {title: '+10', align: 'start', sortable: true, value: 'first_level'},
                {title: '+20', align: 'start', sortable: true, value: 'second_level'},
            ],
            notHurdledSkillsHeaders: [
                {title: 'Umiejętność', align: 'start', sortable: true, value: 'name'},
                {title: 'Cecha', align: 'start', sortable: true, value: 'characteristic'},
                {title: 'Rzut', align: 'start', sortable: true, value: 'test'},
                {title: 'Dodaj', align: 'start', sortable: true, value: 'add'},
            ],
        };
    },
    computed: {
        hurdledSkills() {
            return this.skillsData.filter((skill) => Boolean(skill.pivot.hurdled)) .sort((a, b) => a.name.localeCompare(b.name));
        },
        notHurdledSkills() {
            return this.skillsData.filter((skill) => !Boolean(skill.pivot.hurdled))
                .filter((skill, index, self) =>
                        index === self.findIndex((t) => t.id === skill.id)
                )
                .sort((a, b) => a.name.localeCompare(b.name));
        },
        characteristic() {
            return this.characteristicData;
        }
    },
    methods: {
        toggleOpen(panel = 'main') {
            switch (panel) {
                case 'main':
                    this.isOpen = !this.isOpen;
                    break;
                case 'hurdled':
                    this.isHurdledOpen = !this.isHurdledOpen;
                    break;
                case 'notHurdled':
                    this.isNotHurdledOpen = !this.isNotHurdledOpen;
                    break;
            }
        },
        isLevelChecked(level, item) {
            return Boolean(item.pivot[level])
        },
        updateSkill(skill, field, value, update) {
            let action = null;
            if (update) {
                skill['pivot'][field] = value;
            }

            if (skill.expandable && field === 'hurdled' && value) {
                action = 'add'
            } else if (
                skill.expandable === 1 &&
                field === 'hurdled' &&
                !value &&
                this.hurdledSkills.filter((currentSkill) => currentSkill.name === skill.name).length > 0
            ) {
                action = 'remove'
            }

            axios.post('karta-postaci/' + this.heroId + '/update-skill', {skill: skill, action: action})
                .then((response) => {
                    if (action === 'add') {
                        const newSkill = JSON.parse(JSON.stringify(skill))
                        newSkill.pivot = response.data.skill
                        console.log(newSkill)
                        console.log(skill)
                        this.skillsData.push(newSkill)
                    }
                    this.$toast.success(response.data.message)
                })
                .catch((error) => {
                    console.log(error);
                    this.$toast.error('Wystąpił błąd poczas aktualizacji cechy: ' + error.data?.message)
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
