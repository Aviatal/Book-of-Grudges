<template>
    <div>
        <p v-if="!stats" class="page-hint">
            Nie posiadasz bohatera w tej kampanii, więc nie ma jeszcze żadnych statystyk testów do pokazania.
        </p>

        <template v-else>
            <p class="page-hint">
                Statystyki testów cech i umiejętności wykonanych podczas sesji jako {{ heroName }}. Test umiejętności
                liczy się też do statystyk cechy, pod którą ta umiejętność jest przypisana.
            </p>

            <TestStatBlock title="Wszystkie testy" :stats="stats.overall" />

            <template v-if="stats.characteristics.length">
                <h2 class="section-title">Statystyki wg cech</h2>
                <div class="char-card-grid">
                    <CharacteristicTestCard
                        v-for="item in stats.characteristics"
                        :key="item.characteristic"
                        :characteristic="item.characteristic"
                        :stats="item"
                        :skills="skillsFor(item.characteristic)"
                    />
                </div>
            </template>
            <p v-else class="page-hint page-hint--empty">
                Nie wykonano jeszcze żadnego testu. Statystyki pojawią się tu automatycznie po pierwszym rzucie na
                cechę lub umiejętność podczas sesji.
            </p>
        </template>
    </div>
</template>

<script setup lang="ts">
import TestStatBlock from '@/components/statistics/TestStatBlock.vue';
import CharacteristicTestCard from '@/components/statistics/CharacteristicTestCard.vue';
import type { RollStatistics, SkillTestStats } from '../../../types/Statistics';

const props = defineProps<{
    stats: RollStatistics | null;
    heroName: string | null;
}>();

const skillsFor = (characteristic: string): SkillTestStats[] =>
    props.stats?.skills.filter((skill) => skill.characteristic === characteristic) ?? [];
</script>

<style scoped>
.page-hint {
    margin: 0 0 20px;
    font-size: 16px;
    color: var(--text-faint);
    font-style: italic;
}

.page-hint--empty {
    margin-top: 20px;
}

.section-title {
    font-family: var(--font-heading), serif;
    font-size: 18px;
    letter-spacing: .04em;
    color: var(--text-body);
    margin: 32px 0 14px;
}

.char-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 16px;
}
</style>
