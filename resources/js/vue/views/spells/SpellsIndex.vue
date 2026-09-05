<template>
    <div class="spells-page">
        <div class="page-content">
            <div class="search-box">
                <span class="search-box__icon">⌕</span>
                <input
                    v-model="searchValue"
                    type="text"
                    placeholder="Przeszukaj zakazane arkana…"
                    class="search-box__input"
                    @input="getSpells"
                >
            </div>

            <div class="grimoire-title">
                <h1 class="grimoire-title__heading">KSIĘGA CZARÓW</h1>
                <div class="grimoire-title__divider">
                    <div class="grimoire-title__line"></div>
                    <span class="grimoire-title__icon">☠</span>
                    <div class="grimoire-title__line"></div>
                </div>
            </div>

            <div v-for="(specializations, type) in groupedSpells" :key="type" class="spell-type-group">

                <div class="spell-type-group__header">
                    <div class="spell-type-group__line"></div>
                    <div class="spell-type-group__badge">
                        <h2 class="spell-type-group__title">{{ type }}</h2>
                    </div>
                </div>

                <div v-for="(spellsInSpec, specName) in specializations" :key="specName" class="spell-spec-group">

                    <div class="spell-spec-group__header">
                        <span class="spell-spec-group__icon">✦</span>
                        <h3 v-if="specName && specName !== 'null'" class="spell-spec-group__title">{{ specName }}</h3>
                        <h3 v-else class="spell-spec-group__title spell-spec-group__title--muted">Wiedza Powszechna</h3>
                    </div>

                    <div class="spell-grid">
                        <div v-for="spell in spellsInSpec" :key="spell.id" class="spell-card">

                            <div class="spell-card__header">
                                <h4 class="spell-card__name">{{ spell.name }}</h4>
                                <div class="spell-card__badge" title="Wymagany poziom mocy">
                                    WPM {{ spell.casting_number }}
                                </div>
                            </div>

                            <div class="spell-card__meta">
                                <div class="spell-card__meta-row">
                                    <span class="spell-card__meta-label">Inkantacja</span>
                                    <span class="spell-card__meta-value">{{ spell.casting_duration }}</span>
                                </div>
                                <div class="spell-card__meta-row spell-card__meta-row--bordered">
                                    <span class="spell-card__meta-label">Składnik</span>
                                    <span class="spell-card__meta-ingredient">{{ spell.ingredient || 'Brak' }}</span>
                                </div>
                            </div>

                            <div class="description-box">
                                {{ spell.description }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "SpellsIndex",
    data() {
        return {
            loading: false,
            spells: [],
            searchValue: '',
            debounceTimer: null,
        }
    },
    created() {
        this.getSpells()
    },
    computed: {
        spellsLength() {
            return this.spells.length;
        },
        groupedSpells() {
            return this.spells.reduce((acc, spell) => {
                const type = spell.type || 'Inne';
                const spec = spell.specialization || 'Ogólne';
                if (!acc[type]) acc[type] = {};
                if (!acc[type][spec]) acc[type][spec] = [];
                acc[type][spec].push(spell);
                return acc;
            }, {});
        }
    },
    methods: {
        getSpells() {
            this.loading = true;
            if (this.debounceTimer) clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                axios.get('zaklecia/get-spells?search=' + this.searchValue)
                    .then(response => { this.spells = response.data; })
                    .finally(() => { this.loading = false; })
            }, 300);
        }
    }
}
</script>

<style scoped>
.spells-page {
    background: var(--bg-base);
    background-image: var(--bg-base-gradient);
    min-height: 100%;
}

.page-content {
    max-width: 1240px;
    margin: 0 auto;
    padding: 26px 34px 60px;
}

.search-box {
    display: flex;
    align-items: center;
    border: 1px solid var(--border-default);
    background: var(--bg-inset);
    max-width: 480px;
    margin: 0 auto 48px;
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

.grimoire-title {
    text-align: center;
    margin-bottom: 56px;
}

.grimoire-title__heading {
    margin: 0;
    font-family: var(--font-heading), serif;
    font-size: 42px;
    font-weight: 700;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: var(--magic-accent);
}

.grimoire-title__divider {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 16px;
    gap: 24px;
}

.grimoire-title__line {
    height: 1px;
    width: 120px;
    background: linear-gradient(90deg, transparent, var(--gold-muted));
}

.grimoire-title__divider .grimoire-title__line:last-child {
    background: linear-gradient(90deg, var(--gold-muted), transparent);
}

.grimoire-title__icon {
    color: var(--gold-muted);
    font-size: 22px;
}

.spell-type-group {
    margin-bottom: 60px;
}

.spell-type-group__header {
    position: relative;
    display: flex;
    justify-content: center;
    margin-bottom: 40px;
}

.spell-type-group__line {
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    height: 1px;
    background: rgba(123, 17, 19, .3);
}

.spell-type-group__badge {
    position: relative;
    background: var(--bg-base);
    padding: 8px 32px;
    border: 2px solid var(--magic-accent);
}

.spell-type-group__title {
    margin: 0;
    font-family: var(--font-heading), serif;
    font-size: 28px;
    font-weight: 700;
    color: var(--magic-accent);
    text-transform: uppercase;
    letter-spacing: .16em;
}

.spell-spec-group {
    margin-bottom: 40px;
}

.spell-spec-group__header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-left: 16px;
}

.spell-spec-group__icon {
    color: var(--gold-muted);
    font-size: 14px;
}

.spell-spec-group__title {
    margin: 0;
    font-family: var(--font-heading), serif;
    font-size: 22px;
    font-style: italic;
    color: var(--gold-muted);
}

.spell-spec-group__title--muted {
    color: var(--text-faint);
}

.spell-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
}

.spell-card {
    display: flex;
    flex-direction: column;
    background: var(--bg-inset);
    border: 1px solid var(--border-default);
    border-left: 4px solid var(--magic-accent);
    box-shadow: 0 20px 40px -20px #000;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.spell-card:hover {
    transform: scale(1.02);
    box-shadow: 0 20px 40px -12px rgba(0, 0, 0, .6);
}

.spell-card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background: var(--bg-panel);
    border-bottom: 1px solid var(--border-subtle);
}

.spell-card__name {
    margin: 0;
    font-family: var(--font-heading), serif;
    font-size: 18px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--text-body);
}

.spell-card__badge {
    background: var(--magic-accent);
    color: var(--text-body);
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 700;
}

.spell-card__meta {
    padding: 16px 20px;
    background: var(--bg-inset);
    font-size: 14px;
}

.spell-card__meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
}

.spell-card__meta-row--bordered {
    border-top: 1px solid var(--border-subtle);
    align-items: flex-start;
}

.spell-card__meta-label {
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: -.01em;
    color: var(--text-faint);
    font-size: 13px;
}

.spell-card__meta-value {
    color: var(--text-muted-alt);
}

.spell-card__meta-ingredient {
    color: var(--gold-muted);
    font-style: italic;
    text-align: right;
    max-width: 150px;
}

.description-box {
    flex-grow: 1;
    padding: 16px 20px;
    background: var(--bg-inset-alt);
    color: var(--text-faint-alt);
    font-size: 15px;
    line-height: 1.5;
    font-style: italic;
    border-top: 1px solid var(--border-subtle);
    min-height: 140px;
    max-height: 140px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: var(--magic-accent) var(--bg-inset-alt);
}

.description-box::-webkit-scrollbar {
    width: 3px;
}
.description-box::-webkit-scrollbar-track {
    background: var(--bg-inset-alt);
}
.description-box::-webkit-scrollbar-thumb {
    background: var(--magic-accent);
}

@media (max-width: 640px) {
    .page-content {
        padding-left: 16px;
        padding-right: 16px;
    }

    .search-box {
        max-width: none;
    }

    .grimoire-title__heading {
        font-size: 28px;
        letter-spacing: .1em;
    }

    .grimoire-title__line {
        width: 40px;
    }

    .grimoire-title__divider {
        gap: 12px;
    }
}
</style>
