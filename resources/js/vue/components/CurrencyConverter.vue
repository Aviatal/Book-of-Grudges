<template>
    <div class="container mx-auto p-4">
        <h2 class="text-center text-2xl font-bold text-[#d4af37] mb-4">Przelicznik walut</h2>
        <v-row>
            <v-col cols="12" sm="6" lg="6">
                <v-text-field
                    v-model="valueToConvert"
                    label="Wartość do przeliczenia"
                    class="custom-input w-full"
                    variant="filled"
                ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" lg="6">
                <div class="select-wrapper">
                    <select
                        v-model="selectedCurrency"
                        class="currency-select w-full"
                    >
                        <option v-for="currency in currencies" :value="currency">{{ currency }}</option>
                    </select>
                </div>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-text-field
                    v-model="convertedValue"
                    label="Przeliczona wartość"
                    class="custom-input w-full"
                    variant="filled"
                    readonly
                ></v-text-field>
            </v-col>
        </v-row>
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
                pennies = this.roundTo2Decimals(parseFloat(this.valueToConvert * 240))
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
.currency-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    width: 100%;
    padding: 15px 15px;
    font-size: 16px;
    border: 1px solid #807a69;
    border-radius: 4px;
    background-color: #42413b;
    cursor: pointer;
}
</style>
