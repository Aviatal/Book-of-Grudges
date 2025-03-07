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
        <hero-weapons-section
            :cold-weapons-data="hero.cold_weapons"
            :ranged-weapons-data="hero.ranged_weapons"
            v-on:update-hero-characteristic="updateHeroWeapons"
        ></hero-weapons-section>
    </div>
</template>

<script>
import HeroSection from "./sections/HeroSection.vue";
import HeroDescriptionSection from "./sections/HeroDescriptionSection.vue";
import HeroCharacteristicSection from "./sections/HeroCharacteristicSection.vue";
import HeroWeaponsSection from "./sections/HeroWeaponsSection.vue";

export default {
    name: "CharacterSheet",
    components: {HeroDescriptionSection, HeroSection, HeroCharacteristicSection, HeroWeaponsSection},
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
