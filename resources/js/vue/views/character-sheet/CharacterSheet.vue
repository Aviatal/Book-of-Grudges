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
                v-on:add-characteristic="handleAddCharacteristic"
            ></hero-characteristic-section>
            <hero-weapons-section
                :hero-id="hero.id"
                :cold-weapons-data="hero.cold_weapons"
                :ranged-weapons-data="hero.ranged_weapons"
                @unequip-weapon="addWeaponToInventory"
            ></hero-weapons-section>
            <hero-armors-section
                :hero-id="hero.id"
                :armors-data="hero.armors"
                @unequip-armor="addArmorToInventory"
            ></hero-armors-section>
            <hero-skills-section
                :hero-id="hero.id"
                :skills-data="hero.skills"
                :characteristic-data="hero.characteristic"
            ></hero-skills-section>
            <hero-talents-section
                :hero-id="hero.id"
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

        const handleAddCharacteristic = (characteristicName, characteristic, changeCurrentWounds) => {
            hero.characteristic[characteristicName].pivot = characteristic
            if (changeCurrentWounds > 0) {
                hero.current_wounds = changeCurrentWounds
            }
        }

        const addWeaponToInventory = (weapon) => {
            hero.inventory.push(weapon)
        }
        const addArmorToInventory = (armor) => {
            hero.inventory.push(armor)
        }

        return {
            hero, isLoading,
            getHero, handleAddCharacteristic, addWeaponToInventory, addArmorToInventory
        }
    },
    created() {
        this.getHero();
    },
};
</script>
