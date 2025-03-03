<template>
    <div>
        <hero-section
            :hero-data="hero"
            v-on:update-hero="updateHero"
        ></hero-section>
    </div>
</template>

<script>
import HeroSection from "./sections/HeroSection.vue";

export default {
    name: "CharacterSheet",
    components: {HeroSection},
    props: {
        initHero: {
            type: Object
        }
    },
    data() {
        return {
            hero: {},
        };
    },
    created() {
        this.hero = this.initHero;
    },
    methods: {
        updateHero() {
            axios.post('karta-postaci/' + this.hero.id + '/update-hero-data', this.hero)
                .then(response => {
                    this.hero = response.data;
                    this.$toast.success('Zaktualizowano bohatera!')
                })
                .catch(error => {
                    console.log(error);
                    this.$toast.error('Wystąpił błąd podczas aktualizacji bohatera!')
                })
        }
    }
};
</script>
