import './bootstrap';

import { createApp } from 'vue';
import CharacterSheet from "./vue/views/character-sheet/CharacterSheet.vue";
import {createVuetify} from "vuetify";
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';

import { VTextField, VBtn, VSelect } from 'vuetify/components';

const vuetify = createVuetify({
    components: {
        VTextField,
        VBtn,
        VSelect
    }
});

window.axios.defaults.baseURL = document.head
    .querySelector('meta[name="base-url"]')
    .content;

const app = createApp({}).use(vuetify).use(ToastPlugin);

app.component('character-sheet', CharacterSheet);

app.mount('#app');
