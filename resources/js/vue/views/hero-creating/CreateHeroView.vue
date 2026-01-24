<template>
    <div class="hero-creation-overlay" v-if="isCreating">
        <!-- Tło z efektem parallax -->
        <div class="creation-background">
            <div class="parchment-overlay"></div>
            <div class="floating-runes">
                <div class="rune" v-for="i in 5" :key="i"></div>
            </div>
        </div>

        <!-- Główny kontener kreacji -->
        <div class="creation-container">
            <!-- Pasek postępu -->
            <div class="progress-section">
                <div class="progress-bar">
                    <div class="progress-fill" :style="{ width: progressPercentage + '%' }"></div>
                </div>
                <div class="progress-text">
                    Krok {{ currentStep }} z {{ totalSteps }}
                </div>
            </div>

            <!-- Slajd 1: Wybór rasy -->
            <div v-if="currentStep === 1" class="creation-slide slide-enter">
                <div class="slide-header">
                    <h2 class="slide-title">Wybierz rasę swojego bohatera</h2>
                    <p class="slide-subtitle">Każda rasa ma swoje unikalne cechy i predyspozycje</p>
                </div>

                <div class="race-selection-grid">
                    <div
                        v-for="race in availableRaces"
                        :key="race.id"
                        class="race-card"
                        :class="{ active: selectedRace?.id === race.id }"
                        @click="selectRace(race)"
                    >
                        <div class="race-icon">
                            <img :src="race.icon" :alt="race.name"/>
                        </div>
                        <div class="race-info">
                            <h3 class="race-name">{{ race.name }}</h3>
                            <p class="race-description">{{ race.description }}</p>
                            <div class="race-bonuses">
                                <div v-for="bonus in race.bonuses" :key="bonus" class="bonus-tag">
                                    +{{ bonus }}
                                </div>
                            </div>
                        </div>
                        <div class="selection-indicator">
                            <div class="check-mark" v-if="selectedRace?.id === race.id">✓</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slajd 2: Rzut kostkami na cechy -->
            <div v-if="currentStep === 2" class="creation-slide slide-enter">
                <div class="slide-header">
                    <h2 class="slide-title">Wylosuj profesję dla {{ selectedRace?.name }}</h2>
                    <p class="slide-subtitle">Rzuć kostkami d10, aby określić profesję startową</p>
                </div>

                <div class="profession-rolling-section">
                    <div class="dice-roll-container">
                        <!-- Kontener DiceBox -->
                        <div id="dice-box-canvas" class="dice-canvas"></div>

                        <!-- Wyniki rzutu -->
                        <div class="dice-result-display" v-if="professionRoll > 0 && !isRollingProfession">
                            <div class="total-result">
                                <span class="total-label">Wynik:</span>
                                <span class="total-value">{{ professionRoll }}</span>
                            </div>
                        </div>

                        <div class="roll-controls">
                            <button
                                v-if="!selectedProfession"
                                class="roll-profession-btn"
                                @click="rollForProfession"
                                :disabled="isRollingProfession"
                            >
                                <span class="btn-icon">🎲</span>
                                {{ isRollingProfession ? 'Rzucam kostkami...' : 'Rzuć kostkami' }}
                            </button>

                            <button
                                class="reroll-btn"
                                @click="rollForProfession"
                                v-if="selectedProfession && !isRollingProfession"
                            >
                                <span class="btn-icon">🔄</span>
                                Przerzuć
                            </button>
                        </div>
                    </div>
                    <transition name="fade-slide">
                        <div v-if="selectedProfession && !isRollingProfession" class="profession-display-container">
                            <div class="profession-display">
                                <div class="profession-header">
                                    <h3 class="profession-name">{{ selectedProfession.name }}</h3>
                                    <div class="profession-class" v-if="selectedProfession.class">
                                        {{ selectedProfession.class }}
                                    </div>
                                    <div class="dice-roll-indicator">
                                        Wynik rzutu: {{ professionRoll }}
                                    </div>
                                </div>

                                <div class="profession-content-scroll">
                                    <div class="profession-description">
                                        <p v-if="selectedProfession.description">{{ selectedProfession.description }}</p>
                                        <p v-else>Profesja wylosowana pomyślnie. Twoja postać jest gotowa do drogi.</p>
                                    </div>

                                    <div class="profession-details-grid">
                                        <div class="detail-section full-width">
                                            <h4 class="detail-title">Schemat Rozwoju</h4>

                                            <div class="stat-category-label">Cechy Główne</div>
                                            <div class="wfrp-stat-grid">
                                                <div v-for="stat in mainProfileConfig" :key="stat.label" class="stat-cell">
                                                    <div class="stat-header">{{ stat.label }}</div>
                                                    <div class="stat-value">
                                                        {{ formatStatBonus(getStatValue(selectedProfession.characteristics, stat.label)) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="stat-category-label secondary-label">Cechy Drugorzędne</div>
                                            <div class="wfrp-stat-grid">
                                                <div v-for="stat in secondaryProfileConfig" :key="stat.label" class="stat-cell">
                                                    <div class="stat-header">{{ stat.label }}</div>
                                                    <div class="stat-value">
                                                        {{ formatStatBonus(getStatValue(selectedProfession.characteristics, stat.label)) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="detail-section">
                                            <h4 class="detail-title">Umiejętności</h4>
                                            <div class="items-container">
                                                <div class="mandatory-group" v-if="getMandatoryItems(selectedProfession.skills).length">
                                                    <span class="group-label">Otrzymujesz:</span>
                                                    <div class="tags-wrapper">
                                                        <span v-for="skill in getMandatoryItems(selectedProfession.skills)" :key="skill.id" class="skill-tag">
                                                            {{ formatSkillName(skill) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="choices-container" v-if="getChoiceGroups(selectedProfession.skills).length">
                                                    <div v-for="(group, index) in getChoiceGroups(selectedProfession.skills)" :key="'s-choice-'+index" class="choice-block">
                                                        <span class="choice-label">Wybierz jedną z:</span>
                                                        <div class="choice-options">
                                                            <span v-for="(skill, i) in group" :key="skill.id">
                                                                <span class="choice-item">{{ formatSkillName(skill) }}</span>
                                                                <span v-if="i < group.length - 1" class="separator">LUB</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="detail-section">
                                            <h4 class="detail-title">Zdolności</h4>
                                            <div class="items-container">
                                                <div class="mandatory-group" v-if="getMandatoryItems(selectedProfession.talents).length">
                                                    <span class="group-label">Otrzymujesz:</span>
                                                    <div class="tags-wrapper">
                                                        <span v-for="talent in getMandatoryItems(selectedProfession.talents)" :key="talent.id" class="talent-tag">
                                                            {{ formatTalentName(talent) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="choices-container" v-if="getChoiceGroups(selectedProfession.talents).length">
                                                    <div v-for="(group, index) in getChoiceGroups(selectedProfession.talents)" :key="'t-choice-'+index" class="choice-block">
                                                        <span class="choice-label">Wybierz jedną z:</span>
                                                        <div class="choice-options">
                                                            <span v-for="(talent, i) in group" :key="talent.id">
                                                                <span class="choice-item">{{ formatTalentName(talent) }}</span>
                                                                <span v-if="i < group.length - 1" class="separator">LUB</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="detail-section">
                                            <h4 class="detail-title">Wyposażenie</h4>
                                            <div class="equipment-list">
                                                <div class="mandatory-eq" v-if="getMandatoryItems(selectedProfession.equipments).length">
                                                    <div v-for="item in getMandatoryItems(selectedProfession.equipments)" :key="item.id" class="equipment-item">
                                                        {{ formatItemName(item) }}
                                                    </div>
                                                </div>
                                                <div class="choices-container" v-if="getChoiceGroups(selectedProfession.equipments).length">
                                                    <div v-for="(group, index) in getChoiceGroups(selectedProfession.equipments)" :key="'t-choice-'+index" class="choice-block">
                                                        <span class="choice-label">Wybierz jedną z:</span>
                                                        <div class="choice-options">
                                                            <span v-for="(item, i) in group" :key="item.id">
                                                                <span class="choice-item">{{ formatItemName(item) }}</span>
                                                                <span v-if="i < group.length - 1" class="separator">LUB</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
            <!-- Slajd 3: Imię bohatera -->
            <div v-if="currentStep === 3" class="creation-slide slide-enter">
                <div class="slide-header">
                    <h2 class="slide-title">Nazwij swojego bohatera</h2>
                    <p class="slide-subtitle">Wybierz imię godne {{ selectedRace?.name || 'bohatera' }}</p>
                </div>

                <div class="name-input-section">
                    <div class="input-group">
                        <label class="input-label">Imię</label>
                        <input
                            v-model="heroData.firstName"
                            type="text"
                            class="fantasy-input"
                            placeholder="Wpisz imię bohatera..."
                            @input="validateName"
                        >
                    </div>

                    <div class="input-group">
                        <label class="input-label">Nazwisko (opcjonalne)</label>
                        <input
                            v-model="heroData.lastName"
                            type="text"
                            class="fantasy-input"
                            placeholder="Wpisz nazwisko bohatera..."
                        >
                    </div>

                    <div class="name-suggestions" v-if="selectedRace">
                        <h4>Sugerowane imiona dla {{ selectedRace.name }}:</h4>
                        <div class="suggestion-tags">
                          <span
                              v-for="name in selectedRace.suggestedNames"
                              :key="name"
                              class="suggestion-tag"
                              @click="heroData.firstName = name"
                          >
                            {{ name }}
                          </span>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Nawigacja -->
            <div class="navigation-section">
                <button
                    class="nav-btn prev-btn"
                    @click="previousStep"
                    v-if="currentStep > 1"
                >
                    <span class="btn-icon">←</span>
                    Wstecz
                </button>

                <div class="step-indicators">
                    <div
                        v-for="step in totalSteps"
                        :key="step"
                        class="step-dot"
                        :class="{
              active: step === currentStep,
              completed: step < currentStep
            }"
                    ></div>
                </div>

                <button
                    class="nav-btn next-btn"
                    @click="nextStep"
                    :disabled="!canProceed"
                    v-if="currentStep < totalSteps"
                >
                    Dalej
                    <span class="btn-icon">→</span>
                </button>

                <button
                    class="nav-btn finish-btn"
                    @click="finishCreation"
                    :disabled="!canProceed"
                    v-if="currentStep === totalSteps"
                >
                    <span class="btn-icon">⚔️</span>
                    Stwórz bohatera
                </button>
            </div>

            <!-- Przycisk zamknięcia -->
            <button class="close-btn" @click="closeCreation">
                <span class="close-icon">✕</span>
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, nextTick, onMounted, ref, watch} from 'vue'
import {rollProfession} from '../../../data/professions'
import DiceBox from '@3d-dice/dice-box'

let diceBox = null

// Props i emits
const emit = defineEmits(['hero-created', 'creation-closed'])

// Stan komponentu
const isCreating = ref(false)
const currentStep = ref(1)
const totalSteps = 3

// Dane bohatera
const heroData = ref({
    firstName: '',
    lastName: '',
    race: null,
    characteristics: {}
})

const selectedRace = ref(null)

watch(currentStep, async (newStep) => {
    if (newStep === 2 && !diceBox) {
        // Poczekaj na renderowanie DOM
        await nextTick()

        // Sprawdź czy element już istnieje
        const canvasElement = document.querySelector('#dice-box-canvas')
        if (canvasElement) {
            await initializeDiceBox()
        } else {
            // Jeśli element nie istnieje, spróbuj ponownie po krótkim czasie
            setTimeout(async () => {
                const retryElement = document.querySelector('#dice-box-canvas')
                if (retryElement) {
                    await initializeDiceBox()
                }
            }, 100)
        }
    }
}, {immediate: true})

const initializeDiceBox = async () => {
    try {
        const container = document.querySelector('#dice-box-canvas');
        if (!container) return;

        // Pobieramy wymiary raz i blokujemy je
        const {width, height} = container.getBoundingClientRect();

        diceBox = new DiceBox('#dice-box-canvas', {
            assetPath: '/assets/dice-box/',
            origin: window.location.origin,
            theme: 'default',
            themeColor: '#d4af37',
            scale: 9,
            offscreen: false,
            // Blokujemy automatyczne zmienianie rozmiaru przez bibliotekę
            container_width: width,
            container_height: height,
            settleTimeout: 5000 // Dajemy kostkom czas na uspokojenie się
        });

        await diceBox.init();

        // Po inicjalizacji jeszcze raz upewniamy się, że canvas nie ma inline styles, które go psują
        const canvas = container.querySelector('canvas');
        if (canvas) {
            canvas.style.width = '100%';
            canvas.style.height = '100%';
        }
    } catch (error) {
        console.error('Błąd inicjalizacji DiceBox:', error);
    }
}

// Metoda startCreation z dodatkową inicjalizacją
const startCreation = async () => {
    isCreating.value = true
    currentStep.value = 1
}

// Metoda closeCreation z czyszczeniem
const closeCreation = () => {
    // Wyczyść DiceBox jeśli istnieje
    if (diceBox) {
        diceBox.clear()
        diceBox = null
    }
    isCreating.value = false
    emit('creation-closed')
}

// Dostępne rasy (przykładowe dane)
const availableRaces = ref([
    {
        id: 1,
        name: 'Człowiek',
        key: 'human',
        description: 'Wszechstronni i ambitni',
        icon: '/images/races/human.png',
        bonuses: ['Wszechstronność', 'Ambicja',],
        suggestedNames: ['Alexa', 'Felix', 'Klara', 'Otto', 'Mathilde', 'Wolfgang']
    },
    {
        id: 2,
        name: 'Krasnolud',
        key: 'dwarf',
        description: 'Krzepcy i odważni',
        icon: '/images/races/dwarf.png',
        bonuses: ['Pamiętliwość', 'Tradycjonalizm', 'Solisdność'],
        suggestedNames: ['Gotrek', 'Astrid', 'Bardin', 'Greta', 'Anika', 'Thylda']
    },
    {
        id: 3,
        name: 'Elf',
        key: 'elf',
        description: 'Dostojni i długowieczni',
        icon: '/images/races/elf.png',
        bonuses: ['Perfekcjonizm', 'Długowieczność', 'Wyczulone zmysły'],
        suggestedNames: ['Halion', 'Teclis', 'Ulliana', 'Aluthol', 'Dolwen']
    },
    {
        id: 4,
        name: 'Niziołek',
        key: 'halfling',
        description: 'Spokojni i łakomi',
        icon: '/images/races/halfling.png',
        bonuses: ['Zaradność', 'Pogodność', 'Odporność na strach'],
        suggestedNames: ['Ludo', 'Paul', 'Leni', 'Max', 'Theo', 'Sophia']
    }
])

// Cechy
const characteristics = ref({
    weaponSkill: {
        name: 'Walka Wręcz',
        dice: [],
        total: 0,
        isRolling: false
    },
    ballisticSkill: {
        name: 'Umiejętności Strzeleckie',
        dice: [],
        total: 0,
        isRolling: false
    },
    strength: {
        name: 'Siła',
        dice: [],
        total: 0,
        isRolling: false
    },
    toughness: {
        name: 'Wytrzymałość',
        dice: [],
        total: 0,
        isRolling: false
    },
    agility: {
        name: 'Zwinność',
        dice: [],
        total: 0,
        isRolling: false
    },
    intelligence: {
        name: 'Inteligencja',
        dice: [],
        total: 0,
        isRolling: false
    },
    willpower: {
        name: 'Siła Woli',
        dice: [],
        total: 0,
        isRolling: false
    },
    fellowship: {
        name: 'Ogłada',
        dice: [],
        total: 0,
        isRolling: false
    }
})

const selectedProfession = ref(null)
const professionRoll = ref(0)
const isRollingProfession = ref(false)

const progressPercentage = computed(() => {
    return (currentStep.value / totalSteps) * 100
})

const getCharacteristicName = (charKey) => {
    const names = {
        weaponSkill: 'Walka Wręcz',
        ballisticSkill: 'Umiejętności Strzeleckie',
        strength: 'Siła',
        toughness: 'Wytrzymałość',
        agility: 'Zwinność',
        intelligence: 'Inteligencja',
        willpower: 'Siła Woli',
        fellowship: 'Ogłada'
    }
    return names[charKey] || charKey
}


const rollForProfession = async () => {
    if (!selectedRace.value || isRollingProfession.value) return

    if (!diceBox) {
        await initializeDiceBox()
        if (!diceBox) return
    }

    try {
        isRollingProfession.value = true

        selectedProfession.value = null
        professionRoll.value = 0

        await new Promise(resolve => setTimeout(resolve, 500))
        await diceBox.clear()
        const results = await diceBox.roll('1d100')
        const total = results[0].value
        professionRoll.value = total;

        try {
            const raceKey = selectedRace.value.key
            const response = await rollProfession(raceKey, total)
            const professionData = response.data || response;

            selectedProfession.value = professionData;
            heroData.value.profession = professionData;

        } catch (error) {
            console.error('Błąd pobierania profesji:', error)
        }

    } catch (error) {
        console.error('Błąd rzutu kostkami:', error)
    } finally {
        isRollingProfession.value = false
    }
}

const mainProfileConfig = [
    { label: 'WW',  keys: ['ws', 'weapon_skill', 'weaponSkill', 'Walka Wręcz'] },
    { label: 'US',  keys: ['bs', 'ballistic_skill', 'ballisticSkill', 'Umiejętności Strzeleckie'] },
    { label: 'K',   keys: ['s', 'strength', 'Siła'] },
    { label: 'Odp', keys: ['t', 'toughness', 'Wytrzymałość'] },
    { label: 'Zr',  keys: ['ag', 'agility', 'Zwinność'] },
    { label: 'Int', keys: ['int', 'intelligence', 'Inteligencja'] },
    { label: 'SW',  keys: ['wp', 'willpower', 'Siła Woli'] },
    { label: 'Ogd', keys: ['fel', 'fellowship', 'Ogłada'] }
]

const secondaryProfileConfig = [
    { label: 'A',   keys: ['a', 'attacks', 'Ataki'] },
    { label: 'Żyw', keys: ['w', 'wounds', 'Żywotność'] },
    { label: 'S',   keys: ['sb', 'strength_bonus'] },
    { label: 'Wt',  keys: ['tb', 'toughness_bonus'] },
    { label: 'Sz',  keys: ['m', 'movement', 'Szybkość'] },
    { label: 'Mag', keys: ['mag', 'magic', 'Magia'] },
    { label: 'PO',  keys: ['ip', 'insanity', 'Punkty Obłędu'] },
    { label: 'PP',  keys: ['fp', 'fate', 'Punkty Przeznaczenia'] }
]

// Funkcja szukająca wartości w obiekcie characteristics niezależnie od nazewnictwa klucza
// Uniwersalna funkcja szukająca wartości w Tablicy LUB Obiekcie
const getStatValue = (characteristics: any, label: string) => {
    if (!characteristics) return null;
    // PRZYPADEK 1: Backend zwraca TABLICĘ obiektów (np. [{name: 'ws', value: 10}, ...])
    if (Array.isArray(characteristics)) {
        const foundItem = characteristics.find(item => {
            return label === item?.characteristic?.short_name;
        });

        if (foundItem) {
            return foundItem.available_advancement ?? null;
        }
        return null;
    }

    // PRZYPADEK 2: Backend zwraca OBIEKT (np. { ws: 10, bs: 5 })
    for (const key of keys) {
        if (characteristics[key] !== undefined && characteristics[key] !== null) {
            return characteristics[key];
        }
    }

    return null;
}

// Formatowanie wartości (dodaje "+", chyba że to 0 lub null)
const formatStatBonus = (val: any) => {
    if (val === null || val === 0 || val === undefined) return '—';
    return `+${val}`;
}

// Pobiera listę przedmiotów obowiązkowych (klucz "0")
const getMandatoryItems = (groupedCollection: any) => {
    if (!groupedCollection) return [];
    console.log(groupedCollection)
    return Object.values(groupedCollection)
        .filter((group: any) => Array.isArray(group) && group.length === 1)
        .flat();
}

const getChoiceGroups = (groupedCollection: any) => {
    if (!groupedCollection) return [];
    return Object.values(groupedCollection)
        .filter((group: any) => Array.isArray(group) && group.length > 1);
}
// Formatowanie nazwy umiejętności (Obsługa HasMany z modelem pośrednim)
// Formatowanie nazwy umiejętności
const formatSkillName = (item: any) => {
    const name = item.skill?.name || 'Nieznana umiejętność';
    const additional = item.additional_name;

    return additional ? `${name} (${additional})` : name;
}

// Formatowanie nazwy zdolności
const formatTalentName = (item: any) => {
    const name = item.talent?.name;
    const additional = item.additional_name;
    return additional ? `${name} (${additional})` : name;
}

// Formatowanie nazwy ekwipunku
const formatItemName = (pivotItem: any) => {
    const customName = pivotItem.item_name;
    if (customName) return customName;

    return pivotItem?.item?.tradeable?.name || 'Nieznany';
}
const canProceed = computed(() => {
    switch (currentStep.value) {
        case 1:
            return selectedRace.value !== null
        case 2:
            return heroData.value.firstName.trim().length > 0
        case 3:
            return Object.values(characteristics.value).every(char => char.total > 0)
        default:
            return false
    }
})

// Metody

const selectRace = (race) => {
    selectedRace.value = race
    heroData.value.race = race
}

const validateName = () => {
    // Dodatkowa walidacja nazwy jeśli potrzebna
}

const rollCharacteristic = (charKey) => {
    const char = characteristics.value[charKey]
    char.isRolling = true

    setTimeout(() => {
        // Rzut 2d10 + 20 (standardowy dla WFRP)
        const die1 = Math.floor(Math.random() * 10) + 1
        const die2 = Math.floor(Math.random() * 10) + 1

        char.dice = [
            {id: 1, value: die1},
            {id: 2, value: die2}
        ]
        char.total = die1 + die2 + 20
        char.isRolling = false
    }, 1000)
}

const rollAllCharacteristics = () => {
    Object.keys(characteristics.value).forEach(key => {
        rollCharacteristic(key)
    })
}

const nextStep = () => {
    if (canProceed.value && currentStep.value < totalSteps) {
        currentStep.value++
    }
}

const previousStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--
    }
}

const finishCreation = () => {
    // Przygotowanie danych do wysłania
    const finalHeroData = {
        firstName: heroData.value.firstName,
        lastName: heroData.value.lastName,
        race: selectedRace.value,
        characteristics: Object.fromEntries(
            Object.entries(characteristics.value).map(([key, value]) => [key, value.total])
        )
    }

    emit('hero-created', finalHeroData)
    isCreating.value = false
}

// Exposing metody dla rodzica
defineExpose({
    startCreation
})

onMounted(() => {
    // Inicjalizacja jeśli potrzebna
})
</script>

<style scoped>
/* =========================================
   1. GŁÓWNY UKŁAD (LAYOUT)
   ========================================= */

.hero-creation-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(0, 0, 0, 0.9); /* Mocniejsze przyciemnienie tła */
    padding: 1rem;
    overflow-y: auto;
}

.creation-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #111;
    pointer-events: none;
    z-index: -1;
}

.parchment-overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at center, rgba(212, 175, 55, 0.05), rgba(0, 0, 0, 0.9));
}

.creation-container {
    position: relative;
    width: 100%;
    max-width: 1400px;
    height: 90vh;
    background: linear-gradient(145deg, #1f1e1b 0%, #2d2a25 100%);
    border: 3px solid #d4af37;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 0 80px rgba(0, 0, 0, 1);
    font-family: 'Cinzel', serif;
    color: #e4d8b4;
    overflow: hidden;
}

/* =========================================
   2. NAGŁÓWEK I PASEK POSTĘPU
   ========================================= */

.progress-section {
    padding: 1rem 1.5rem;
    border-bottom: 2px solid #d4af37;
    text-align: center;
    background: rgba(0, 0, 0, 0.4);
    flex-shrink: 0;
}

.progress-bar {
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: #d4af37;
    transition: width 0.5s ease-out;
    box-shadow: 0 0 10px #d4af37;
}

.progress-text {
    color: #d4af37;
    font-size: 0.9rem;
    font-weight: bold;
    letter-spacing: 1px;
}

/* =========================================
   3. SLAJDY (SLIDES)
   ========================================= */

.creation-slide {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 1.5rem;
    overflow-y: auto;
    position: relative;
    /* Ukrywamy scrollbar systemowy, ale zachowujemy funkcjonalność */
    scrollbar-width: thin;
    scrollbar-color: #d4af37 transparent;
}

.slide-header {
    text-align: center;
    margin-bottom: 2rem;
    flex-shrink: 0;
}

.slide-title {
    font-size: 2rem;
    color: #d4af37;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8);
}

.slide-subtitle {
    color: #a89f91;
    font-size: 1rem;
}

/* =========================================
   4. KROK 1: WYBÓR RASY
   ========================================= */

.race-selection-grid {
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    flex-wrap: wrap;
    padding-bottom: 1rem;
}

.race-card {
    background: rgba(255, 255, 255, 0.03);
    border: 2px solid rgba(212, 175, 55, 0.2);
    border-radius: 10px;
    padding: 1.5rem;
    width: 250px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.race-card:hover {
    border-color: #d4af37;
    background: rgba(255, 255, 255, 0.07);
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}

.race-card.active {
    border-color: #d4af37;
    background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(0, 0, 0, 0.4));
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
}

.race-icon {
    width: 90px;
    height: 90px;
    margin-bottom: 1rem;
    border-radius: 50%;
    border: 2px solid rgba(212, 175, 55, 0.4);
    overflow: hidden;
}

.race-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.race-name {
    color: #d4af37;
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
}

.race-description {
    text-align: center;
    font-size: 0.85rem;
    color: #ccc;
    margin-bottom: 1rem;
}

.race-bonuses {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    justify-content: center;
}

.bonus-tag {
    background: rgba(0, 0, 0, 0.4);
    color: #d4af37;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    border: 1px solid rgba(212, 175, 55, 0.3);
}

.check-mark {
    position: absolute;
    top: 10px;
    right: 10px;
    color: #d4af37;
    font-size: 1.2rem;
    font-weight: bold;
}

/* =========================================
   5. KROK 2: RZUT I SZCZEGÓŁY (Kluczowe poprawki)
   ========================================= */

.profession-rolling-section {
    display: flex;
    gap: 2rem;
    height: 100%;
    overflow: hidden;
    padding-bottom: 1rem;
    /* Centrujemy zawartość. Jeśli jest tylko lewa kolumna, będzie na środku.
       Jeśli dojdzie prawa, obie się zmieszczą obok siebie. */
    justify-content: center;
}

/* --- LEWA KOLUMNA: KOSTKI --- */
.dice-roll-container {
    /* KLUCZOWA ZMIANA: */
    flex: 0 0 450px; /* 0: nie rośnij, 0: nie malej, 450px: bazowa szerokość */
    width: 450px;    /* Sztywna szerokość */

    background: #0a0a0a;
    border: 2px solid #444;
    border-radius: 12px;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 500px; /* Stała wysokość */
    overflow: hidden;
    box-shadow: inset 0 0 50px rgba(0,0,0,0.8);
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.5s ease;
    max-height: 1000px; /* Potrzebne do animacji wysokości */
    opacity: 1;
    overflow: hidden;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateX(50px);
    max-height: 0; /* Zwijamy element przy wyjściu */
    margin: 0;
    padding: 0;
}
/* Canvas biblioteki */
#dice-box-canvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    display: block;
}

:deep(canvas) {
    display: block !important;
    width: 100% !important;
    height: 100% !important;
}

/* UI nad kostkami */
.dice-result-display {
    position: relative;
    z-index: 10;
    margin-top: 2rem;
    margin-bottom: auto;
}

.total-result {
    background: rgba(212, 175, 55, 0.95);
    color: #1a1a1a;
    padding: 0.8rem 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
    text-align: center;
    min-width: 140px;
    border: 1px solid #fff;
}

.total-label {
    display: block;
    font-size: 0.7rem;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.total-value {
    display: block;
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1;
}

.roll-controls {
    position: relative;
    z-index: 10;
    margin-top: auto;
    padding-bottom: 2rem;
    display: flex;
    gap: 1rem;
    width: 100%;
    justify-content: center;
}

.roll-profession-btn {
    background: linear-gradient(to bottom, #d4af37, #b4941f);
    border: 1px solid #ffd700;
    color: #1a1a1a;
    padding: 12px 30px;
    font-weight: bold;
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
    border-radius: 4px;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.6);
    transition: 0.2s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.roll-profession-btn:hover:not(:disabled) {
    transform: scale(1.05);
    filter: brightness(1.1);
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
}

.roll-profession-btn:disabled {
    filter: grayscale(1);
    opacity: 0.7;
    cursor: not-allowed;
}

.reroll-btn {
    background: rgba(0, 0, 0, 0.7);
    border: 1px solid #d4af37;
    color: #d4af37;
    padding: 10px 20px;
    font-weight: bold;
    cursor: pointer;
    border-radius: 4px;
    transition: 0.2s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.reroll-btn:hover {
    background: rgba(212, 175, 55, 0.2);
}

/* --- PRAWA KOLUMNA: OPIS PROFESJI --- */
.profession-display-container {
    /* Zmieniamy na flex: 1, ale z ograniczeniem szerokości */
    flex: 1;
    max-width: 800px; /* Nie pozwalamy, żeby była zbyt szeroka */
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideIn 0.5s ease-out;
}

.profession-display {
    background: rgba(30, 30, 30, 0.95);
    border: 1px solid #d4af37;
    border-radius: 10px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    height: 100%;
    box-shadow: inset 0 0 50px rgba(0, 0, 0, 0.5);
}

/* Responsywność dla kroku 2 */
@media (max-width: 1024px) {
    .profession-rolling-section {
        flex-direction: column;
        align-items: center; /* Centrujemy na tablecie */
        overflow-y: auto;
    }

    .dice-roll-container {
        /* Na małych ekranach pozwalamy na 100% szerokości */
        flex: none;
        width: 100%;
        max-width: 500px;
        min-height: 350px;
    }

    .profession-display-container {
        width: 100%;
        max-width: none;
        height: auto;
        overflow: visible;
    }

    .profession-display {
        height: auto;
    }
}

.profession-header {
    text-align: center;
    border-bottom: 1px solid rgba(212, 175, 55, 0.3);
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    flex-shrink: 0;
}

.profession-name {
    font-size: 2.2rem;
    color: #d4af37;
    margin: 0;
    text-shadow: 0 2px 5px rgba(0, 0, 0, 0.8);
}

.profession-class {
    color: #888;
    font-style: italic;
    font-size: 1rem;
    margin-top: 0.3rem;
}

/* Przewijalna treść */
.profession-content-scroll {
    flex: 1;
    overflow-y: auto;
    padding-right: 1rem;
    /* Firefox scrollbar */
    scrollbar-width: thin;
    scrollbar-color: #d4af37 rgba(255,255,255,0.05);
}

/* Chrome/Safari Scrollbar */
.profession-content-scroll::-webkit-scrollbar { width: 6px; }
.profession-content-scroll::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
.profession-content-scroll::-webkit-scrollbar-thumb { background: #d4af37; border-radius: 3px; }

.profession-description {
    font-style: italic;
    color: #c09f80;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    padding: 1rem;
    border-left: 2px solid #d4af37;
    border-radius: 0 4px 4px 0;
}

.profession-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.detail-section {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    padding: 1rem;
}

.detail-title {
    color: #d4af37;
    font-size: 0.9rem;
    font-weight: bold;
    text-transform: uppercase;
    border-bottom: 1px solid rgba(212, 175, 55, 0.3);
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
    letter-spacing: 1px;
}

/* Statystyki */
.characteristics-table {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
    gap: 0.5rem;
}

.char-row {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: rgba(255, 255, 255, 0.05);
    padding: 0.4rem;
    border-radius: 4px;
}

.char-name { font-size: 0.7rem; color: #888; font-weight: bold; }
.char-bonus { font-size: 1rem; color: #d4af37; font-weight: bold; }

/* Umiejętności i Zdolności */
.items-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.group-label {
    font-size: 0.7rem;
    color: #666;
    text-transform: uppercase;
    display: block;
    margin-bottom: 0.3rem;
}

.tags-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.skill-tag, .talent-tag {
    background: #1a1a1a;
    border: 1px solid #444;
    color: #ccc;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 0.85rem;
}

/* Wybory (Choices) */
.choices-container {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
    border-top: 1px dashed #444;
    padding-top: 0.8rem;
}

.choice-block {
    background: rgba(212, 175, 55, 0.05);
    border-left: 3px solid #d4af37;
    padding: 0.6rem 0.8rem;
}

.choice-label {
    color: #d4af37;
    font-size: 0.7rem;
    font-weight: bold;
    text-transform: uppercase;
    display: block;
    margin-bottom: 0.3rem;
}

.choice-options {
    font-size: 0.9rem;
    color: #fff;
    line-height: 1.4;
}

.separator {
    color: #d4af37;
    font-size: 0.7rem;
    margin: 0 0.4rem;
    opacity: 0.7;
}

.choice-item {
    font-weight: 500;
}

/* Ekwipunek */
.equipment-list {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.equipment-item {
    font-size: 0.9rem;
    padding: 0.3rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.equipment-choice-row {
    background: rgba(212, 175, 55, 0.05);
    padding: 0.4rem;
    border-radius: 3px;
    display: flex;
    align-items: baseline;
}

.choice-label-inline {
    color: #d4af37;
    font-size: 0.7rem;
    font-weight: bold;
    margin-right: 0.5rem;
}

.text-separator {
    color: #d4af37;
    font-weight: bold;
    margin: 0 0.3rem;
}

/* =========================================
   6. KROK 3: IMIĘ (INPUTY)
   ========================================= */

.name-input-section {
    width: 100%;
    max-width: 500px;
    margin: 2rem auto;
}

.input-group { margin-bottom: 1.5rem; }
.input-label { display: block; color: #d4af37; margin-bottom: 0.5rem; font-weight: bold; }

.fantasy-input {
    width: 100%;
    padding: 12px;
    background: #0f0f0f;
    border: 1px solid #444;
    color: #e4d8b4;
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
    border-radius: 4px;
    transition: 0.3s;
}

.fantasy-input:focus {
    border-color: #d4af37;
    outline: none;
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
}

.suggestion-tags {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
}

.suggestion-tag {
    background: rgba(212, 175, 55, 0.1);
    color: #d4af37;
    padding: 4px 10px;
    border-radius: 15px;
    cursor: pointer;
    border: 1px solid rgba(212, 175, 55, 0.2);
    font-size: 0.9rem;
    transition: 0.2s;
}

.suggestion-tag:hover {
    background: #d4af37;
    color: #000;
}

/* =========================================
   7. STOPKA I NAWIGACJA
   ========================================= */

.navigation-section {
    padding: 1.5rem 2rem;
    background: rgba(0, 0, 0, 0.4);
    border-top: 1px solid #444;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
    margin-top: auto;
}

.nav-btn {
    background: #d4af37;
    border: none;
    padding: 10px 25px;
    font-family: 'Cinzel', serif;
    font-weight: bold;
    font-size: 1rem;
    color: #1a1a1a;
    cursor: pointer;
    border-radius: 3px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: 0.2s;
}

.nav-btn:hover:not(:disabled) {
    background: #f4d03f;
    box-shadow: 0 0 15px rgba(212, 175, 55, 0.4);
}

.nav-btn:disabled {
    background: #444;
    color: #777;
    cursor: not-allowed;
}

.nav-btn.prev-btn {
    background: transparent;
    border: 1px solid #666;
    color: #ccc;
}

.nav-btn.prev-btn:hover {
    color: #d4af37;
    border-color: #d4af37;
}

.step-indicators { display: flex; gap: 0.5rem; }
.step-dot { width: 8px; height: 8px; border-radius: 50%; background: #444; transition: 0.3s; }
.step-dot.active { background: #d4af37; transform: scale(1.4); }
.step-dot.completed { background: #888; }

.close-btn {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    background: none;
    border: none;
    color: #666;
    font-size: 1.5rem;
    cursor: pointer;
    z-index: 100;
    transition: 0.3s;
}

.close-btn:hover {
    color: #d4af37;
    transform: rotate(90deg);
}

/* =========================================
   8. ANIMACJE I MOBILE
   ========================================= */

@keyframes slideIn {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}

.slide-enter {
    animation: slideIn 0.4s ease-out;
}

@media (max-width: 1024px) {
    .profession-rolling-section {
        flex-direction: column;
        overflow-y: auto; /* Pozwala scrollować na tablecie */
        padding-bottom: 2rem;
    }

    .dice-roll-container {
        width: 100%;
        max-width: none;
        min-height: 350px;
        flex-shrink: 0;
    }

    .profession-display-container {
        width: 100%;
        height: auto;
        overflow: visible;
    }

    .profession-display {
        height: auto;
    }
}

@media (max-width: 768px) {
    .hero-creation-overlay { padding: 0; align-items: flex-end; }
    .creation-container { width: 100%; height: 100%; border-radius: 0; border: none; max-height: none; }
    .race-selection-grid { gap: 1rem; }
    .race-card { width: 100%; }
}

.detail-section.full-width {
    grid-column: 1 / -1; /* Rozciągnij na całą szerokość grida */
}

.stat-category-label {
    font-size: 0.8rem;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 0.5rem;
    font-weight: bold;
}

.secondary-label {
    margin-top: 1.5rem;
}

.wfrp-stat-grid {
    display: grid;
    /* 8 kolumn dla cech głównych i drugorzędnych */
    grid-template-columns: repeat(8, 1fr);
    border: 2px solid #d4af37;
    border-radius: 4px;
    background: rgba(0, 0, 0, 0.4);
    overflow: hidden; /* Żeby border-radius działał na dzieci */
}

.stat-cell {
    display: flex;
    flex-direction: column;
    align-items: center;
    border-right: 1px solid rgba(212, 175, 55, 0.3);
}

.stat-cell:last-child {
    border-right: none;
}

.stat-header {
    width: 100%;
    text-align: center;
    font-size: 0.75rem;
    font-weight: bold;
    color: #111;
    background: #d4af37; /* Złote tło nagłówka */
    padding: 0.3rem 0;
    text-transform: uppercase;
}

.stat-value {
    width: 100%;
    text-align: center;
    font-size: 1.1rem;
    font-weight: bold;
    color: #e4d8b4;
    padding: 0.5rem 0;
    min-height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Responsywność dla tabelki */
@media (max-width: 600px) {
    .wfrp-stat-grid {
        /* Na bardzo małych ekranach dzielimy na 2 rzędy po 4 */
        grid-template-columns: repeat(4, 1fr);
    }

    .stat-cell:nth-child(4n) {
        border-right: none; /* Usuń border co 4 element */
    }

    .stat-cell:nth-child(-n+4) {
        border-bottom: 1px solid rgba(212, 175, 55, 0.3); /* Linia pozioma */
    }
}
</style>
