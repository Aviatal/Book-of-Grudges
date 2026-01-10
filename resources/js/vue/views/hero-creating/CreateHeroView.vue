
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
                            <img :src="race.icon" :alt="race.name" />
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

            <!-- Slajd 2: Imię bohatera -->
            <div v-if="currentStep === 2" class="creation-slide slide-enter">
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

            <!-- Slajd 3: Rzut kostkami na cechy -->
            <div v-if="currentStep === 3" class="creation-slide slide-enter">
                <div class="slide-header">
                    <h2 class="slide-title">Ustal cechy swojego bohatera</h2>
                    <p class="slide-subtitle">Rzuć kostkami aby określić bazowe cechy</p>
                </div>

                <div class="dice-rolling-section">
                    <div class="characteristics-grid">
                        <div
                            v-for="(characteristic, key) in characteristics"
                            :key="key"
                            class="characteristic-card"
                            :class="{ rolling: characteristic.isRolling }"
                        >
                            <div class="characteristic-name">{{ characteristic.name }}</div>
                            <div class="dice-container">
                                <div class="dice-display">
                                    <div class="dice-faces" v-if="!characteristic.isRolling">
                                        <div
                                            v-for="die in characteristic.dice"
                                            :key="die.id"
                                            class="die-face"
                                        >
                                            {{ die.value }}
                                        </div>
                                    </div>
                                    <div class="rolling-dice" v-else>
                                        <div class="rolling-die" v-for="i in 2" :key="i">🎲</div>
                                    </div>
                                </div>
                                <div class="characteristic-total">
                                    {{ characteristic.total }}
                                </div>
                            </div>
                            <button
                                class="reroll-btn"
                                @click="rollCharacteristic(key)"
                                :disabled="characteristic.isRolling"
                            >
                                {{ characteristic.isRolling ? 'Rzucam...' : 'Przerzuć' }}
                            </button>
                        </div>
                    </div>

                    <div class="dice-controls">
                        <button class="roll-all-btn" @click="rollAllCharacteristics">
                            <span class="btn-icon">🎲</span>
                            Rzuć wszystkimi kostkami
                        </button>
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
import { ref, computed, onMounted } from 'vue'

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

// Dostępne rasy (przykładowe dane)
const availableRaces = ref([
    {
        id: 1,
        name: 'Człowiek',
        description: 'Wszechstronni i ambitni mieszkańcy Imperium',
        icon: '/images/races/human.png',
        bonuses: ['Wszechstronność', '+5 do jednej cechy'],
        suggestedNames: ['Magnus', 'Sigmar', 'Wilhelm', 'Brunhilde', 'Katarina']
    },
    {
        id: 2,
        name: 'Krasnolud',
        description: 'Krzepcy i odważni górnicy i rzemieślnicy',
        icon: '/images/races/dwarf.png',
        bonuses: ['Odporność', '+10 Wytrzymałość', 'Noktowizja'],
        suggestedNames: ['Gotrek', 'Thorgrim', 'Bardin', 'Valka', 'Mira']
    },
    {
        id: 3,
        name: 'Elf',
        description: 'Graccy i długowieczni strażnicy lasu',
        icon: '/images/races/elf.png',
        bonuses: ['Zręczność', '+10 Zręczność', 'Wyczulone zmysły'],
        suggestedNames: ['Tyrion', 'Teclis', 'Alarielle', 'Araloth', 'Naestra']
    },
    {
        id: 4,
        name: 'Niziołek',
        description: 'Spokojni mieszkańcy wiejskich hrabstw',
        icon: '/images/races/halfling.png',
        bonuses: ['Szczęście', '+10 Szczęście', 'Odporność na strach'],
        suggestedNames: ['Ludo', 'Hisme', 'Peregrin', 'Rosie', 'Mungo']
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

// Computed properties
const progressPercentage = computed(() => {
    return (currentStep.value / totalSteps) * 100
})

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
const startCreation = () => {
    isCreating.value = true
    currentStep.value = 1
}

const closeCreation = () => {
    isCreating.value = false
    emit('creation-closed')
}

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
            { id: 1, value: die1 },
            { id: 2, value: die2 }
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
    align-items: center;
    justify-content: center;
}

.creation-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
    overflow: hidden;
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

.rune:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
.rune:nth-child(2) { top: 20%; right: 15%; animation-delay: 1.5s; }
.rune:nth-child(3) { bottom: 20%; left: 20%; animation-delay: 3s; }
.rune:nth-child(4) { bottom: 15%; right: 10%; animation-delay: 4.5s; }
.rune:nth-child(5) { top: 50%; left: 5%; animation-delay: 2s; }

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.creation-container {
    position: relative;
    width: 90vw;
    max-width: 1200px;
    min-height: 80vh;
    background: linear-gradient(145deg, #2a2926 0%, #3d3a35 100%);
    border: 3px solid #d4af37;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.1);
    display: flex;
    flex-direction: column;
    font-family: 'Cinzel', serif;
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
    background: linear-gradient(90deg, #d4af37 0%, #f4d03f  100%);
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
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
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

.race-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
    border-radius: 50%;
    background: rgba(212, 175, 55, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.race-icon img {
    width: 50px;
    height: 50px;
    object-fit: contain;
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
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(90deg); }
    50% { transform: rotate(180deg); }
    75% { transform: rotate(270deg); }
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

/* Responsive */
@media (max-width: 768px) {
    .creation-container {
        width: 95vw;
        min-height: 90vh;
    }

    .slide-title {
        font-size: 2rem;
    }

    .race-selection-grid {
        grid-template-columns: 1fr;
    }

    .characteristics-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }

    .navigation-section {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
