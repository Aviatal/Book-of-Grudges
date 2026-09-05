<template>
    <div class="wfrp-wrapper">
        <v-card class="grudge-card" elevation="10">

            <div class="grudge-header">
                <h2>Rynek - Sprzedaż Łupów</h2>
            </div>

            <v-card-text class="grudge-body">
                <v-form @submit.prevent="submitTransaction">

                    <div class="input-group">
                        <label class="wfrp-label">Wybierz Bohatera</label>
                        <v-autocomplete
                            v-model="selectedHero"
                            :items="props.activeHeroes"
                            item-title="name"
                            item-value="id"
                            placeholder="Kto dokonuje transakcji?"
                            variant="outlined"
                            color="var(--gold)"
                            base-color="var(--text-faint)"
                            bg-color="var(--bg-inset)"
                            hide-details="auto"
                            class="wfrp-autocomplete"
                            return-object
                            :menu-props="{ contentClass: 'wfrp-autocomplete-menu' }"
                        >
                            <template v-slot:item="{ props, item }">
                                <v-list-item v-bind="props" class="wfrp-list-item">
                                    <template v-slot:append>
                                        <span class="sub-text">{{ item.raw.class }}</span>
                                    </template>
                                </v-list-item>
                            </template>
                        </v-autocomplete>
                    </div>

                    <div class="input-group mt-6">
                        <label class="wfrp-label">Przedmiot na sprzedaż</label>
                        <v-autocomplete
                            v-model="selectedItem"
                            :items="props.marketplaceItems"
                            item-title="tradeable.name"
                            item-value="id"
                            placeholder="Wpisz nazwę przedmiotu..."
                            variant="outlined"
                            color="var(--gold)"
                            base-color="var(--text-faint)"
                            bg-color="var(--bg-inset)"
                            hide-details="auto"
                            class="wfrp-autocomplete"
                            return-object
                            :menu-props="{ contentClass: 'wfrp-autocomplete-menu' }"
                        >
                            <template v-slot:selection="{ item }">
                                <span style="color: var(--text-body)">
                                    <template v-if="item.raw.tradeable_type === 'App\\Models\\Armor'">
                                        {{ item.raw.tradeable.category }} -
                                    </template>
                                    {{ item.raw.tradeable.name }}
                                </span>
                            </template>

                            <template v-slot:item="{ props, item }">
                                <v-list-item v-bind="props" class="wfrp-list-item">
                                    <v-list-item-title>
                                        <span v-if="item.raw.tradeable_type === 'App\\Models\\Armor'"
                                              style="color: var(--text-faint); font-size: 0.9em;">
                                            {{ item.raw.tradeable.category }}
                                        </span>
                                    </v-list-item-title>
                                </v-list-item>
                            </template>
                        </v-autocomplete>
                    </div>

                    <div class="input-group mt-6">
                        <label class="wfrp-label">Nazwa w ekwipunku (Edytowalna)</label>
                        <v-text-field
                            v-model="customName"
                            placeholder="Np. Skórzana Kurtka"
                            variant="outlined"
                            color="var(--gold)"
                            base-color="var(--text-faint)"
                            bg-color="var(--bg-inset)"
                            hide-details="auto"
                            class="wfrp-input-field"
                        ></v-text-field>
                        <small class="helper-text" style="text-align: left; margin-top: 5px;">
                            Możesz zmienić nazwę, pod jaką przedmiot trafi do ekwipunku.
                        </small>
                    </div>

                    <div class="input-group mt-6">
                        <label class="wfrp-label">Wartość Transakcji</label>

                        <v-row dense>
                            <v-col cols="4">
                                <v-text-field
                                    v-model="gold"
                                    type="number"
                                    placeholder="0"
                                    variant="outlined"
                                    color="var(--gold)"
                                    base-color="var(--text-faint)"
                                    bg-color="var(--bg-inset)"
                                    class="wfrp-input-field right-aligned-input"
                                    hide-details="auto"
                                >
                                    <template v-slot:append-inner>
                                        <span class="custom-suffix gold-suffix">ZK</span>
                                    </template>
                                </v-text-field>
                                <span class="coin-label label-gold">Złoto</span>
                            </v-col>

                            <v-col cols="4">
                                <v-text-field
                                    v-model="silver"
                                    type="number"
                                    placeholder="0"
                                    variant="outlined"
                                    color="var(--silver)"
                                    base-color="var(--text-faint)"
                                    bg-color="var(--bg-inset)"
                                    class="wfrp-input-field right-aligned-input"
                                    hide-details="auto"
                                >
                                    <template v-slot:append-inner>
                                        <span class="custom-suffix silver-suffix">s</span>
                                    </template>
                                </v-text-field>
                                <span class="coin-label label-silver">Srebro</span>
                            </v-col>

                            <v-col cols="4">
                                <v-text-field
                                    v-model="copper"
                                    type="number"
                                    placeholder="0"
                                    variant="outlined"
                                    color="var(--copper)"
                                    base-color="var(--text-faint)"
                                    bg-color="var(--bg-inset)"
                                    class="wfrp-input-field right-aligned-input"
                                    hide-details="auto"
                                >
                                    <template v-slot:append-inner>
                                        <span class="custom-suffix copper-suffix">p</span>
                                    </template>
                                </v-text-field>
                                <span class="coin-label label-copper">Miedź</span>
                            </v-col>
                        </v-row>
                    </div>

                    <div class="action-area mt-8">
                        <v-btn
                            block
                            height="50"
                            variant="outlined"
                            class="wfrp-btn"
                            type="submit"
                        >
                            Wyślij transakcje
                        </v-btn>
                    </div>

                </v-form>
            </v-card-text>
        </v-card>
    </div>
</template>

<script setup lang="ts">
import axios from "axios";
import { ref, defineProps, watch } from 'vue';
import { Hero } from "../../../../types/Hero";
import { MarketplaceItem } from "../../../../types/MarketplaceItem";
import {useToast} from "vue-toast-notification";

const props = defineProps<{
    activeHeroes: Hero[];
    marketplaceItems: MarketplaceItem[];
}>();
const toast = useToast();

const selectedHero = ref<Hero | null>(null);
const selectedItem = ref<MarketplaceItem | null>(null);

const customName = ref<string | null>(null);

const gold = ref<number | null>(null);
const silver = ref<number | null>(null);
const copper = ref<number | null>(null);

watch(selectedItem, (newItem) => {
    if (!newItem || !newItem.tradeable) {
        gold.value = null;
        silver.value = null;
        copper.value = null;
        customName.value = null; // Reset nazwy
        return;
    }

    customName.value = newItem.tradeable.name;

    if(newItem.tradeable.price) {
        let totalCopper = newItem.tradeable.price;
        const g = Math.floor(totalCopper / 240);
        totalCopper %= 240;
        const s = Math.floor(totalCopper / 12);
        totalCopper %= 12;
        const c = totalCopper;

        gold.value = g > 0 ? g : null;
        silver.value = s > 0 ? s : null;
        copper.value = c > 0 ? c : null;
    } else {
        gold.value = null;
        silver.value = null;
        copper.value = null;
    }
});


const submitTransaction = () => {
    const g = gold.value || 0;
    const s = silver.value || 0;
    const c = copper.value || 0;

    if (!selectedHero.value || !selectedItem.value || (g === 0 && s === 0 && c === 0)) {
        toast.error("Uzupełnij Księgę Uraz! Wybierz bohatera, przedmiot i ustal cenę.");
        return;
    }

    const payload = {
        hero_id: selectedHero.value.id,
        item_id: selectedItem.value.id,
        custom_name: customName.value,
        price: (g * 240) + (s * 12) + c
    };

    axios
        .post('panel/purchases', payload)
        .then(() => {
            toast.success('Transakcja została wysłana do gracza');
            selectedHero.value = null;
            selectedItem.value = null;
            customName.value = null;
            gold.value = null;
            silver.value = null;
            copper.value = null;
        })
        .catch(error => {
            toast.error(error.response.data.message);
        })
};
</script>

<style scoped>
.wfrp-wrapper {
    display: flex;
    justify-content: center;
    padding: 20px;
    font-family: var(--font-body);
}

.grudge-card {
    width: 100%;
    max-width: 600px;
    background-color: var(--bg-panel) !important;
    border: 1px solid var(--border-default);
    color: var(--text-body) !important;
}

.grudge-header {
    background: rgba(0, 0, 0, 0.3);
    padding: 20px;
    border-bottom: 1px solid var(--gold);
    text-align: center;
}

.grudge-header h2 {
    margin: 0;
    color: var(--gold);
    font-family: var(--font-heading);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 1.4rem;
}

.grudge-body {
    padding: 30px 25px !important;
}

.wfrp-label {
    display: block;
    color: var(--text-faint);
    font-family: var(--font-heading);
    margin-bottom: 8px;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .18em;
}

.helper-text {
    color: var(--text-faint-alt);
    display: block;
    font-style: italic;
    text-align: center;
}

.coin-label {
    display: block;
    text-align: center;
    font-size: 0.75rem;
    margin-top: 4px;
    font-family: var(--font-heading);
}

.label-gold { color: var(--gold); }
.label-silver { color: var(--silver); }
.label-copper { color: var(--copper); }

/* Input styles */
:deep(.v-field__input) {
    color: var(--text-body) !important;
    font-family: var(--font-body);
}

:deep(.v-field__input::placeholder) {
    color: var(--text-faint-alt) !important;
    opacity: 1;
    font-style: italic;
}

.right-aligned-input :deep(input) {
    text-align: right;
}

/* Hide spinners */
:deep(input::-webkit-outer-spin-button),
:deep(input::-webkit-inner-spin-button) {
    -webkit-appearance: none;
    margin: 0;
}

:deep(input[type=number]) {
    -moz-appearance: textfield;
}

/* Suffixes */
.custom-suffix {
    font-weight: 700;
    font-family: var(--font-heading);
    padding-left: 4px;
    opacity: 1 !important;
    user-select: none;
}
.gold-suffix { color: var(--gold) !important; }
.silver-suffix { color: var(--silver) !important; }
.copper-suffix { color: var(--copper) !important; }

:deep(.v-field__append-inner) {
    align-items: center;
    padding-top: 0;
}

.wfrp-btn {
    border-color: var(--border-accent) !important;
    color: var(--gold) !important;
    font-family: var(--font-heading) !important;
    font-weight: 700 !important;
    letter-spacing: 1px;
    transition: border-color 0.2s ease, background-color 0.2s ease, color 0.2s ease;
    background: transparent !important;
}

.wfrp-btn:hover {
    border-color: var(--gold) !important;
    background-color: var(--gold) !important;
    color: var(--bg-base) !important;
}
</style>

<style>
/* Global styles for dropdown */
.wfrp-autocomplete-menu .v-list {
    background-color: var(--bg-panel) !important;
    border: 1px solid var(--gold) !important;
    padding: 0 !important;
}
.wfrp-autocomplete-menu .v-list-item__overlay {
    opacity: 0 !important;
    background-color: transparent !important;
}
.wfrp-autocomplete-menu .v-list-item:hover {
    background-color: rgba(212, 175, 55, 0.15) !important;
}
.wfrp-autocomplete-menu .v-list-item-title,
.wfrp-autocomplete-menu .sub-text,
.wfrp-autocomplete-menu .sub-quality {
    font-family: var(--font-body) !important;
    color: var(--text-body) !important;
}
.wfrp-autocomplete-menu .v-list-item--active .v-list-item-title {
    color: var(--gold) !important;
    font-weight: bold !important;
}
</style>
