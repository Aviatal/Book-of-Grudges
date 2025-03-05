<template>
    <div>
        <hero-section
            :hero-data="hero"
            v-on:update-hero="updateHero"
        ></hero-section>
        <hero-description-section
            :description-data="hero.description"
            v-on:update-hero-descriptions="updateHeroDescription"
        ></hero-description-section>
        <hero-characteristic-section
            :characteristic-data="hero.characteristic"
            v-on:update-hero-characteristic="updateHeroCharacteristic"
        ></hero-characteristic-section>
    </div>
</template>

<script>
import HeroSection from "./sections/HeroSection.vue";
import HeroDescriptionSection from "./sections/HeroDescriptionSection.vue";
import HeroCharacteristicSection from "./sections/HeroCharacteristicSection.vue";

export default {
    name: "CharacterSheet",
    components: {HeroDescriptionSection, HeroSection, HeroCharacteristicSection},
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
        },
        updateHeroDescription() {
            axios.post('karta-postaci/' + this.hero.id + '/update-hero-description', this.hero.description)
                .then(response => {
                    this.hero = response.data;
                    this.$toast.success('Zaktualizowano opis bohatera!')
                })
                .catch(error => {
                    console.log(error);
                    this.$toast.error('Wystąpił błąd podczas aktualizacji opisu bohatera!')
                })
        },
        updateHeroCharacteristic() {
            axios.post('karta-postaci/' + this.hero.id + '/update-hero-characteristic', this.hero.characteristic)
                .then(response => {
                    this.hero = response.data;
                    this.$toast.success('Zaktualizowano cechy bohatera!')
                })
                .catch(error => {
                    console.log(error);
                    this.$toast.error('Wystąpił błąd podczas aktualizacji cech bohatera!')
                })
        }
    }
};
</script>
