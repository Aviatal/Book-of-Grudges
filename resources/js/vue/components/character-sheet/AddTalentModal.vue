<template>
    <v-dialog v-model="dialog" max-width="800px">
        <template v-slot:activator="{ on, attrs }">
            <button class="button-panel-custom button-orange for-vue" @click="dialog = true">
                Dodaj zdolność
            </button>
        </template>
        <v-card class="bg-dark-custom border-custom shadow-custom">
            <v-card-title class="text-h5 font-weight-bold gold-text text-center v-card-title">
                Dodaj zdolność
            </v-card-title>
            <template v-if="!isLoading">
                <v-card-text class="v-card-text">
                    <v-row class="mb-5">
                        <v-col cols="12">
                            <v-select
                                v-model="newTalentId"
                                :options="talents"
                                :reduce="talent => talent.id"
                                label="name"
                                placeholder="Wybierz zdolność"
                                class="custom-select w-full"
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-card-text>
            </template>
            <template v-else>
                <div class="text-center py-8">
                    <v-progress-circular indeterminate color="amber"></v-progress-circular>
                    <p class="mt-4 text-amber-400">Pobieranie zdolności...</p>
                </div>
            </template>
            <v-card-actions class="justify-center">
                <button @click="dialog = false" class="cancel-button">Anuluj</button>
                <button @click="addTalent" class="add-button">Dodaj</button>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>

export default {
    name: 'AddTalentModal',
    props: {
        heroId: {
            type: Number
        }
    },
    data() {
        return {
            dialog: false,
            talents: null,
            isLoading: false,

            newTalentId: null
        }
    },
    methods: {
        getTalents() {
            this.isLoading = true;
            axios.get('zdolnosci/get-talents?withoutOwned=' + this.heroId)
                .then(response => {
                    this.talents = response.data
                })
                .catch(error => {
                    console.log(error)
                    this.$toast.error('Wystąpił błąd podczas pobierania zdolności')
                })
                .finally(() => {
                    this.isLoading = false
                })
        },
        addTalent() {
            axios.post('karta-postaci/' + this.heroId + '/add-talent', {talentId: this.newTalentId})
                .then((response) => {
                    this.dialog = false;
                    this.newTalentId = null;
                    this.$toast.success('Pomyślnie dodano zdolność')
                    this.$emit('talent-added', response.data)
                })
                .catch(error => {
                    console.error(error);
                    this.$toast.error(error.response.data.message, {duration: 10000})
                });
        },
    },
    watch: {
        dialog: function () {
            if (this.dialog) {
                this.getTalents();
            }
        }
    }
}
</script>

<style scoped>
.bg-dark-custom {
    background-color: #2a2926;
    border-radius: 12px;
}

.border-custom {
    border: 2px solid #c09f80;
}

.shadow-custom {
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
}

.gold-text {
    color: #d4b38a;
}

.v-card-title {
    font-size: 1.8rem;
    margin-bottom: 20px;
}

.v-card-text {
    margin: 20px;
}

.button-panel-custom {
    background-color: #c09f80;
    color: #1c1c1c;
    font-size: 1.2rem;
    font-weight: bold;
    padding: 0.8rem;
    margin: 0.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.button-panel-custom:hover {
    background-color: #d4b38a;
    transform: scale(1.1);
}

.button-panel-custom:active {
    transform: scale(0.95);
}
</style>
