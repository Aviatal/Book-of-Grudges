<template>
    <div>
        <div class="tabs">
            <button
                v-for="tab in tabs"
                :key="tab.key"
                type="button"
                class="tab"
                :class="{ 'tab--active': activeTab === tab.key }"
                @click="activeTab = tab.key"
            >
                {{ tab.label }}
            </button>
        </div>

        <section v-if="activeTab === 'global'" class="tab-panel">
            <p class="page-hint">Ile razy wydanie punktu szczęścia w tej kampanii uznano za opłacalne.</p>

            <div class="summary-grid">
                <div class="summary-card">
                    <span class="summary-card__value">{{ globalStatistics.summary.total }}</span>
                    <span class="summary-card__label">Wydanych punktów</span>
                </div>
                <div class="summary-card">
                    <span class="summary-card__value">{{ globalStatistics.summary.satisfied }}</span>
                    <span class="summary-card__label">Opłacalnych</span>
                </div>
                <div class="summary-card">
                    <span class="summary-card__value">{{ globalStatistics.summary.unsatisfied }}</span>
                    <span class="summary-card__label">Nieopłacalnych</span>
                </div>
                <div class="summary-card summary-card--accent">
                    <span class="summary-card__value">{{ formatPercent(globalStatistics.summary.satisfied_percent) }}</span>
                    <span class="summary-card__label">% opłacalności</span>
                </div>
            </div>

            <template v-if="showHeroBreakdown">
                <template v-if="globalStatistics.heroes.length">
                    <h2 class="section-title">Rozbicie na bohaterów</h2>
                    <div class="hero-table">
                        <div class="hero-table__row hero-table__row--head">
                            <span>Bohater</span>
                            <span>Wydane</span>
                            <span>Opłacalne</span>
                            <span>Nieopłacalne</span>
                            <span>% opłacalności</span>
                        </div>
                        <div v-for="hero in globalStatistics.heroes" :key="hero.hero_id" class="hero-table__row">
                            <span>{{ hero.hero_name }}</span>
                            <span>{{ hero.total }}</span>
                            <span>{{ hero.satisfied }}</span>
                            <span>{{ hero.unsatisfied }}</span>
                            <span>{{ formatPercent(hero.satisfied_percent) }}</span>
                        </div>
                    </div>
                </template>
                <p v-else class="page-hint">Nikt w tej kampanii nie wydał jeszcze punktu szczęścia.</p>
            </template>
        </section>

        <section v-else class="tab-panel">
            <template v-if="playerStatistics">
                <p class="page-hint">Twoje statystyki wydawania punktów szczęścia jako {{ playerHeroName }}.</p>

                <div class="summary-grid">
                    <div class="summary-card">
                        <span class="summary-card__value">{{ playerStatistics.total }}</span>
                        <span class="summary-card__label">Wydanych punktów</span>
                    </div>
                    <div class="summary-card">
                        <span class="summary-card__value">{{ playerStatistics.satisfied }}</span>
                        <span class="summary-card__label">Opłacalnych</span>
                    </div>
                    <div class="summary-card">
                        <span class="summary-card__value">{{ playerStatistics.unsatisfied }}</span>
                        <span class="summary-card__label">Nieopłacalnych</span>
                    </div>
                    <div class="summary-card summary-card--accent">
                        <span class="summary-card__value">{{ formatPercent(playerStatistics.satisfied_percent) }}</span>
                        <span class="summary-card__label">% opłacalności</span>
                    </div>
                </div>
            </template>
            <p v-else class="page-hint">Nie posiadasz bohatera w tej kampanii, więc nie ma jeszcze żadnych statystyk do pokazania.</p>
        </section>
    </div>
</template>
<script>
export default {
    name: "FortunePointsStatistics",
    props: {
        globalStatistics: {
            type: Object,
            required: true,
        },
        playerStatistics: {
            type: Object,
            default: null,
        },
        playerHeroName: {
            type: String,
            default: null,
        },
        showHeroBreakdown: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            activeTab: 'global',
            tabs: [
                { key: 'global', label: 'Globalne' },
                { key: 'player', label: 'Mój bohater' },
            ],
        };
    },
    methods: {
        formatPercent(value) {
            return value === null || value === undefined ? '—' : `${value}%`;
        },
    },
}
</script>

<style scoped>
.page-hint {
    margin: 0 0 20px;
    font-size: 16px;
    color: var(--text-faint);
    font-style: italic;
}

.tabs {
    display: flex;
    gap: 6px;
    border-bottom: 1px solid var(--border-default);
    margin-bottom: 24px;
}

.tab {
    font-family: var(--font-heading), serif;
    font-size: 13px;
    letter-spacing: .08em;
    color: var(--text-faint-alt);
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    padding: 10px 16px;
    cursor: pointer;
    transition: color 0.2s ease, border-color 0.2s ease;
}

.tab:hover {
    color: var(--text-body);
}

.tab--active {
    color: var(--gold);
    border-bottom-color: var(--gold);
}

.section-title {
    font-family: var(--font-heading), serif;
    font-size: 18px;
    letter-spacing: .04em;
    color: var(--text-body);
    margin: 32px 0 14px;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 14px;
}

.summary-card {
    display: flex;
    flex-direction: column;
    gap: 6px;
    padding: 18px 20px;
    border: 1px solid var(--border-accent);
    background: linear-gradient(#2a2117, #1d1710);
}

.summary-card--accent {
    border-color: var(--gold);
}

.summary-card__value {
    font-family: var(--font-heading), serif;
    font-size: 28px;
    color: var(--gold);
}

.summary-card__label {
    font-size: 13px;
    color: var(--text-faint-alt);
}

.hero-table {
    display: flex;
    flex-direction: column;
    border: 1px solid var(--border-default);
}

.hero-table__row {
    display: grid;
    grid-template-columns: 2fr repeat(4, 1fr);
    gap: 10px;
    padding: 10px 16px;
    font-size: 14px;
    color: var(--text-body);
    border-bottom: 1px solid var(--border-subtle);
}

.hero-table__row:last-child {
    border-bottom: none;
}

.hero-table__row--head {
    font-family: var(--font-heading), serif;
    font-size: 11px;
    letter-spacing: .1em;
    color: var(--text-faint-alt);
    background: var(--bg-inset-alt);
}

@media (max-width: 640px) {
    .hero-table__row {
        grid-template-columns: 1.4fr repeat(4, 1fr);
        font-size: 12px;
    }
}
</style>
