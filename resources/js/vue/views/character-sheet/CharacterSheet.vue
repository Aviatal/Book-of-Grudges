<template>
    <div>
        <template v-if="!isLoading">
            <hero-section
                :hero-data="hero"
            ></hero-section>
            <hero-description-section
                :description-data="hero.description"
            ></hero-description-section>
            <hero-characteristic-section
                :characteristic-data="hero.characteristic"
                v-on:update-hero-characteristic="updateHeroCharacteristic"
            ></hero-characteristic-section>
            <hero-weapons-section
                :cold-weapons-data="hero.cold_weapons"
                :ranged-weapons-data="hero.ranged_weapons"
            ></hero-weapons-section>
            <hero-armors-section
                :armors-data="hero.armors"
            ></hero-armors-section>
            <hero-skills-section
                :hurdled-skills-data="hero.skills"
                v-on:update-hero-skills="updateHeroSkills"
            ></hero-skills-section>
            <hero-talents-section
                :talents-data="hero.talents"
            ></hero-talents-section>
            <hero-inventory-section
                :hero-data="hero"
                v-on:get-hero="getHero"
            ></hero-inventory-section>
        </template>
        <template v-else>
            <div class="text-center py-8">
                <v-progress-circular indeterminate color="amber"></v-progress-circular>
                <p class="mt-4 text-amber-400">Ładowanie danych bohatera...</p>
            </div>
        </template>
    </div>
</template>

<script>
import HeroSection from "./sections/HeroSection.vue";
import HeroDescriptionSection from "./sections/HeroDescriptionSection.vue";
import HeroCharacteristicSection from "./sections/HeroCharacteristicSection.vue";
import HeroWeaponsSection from "./sections/HeroWeaponsSection.vue";
import HeroArmorsSection from "./sections/HeroArmorsSection.vue";
import HeroSkillsSection from "./sections/HeroSkillsSection.vue";
import HeroTalentsSection from "./sections/HeroTalentsSection.vue";
import HeroInventorySection from "./sections/HeroInventorySection.vue";
import {reactive, ref} from "vue";
import {useToast} from "vue-toast-notification";

export default {
    name: "CharacterSheet",
    components: {
        HeroInventorySection,
        HeroTalentsSection,
        HeroSkillsSection,
        HeroArmorsSection, HeroDescriptionSection, HeroSection, HeroCharacteristicSection, HeroWeaponsSection
    },
    props: {
        userId: {
            type: Number
        },
    },
    setup(props) {
        let isLoading = ref(true)
        const hero = reactive({});
        const toast = useToast();

        const getHero = () => {
            isLoading.value = true;
            axios.get('karta-postaci/' + props.userId + '/get-hero')
                .then(response => {
                    Object.assign(hero, response.data);
                })
                .catch(error => {
                    console.log(error);
                    toast.error('Wystąpił błąd podczas pobierania bohatera!')
                })
                .finally(() => {
                    isLoading.value = false;
                })
        }

        return {hero, getHero, isLoading}
    },
    created() {
        this.getHero();
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
        },
        updateHeroSkills() {
            axios.post('karta-postaci/' + this.hero.id + '/update-hero-skills', this.hero.skills)
                .then(response => {
                    this.hero = response.data;
                    this.$toast.success('Zaktualizowano umiejętności bohatera!')
                })
                .catch(error => {
                    console.log(error);
                    this.$toast.error('Wystąpił błąd podczas aktualizacji umiejętności bohatera!')
                })
        },
    }
};
</script>
