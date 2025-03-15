<template>
    <v-dialog v-model="dialog" max-width="800px">
        <template v-slot:activator="{ on, attrs }">
            <button class="button-panel-custom button-orange for-vue" @click="dialog = true">
                Dodaj broń
            </button>
        </template>
        <v-card class="bg-dark-custom border-custom shadow-custom">
            <v-card-title class="text-h5 font-weight-bold gold-text text-center v-card-title">
                Dodaj Broń
            </v-card-title>
            <template v-if="!isLoading">
                <v-card-text class="v-card-text">
                    <v-row class="mb-5">
                        <v-col cols="12">
                            <v-select
                                v-model="newWeapon.weaponId"
                                :options="weapons"
                                :reduce="weapon => weapon.id"
                                label="name"
                                placeholder="Wybierz rodzaj broni"
                                class="custom-select w-full"
                            ></v-select>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                v-model="newWeapon.additionalWeaponName"
                                label="Dodatkowa nazwa broni (np. kordelas dla broni jednoręcznej)"
                                class="custom-input"
                                variant="filled"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
            </template>
            <template v-else>
                <div class="text-center py-8">
                    <v-progress-circular indeterminate color="amber"></v-progress-circular>
                    <p class="mt-4 text-amber-400">Ładowanie broni...</p>
                </div>
            </template>
            <v-card-actions class="justify-center">
                <button @click="dialog = false" class="cancel-button">Anuluj</button>
                <button @click="addWeapon" class="add-button">Dodaj</button>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>

export default {
    name: 'AddWeaponModal',
    props: {
        heroId: {
            type: Number
        }
    },
    data() {
        return {
            dialog: false,
            filteredWeapons: null,
            weapons: null,
            isLoading: false,

            newWeapon: {
                weaponId: null,
                additionalWeaponName: '',
            },
        }
    },
    methods: {
        getWeapons() {
            this.isLoading = true;
            axios.get('bronie/get-weapons?grouped=true')
                .then(response => {
                    this.weapons = response.data
                    console.log(this.weapons)
                })
                .catch(error => {
                    console.log(error)
                    this.$toast.error('Wystąpił błąd podczas pobierania broni')
                })
                .finally(() => {
                    this.isLoading = false
                })
        },
        addWeapon() {
                axios.post('karta-postaci/' + this.heroId + '/add-weapon', this.newWeapon)
                    .then((response) => {
                        this.dialog = false;
                        this.newWeapon = {weaponId: null, additionalWeaponName: ''};
                        this.$toast.success('Pomyślnie odano broń')
                        this.$emit('weapon-added', response.data)
                    })
                    .catch(error => {
                        console.error(error);
                        this.$toast.error(error.response.data.message, {duration: 10000})
                    });
        }
    },
    watch: {
        dialog: function () {
            if (this.dialog) {
                this.getWeapons();
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

.custom-input ::v-deep .v-input__control {
    background-color: #3b3a36;
    border: 1px solid #c09f80;
    color: #fff;
    border-radius: 6px;
}

.custom-input ::v-deep {
    color: #c09f80;
}

.cancel-button {
    background-color: transparent;
    color: #999;
    border: 1px solid #999;
    padding: 10px 20px;
    font-weight: bold;
    border-radius: 6px;
    transition: 0.3s;
}

.cancel-button:hover {
    color: #c09f80;
    border-color: #c09f80;
}

.add-button {
    background-color: #c09f80;
    color: #1c1c1c;
    padding: 10px 20px;
    font-weight: bold;
    border-radius: 6px;
    transition: 0.3s;
}

.add-button:hover {
    background-color: #d4b38a;
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

.v-autocomplete :deep(.v-input__control) {
    background-color: #3b3a36;
    color: #fff;
    border-radius: 6px;
    padding: 10px;
}

.v-autocomplete :deep(.v-input__control:hover) {
    border-color: #d4b38a;
}

.v-autocomplete :deep(.v-input__control:focus) {
    border-color: #d4b38a;
    box-shadow: 0 0 5px #d4b38a;
}


.v-autocomplete :deep(.v-list) {
    background-color: #2a2926;
    border: 1px solid #c09f80;
    color: #fff;
    border-radius: 6px;
}

.v-autocomplete :deep(.v-list-item) {
    background-color: transparent;
    color: #c09f80;
}

.v-autocomplete :deep(.v-list-item:hover) {
    background-color: #3b3a36;
    color: #d4b38a;
}

.v-autocomplete :deep(.v-label) {
    color: #c09f80;
}

.v-autocomplete :deep(.v-label.v-label--active) {
    color: #d4b38a;
}

</style>
