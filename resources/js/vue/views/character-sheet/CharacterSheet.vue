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
                :hero-id="hero.id"
                @add-new-item="handleNewItem"
                @experience-changed="handleAddExperience"
                @fortune-points-changed="handleAddFortunePoints"
            ></hero-watcher>
        </template>
        <template v-else-if="isLoading">
            <div class="text-center py-8">
                <v-progress-circular indeterminate color="amber"></v-progress-circular>
                <p class="mt-4 text-amber-400">Ładowanie danych bohatera...</p>
            </div>
        </template>
        <template v-else>
            <div class="text-center py-8">
                <button
                    class="create-hero-btn"
                    @click="startHeroCreation"
                >
                    <span class="btn-icon">⚔️</span>
                    Stwórz bohatera
                </button>
            </div>
        </template>

        <create-hero-view
            ref="heroCreationRef"
            @hero-created="handleHeroCreated"
            @creation-closed="handleCreationClosed"
        />
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
import { emitter } from '../../../emitter'
import {MarketplaceItem} from "../../../types/MarketplaceItem";
import {Weapon} from "../../../types/Weapon";
import {Armor} from "../../../types/Armor";
import CreateHeroView from "@/views/hero-creating/CreateHeroView.vue";


const props = defineProps({
    userId: Number
});

interface wealthUpdateObject {
    goldCrowns: number,
    silverShillings: number,
    brassPennies: number
}

const isLoading = ref<boolean>(true);
const hero = ref<Hero | null>(null);
const toast = useToast();
const heroCreationRef = ref(null)

const getHero = async(): Promise<void> => {
    isLoading.value = true;
    axios.get('karta-postaci/' + props.userId + '/get-hero')
        .then(response => {
            hero.value = response.data;
            if (Object.keys(hero.value).length === 0) {
                hero.value = null;
            }
        })
        .catch(error => {
            console.log(error);
            toast.error('Wystąpił błąd podczas pobierania bohatera!')
        })
        .finally(() => {
            isLoading.value = false;
        })
}

const startHeroCreation = () => {
    heroCreationRef.value?.startCreation()
}

const handleHeroCreated = async (heroData) => {
    console.log('Nowy bohater:', heroData)
    try {
        toast.success('Bohater został utworzony!')
    } catch (error) {
        console.error(error)
        toast.error('Błąd podczas tworzenia bohatera!')
    }
}

const handleCreationClosed = () => {
    console.log('Tworzenie bohatera anulowane')
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
const handleNewItem = (newItem: MarketplaceItem|Weapon|Armor, type: string, wealth: wealthUpdateObject, equip: boolean) => {
    if (equip) {
        switch (type) {
            case 'armor':
                hero.value.armors.push(newItem)
                break;
            case 'weapon':
                if (newItem.is_ranged) {
                    hero.value.ranged_weapons.push(newItem);
                } else {
                    hero.value.cold_weapons.push(newItem);
                }
                break;
            case 'marketplace':
                hero.value.inventory.push(newItem);
                break;
        }
    }
    hero.value.gold_crowns = wealth.goldCrowns;
    hero.value.silver_shillings = wealth.silverShillings;
    hero.value.brass_pennies = wealth.brassPennies;
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
    hero.value.fortune_points++;
}
onMounted(() => {
    getHero();
    emitter.on('luck-spent', () => {
        hero.value.fortune_points--
    })
})
</script>

<style scoped>
.create-hero-btn {
    background: linear-gradient(145deg, #d4af37 0%, #f4d03f 100%);
    color: #2a2926;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-size: 1.2rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.8rem;
    font-family: 'Cinzel', serif;
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
}

.create-hero-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.5);
}

.btn-icon {
    font-size: 1.3rem;
}
</style>
