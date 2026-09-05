<template>
    <div>
        <div class="page-header">
            <div class="page-header__inner">
                <div class="page-header__titles">
                    <div class="page-header__eyebrow">KOMPENDIUM</div>
                    <h1 class="page-header__title">Opancerzenie</h1>
                </div>
                <div class="search-box">
                    <span class="search-box__icon">⌕</span>
                    <input
                        v-model="searchValue"
                        type="text"
                        placeholder="Wyszukaj pancerz…"
                        class="search-box__input"
                        @input="getArmors"
                    >
                </div>
            </div>
        </div>

        <div class="page-content">
            <template v-if="leatherArmors.length > 0">
                <div class="section-label"><span>OPANCERZENIE SKÓRZANE</span><span class="section-label-line"></span></div>
                <div class="armor-grid">
                    <div v-for="armor in leatherArmors" :key="armor.name" class="armor-card">
                        <div class="armor-card__top">
                            <h3 class="armor-card__name">{{ armor.name }}</h3>
                            <span class="armor-card__price">{{ $calculatePrice(armor.price) }}</span>
                        </div>
                        <div class="armor-card__divider"></div>
                        <div class="armor-card__stats">
                            <div>
                                <div class="stat-label">PZ</div>
                                <div class="stat-value">{{ armor.armor_points }}</div>
                            </div>
                            <div>
                                <div class="stat-label">OBC.</div>
                                <div class="stat-value">{{ armor.loading ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="stat-label">DOSTĘPNOŚĆ</div>
                                <div class="stat-value stat-value--muted">{{ armor.availability ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="armor-card__locations">
                            <span v-if="armor.locations.length < 1" class="stat-value--muted">-</span>
                            <span v-for="(location, index) in armor.locations" :key="index" class="location-pill">{{ location.name }}</span>
                        </div>
                    </div>
                </div>
            </template>

            <template v-if="mailArmors.length > 0">
                <div class="section-label section-label--spaced"><span>OPANCERZENIE KOLCZE</span><span class="section-label-line"></span></div>
                <div class="armor-grid">
                    <div v-for="armor in mailArmors" :key="armor.name" class="armor-card">
                        <div class="armor-card__top">
                            <h3 class="armor-card__name">{{ armor.name }}</h3>
                            <span class="armor-card__price">{{ $calculatePrice(armor.price) }}</span>
                        </div>
                        <div class="armor-card__divider"></div>
                        <div class="armor-card__stats">
                            <div>
                                <div class="stat-label">PZ</div>
                                <div class="stat-value">{{ armor.armor_points }}</div>
                            </div>
                            <div>
                                <div class="stat-label">OBC.</div>
                                <div class="stat-value">{{ armor.loading ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="stat-label">DOSTĘPNOŚĆ</div>
                                <div class="stat-value stat-value--muted">{{ armor.availability ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="armor-card__locations">
                            <span v-if="armor.locations.length < 1" class="stat-value--muted">-</span>
                            <span v-for="(location, index) in armor.locations" :key="index" class="location-pill">{{ location.name }}</span>
                        </div>
                    </div>
                </div>
            </template>

            <template v-if="plateArmors.length > 0">
                <div class="section-label section-label--spaced"><span>OPANCERZENIE PŁYTOWE</span><span class="section-label-line"></span></div>
                <div class="armor-grid">
                    <div v-for="armor in plateArmors" :key="armor.name" class="armor-card">
                        <div class="armor-card__top">
                            <h3 class="armor-card__name">{{ armor.name }}</h3>
                            <span class="armor-card__price">{{ $calculatePrice(armor.price) }}</span>
                        </div>
                        <div class="armor-card__divider"></div>
                        <div class="armor-card__stats">
                            <div>
                                <div class="stat-label">PZ</div>
                                <div class="stat-value">{{ armor.armor_points }}</div>
                            </div>
                            <div>
                                <div class="stat-label">OBC.</div>
                                <div class="stat-value">{{ armor.loading ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="stat-label">DOSTĘPNOŚĆ</div>
                                <div class="stat-value stat-value--muted">{{ armor.availability ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="armor-card__locations">
                            <span v-if="armor.locations.length < 1" class="stat-value--muted">-</span>
                            <span v-for="(location, index) in armor.locations" :key="index" class="location-pill">{{ location.name }}</span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    name: "ArmorsIndex",
    data() {
        return {
            loading: false,
            leatherArmors: [],
            mailArmors: [],
            plateArmors: [],
            searchValue: '',
            debounceTimer: null,
        }
    },
    created() {
        this.getArmors()
    },
    methods: {
        getArmors() {
            if (this.debounceTimer) {
                clearTimeout(this.debounceTimer)
            }

            this.debounceTimer = setTimeout(() => {
                this.loading = true;

                axios.get('opancerzenie/get-armors?search=' + this.searchValue)
                    .then(response => {
                        this.leatherArmors = response.data.leather;
                        this.mailArmors = response.data.mail;
                        this.plateArmors = response.data.plate;
                    })
                    .catch(error => {
                        console.log(error);
                        this.$toast.error('Nie udało się pobrać opancerzeń')
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
    font-family: var(--font-heading);
    font-size: 11px;
    letter-spacing: .24em;
    color: var(--text-faint);
    margin-bottom: 6px;
}

.page-header__title {
    margin: 0;
    font-family: var(--font-heading);
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
    font-family: var(--font-body);
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
    font-family: var(--font-heading);
    font-size: 11px;
    letter-spacing: .2em;
    color: var(--text-faint);
}

.section-label-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, var(--border-default), transparent);
}

.armor-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 14px;
}

.armor-card {
    border: 1px solid var(--border-default);
    border-top: 1px solid var(--border-accent);
    background: linear-gradient(#1d1913, #161209);
    padding: 18px 20px;
    transition: border-color 0.2s ease;
}

.armor-card:hover {
    border-color: var(--border-accent-hover);
}

.armor-card__top {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 12px;
}

.armor-card__name {
    margin: 0;
    font-family: var(--font-heading);
    font-size: 18px;
    font-weight: 600;
    color: var(--text-body);
    letter-spacing: .04em;
}

.armor-card__price {
    color: var(--gold);
    font-size: 16px;
    white-space: nowrap;
}

.armor-card__divider {
    height: 1px;
    background: var(--border-subtle);
    margin: 12px 0;
}

.armor-card__stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.stat-label {
    font-family: var(--font-heading);
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

.armor-card__locations {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 14px;
}

.location-pill {
    display: inline-block;
    border: 1px solid var(--border-frame);
    background: #221d16;
    color: var(--text-muted-alt);
    padding: 3px 9px;
    font-size: 13px;
    letter-spacing: .03em;
}
</style>
