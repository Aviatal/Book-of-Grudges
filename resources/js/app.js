import './bootstrap';
import { createApp } from 'vue';
import CharacterSheet from "./vue/views/character-sheet/character-sheet.vue";

const app = createApp({});

app.component('character-sheet', CharacterSheet);

app.mount('#app');
