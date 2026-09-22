<template>
    <div>
        <div class="page-header">
            <div class="page-header__inner">
                <div class="page-header__eyebrow">KSIĘGA</div>
                <h1 class="page-header__title">Statystyki</h1>
            </div>
        </div>

        <div class="page-content">
            <div class="main-tabs">
                <button
                    v-for="tab in mainTabs"
                    :key="tab.key"
                    type="button"
                    class="main-tab"
                    :class="{ 'main-tab--active': activeMainTab === tab.key }"
                    @click="activeMainTab = tab.key"
                >
                    <span class="main-tab__icon">{{ tab.icon }}</span>
                    {{ tab.label }}
                </button>
            </div>

            <FortunePointsStatistics
                v-if="activeMainTab === 'fortune-points'"
                :global-statistics="globalStatistics"
                :player-statistics="playerStatistics"
                :player-hero-name="playerHeroName"
                :show-hero-breakdown="showHeroBreakdown"
            />
            <RollStatistics
                v-else
                :stats="rollStatistics"
                :hero-name="playerHeroName"
            />
        </div>
    </div>
</template>

<script>
import FortunePointsStatistics from "./FortunePointsStatistics.vue";
import RollStatistics from "./RollStatistics.vue";

export default {
    name: "Statistics",
    components: { FortunePointsStatistics, RollStatistics },
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
        rollStatistics: {
            type: Object,
            default: null,
        },
    },
    data() {
        return {
            activeMainTab: 'fortune-points',
            mainTabs: [
                { key: 'fortune-points', label: 'Punkty szczęścia', icon: '🍀' },
                { key: 'rolls', label: 'Testy', icon: '🎲' },
            ],
        };
    },
}
</script>

<style scoped>
.page-header {
    background: var(--bg-panel-gradient);
    border-bottom: 1px solid var(--border-default);
    padding: 26px 34px 20px;
}

.page-header__inner {
    max-width: 1000px;
    margin: 0 auto;
}

.page-header__eyebrow {
    font-family: var(--font-heading), serif;
    font-size: 11px;
    letter-spacing: .24em;
    color: var(--text-faint);
    margin-bottom: 6px;
}

.page-header__title {
    margin: 0;
    font-family: var(--font-heading), serif;
    font-size: 30px;
    font-weight: 700;
    letter-spacing: .06em;
    color: var(--gold);
}

.page-content {
    max-width: 1000px;
    margin: 0 auto;
    padding: 26px 34px 60px;
}

.main-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 28px;
}

.main-tab {
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: var(--font-heading), serif;
    font-size: 13px;
    letter-spacing: .06em;
    color: var(--text-faint-alt);
    background: var(--bg-inset-alt);
    border: 1px solid var(--border-default);
    padding: 12px 20px;
    cursor: pointer;
    transition: color 0.2s ease, border-color 0.2s ease, background 0.2s ease;
}

.main-tab__icon {
    font-size: 15px;
}

.main-tab:hover {
    color: var(--text-body);
    border-color: var(--border-accent);
}

.main-tab--active {
    color: var(--gold);
    border-color: var(--gold);
    background: linear-gradient(#2a2117, #1d1710);
}

@media (max-width: 640px) {
    .page-header,
    .page-content {
        padding-left: 16px;
        padding-right: 16px;
    }

    .main-tab {
        flex: 1 1 auto;
        justify-content: center;
        padding: 12px 10px;
    }
}
</style>
