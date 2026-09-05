<template>
    <div>
        <div class="page-header">
            <div class="page-header__inner">
                <div class="page-header__eyebrow">PANEL MISTRZA GRY</div>
                <h1 class="page-header__title">Przydziel punkty szczęścia</h1>
            </div>
        </div>

        <div class="page-content">
            <p class="page-hint">Kliknij bohatera, aby dopisać mu punkt szczęścia.</p>
            <div class="hero-grid">
                <button v-for="user in activeUsers" :key="user.hero.id" class="hero-card" @click="addFortunePoint(user.hero)">
                    <span class="hero-card__marker"></span>
                    <span class="hero-card__info">
                        <span class="hero-card__name">{{ user.hero.name }}</span>
                        <span class="hero-card__meta">{{ user.name }} · PS: {{ user.hero.fortune_points }}</span>
                    </span>
                    <span class="hero-card__plus">+</span>
                </button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "FortunePointsManagement",
    props: ['activeUsers'],
    methods: {
        addFortunePoint(hero) {
            axios.post('panel/fortune-points/assign-fortune-point', {heroId: hero.id})
                .then(() => {
                    hero.fortune_points = (hero.fortune_points ?? 0) + 1;
                    this.$toast.success('Przydzielono punkt szczęścia!')
                })
                .catch(() => {
                    this.$toast.error('Wystąpił błąd podczas przydzielana punktu szczęścia')
                })
        }
    }
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

.page-hint {
    margin: 0 0 20px;
    font-size: 16px;
    color: var(--text-faint);
    font-style: italic;
}

.hero-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 14px;
}

.hero-card {
    text-align: left;
    padding: 16px 18px;
    border: 1px solid var(--border-accent);
    background: linear-gradient(#2a2117, #1d1710);
    cursor: pointer;
    font-family: var(--font-body), serif;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: border-color 0.2s ease, background 0.2s ease;
}

.hero-card:hover {
    border-color: var(--gold);
    background: #332714;
}

.hero-card__marker {
    width: 11px;
    height: 11px;
    background: var(--gold);
    transform: rotate(45deg);
    flex: none;
}

.hero-card__info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.hero-card__name {
    font-size: 18px;
    color: var(--text-body);
}

.hero-card__meta {
    font-size: 14px;
    color: var(--text-faint-alt);
    margin-top: 2px;
}

.hero-card__plus {
    font-family: var(--font-heading), serif;
    font-size: 18px;
    color: var(--gold);
}

@media (max-width: 640px) {
    .page-header,
    .page-content {
        padding-left: 16px;
        padding-right: 16px;
    }
}
</style>
