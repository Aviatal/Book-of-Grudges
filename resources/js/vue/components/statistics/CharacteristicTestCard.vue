<template>
    <div class="char-card">
        <div class="char-card__header">
            <div class="char-card__title">
                <span class="char-card__code">{{ characteristic }}</span>
                <span class="char-card__name">{{ fullName }}</span>
            </div>
            <button
                v-if="hasSkills"
                type="button"
                class="char-card__toggle"
                :aria-expanded="expanded"
                @click="expanded = !expanded"
            >
                {{ skills.length }} {{ pluralizeSkillsCount(skills.length) }}
                <svg
                    class="char-card__chevron"
                    :class="{ 'char-card__chevron--open': expanded }"
                    width="12" height="12" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                >
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
        </div>

        <TestStatBlock :stats="stats" />

        <div v-if="hasSkills && expanded" class="char-card__skills">
            <div class="skill-row skill-row--head">
                <span>Umiejętność</span>
                <span>Testy</span>
                <span>% zdanych</span>
                <span>Bez mod.</span>
                <span>Z mod.</span>
            </div>
            <div v-for="skill in skills" :key="skill.skill_id" class="skill-row">
                <span class="skill-row__name">{{ skill.skill_name }}</span>
                <span>{{ skill.combined.total }}</span>
                <span>{{ formatPercent(skill.combined.pass_percent) }}</span>
                <span>{{ skill.without_modifier.total }}</span>
                <span>{{ skill.with_modifier.total }}</span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import TestStatBlock from './TestStatBlock.vue';
import { characteristicFullName, pluralizeSkillsCount } from './characteristicNames';
import type { SkillTestStats, TestBreakdown } from '../../../types/Statistics';

const props = defineProps<{
    characteristic: string;
    stats: TestBreakdown;
    skills: SkillTestStats[];
}>();

const expanded = ref(false);
const fullName = computed(() => characteristicFullName(props.characteristic));
const hasSkills = computed(() => props.skills.length > 0);

const formatPercent = (value: number | null): string => (value === null ? '—' : `${value}%`);
</script>

<style scoped>
.char-card {
    padding: 18px 20px;
    border: 1px solid var(--border-default);
    background: var(--bg-inset);
}

.char-card__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 14px;
}

.char-card__title {
    display: flex;
    align-items: baseline;
    gap: 10px;
}

.char-card__code {
    font-family: var(--font-heading), serif;
    font-size: 18px;
    letter-spacing: .04em;
    color: var(--gold);
}

.char-card__name {
    font-size: 14px;
    color: var(--text-faint-alt);
}

.char-card__toggle {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    letter-spacing: .04em;
    color: var(--text-faint-alt);
    background: transparent;
    border: 1px solid var(--border-subtle);
    padding: 6px 10px;
    cursor: pointer;
    transition: color 0.2s ease, border-color 0.2s ease;
}

.char-card__toggle:hover {
    color: var(--gold);
    border-color: var(--gold);
}

.char-card__chevron {
    transition: transform 0.15s ease;
}

.char-card__chevron--open {
    transform: rotate(180deg);
}

.char-card__skills {
    display: flex;
    flex-direction: column;
    margin-top: 16px;
    border: 1px solid var(--border-subtle);
}

.skill-row {
    display: grid;
    grid-template-columns: 2fr repeat(4, 1fr);
    gap: 10px;
    padding: 9px 14px;
    font-size: 13px;
    color: var(--text-body);
    border-bottom: 1px solid var(--border-subtle);
}

.skill-row:last-child {
    border-bottom: none;
}

.skill-row--head {
    font-family: var(--font-heading), serif;
    font-size: 10px;
    letter-spacing: .08em;
    color: var(--text-faint-alt);
    background: var(--bg-inset-alt);
}

.skill-row__name {
    color: var(--text-body);
}

@media (max-width: 640px) {
    .skill-row {
        grid-template-columns: 1.4fr repeat(4, 1fr);
        font-size: 11px;
    }

    .char-card__name {
        display: none;
    }
}
</style>
