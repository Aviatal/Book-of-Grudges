<template>
    <div>
        <div class="page-header">
            <div class="page-header__inner">
                <div class="page-header__titles">
                    <div class="page-header__eyebrow">KOMPENDIUM</div>
                    <h1 class="page-header__title">Zdolności</h1>
                </div>
                <div class="search-box">
                    <span class="search-box__icon">⌕</span>
                    <input
                        v-model="searchValue"
                        type="text"
                        placeholder="Wyszukaj zdolność…"
                        class="search-box__input"
                        @input="getTalents"
                    >
                </div>
            </div>
        </div>

        <div class="page-content">
            <div v-if="talentsLength > 0" class="talent-grid">
                <div v-for="talent in talents" :key="talent.id" class="talent-card">
                    <h3 class="talent-card__title">{{ talent.name }}</h3>
                    <div class="talent-card__divider"></div>
                    <p class="talent-card__description">{{ talent.description }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "TalentsIndex",
    data() {
        return {
            loading: false,
            talents: [],
            searchValue: '',
            debounceTimer: null,
        }
    },
    created() {
        this.getTalents()
    },
    computed: {
        talentsLength() {
            return this.talents.length;
        }
    },
    methods: {
        getTalents() {
            if (this.debounceTimer) {
                clearTimeout(this.debounceTimer)
            }

            this.debounceTimer = setTimeout(() => {
                axios.get('zdolnosci/get-talents?search=' + this.searchValue)
                    .then(response => {
                        this.talents = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                        this.$toast.error('Nie udało się pobrać umiejętności')
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            })
        },
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
    transition: border-color 0.2s ease;
}

.talent-card:hover {
    border-color: var(--border-accent-hover);
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
    font-size: 16px;
    line-height: 1.5;
    color: var(--text-muted);
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
