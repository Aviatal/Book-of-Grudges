<template>
    <div>
        <template v-if="!isLoading && hero">
            <hero-section
                :hero="hero"
                @update-characteristics="refreshCharacteristic"
            ></hero-section>
            <hero-description-section
                :hero-descriptions="hero.description"
            ></hero-description-section>
            <hero-characteristic-section
                :characteristic-data="hero.characteristic"
                :hero-id="hero.id"
                @add-characteristic="handleAddCharacteristic"
            ></hero-characteristic-section>
            <hero-weapons-section
                :hero-id="hero.id"
                :characteristic-data="hero.characteristic"
                :talents-data="hero.talents"
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
                :hero-id="hero.id"
                :inventory-data="hero.inventory"
                @get-hero="getHero"
            ></hero-inventory-section>
            <hero-watcher
                @experience-changed="handleAddExperience"
                @fortune-points-changed="handleAddFortunePoints"
            ></hero-watcher>
        </template>
        <template v-else>
            <div class="text-center py-8">
                <v-progress-circular indeterminate color="amber"></v-progress-circular>
                <p class="mt-4 text-amber-400">Ładowanie danych bohatera...</p>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import HeroSection from "./sections/HeroSection.vue";
import HeroDescriptionSection from "./sections/HeroDescriptionSection.vue";
import HeroCharacteristicSection from "./sections/HeroCharacteristicSection.vue";
import HeroWeaponsSection from "./sections/HeroWeaponsSection.vue";
import HeroArmorsSection from "./sections/HeroArmorsSection.vue";
import HeroSkillsSection from "./sections/HeroSkillsSection.vue";
import HeroTalentsSection from "./sections/HeroTalentsSection.vue";
import HeroInventorySection from "./sections/HeroInventorySection.vue";
import HeroWatcher from "../../components/HeroWatcher.vue";
import {CharacteristicPivot, Hero} from "../../../types/Hero";
import {onMounted, ref} from "vue";
import {useToast} from "vue-toast-notification";
import axios from "axios";


const props = defineProps({
    userId: Number
});

const isLoading = ref<boolean>(true);
const hero = ref<Hero | null>(null);
const toast = useToast();

const getHero = async(): Promise<void> => {
    isLoading.value = true;
    axios.get('karta-postaci/' + props.userId + '/get-hero')
        .then(response => {
            hero.value = response.data;
        })
        .catch(error => {
            console.log(error);
            toast.error('Wystąpił błąd podczas pobierania bohatera!')
        })
        .finally(() => {
            isLoading.value = false;
        })
}
const handleAddCharacteristic = (characteristicName: string, characteristic: CharacteristicPivot, changeCurrentWounds: number, spentExperience: number) => {
    if (!hero.value) {
        return;
    }
    console.log('xx')
    console.log(hero.value.characteristic[characteristicName].pivot, characteristic);
    hero.value.characteristic[characteristicName].pivot = characteristic
    hero.value.current_experience -= spentExperience;
    if (changeCurrentWounds > 0) {
        hero.value.current_wounds = changeCurrentWounds
    }
}
const refreshCharacteristic = (characteristics: Record<string, { pivot: CharacteristicPivot }>) => {
    if (!hero.value) {
        return;
    }
    hero.value.characteristic = {...characteristics};
}

const addWeaponToInventory = (weapon: any[]) => {
    if (!hero.value) {
        return;
    }
    hero.value.inventory.push(weapon)
}
const addArmorToInventory = (armor: any[]) => {
    if (!hero.value) {
        return;
    }
    hero.value.inventory.push(armor)
}
const handleAddExperience = (experience: number) => {
    if (!hero.value) {
        return;
    }
    hero.value.current_experience += experience;
    hero.value.all_experience += experience;
}
const handleAddFortunePoints = () => {
    if (!hero.value) {
        return;
    }
    hero.value.current_experience ++;
}

onMounted(() => {
    getHero();
})
</script>
