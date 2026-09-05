<template>
    <div>
        <collapsible-section title="Wykupione" :default-open="true">
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
                    {{ characteristic[item.characteristic].pivot.start_value + characteristic[item.characteristic].pivot.advancement }}
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
        </collapsible-section>

        <collapsible-section title="Niewykupione" :default-open="false">
            <v-data-table
                :headers="notHurdledSkillsHeaders"
                :items="notHurdledSkills"
                class="custom-table"
                hide-default-footer
                items-per-page="-1"
                no-data-text="Jesteś wcieleniem Sigmara! Posiadasz wszystkie umiejętności!"
            >
                <template v-slot:item.test="{ item }">
                    {{ Math.floor((characteristic[item.characteristic].pivot.start_value + characteristic[item.characteristic].pivot.advancement) / 2) }}
                </template>
                <template v-slot:item.add="{ item }">
                    <button @click="updateSkill(item ,'hurdled', 1, !item.expandable)" block class="simple-button">
                        Wykup
                    </button>
                </template>
            </v-data-table>
        </collapsible-section>
    </div>
</template>
<script setup lang="ts">
import {ref, defineProps, computed} from "vue";
import {Skill} from "../../../../types/Skill";
import {Characteristic} from "../../../../types/Characteristic";
import {TableHeader} from "../../../../types/general/TableHeader";
import {useToast} from "vue-toast-notification";
const props = defineProps<{
    skillsData: Skill[],
    characteristicData: {[key: string]: Characteristic},
    heroId: number,
}>();
const toast = useToast();

const hurdledSkillsHeaders = ref<TableHeader[]>([
    {title: 'Umiejętność', align: 'start', sortable: true, value: 'name'},
    {title: 'Cecha', align: 'start', sortable: true, value: 'characteristic'},
    {title: 'Rzut', align: 'start', sortable: true, value: 'test'},
    {title: 'Dodatkowe informacje', align: 'start', sortable: true, value: 'additional_skill_name'},
    {title: 'Wykupione', align: 'start', sortable: true, value: 'hurdled'},
    {title: '+10', align: 'start', sortable: true, value: 'first_level'},
    {title: '+20', align: 'start', sortable: true, value: 'second_level'},
]);

const notHurdledSkillsHeaders = ref<TableHeader[]>([
    {title: 'Umiejętność', align: 'start', sortable: true, value: 'name'},
    {title: 'Cecha', align: 'start', sortable: true, value: 'characteristic'},
    {title: 'Rzut', align: 'start', sortable: true, value: 'test'},
    {title: 'Dodaj', align: 'start', sortable: true, value: 'add'},
]);

const hurdledSkills = computed(() => {
    return props.skillsData.filter((skill) => Boolean(skill.pivot.hurdled))
        .sort((a, b) => a.name.localeCompare(b.name))
});
const notHurdledSkills = computed(() => {
    return props.skillsData.filter((skill) => !Boolean(skill.pivot.hurdled))
        .filter((skill, index, self) =>
            index === self.findIndex((t) => t.id === skill.id)
        )
        .sort((a, b) => a.name.localeCompare(b.name));
});
const characteristic = computed(() => props.characteristicData ?? []);

const isLevelChecked = (level: string, item: Skill) => {
    return Boolean(item.pivot[level])
};

const updateSkill = (skill: Skill, field: string, value: any, update: boolean) => {
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

    axios
        .post('karta-postaci/' + props.heroId + '/update-skill', {skill: skill, action: action})
        .then((response) => {
            if (action === 'add') {
                const newSkill = JSON.parse(JSON.stringify(skill))
                newSkill.pivot = response.data.skill
                props.skillsData.value.push(newSkill)
            }
            toast.success(response.data.message)
        })
        .catch((error) => {
            console.log(error);
            toast.error('Wystąpił błąd poczas aktualizacji cechy: ' + error.data?.message)
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
</style>
