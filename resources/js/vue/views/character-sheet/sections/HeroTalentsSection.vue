<template>
    <div>
        <add-talent-modal
            :hero-id="heroId"
            v-on:talent-added="handleNewTalent"
        ></add-talent-modal>
        <div class="talent-grid">
            <div v-for="(talent, index) in talents" :key="talent.id" class="talent-card">
                <h3 class="talent-card__title">{{ talent.name }} <template v-if="talent.pivot.additional_talent_name">({{ talent.pivot.additional_talent_name }})</template></h3>
                <div class="talent-card__divider"></div>
                <p class="talent-card__description">{{ talent.description }}</p>
                <button @click="removeTalent(talent, index)" class="talent-card__remove">Usuń</button>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import AddTalentModal from "../../../components/character-sheet/AddTalentModal.vue";
import {defineProps, computed} from "vue";
import {Talent} from "../../../../types/Talent";
import {useToast} from "vue-toast-notification";
import axios from "axios";

const props = defineProps<{
    talentsData: Talent[],
    heroId: number
}>();
const toast = useToast();

const talents = computed(() => props.talentsData.sort((a, b) => a.name.localeCompare(b.name)));

const handleNewTalent = (newTalent: Talent): void => {
    props.talentsData.push(newTalent);
};

const removeTalent = (talent: Talent, index: number): void => {
    axios
        .post('karta-postaci/' + props.heroId + '/drop-talent', {talent: talent})
        .then(response => {
            talents.value.splice(index, 1)
            toast.success(response.data.message)
        })
        .catch((error) => {
            console.log(error);
            toast.error('Wystąpił błąd podczas usuwania talentu')
        })
}
</script>
<style scoped>
.talent-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 14px;
}

.talent-card {
    border: 1px solid var(--border-default);
    border-top: 1px solid var(--border-accent);
    background: linear-gradient(#1d1913, #161209);
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
}

.talent-card__title {
    margin: 0;
    font-family: var(--font-heading), serif;
    font-size: 17px;
    font-weight: 600;
    color: var(--text-body);
    letter-spacing: .04em;
}

.talent-card__divider {
    height: 1px;
    background: var(--border-subtle);
    margin: 12px 0;
}

.talent-card__description {
    margin: 0;
    flex: 1;
    font-size: 16px;
    line-height: 1.5;
    color: var(--text-muted);
}

.talent-card__remove {
    margin-top: 14px;
    align-self: flex-end;
    padding: 5px 11px;
    border: 1px solid var(--danger-border);
    background: var(--danger-bg);
    color: var(--danger-text);
    font-size: 13px;
    cursor: pointer;
    font-family: var(--font-body), serif;
    transition: border-color 0.2s ease, color 0.2s ease;
}

.talent-card__remove:hover {
    border-color: var(--danger-border-hover);
    color: var(--danger-text-hover);
}
</style>
