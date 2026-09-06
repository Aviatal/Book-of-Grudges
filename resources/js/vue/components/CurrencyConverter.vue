<template>
    <div class="cc">
        <div class="cc__title">Przelicznik walut</div>

        <div class="cc__field">
            <label class="cc__label" for="cc-value">Wartość do przeliczenia</label>
            <input
                id="cc-value"
                v-model="valueToConvert"
                type="number"
                inputmode="decimal"
                class="cc__input"
                placeholder="0"
            >
        </div>

        <div class="cc__field">
            <label class="cc__label">Waluta</label>
            <v-select
                v-model="selectedCurrency"
                :options="currencies"
                :clearable="false"
                :searchable="false"
                class="cc__select"
            ></v-select>
        </div>

        <div class="cc__result">
            <span class="cc__result-label">Przeliczona wartość</span>
            <span class="cc__result-value">{{ convertedValue }}</span>
        </div>
    </div>
</template>
<script>
export default {
    name: "CurrencyConverter",
    data() {
        return {
            valueToConvert: 0,
            selectedCurrency: 'Złote korony',
            currencies: ['Złote korony', 'Srebrne Szylingi', 'Miedziane pensy'],
        }
    },
    computed: {
        convertedValue() {
            let goldCrowns = 0;
            let silverShillings = 0;
            let pennies = 0;
            if (this.selectedCurrency === 'Złote korony') {
                goldCrowns = this.valueToConvert;
                silverShillings = this.roundTo2Decimals(parseFloat(this.valueToConvert * 20))
                pennies = this.roundTo2Decimals(parseFloat(this.valueToConvert * 240))
            } else if (this.selectedCurrency === 'Srebrne Szylingi') {
                goldCrowns =this.roundTo2Decimals(parseFloat(this.valueToConvert / 20));
                silverShillings = this.valueToConvert
                pennies = this.roundTo2Decimals(parseFloat(this.valueToConvert * 12))
            } else if (this.selectedCurrency === 'Miedziane pensy') {
                goldCrowns = this.roundTo2Decimals(parseFloat(this.valueToConvert / 240));
                silverShillings = this.roundTo2Decimals(parseFloat(this.valueToConvert / 12));
                pennies = this.valueToConvert
            }
            if (goldCrowns > 10000) {
                return 'I tak tyle nie masz...'
            }
            return goldCrowns + 'zk ' + silverShillings + 'sz ' + pennies + 'p';
        }
    },
    methods: {
        roundTo2Decimals(number) {
            return Math.round(number * 100) / 100
        }
    }
}
</script>
<style scoped>
.cc {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.cc__title {
    font-family: var(--font-heading), serif;
    font-size: 10px;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: var(--text-faint-alt);
}

.cc__field {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.cc__label {
    font-family: var(--font-heading), serif;
    font-size: 10px;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--text-faint);
}

.cc__input {
    width: 100%;
    padding: 8px 10px;
    font-family: var(--font-body), serif;
    font-size: 14px;
    color: var(--text-body);
    background: var(--bg-inset);
    border: 1px solid var(--border-default);
    border-radius: 2px;
}

.cc__input::-webkit-outer-spin-button,
.cc__input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.cc__input {
    -moz-appearance: textfield;
}

.cc__input:focus {
    outline: none;
    border-color: var(--border-accent-hover);
    box-shadow: 0 0 0 2px rgba(212, 175, 55, .18);
}

/* vue-select — kompaktowo pod sidebar (motyw globalny w app.css) */
.cc__select :deep(.vs__dropdown-toggle) {
    padding: 2px 4px 5px;
    background: var(--bg-inset);
    border: 1px solid var(--border-default);
    border-radius: 2px;
}

.cc__select :deep(.vs__selected) {
    margin: 4px 2px 0;
    padding: 0 2px;
    font-family: var(--font-body), serif;
    font-size: 14px;
    font-weight: 400;
    color: var(--text-body);
}

.cc__select :deep(.vs__search) {
    margin: 4px 0 0;
    padding: 0 2px;
}

.cc__select :deep(.vs__actions) {
    padding-top: 3px;
}

.cc__select.vs--open :deep(.vs__dropdown-toggle) {
    border-color: var(--border-accent-hover);
}

.cc__result {
    display: flex;
    flex-direction: column;
    gap: 3px;
    margin-top: 2px;
    padding: 9px 11px;
    background: linear-gradient(#221d16, #171310);
    border: 1px solid var(--border-frame);
    border-left: 2px solid var(--gold-muted);
}

.cc__result-label {
    font-family: var(--font-heading), serif;
    font-size: 9px;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--text-faint);
}

.cc__result-value {
    font-size: 15px;
    color: var(--gold-bright);
    font-variant-numeric: tabular-nums;
    line-height: 1.3;
}
</style>
