import './bootstrap';

import { createApp } from 'vue';
import CharacterSheet from "./vue/views/character-sheet/CharacterSheet.vue";
import {createVuetify} from "vuetify";
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';
import VueSelect  from "vue-select";
import { VTextField, VBtn, VCol, VRow, VDataTable } from 'vuetify/components';

const vuetify = createVuetify({
    components: {
        VTextField, VBtn, VCol, VRow, VDataTable
    }
});

window.axios.defaults.baseURL = document.head
    .querySelector('meta[name="base-url"]')
    .content;

const app = createApp({})
    .use(vuetify)
    .use(ToastPlugin);

app.component('v-select', VueSelect);

app.component('character-sheet', CharacterSheet);

app.mount('#app');
