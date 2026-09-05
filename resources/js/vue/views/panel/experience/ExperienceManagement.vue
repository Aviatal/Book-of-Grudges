<template>
    <div>
        <div class="page-header">
            <div class="page-header__inner">
                <div class="page-header__eyebrow">PANEL MISTRZA GRY</div>
                <h1 class="page-header__title">Rozdanie punktów doświadczenia</h1>
            </div>
        </div>

        <div class="page-content">
            <template v-if="step === 1">
                <div class="step-card">
                    <div class="section-label"><span>KROK 1 — PD WSPÓLNE DLA DRUŻYNY</span><span class="section-label-line"></span></div>
                    <div class="step-card__row">
                        <label class="field">
                            <span class="field__label">DODAJ PUNKTY</span>
                            <v-text-field
                                v-model="newExp"
                                type="number"
                                variant="filled"
                                hide-details
                                @change="addExp"
                            ></v-text-field>
                        </label>
                        <button class="simple-button" @click="addExp">DODAJ</button>
                    </div>
                    <template v-if="addedExp.length > 0">
                        <div class="added-exp">
                            <span v-for="(exp, index) in addedExp" :key="index" class="added-exp__pill">{{ exp }}</span>
                            <span class="added-exp__label">suma</span>
                            <span class="added-exp__total">{{ totalCommonExp }}</span>
                        </div>
                        <div class="step-card__actions">
                            <button class="simple-button" @click="step = 2">Zatwierdź</button>
                        </div>
                    </template>
                </div>
            </template>
            <template v-if="step === 2">
                <div class="step-card">
                    <div class="section-label"><span>KROK 2 — PD DLA POSZCZEGÓLNYCH BOHATERÓW</span><span class="section-label-line"></span></div>
                    <table class="step-table">
                        <thead>
                            <tr>
                                <th>Użytkownik</th>
                                <th>Bohater</th>
                                <th>Liczba PD</th>
                                <th>Notatka</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in activeUsers" :key="user.hero.id">
                                <td>{{ user.name }}</td>
                                <td>{{ user.hero.name }}</td>
                                <td>
                                    <v-text-field
                                        v-model="heroesExp[user.hero.id]"
                                        variant="filled"
                                        hide-details
                                    ></v-text-field>
                                </td>
                                <td>
                                    <v-text-field
                                        v-model="heroesNotes[user.hero.id]"
                                        placeholder="za odegranie roli"
                                        variant="filled"
                                        hide-details
                                    ></v-text-field>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="step-card__actions">
                        <button class="simple-button" @click="saveExperience">Zatwierdź rozdanie</button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        activeUsers: {
            type: Array,
        }
    },
    data() {
        return {
            step: 1,
            newExp: '',
            addedExp: [],
            heroesExp: {},
            heroesNotes: {},
        }
    },
    computed: {
        totalCommonExp() {
            return this.addedExp.reduce((partialSum, exp) => partialSum + parseInt(exp), 0)
        }
    },
    methods: {
        addExp() {
            if (!this.newExp) {
                return;
            }
            this.addedExp.push(this.newExp);
            this.newExp = '';
        },
        saveExperience() {
            axios.post('panel/experience/save-experience', {commonExperience: this.totalCommonExp, heroesExperience: this.heroesExp, heroesNotes: this.heroesNotes})
                .then(() => {
                    this.$toast.success('Dodano punkty doświadczenia bohaterom')
                    this.step = 1;
                    this.addedExp = [];
                })
                .catch(error => {
                    this.$toast.error('Wystąpił błąd poczas dodawania punktów doświadczenia');
                    console.log(error)
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

.page-content {
    max-width: 1000px;
    margin: 0 auto;
    padding: 26px 34px 60px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.step-card {
    border: 1px solid var(--border-default);
    border-top: 1px solid var(--border-accent);
    background: var(--bg-panel);
    padding: 22px;
}

.section-label {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 16px;
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

.step-card__row {
    display: flex;
    gap: 12px;
    align-items: flex-end;
    flex-wrap: wrap;
}

.field {
    display: block;
    flex: 1;
    min-width: 200px;
}

.field__label {
    display: block;
    font-family: var(--font-heading);
    font-size: 10px;
    letter-spacing: .18em;
    color: var(--text-faint);
    margin-bottom: 6px;
}

.added-exp {
    margin-top: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.added-exp__pill {
    border: 1px solid var(--border-frame);
    background: #221d16;
    color: var(--text-muted-alt);
    padding: 4px 10px;
    font-size: 14px;
}

.added-exp__label {
    color: var(--text-faint-alt);
    margin-left: 6px;
}

.added-exp__total {
    color: var(--gold);
    font-size: 20px;
}

.step-card__actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 18px;
}

.step-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 16px;
}

.step-table th {
    text-align: left;
    padding: 10px 12px;
    font-family: var(--font-heading);
    font-size: 10px;
    letter-spacing: .16em;
    color: var(--text-faint);
    font-weight: 600;
    border-bottom: 1px solid var(--border-default);
}

.step-table td {
    padding: 12px;
    color: var(--text-muted-alt);
    border-bottom: 1px solid var(--border-subtle);
}
</style>
