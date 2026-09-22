<template>
    <div class="test-stat-block">
        <h3 v-if="title" class="test-stat-block__title">{{ title }}</h3>

        <div class="test-stat-block__grid">
            <div class="test-stat-block__cell">
                <span class="test-stat-block__value">{{ stats.combined.total }}</span>
                <span class="test-stat-block__label">Testy</span>
            </div>
            <div class="test-stat-block__cell test-stat-block__cell--pass">
                <span class="test-stat-block__value">{{ stats.combined.passed }}</span>
                <span class="test-stat-block__label">Zdane</span>
            </div>
            <div class="test-stat-block__cell test-stat-block__cell--fail">
                <span class="test-stat-block__value">{{ stats.combined.failed }}</span>
                <span class="test-stat-block__label">Niezdane</span>
            </div>
            <div class="test-stat-block__cell test-stat-block__cell--accent">
                <span class="test-stat-block__value">{{ formatPercent(stats.combined.pass_percent) }}</span>
                <span class="test-stat-block__label">% zdanych</span>
            </div>
        </div>

        <div v-if="stats.by_modifier.length" class="test-stat-block__modifiers">
            <div
                v-for="entry in stats.by_modifier"
                :key="modifierKey(entry.modifier, entry.half)"
                class="modifier-chip"
                :class="{ 'modifier-chip--plain': !entry.modifier && !entry.half }"
            >
                <span class="modifier-chip__dot" :class="entry.modifier || entry.half ? 'modifier-chip__dot--on' : 'modifier-chip__dot--off'"></span>
                <span class="modifier-chip__text">
                    {{ formatModifierLabel(entry.modifier, entry.half) }}: <strong>{{ entry.total }}</strong>
                    <span class="modifier-chip__percent">({{ formatPercent(entry.pass_percent) }} zdanych)</span>
                </span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { TestBreakdown } from '../../../types/Statistics';
import { formatModifierLabel, modifierKey } from './modifierLabel';

defineProps<{
    stats: TestBreakdown;
    title?: string | null;
}>();

const formatPercent = (value: number | null): string => (value === null ? '—' : `${value}%`);
</script>

<style scoped>
.test-stat-block__title {
    font-family: var(--font-heading), serif;
    font-size: 16px;
    letter-spacing: .04em;
    color: var(--text-body);
    margin: 0 0 12px;
}

.test-stat-block__grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
}

.test-stat-block__cell {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 14px 16px;
    border: 1px solid var(--border-accent);
    background: linear-gradient(#2a2117, #1d1710);
}

.test-stat-block__cell--pass {
    border-color: #2e7d32;
}

.test-stat-block__cell--fail {
    border-color: #7f1d1d;
}

.test-stat-block__cell--accent {
    border-color: var(--gold);
}

.test-stat-block__value {
    font-family: var(--font-heading), serif;
    font-size: 24px;
    color: var(--gold);
}

.test-stat-block__cell--pass .test-stat-block__value {
    color: #4caf50;
}

.test-stat-block__cell--fail .test-stat-block__value {
    color: #f44336;
}

.test-stat-block__label {
    font-size: 12px;
    color: var(--text-faint-alt);
}

.test-stat-block__modifiers {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 12px;
}

.modifier-chip {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 6px 12px;
    font-size: 13px;
    color: var(--text-faint-alt);
    border: 1px solid var(--border-subtle);
    background: var(--bg-inset-alt);
}

.modifier-chip--plain {
    border-style: dashed;
}

.modifier-chip strong {
    color: var(--text-body);
    font-weight: 700;
}

.modifier-chip__percent {
    color: var(--text-faint);
    font-style: italic;
}

.modifier-chip__dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex: none;
}

.modifier-chip__dot--off {
    background: var(--text-faint-alt);
}

.modifier-chip__dot--on {
    background: #9d91f0;
    box-shadow: 0 0 6px rgba(157, 145, 240, .6);
}

@media (max-width: 640px) {
    .test-stat-block__grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
