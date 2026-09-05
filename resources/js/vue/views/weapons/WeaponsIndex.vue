<template>
    <div>
        <div class="page-header">
            <div class="page-header__inner">
                <div class="page-header__titles">
                    <div class="page-header__eyebrow">KOMPENDIUM</div>
                    <h1 class="page-header__title">Bronie</h1>
                </div>
                <div class="search-box">
                    <span class="search-box__icon">⌕</span>
                    <input
                        v-model="searchValue"
                        type="text"
                        placeholder="Wyszukaj broń…"
                        class="search-box__input"
                        @input="getWeapons"
                    >
                </div>
            </div>
        </div>

        <div class="page-content">
            <template v-if="coldWeaponsLength > 0">
                <div class="section-label"><span>BROŃ BIAŁA</span><span class="section-label-line"></span></div>
                <div class="weapon-grid">
                    <div v-for="weapon in coldWeapons" :key="weapon.name" class="weapon-card">
                        <div class="weapon-card__top">
                            <h3 class="weapon-card__name">{{ weapon.name }}</h3>
                            <span class="weapon-card__price">{{ $calculatePrice(weapon.price) }}</span>
                        </div>
                        <div class="weapon-card__divider"></div>
                        <div class="weapon-card__stats" style="grid-template-columns: repeat(3, 1fr)">
                            <div>
                                <div class="stat-label">SIŁA</div>
                                <div class="stat-value">{{ weapon.power }}</div>
                            </div>
                            <div>
                                <div class="stat-label">OBC.</div>
                                <div class="stat-value">{{ weapon.loading ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="stat-label">DOSTĘPNOŚĆ</div>
                                <div class="stat-value stat-value--muted">{{ weapon.availability ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="weapon-card__traits">
                            <span v-if="weapon.traits.length < 1" class="stat-value--muted">-</span>
                            <span v-for="(trait, index) in weapon.traits" :key="index" class="trait-pill">{{ trait.name }}</span>
                        </div>
                    </div>
                </div>
            </template>

            <template v-if="rangedWeaponsLength > 0">
                <div class="section-label section-label--spaced"><span>BROŃ STRZELECKA</span><span class="section-label-line"></span></div>
                <div class="weapon-grid">
                    <div v-for="weapon in rangedWeapons" :key="weapon.name" class="weapon-card">
                        <div class="weapon-card__top">
                            <h3 class="weapon-card__name">{{ weapon.name }}</h3>
                            <span class="weapon-card__price">{{ $calculatePrice(weapon.price) }}</span>
                        </div>
                        <div class="weapon-card__divider"></div>
                        <div class="weapon-card__stats" style="grid-template-columns: repeat(4, 1fr)">
                            <div>
                                <div class="stat-label">SIŁA</div>
                                <div class="stat-value">{{ weapon.power }}</div>
                            </div>
                            <div>
                                <div class="stat-label">ZASIĘG</div>
                                <div class="stat-value">{{ weapon.short_range }}/{{ weapon.long_range }}</div>
                            </div>
                            <div>
                                <div class="stat-label">PRZEŁ.</div>
                                <div class="stat-value">{{ weapon.reload_time }}</div>
                            </div>
                            <div>
                                <div class="stat-label">OBC.</div>
                                <div class="stat-value">{{ weapon.loading ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="weapon-card__traits">
                            <span v-if="weapon.traits.length < 1" class="stat-value--muted">-</span>
                            <span v-for="(trait, index) in weapon.traits" :key="index" class="trait-pill">{{ trait.name }}</span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    name: "WeaponsIndex",
    data() {
        return {
            loading: false,
            coldWeapons: [],
            rangedWeapons: [],
            searchValue: '',
            debounceTimer: null,
        }
    },
    created() {
        this.getWeapons()
    },
    computed: {
        rangedWeaponsLength() {
            return this.rangedWeapons.length;
        },
        coldWeaponsLength() {
            return this.coldWeapons.length;
        },
    },
    methods: {
        getWeapons() {
            if (this.debounceTimer) {
                clearTimeout(this.debounceTimer)
            }

            this.debounceTimer = setTimeout(() => {
                axios.get('bronie/get-weapons?search=' + this.searchValue)
                    .then(response => {
                        this.coldWeapons = response.data.cold;
                        this.rangedWeapons = response.data.ranged;
                    })
                    .catch(error => {
                        console.log(error);
                        this.$toast.error('Nie udało się pobrać broni')
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            }, 50)
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
    max-width: 1240px;
    margin: 0 auto;
    display: flex;
    align-items: flex-end;
    gap: 24px;
    flex-wrap: wrap;
}

.page-header__titles {
    flex: 1;
    min-width: 260px;
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

.search-box {
    display: flex;
    align-items: center;
    border: 1px solid var(--border-default);
    background: var(--bg-inset);
    min-width: 320px;
}

.search-box__icon {
    padding: 0 12px;
    color: var(--text-faint);
}

.search-box__input {
    flex: 1;
    background: transparent;
    border: none;
    padding: 12px 12px 12px 0;
    color: var(--text-body);
    font-family: var(--font-body), serif;
    font-size: 16px;
    outline: none;
}

.page-content {
    max-width: 1240px;
    margin: 0 auto;
    padding: 26px 34px 60px;
}

.section-label {
    display: flex;
    align-items: center;
    gap: 14px;
    margin: 0 0 16px;
}

.section-label--spaced {
    margin-top: 32px;
}

.section-label span:first-child {
    font-family: var(--font-heading), serif;
    font-size: 11px;
    letter-spacing: .2em;
    color: var(--text-faint);
}

.section-label-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, var(--border-default), transparent);
}

.weapon-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 14px;
}

.weapon-card {
    border: 1px solid var(--border-default);
    border-top: 1px solid var(--border-accent);
    background: linear-gradient(#1d1913, #161209);
    padding: 18px 20px;
    transition: border-color 0.2s ease;
}

.weapon-card:hover {
    border-color: var(--border-accent-hover);
}

.weapon-card__top {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 12px;
}

.weapon-card__name {
    margin: 0;
    font-family: var(--font-heading), serif;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-body);
    letter-spacing: .04em;
}

.weapon-card__price {
    color: var(--gold);
    font-size: 16px;
    white-space: nowrap;
}

.weapon-card__divider {
    height: 1px;
    background: var(--border-subtle);
    margin: 12px 0;
}

.weapon-card__stats {
    display: grid;
    gap: 10px;
}

.stat-label {
    font-family: var(--font-heading), serif;
    font-size: 9px;
    letter-spacing: .16em;
    color: var(--text-faint-alt);
}

.stat-value {
    font-size: 19px;
    color: var(--text-body);
}

.stat-value--muted {
    font-size: 15px;
    color: var(--text-muted);
}

.weapon-card__traits {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 14px;
}

.trait-pill {
    display: inline-block;
    border: 1px solid var(--border-frame);
    background: #221d16;
    color: var(--text-muted-alt);
    padding: 3px 9px;
    font-size: 13px;
    letter-spacing: .03em;
}

@media (max-width: 640px) {
    .page-header,
    .page-content {
        padding-left: 16px;
        padding-right: 16px;
    }

    .page-header__inner {
        flex-direction: column;
        align-items: stretch;
    }

    .search-box {
        min-width: 0;
        width: 100%;
    }
}
</style>
