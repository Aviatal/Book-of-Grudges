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
                                <!-- Nagłówek profesji -->
                                <div class="profession-header">
                                    <h3 class="profession-name">{{ selectedProfession.name }}</h3>
                                    <div class="profession-class">{{ selectedProfession.class }}</div>
                                    <div class="dice-roll-indicator">
                                        Wynik rzutu: {{ selectedProfession.diceRoll }}
                                    </div>
                                </div>

                                <!-- Opis i detale - teraz w przewijalnym kontenerze -->
                                <div class="profession-content-scroll">Q
                                    <div class="profession-description">
                                        <p>{{ selectedProfession.description }}</p>
                                    </div>

                                    <div class="profession-details-grid">
                                        <!-- Schemat rozwoju -->
                                        <div class="detail-section">
                                            <h4 class="detail-title">Schemat Rozwoju</h4>
                                            <div class="characteristics-table">
                                                <div class="char-row"
                                                     v-for="(bonus, charKey) in selectedProfession.characteristics"
                                                     :key="charKey">
                                                    <span class="char-name">{{ getCharacteristicName(charKey) }}</span>
                                                    <span class="char-bonus">+{{ bonus }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Umiejętności -->
                                        <div class="detail-section">
                                            <h4 class="detail-title">Umiejętności</h4>
                                            <div class="skills-list">
                                                <span v-for="skill in selectedProfession.skills" :key="skill" class="skill-tag">
                                                    {{ skill }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Zdolności -->
                                        <div class="detail-section">
                                            <h4 class="detail-title">Zdolności</h4>
                                            <div class="talents-list">
                                                <span v-for="talent in selectedProfession.talents" :key="talent" class="talent-tag">
                                                    {{ talent }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Wyposażenie -->
                                        <div class="detail-section">
                                            <h4 class="detail-title">Wyposażenie</h4>
                                            <div class="equipment-list">
                                                <div v-for="item in selectedProfession.equipment" :key="item"
                                                     class="equipment-item">
                                                    {{ item }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Profesje wstępne -->
                                        <div class="detail-section">
                                            <h4 class="detail-title">Profesje Wstępne</h4>
                                            <div class="professions-list">
                                                <span v-for="prof in selectedProfession.entryProfessions" :key="prof"
                                                      class="profession-tag entry">
                                                    {{ prof }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Profesje wyjściowe -->
                                        <div class="detail-section">
                                            <h4 class="detail-title">Profesje Wyjściowe</h4>
                                            <div class="professions-list">
                                                <span v-for="prof in selectedProfession.exitProfessions" :key="prof"
                                                      class="profession-tag exit">
                                                    {{ prof }}
                                                </span>
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
import {ref, computed, onMounted, nextTick, watch} from 'vue'
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

// Metoda rzutu z prawdziwą fizyką
const rollForProfession = async () => {
    if (!selectedRace.value || isRollingProfession.value) return

    // Jeśli DiceBox nie jest zainicjalizowany, spróbuj ponownie
    if (!diceBox) {
        console.log('DiceBox nie jest zainicjalizowany, próba ponownej inicjalizacji...')
        await initializeDiceBox()

        if (!diceBox) {
            console.error('Nie można zainicjalizować DiceBox')
            return
        }
    }

    try {
        isRollingProfession.value = true
        selectedProfession.value = null
        professionRoll.value = 0

        console.log('DiceBox state:', diceBox)

        // Wyczyść poprzednie kostki
        await diceBox.clear()

        console.log('Rolling dice...')
        // Rzuć dwoma d10
        const results = await diceBox.roll('1d100')

        console.log('Roll results:', results)

        // Przetwórz wyniki
        const total = results[0].value

        professionRoll.value = total;

        // Wylosuj profesję
        try {
            const raceKey = selectedRace.value.key.toLowerCase()
            console.log(raceKey, total)
            selectedProfession.value = rollProfession(raceKey, total)
            heroData.value.profession = selectedProfession.value
        } catch (error) {
            console.error('Błąd podczas losowania profesji:', error)
            selectedProfession.value = {
                name: 'Chłop',
                description: 'Prosty rolnik pracujący na roli',
                diceRoll: total,
                class: 'Łokciowa',
                characteristics: {},
                skills: [],
                talents: [],
                equipment: [],
                entryProfessions: [],
                exitProfessions: []
            }
        }

    } catch (error) {
        console.error('Błąd rzutu kostkami:', error)
    } finally {
        isRollingProfession.value = false
    }
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
.hero-creation-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 1000;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    overflow-y: auto;
    padding: 2rem 0;
}

.creation-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
    overflow: hidden;
    pointer-events: none;
}

.parchment-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at center, rgba(212, 175, 55, 0.1) 0%, rgba(0, 0, 0, 0.7) 70%);
    backdrop-filter: blur(1px);
}

.floating-runes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.rune {
    position: absolute;
    width: 60px;
    height: 60px;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23d4af37" opacity="0.2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>') no-repeat center;
    animation: float 6s ease-in-out infinite;
}

.rune:nth-child(1) {
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.rune:nth-child(2) {
    top: 20%;
    right: 15%;
    animation-delay: 1.5s;
}

.rune:nth-child(3) {
    bottom: 20%;
    left: 20%;
    animation-delay: 3s;
}

.rune:nth-child(4) {
    bottom: 15%;
    right: 10%;
    animation-delay: 4.5s;
}

.rune:nth-child(5) {
    top: 50%;
    left: 5%;
    animation-delay: 2s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
    }
}

.creation-container {
    position: relative;
    width: 95vw;
    max-width: 1600px;
    min-height: 80vh;
    max-height: none;
    background: linear-gradient(145deg, #2a2926 0%, #3d3a35 100%);
    border: 3px solid #d4af37;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.1);
    display: flex;
    flex-direction: column;
    font-family: 'Cinzel', serif;
    margin: auto;
}

.progress-section {
    padding: 2rem;
    border-bottom: 2px solid #d4af37;
    text-align: center;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: rgba(212, 175, 55, 0.2);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 1rem;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #d4af37 0%, #f4d03f 100%);
    border-radius: 3px;
    transition: width 0.5s ease-out;
}

.progress-text {
    color: #d4af37;
    font-size: 1.1rem;
    font-weight: 600;
}

.creation-slide {
    flex: 1;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    gap: 2rem;
    overflow-y: auto;
    max-height: calc(100vh - 300px);
}

.slide-enter {
    animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.slide-header {
    text-align: center;
}

.slide-title {
    font-size: 2.5rem;
    color: #d4af37;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.slide-subtitle {
    font-size: 1.2rem;
    color: #c09f80;
    margin: 0;
}

/* Race Selection Styles */
.race-selection-grid {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 2rem;
    flex-wrap: nowrap;
}

.race-card {
    background: linear-gradient(145deg, #3a3935 0%, #4a4540 100%);
    border: 2px solid rgba(212, 175, 55, 0.3);
    border-radius: 15px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    flex: 1;
    max-width: 320px;
    min-width: 280px;
}

.race-card:hover {
    border-color: #d4af37;
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.2);
}

.race-card.active {
    border-color: #d4af37;
    background: linear-gradient(145deg, #4a4540 0%, #5a5145 100%);
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
}

/* Zmienione wartości */
.race-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    background: rgba(212, 175, 55, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border: 2px solid rgba(212, 175, 55, 0.5);
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
    text-align: center;
}

.race-description {
    color: #c09f80;
    font-size: 0.95rem;
    text-align: center;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.race-bonuses {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
}

.bonus-tag {
    background: rgba(212, 175, 55, 0.2);
    color: #d4af37;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.selection-indicator {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #d4af37;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.race-card.active .selection-indicator {
    opacity: 1;
}

.check-mark {
    color: #2a2926;
    font-size: 1.2rem;
    font-weight: bold;
}

/* Name Input Styles */
.name-input-section {
    max-width: 600px;
    margin: 0 auto;
}

.input-group {
    margin-bottom: 2rem;
}

.input-label {
    display: block;
    color: #d4af37;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.fantasy-input {
    width: 100%;
    padding: 1rem;
    background: rgba(42, 41, 38, 0.8);
    border: 2px solid rgba(212, 175, 55, 0.3);
    border-radius: 10px;
    color: #e4d8b4;
    font-size: 1.1rem;
    font-family: 'Cinzel', serif;
    transition: all 0.3s ease;
}

.fantasy-input:focus {
    outline: none;
    border-color: #d4af37;
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
}

.name-suggestions {
    margin-top: 2rem;
    text-align: center;
}

.name-suggestions h4 {
    color: #c09f80;
    margin-bottom: 1rem;
}

.suggestion-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.8rem;
    justify-content: center;
}

.suggestion-tag {
    background: rgba(192, 159, 128, 0.2);
    color: #c09f80;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

.suggestion-tag:hover {
    background: rgba(212, 175, 55, 0.2);
    color: #d4af37;
    transform: scale(1.05);
}

/* Dice Rolling Styles */
.dice-rolling-section {
    max-width: 800px;
    margin: 0 auto;
}

.characteristics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.characteristic-card {
    background: linear-gradient(145deg, #3a3935 0%, #4a4540 100%);
    border: 2px solid rgba(212, 175, 55, 0.3);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.characteristic-card.rolling {
    border-color: #d4af37;
    box-shadow: 0 0 15px rgba(212, 175, 55, 0.4);
}

.characteristic-name {
    color: #d4af37;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.dice-container {
    margin-bottom: 1rem;
}

.dice-display {
    margin-bottom: 0.5rem;
}

.dice-faces {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.die-face {
    width: 40px;
    height: 40px;
    background: #d4af37;
    color: #2a2926;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: bold;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.rolling-dice {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.rolling-die {
    font-size: 2rem;
    animation: roll 0.5s infinite;
}

@keyframes roll {
    0%, 100% {
        transform: rotate(0deg);
    }
    25% {
        transform: rotate(90deg);
    }
    50% {
        transform: rotate(180deg);
    }
    75% {
        transform: rotate(270deg);
    }
}

.dice-canvas {
    width: 100%;
    height: 300px !important;
    border-radius: 15px;
    background: linear-gradient(135deg, #1a4a1a 0%, #0d2d0d 100%);
    border: 4px solid #8b4513;
    box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.7),
    0 10px 30px rgba(0, 0, 0, 0.4);
    position: relative;
    overflow: hidden;
}

.dice-canvas::before {
    content: '';
    position: absolute;
    top: 8px;
    left: 8px;
    right: 8px;
    bottom: 8px;
    border: 2px solid rgba(212, 175, 55, 0.4);
    border-radius: 10px;
    pointer-events: none;
    z-index: 1;
}

.profession-rolling-section {
    max-width: 1000px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 2rem;
    /* Rezerwujemy miejsce na dole, by scroll nie skakał przy pojawieniu się profesji */
    padding-bottom: 50px;
}

.profession-display-container {
    width: 100%;
    margin-top: 1rem;
    /* Zapobiegamy gwałtownemu rozpychaniu */
    overflow: hidden;
}

.dice-roll-container {
    text-align: center;
    padding: 2rem;
    background: rgba(42, 41, 38, 0.5);
    border-radius: 15px;
    border: 2px solid rgba(212, 175, 55, 0.3);
    /* Wymuszamy stałą wysokość całego bloku rzutu, aby opis profesji pod spodem nie skakał */
    min-width: 1000px;
    display: flex;
    min-height: 800px;
    flex-direction: column;
    align-items: center;
}

.dice-result-display {
    margin-top: 2rem;
    text-align: center;
}

.individual-results {
    display: flex;
    justify-content: center;
    gap: 3rem;
    margin-bottom: 1.5rem;
}

.die-result {
    background: rgba(42, 41, 38, 0.7);
    padding: 1.5rem;
    border-radius: 12px;
    border: 2px solid rgba(212, 175, 55, 0.3);
    min-width: 160px;
    transition: all 0.3s ease;
}

.die-result.tens {
    background: linear-gradient(145deg, #8b4513 0%, #a0522d 100%);
    border-color: #d2691e;
}

.die-result.units {
    background: linear-gradient(145deg, #2e7d32 0%, #388e3c 100%);
    border-color: #4caf50;
}

.die-label {
    display: block;
    color: #e4d8b4;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.die-value {
    display: block;
    color: white;
    font-size: 2.2rem;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
}

.total-result {
    background: linear-gradient(145deg, #d4af37 0%, #f4d03f 100%);
    color: #2a2926;
    padding: 2rem;
    border-radius: 20px;
    display: inline-block;
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.5),
    inset 0 0 20px rgba(255, 255, 255, 0.2);
    animation: resultAppear 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    transform: scale(1.05);
}

@keyframes resultAppear {
    0% {
        transform: scale(0) rotate(-180deg);
        opacity: 0;
    }
    50% {
        transform: scale(1.15) rotate(-90deg);
        opacity: 0.8;
    }
    100% {
        transform: scale(1.05) rotate(0deg);
        opacity: 1;
    }
}

.total-label {
    display: block;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: bold;
}

.total-value {
    display: block;
    font-size: 3.5rem;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.roll-controls {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    margin-top: 2rem;
}

.roll-profession-btn {
    background: linear-gradient(145deg, #d4af37 0%, #f4d03f 100%);
    color: #2a2926;
    border: none;
    padding: 1.2rem 2.5rem;
    border-radius: 12px;
    font-size: 1.2rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.roll-profession-btn:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(212, 175, 55, 0.6);
}

:deep(.dice-box-canvas),
:deep(canvas[id^="dice-canvas"]) {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    display: block !important;
}

.roll-profession-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.reroll-btn {
    background: rgba(212, 175, 55, 0.2);
    color: #d4af37;
    border: 2px solid #d4af37;
    padding: 1rem 2rem;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.reroll-btn:hover {
    background: rgba(212, 175, 55, 0.3);
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
}

.characteristic-total {
    font-size: 2rem;
    font-weight: bold;
    color: #d4af37;
}

.reroll-btn {
    background: rgba(212, 175, 55, 0.2);
    color: #d4af37;
    border: 1px solid rgba(212, 175, 55, 0.5);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.reroll-btn:hover:not(:disabled) {
    background: rgba(212, 175, 55, 0.3);
    transform: scale(1.05);
}

.reroll-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.dice-controls {
    text-align: center;
}

.roll-all-btn {
    background: linear-gradient(145deg, #d4af37 0%, #f4d03f 100%);
    color: #2a2926;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.roll-all-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
}

/* Navigation Styles */
.navigation-section {
    padding: 2rem;
    border-top: 2px solid #d4af37;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-btn {
    background: linear-gradient(145deg, #d4af37 0%, #f4d03f 100%);
    color: #2a2926;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: 'Cinzel', serif;
}

.nav-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
}

.nav-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.finish-btn {
    background: linear-gradient(145deg, #c09f80 0%, #d4af37 100%);
}

.step-indicators {
    display: flex;
    gap: 0.5rem;
}

.step-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(212, 175, 55, 0.3);
    transition: all 0.3s ease;
}

.step-dot.active {
    background: #d4af37;
    transform: scale(1.2);
}

.step-dot.completed {
    background: #c09f80;
}

.close-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 50%;
    color: #d4af37;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-btn:hover {
    background: rgba(212, 175, 55, 0.2);
    transform: scale(1.1);
}

.close-icon {
    font-size: 1.2rem;
    font-weight: bold;
}

.btn-icon {
    font-size: 1.1rem;
}

.profession-content-scroll {
    flex: 1;
    overflow-y: auto;
    padding-right: 10px;
    margin-top: 1rem;
}

.profession-content-scroll {
    flex: 1;
    overflow-y: auto;
    padding-right: 10px;
    margin-top: 1rem;
}

/* Stylizacja scrollbara w klimacie Warhammera */
.profession-content-scroll::-webkit-scrollbar {
    width: 8px;
}
.profession-content-scroll::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 4px;
}
.profession-content-scroll::-webkit-scrollbar-thumb {
    background: #d4af37;
    border-radius: 4px;
}

@keyframes throwMotion {
    0% {
        transform: translateX(-50%) translateY(-50px) rotate(-45deg);
        opacity: 1;
    }
    50% {
        transform: translateX(-50%) translateY(0px) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translateX(-50%) translateY(50px) rotate(45deg);
        opacity: 0;
    }
}

@keyframes bounce-flash {
    0%, 100% {
        filter: brightness(1);
    }
    50% {
        filter: brightness(1.3) drop-shadow(0 0 10px #d4af37);
    }
}

@keyframes settleGlow {
    0% {
        filter: drop-shadow(0 0 20px rgba(212, 175, 55, 0.8)) brightness(1.2);
    }
    100% {
        filter: drop-shadow(0 0 5px rgba(212, 175, 55, 0.3)) brightness(1);
    }
}

.profession-rolling-section {
    max-width: 1000px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.dice-roll-container {
    text-align: center;
    padding: 2rem;
    background: rgba(42, 41, 38, 0.5);
    border-radius: 15px;
    border: 2px solid rgba(212, 175, 55, 0.3);
}

@keyframes diceTableShake {
    0%, 100% {
        transform: translateX(0) translateY(0);
    }
    25% {
        transform: translateX(-3px) translateY(-1px);
    }
    50% {
        transform: translateX(3px) translateY(1px);
    }
    75% {
        transform: translateX(-2px) translateY(2px);
    }
}

@keyframes bounce {
    0% {
        transform: translateY(0px) rotateX(0deg);
    }
    100% {
        transform: translateY(-10px) rotateX(180deg);
    }
}

.total-result {
    font-size: 2.5rem;
    color: #d4af37;
    font-weight: bold;
    margin-left: 1rem;
}

.roll-controls {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

.roll-profession-btn {
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
    gap: 0.5rem;
}

.roll-profession-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.5);
}

.roll-profession-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.reroll-btn {
    background: rgba(212, 175, 55, 0.2);
    color: #d4af37;
    border: 2px solid #d4af37;
    padding: 0.8rem 1.5rem;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.reroll-btn:hover {
    background: rgba(212, 175, 55, 0.3);
    transform: scale(1.05);
}

.dice-d10-container:nth-child(1) .triangle-face {
    background: #8b0000;
    color: white;
}

/* Czerwona - dziesiątki */
.dice-d10-container:nth-child(2) .triangle-face {
    background: #1a1a1a;
    color: #d4af37;
}

@keyframes realisticThrow {
    0% {
        transform: translateX(-50%) translateY(-80px) rotate(-60deg) scale(0.8);
        opacity: 0.8;
    }
    30% {
        transform: translateX(-50%) translateY(-20px) rotate(-20deg) scale(1);
        opacity: 1;
    }
    70% {
        transform: translateX(-50%) translateY(20px) rotate(30deg) scale(1.1);
        opacity: 1;
    }
    100% {
        transform: translateX(-50%) translateY(80px) rotate(60deg) scale(1.2);
        opacity: 0;
    }
}

@keyframes tableShake {
    0%, 100% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(-2px) translateY(-1px);
    }
    75% {
        transform: translateX(2px) translateY(1px);
    }
}

@keyframes diceRoll {
    0% {
        filter: blur(0px);
    }
    50% {
        filter: blur(2px);
    }
    100% {
        filter: blur(0px);
    }
}

.total-result {
    background: linear-gradient(145deg, #d4af37 0%, #f4d03f 100%);
    color: #2a2926;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.5),
    inset 0 0 20px rgba(255, 255, 255, 0.2);
    animation: resultAppear 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes resultAppear {
    0% {
        transform: scale(0) rotate(-180deg);
        opacity: 0;
    }
    50% {
        transform: scale(1.1) rotate(-90deg);
        opacity: 0.8;
    }
    100% {
        transform: scale(1) rotate(0deg);
        opacity: 1;
    }
}

.dice-result-display {
    margin-top: 2rem;
    text-align: center;
}

.total-result {
    background: linear-gradient(145deg, #d4af37 0%, #f4d03f 100%);
    color: #2a2926;
    padding: 1.5rem;
    border-radius: 15px;
    display: inline-block;
    box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
    transform: scale(1.05);
}

.total-label {
    display: block;
    font-size: 1rem;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: bold;
}

.total-value {
    display: block;
    font-size: 3rem;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

@keyframes glowPulse {
    0% {
        opacity: 0.3;
        transform: scale(1);
    }
    100% {
        opacity: 0.7;
        transform: scale(1.1);
    }
}

/* Profession Display */
.profession-display {
    background: linear-gradient(145deg, #3a3935 0%, #4a4540 100%);
    border: 2px solid #d4af37;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    /* Stała wysokość lub max-height dla stabilności */
    height: 600px;
    display: flex;
    flex-direction: column;
}


.profession-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid rgba(212, 175, 55, 0.3);
}

.profession-name {
    font-size: 2.2rem;
    color: #d4af37;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.profession-class {
    color: #c09f80;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.dice-roll-indicator {
    background: rgba(212, 175, 55, 0.2);
    color: #d4af37;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    display: inline-block;
    font-weight: bold;
}

.profession-description {
    text-align: center;
    margin-bottom: 2rem;
    padding: 1rem;
    background: rgba(42, 41, 38, 0.5);
    border-radius: 10px;
    color: #e4d8b4;
    font-size: 1.1rem;
    line-height: 1.6;
    font-style: italic;
}

.profession-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.detail-section {
    background: rgba(42, 41, 38, 0.3);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid rgba(212, 175, 55, 0.2);
}

.detail-title {
    color: #d4af37;
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 1rem;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.characteristics-table {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 0.5rem;
}

.char-row {
    display: contents;
}

.char-name {
    color: #c09f80;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(212, 175, 55, 0.1);
}

.char-bonus {
    color: #d4af37;
    font-weight: bold;
    text-align: right;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(212, 175, 55, 0.1);
}

.skills-list, .talents-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.skill-tag, .talent-tag {
    background: rgba(192, 159, 128, 0.2);
    color: #c09f80;
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
    font-size: 0.9rem;
    font-weight: 500;
}

.talent-tag {
    background: rgba(212, 175, 55, 0.2);
    color: #d4af37;
}

.equipment-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.equipment-item {
    color: #e4d8b4;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(212, 175, 55, 0.1);
    font-size: 0.95rem;
}

.equipment-item:last-child {
    border-bottom: none;
}

.professions-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.profession-tag {
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
    font-size: 0.9rem;
    font-weight: 500;
}

.profession-tag.entry {
    background: rgba(159, 122, 234, 0.2);
    color: #9f7aea;
}

.profession-tag.exit {
    background: rgba(72, 187, 120, 0.2);
    color: #48bb78;
}

.profession-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    max-height: 60vh; /* Ograniczenie wysokości */
    overflow-y: auto; /* Przewijanie jeśli za długie */
    padding-right: 0.5rem; /* Miejsce na scrollbar */
}

/* Stylizacja scrollbar */
.profession-details-grid::-webkit-scrollbar,
.creation-slide::-webkit-scrollbar,
.hero-creation-overlay::-webkit-scrollbar {
    width: 8px;
}

.profession-details-grid::-webkit-scrollbar-track,
.creation-slide::-webkit-scrollbar-track,
.hero-creation-overlay::-webkit-scrollbar-track {
    background: rgba(42, 41, 38, 0.3);
    border-radius: 4px;
}

.profession-details-grid::-webkit-scrollbar-thumb,
.creation-slide::-webkit-scrollbar-thumb,
.hero-creation-overlay::-webkit-scrollbar-thumb {
    background: rgba(212, 175, 55, 0.5);
    border-radius: 4px;
}

.profession-details-grid::-webkit-scrollbar-thumb:hover,
.creation-slide::-webkit-scrollbar-thumb:hover,
.hero-creation-overlay::-webkit-scrollbar-thumb:hover {
    background: rgba(212, 175, 55, 0.7);
}

@media (max-width: 768px) {
    .dice-canvas {
        height: 250px;
    }

    .total-value {
        font-size: 2.8rem;
    }

    .roll-controls {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .profession-details-grid {
        grid-template-columns: 1fr;
    }
}

.bottom-face .face-number {
    transform: rotateZ(180deg);
}

@media (max-width: 1400px) {
    .creation-container {
        max-width: 1400px;
    }

    .race-selection-grid {
        gap: 1.5rem;
    }

    .race-card {
        max-width: 300px;
        min-width: 260px;
    }
}

@media (max-width: 1200px) {
    .creation-container {
        max-width: 1200px;
    }

    .race-card {
        max-width: 280px;
        min-width: 240px;
    }
}

@media (max-height: 800px) {
    .creation-slide {
        max-height: calc(100vh - 200px);
    }
}

@media (max-width: 768px) {
    .hero-creation-overlay {
        padding: 1rem 0;
    }

    .creation-container {
        width: 95vw;
        min-height: auto;
    }

    .creation-slide {
        max-height: calc(100vh - 250px);
        padding: 1rem;
    }

    .slide-title {
        font-size: 2rem;
    }

    .race-selection-grid {
        flex-wrap: wrap;
        justify-content: center;
    }

    .race-card {
        flex: none;
        min-width: 250px;
        max-width: 300px;
    }

    .characteristics-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }

    .navigation-section {
        flex-direction: column;
        gap: 1rem;
    }
}

/* Globalne style dla canvas DiceBox */
#dice-box-canvas canvas {
    width: 100% !important;
    height: 300px !important;
    display: block !important;
    position: relative !important;
    z-index: 2 !important;
}

.dice-box-canvas {
    width: 100% !important;
    height: 300px !important;
    display: block !important;
    position: relative !important;
    z-index: 2 !important;
}

</style>
