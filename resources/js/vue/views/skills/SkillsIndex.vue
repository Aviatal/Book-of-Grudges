<template>
    <div>
        <div class="page-header">
            <div class="page-header__inner">
                <div class="page-header__titles">
                    <div class="page-header__eyebrow">KOMPENDIUM</div>
                    <h1 class="page-header__title">Umiejętności</h1>
                </div>
                <div class="search-box">
                    <span class="search-box__icon">⌕</span>
                    <input
                        v-model="searchValue"
                        type="text"
                        placeholder="Wyszukaj umiejętność…"
                        class="search-box__input"
                        @input="getSkills"
                    >
                </div>
            </div>
        </div>

        <div class="page-content">
            <div v-show="skillsLength > 0" class="skill-grid">
                <div v-for="skill in skills" :key="skill.id" class="skill-card">
                    <div class="skill-card__top">
                        <h3 class="skill-card__name">{{ skill.name }}</h3>
                        <span class="skill-card__type">{{ skill.type }}</span>
                    </div>
                    <div class="skill-card__characteristic">cecha: {{ skill.characteristic }}</div>
                    <div class="skill-card__divider"></div>
                    <p class="skill-card__description">{{ skill.description }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SkillsIndex",
    data() {
        return {
            loading: false,
            skills: [],
            searchValue: '',
            debounceTimer: null,
        }
    },
    created() {
        this.getSkills()
    },
    computed: {
        skillsLength() {
            return this.skills.length;
        }
    },
    methods: {
        getSkills() {
            if (this.debounceTimer) {
                clearTimeout(this.debounceTimer);
            }

            this.debounceTimer = setTimeout(() => {
                axios.get('umiejetnosci/get-skills?search=' + this.searchValue)
                    .then(response => {
                        this.skills = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                        this.$toast.error('Nie udało się pobrać umiejętności')
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            }, 50)
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

.skill-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 14px;
}

.skill-card {
    border: 1px solid var(--border-default);
    border-top: 1px solid var(--border-accent);
    background: linear-gradient(#1d1913, #161209);
    padding: 18px 20px;
    transition: border-color 0.2s ease;
}

.skill-card:hover {
    border-color: var(--border-accent-hover);
}

.skill-card__top {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 12px;
}

.skill-card__name {
    margin: 0;
    font-family: var(--font-heading), serif;
    font-size: 17px;
    font-weight: 600;
    color: var(--text-body);
    letter-spacing: .04em;
}

.skill-card__type {
    border: 1px solid var(--border-frame);
    background: #221d16;
    color: var(--text-muted-alt);
    padding: 2px 8px;
    font-size: 12px;
    white-space: nowrap;
}

.skill-card__characteristic {
    margin-top: 6px;
    font-size: 14px;
    color: var(--gold-muted);
    letter-spacing: .06em;
}

.skill-card__divider {
    height: 1px;
    background: var(--border-subtle);
    margin: 12px 0;
}

.skill-card__description {
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
