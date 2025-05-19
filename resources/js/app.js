import './bootstrap';

import { createApp } from 'vue';
import {createVuetify} from "vuetify";
import CharacterSheet from "./vue/views/character-sheet/CharacterSheet.vue";
import WeaponsIndex from "./vue/views/weapons/WeaponsIndex.vue";
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';
import VueSelect  from "vue-select";
import {
    VTextField, VBtn, VCol, VRow, VDataTable, VDialog, VCard, VCardText, VCardTitle, VCardActions, VProgressCircular,
    VAutocomplete
} from 'vuetify/components';
import ArmorsIndex from "./vue/views/armors/ArmorsIndex.vue";
import SkillsIndex from "./vue/views/skills/SkillsIndex.vue";
import TalentsIndex from "./vue/views/talents/TalentsIndex.vue";
import CurrencyConverter from "./vue/components/CurrencyConverter.vue";
import FooterText from "./vue/components/FooterText.vue";

const vuetify = createVuetify({
    components: {
        VTextField, VBtn, VCol, VRow, VDataTable, VDialog, VCard, VCardText, VCardTitle, VCardActions, VProgressCircular,
        VAutocomplete
    }
});

window.axios.defaults.baseURL = document.head
    .querySelector('meta[name="base-url"]')
    .content;

const app = createApp({})
    .use(vuetify)
    .use(ToastPlugin);

app.config.globalProperties.$calculatePrice = (price) => {
    let priceInGold = price / 240;
    if (price < 1) {
        let priceInSilver = price / 12
        if (priceInSilver < 1) {
            if (price) {
                return price + ' p'
            } else {
                return '-'
            }
        }
        return priceInSilver + ' s'
    }
    return priceInGold + ' zk'
};

app.component('v-select', VueSelect);

app.component('character-sheet', CharacterSheet);
app.component('weapons-index', WeaponsIndex);
app.component('armors-index', ArmorsIndex);
app.component('skills-index', SkillsIndex);
app.component('talents-index', TalentsIndex);
app.component('currency-converter', CurrencyConverter);
app.component('footer-text', FooterText);

app.mount('#app');
