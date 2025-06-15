<template>
    <div id="grid-container">
        <div id="header">
            <h1 class="text-[#d4af37]">{{ headerText }}</h1>
        </div>
        <div id="main">
            <template v-if="step === 1">
                <h2 class="mb-2 text-[#d4af37]">Dodaj punkty</h2>
                <v-text-field
                    v-model="newExp"
                    type="number"
                    @change="addExp"
                ></v-text-field>
                <template v-if="addedExp.length > 0">
                    <h2>Dodane punkty</h2>
                    <ul class="text-center">
                        <li v-for="exp in addedExp">{{ exp }}</li>
                    </ul>
                    <span class="mb-2"> Suma: {{ totalCommonExp }}</span>
                    <button class="simple-button" @click="step = 2">
                        Zatwierdź
                    </button>
                </template>
            </template>
            <template v-if="step === 2">
                <table class="text-center">
                    <thead>
                        <tr>
                            <th class="p-2">Użytkownik</th>
                            <th class="p-2">Bohater</th>
                            <th class="p-2">Liczba PD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in activeUsers">
                            <td class="p-2">{{ user.name }}</td>
                            <td class="p-2">{{ user.hero.name }}</td>
                            <td class="p-2">
                                <v-text-field
                                    v-model="heroesExp[user.hero.id]"
                                ></v-text-field>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button class="simple-button" @click="saveExperience">
                    Zatwierdź
                </button>
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
        }
    },
    computed: {
        headerText() {
            switch (this.step) {
                case 1:
                    return 'Punkty doświadczenia wspólne dla całej drużyny'
                case 2:
                    return 'Punkty dla poszczególnych bohaterów'
            }
        },
        totalCommonExp() {
            return this.addedExp.reduce((partialSum, exp) => partialSum + parseInt(exp), 0)
        }
    },
    methods: {
        addExp() {
            this.addedExp.push(this.newExp);
            this.newExp = '';
        },
        saveExperience() {
            axios.post('panel/experience/save-experience', {commonExperience: this.totalCommonExp, heroesExperience: this.heroesExp})
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
#grid-container {
    display: grid;
    grid-template: 40px auto / 1fr;
    grid-row-gap: 0;
    justify-items: center;
}
#header {
    text-align: center;
}
#main {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
h1 {
    font-size: 20px;
}
h2 {
    font-size: 18px;
}
</style>
