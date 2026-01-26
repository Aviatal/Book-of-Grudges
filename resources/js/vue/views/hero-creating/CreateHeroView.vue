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
                    <h2 class="slide-title">Cechy Bohatera</h2>
                    <p class="slide-subtitle">Najpierw rzuć na cechy główne i przypisz je, a następnie wylosuj Żywotność i Przeznaczenie.</p>
                </div>

                <div class="attributes-assignment-container">

                    <div class="stats-column">

                        <div class="section-label">Cechy Główne</div>
                        <div class="stats-header-row">
                            <span class="col-lbl">Cecha</span>
                            <span class="col-lbl">Baza</span>
                            <span class="col-lbl">Suma</span>
                            <span class="col-lbl action-col">Przypisz</span>
                        </div>

                        <div
                            v-for="(char, key) in characteristics"
                            :key="key"
                            class="stat-assignment-row"
                            :class="{
                                'ready-to-receive': selectedPoolIndex !== null && char.assignedValue === null,
                                'filled': char.assignedValue !== null
                            }"
                            @click="assignSelectedToStat(key)"
                        >
                            <div class="stat-name-block">
                                <span class="stat-abbr">{{ mainProfileConfig.find(c => c.keys.includes(key))?.label }}</span>
                                <span class="stat-full">{{ char.name }}</span>
                            </div>

                            <div class="stat-base-val">{{ char.base }}</div>

                            <div class="stat-total-val">
                                <span v-if="char.assignedValue !== null">{{ char.base + char.assignedValue }}</span>
                                <span v-else class="dimmed">-</span>
                            </div>

                            <div class="stat-assigned-slot">
                                <span v-if="char.assignedValue !== null" class="assigned-val">
                                    +{{ char.assignedValue }}
                                </span>
                                <span v-else class="empty-slot-marker">
                                    {{ selectedPoolIndex !== null ? '↓' : '—' }}
                                </span>
                                <button
                                    v-if="char.assignedValue !== null"
                                    class="remove-assign-btn"
                                    @click.stop="unassignStat(key)"
                                    title="Cofnij"
                                >✕</button>
                            </div>
                        </div>

                        <div class="secondary-stats-section">
                            <div class="section-label mt-4">Cechy Drugorzędne (Rzut 1k10)</div>

                            <div class="secondary-row">
                                <div class="sec-name">
                                    <span class="stat-abbr">Żyw</span>
                                    <span class="stat-full">Żywotność</span>
                                </div>
                                <div class="sec-val">
                                    <span v-if="secondaryStats.wounds.val" class="final-val">{{ secondaryStats.wounds.val }}</span>
                                    <span v-else class="dimmed">-</span>
                                </div>
                                <div class="sec-action">
                                    <button
                                        v-if="!secondaryStats.wounds.val"
                                        class="mini-roll-btn"
                                        @click="rollSecondary('wounds')"
                                        :disabled="isRollingAny"
                                    >
                                        🎲 Rzuć
                                    </button>
                                    <span v-else class="roll-info">
                                        (Rzut: {{ secondaryStats.wounds.roll }})
                                    </span>
                                </div>
                            </div>

                            <div class="secondary-row">
                                <div class="sec-name">
                                    <span class="stat-abbr">PP</span>
                                    <span class="stat-full">Przeznaczenie</span>
                                </div>
                                <div class="sec-val">
                                    <span v-if="secondaryStats.fate.val" class="final-val">{{ secondaryStats.fate.val }}</span>
                                    <span v-else class="dimmed">-</span>
                                </div>
                                <div class="sec-action">
                                    <button
                                        v-if="!secondaryStats.fate.val"
                                        class="mini-roll-btn"
                                        @click="rollSecondary('fate')"
                                        :disabled="isRollingAny"
                                    >
                                        🎲 Rzuć
                                    </button>
                                    <span v-else class="roll-info">
                                        (Rzut: {{ secondaryStats.fate.roll }})
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="pool-column">
                        <div class="dice-area-small">
                            <div id="dice-box-attributes" class="dice-canvas"></div>

                            <button
                                v-if="rollPool.length === 0"
                                class="roll-attributes-btn"
                                @click="rollAttributesPool"
                                :disabled="isRollingAny"
                            >
                                <span class="btn-icon">🎲</span> Rzuć Pule Cech
                            </button>
                        </div>

                        <div class="results-pool-grid" v-if="rollPool.length > 0">
                            <div class="pool-label">Pula Cech Głównych:</div>

                            <div class="pool-items">
                                <div
                                    v-for="(item, index) in rollPool"
                                    :key="item.id"
                                    class="pool-item"
                                    :class="{ 'selected': selectedPoolIndex === index, 'used': item.isUsed }"
                                    @click="selectPoolItem(index)"
                                >
                                    {{ item.value }}
                                    <div class="used-overlay" v-if="item.isUsed">✓</div>
                                </div>
                            </div>

                            <div class="pool-actions">
                                <div v-if="!mercyUsed && selectedPoolIndex !== null && !rollPool[selectedPoolIndex].isUsed">
                                    <button class="reroll-one-btn" @click="rerollSelectedPoolItem">
                                        ✨ Łaska Shallyi (Przerzut)
                                    </button>
                                </div>
                                <div v-else-if="mercyUsed" class="mercy-used-info">Łaska Shallyi zużyta</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="currentStep === 4" class="creation-slide slide-enter">
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
                        <h4>Sugerowane imiona:</h4>
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

// Props i emits
const emit = defineEmits(['hero-created', 'creation-closed'])

// Stan komponentu
const isCreating = ref(false)
const currentStep = ref(1)
const totalSteps = 4

let diceBox = null // Instancja dla profesji
let diceBoxAttributes = null // Instancja dla atrybutów

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
        id: 1, name: 'Człowiek', key: 'human', description: 'Wszechstronni i ambitni', icon: '/images/races/human.png',
        bonuses: ['Wszechstronność', 'Ambicja'],
        suggestedNames: ['Felix', 'Klara', 'Otto', 'Mathilde'],
        baseStats: { WW: 20, US: 20, K: 20, Odp: 20, Zr: 20, Int: 20, SW: 20, Ogd: 20 }
    },
    {
        id: 2, name: 'Krasnolud', key: 'dwarf', description: 'Krzepcy i odważni', icon: '/images/races/dwarf.png',
        bonuses: ['Odporność na magię', 'Krzepki', 'Widzenie w ciemności'],
        suggestedNames: ['Gotrek', 'Bardin', 'Greta', 'Thylda'],
        baseStats: { WW: 30, US: 20, K: 20, Odp: 30, Zr: 10, Int: 20, SW: 20, Ogd: 41 }
    },
    {
        id: 3, name: 'Elf', key: 'elf', description: 'Dostojni i długowieczni', icon: '/images/races/elf.png',
        bonuses: ['Bystry wzrok', 'Nocne widzenie'],
        suggestedNames: ['Teclis', 'Ulliana', 'Aluthol', 'Dolwen'],
        baseStats: { WW: 20, US: 30, K: 20, Odp: 20, Zr: 30, Int: 20, SW: 20, Ogd: 20 }
    },
    {
        id: 4, name: 'Niziołek', key: 'halfling', description: 'Spokojni i łakomi', icon: '/images/races/halfling.png',
        bonuses: ['Odporność na Chaos', 'Nocne widzenie'],
        suggestedNames: ['Ludo', 'Leni', 'Max', 'Sophia'],
        baseStats: { WW: 10, US: 30, K: 20, Odp: 20, Zr: 30, Int: 20, SW: 20, Ogd: 30 }
    }
])
// --- Logika Cech (Zaktualizowana) ---
const characteristics = ref({
    WW:  { name: 'Walka Wręcz', base: 0, assignedValue: null },
    US:  { name: 'Um. Strzeleckie', base: 0, assignedValue: null },
    K:   { name: 'Krzepa', base: 0, assignedValue: null },
    Odp:   { name: 'Odporność', base: 0, assignedValue: null },
    Zr:  { name: 'Zręczność', base: 0, assignedValue: null },
    Int: { name: 'Inteligencja', base: 0, assignedValue: null },
    SW:  { name: 'Siła Woli', base: 0, assignedValue: null },
    Ogd: { name: 'Ogłada', base: 0, assignedValue: null }
})
const secondaryStats = ref({
    wounds: { val: null, roll: null },
    fate: { val: null, roll: null }
})
interface PoolItem {
    id: number;
    value: number;
    isUsed: boolean;
}
const selectedProfession = ref(null)
const professionRoll = ref(0)
const isRollingProfession = ref(false)

const progressPercentage = computed(() => {
    return (currentStep.value / totalSteps) * 100
})

const rollPool = ref<PoolItem[]>([])
const selectedPoolIndex = ref<number | null>(null)
const isRollingAttributes = ref(false)
const hasRolledAttributes = ref(false)
const mercyUsed = ref(false)

const isRollingSecondary = ref(false)

const isRollingAny = computed(() => isRollingAttributes.value || isRollingSecondary.value)

const calculateSecondaryStats = (raceKey: string, type: 'wounds'|'fate', roll: number) => {
    // Tabela Żywotności (WFRP 2ed str. 19)
    const woundsTable = {
        human:    (r: number) => r <= 3 ? 10 : (r <= 6 ? 11 : (r <= 9 ? 12 : 13)),
        elf:      (r: number) => r <= 3 ? 9  : (r <= 6 ? 10 : (r <= 9 ? 11 : 12)),
        dwarf:    (r: number) => r <= 3 ? 11 : (r <= 6 ? 12 : (r <= 9 ? 13 : 14)),
        halfling: (r: number) => r <= 3 ? 8  : (r <= 6 ? 9  : (r <= 9 ? 10 : 11))
    }

    // Tabela Punktów Przeznaczenia (WFRP 2ed str. 19)
    const fateTable = {
        human:    (r: number) => r <= 4 ? 2 : 3,
        elf:      (r: number) => r <= 4 ? 1 : 2,
        dwarf:    (r: number) => r <= 4 ? 1 : (r <= 7 ? 2 : 3),
        halfling: (r: number) => r <= 4 ? 2 : (r <= 7 ? 2 : 3) // Standardowo Niziołki mają szczęście (2-3)
    }

    const key = raceKey || 'human'

    if (type === 'wounds') return woundsTable[key] ? woundsTable[key](roll) : 10
    if (type === 'fate') return fateTable[key] ? fateTable[key](roll) : 2
    return 0
}

const rollSecondary = async (type: 'wounds'|'fate') => {
    if (isRollingAny.value || !diceBoxAttributes) return

    isRollingSecondary.value = true

    try {
        diceBoxAttributes.clear()
        await diceBoxAttributes.roll('1d10')

        const roll = Math.floor(Math.random() * 10) + 1
        const raceKey = selectedRace.value?.key || 'human'
        const result = calculateSecondaryStats(raceKey, type, roll)

        secondaryStats.value[type].roll = roll
        secondaryStats.value[type].val = result

    } catch (e) { console.error(e) }
    finally { isRollingSecondary.value = false }
}

// Przy wyborze rasy ustawiamy bazy
const selectRace = (race) => {
    selectedRace.value = race
    heroData.value.race = race

    // Reset Cech
    hasRolledAttributes.value = false
    mercyUsed.value = false
    Object.keys(characteristics.value).forEach(key => {
        characteristics.value[key].base = race.baseStats[key] || 20
        characteristics.value[key].roll = 0
        characteristics.value[key].total = 0
    })
    secondaryStats.value.wounds = { val: null, roll: null }
    secondaryStats.value.fate = { val: null, roll: null }
}
watch(currentStep, async (newStep) => {
    await nextTick()

    // Inicjalizacja dla kroku 2 (Profesje)
    if (newStep === 2) {
        if (!diceBox) initDiceBox('#dice-box-canvas').then(db => diceBox = db)
    }

    // Inicjalizacja dla kroku 3 (Cechy - NOWE)
    if (newStep === 3) {
        // Czyścimy poprzednie diceboxy dla wydajności, jeśli trzeba, ale lepiej mieć osobną instancję
        if (!diceBoxAttributes) {
            initDiceBox('#dice-box-attributes').then(db => diceBoxAttributes = db)
        }
    }
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
const rollAttributesPool = async () => {
    if (isRollingAttributes.value || !diceBoxAttributes) return;

    isRollingAttributes.value = true;
    rollPool.value = []; // Reset puli
    selectedPoolIndex.value = null;
    mercyUsed.value = false;

    // Reset przypisań w cechach
    Object.keys(characteristics.value).forEach(k => characteristics.value[k].assignedValue = null);

    try {
        diceBoxAttributes.clear();
        // Rzucamy wizualnie np. 5 kostkami 10-ściennymi, żeby zrobić hałas i efekt
        await diceBoxAttributes.roll('16d10');

        // Logika matematyczna (w tle)
        const newPool = [];
        for (let i = 0; i < 8; i++) {
            // Rzut 2d10
            const val = Math.floor(Math.random() * 10) + 1 + Math.floor(Math.random() * 10) + 1;
            newPool.push({ id: i, value: val, isUsed: false });
        }

        // Czekamy chwilę na animację
        setTimeout(() => {
            rollPool.value = newPool;
            isRollingAttributes.value = false;
        }, 1000);

    } catch (e) {
        console.error(e);
        isRollingAttributes.value = false;
    }
}

// Wybór elementu z puli
const selectPoolItem = (index: number) => {
    if (rollPool.value[index].isUsed) return; // Nie można wybrać zużytego

    // Jeśli klikniemy ten sam, odznaczamy
    if (selectedPoolIndex.value === index) {
        selectedPoolIndex.value = null;
    } else {
        selectedPoolIndex.value = index;
    }
}

// Przypisanie wybranego wyniku do cechy
const assignSelectedToStat = (statKey: string) => {
    // Sprawdź czy mamy co przypisać
    if (selectedPoolIndex.value === null) return;

    const poolItem = rollPool.value[selectedPoolIndex.value];
    const stat = characteristics.value[statKey];

    // Jeśli cecha jest już zajęta, najpierw "zwróć" starą wartość do puli
    if (stat.assignedValue !== null) {
        unassignStat(statKey);
        // Uwaga: unassignStat czyści assignedValue, więc możemy kontynuować
    }

    // Przypisz nową wartość
    stat.assignedValue = poolItem.value;

    // Oznacz w puli jako zużyte
    rollPool.value[selectedPoolIndex.value].isUsed = true;

    // Odznacz wybór
    selectedPoolIndex.value = null;
}

// Cofnięcie przypisania (zwrot do puli)
const unassignStat = (statKey: string) => {
    const stat = characteristics.value[statKey];
    if (stat.assignedValue === null) return;

    // Znajdź w puli element, który ma tę wartość i jest oznaczony jako used
    // (W uproszczonym modelu szukamy po wartości, ale najlepiej byłoby trzymać ID przypisanego elementu w statystyce.
    //  Tutaj uprościmy: szukamy pierwszego zużytego o tej wartości).
    //  Dla 100% poprawności przy duplikatach wartości:
    //  Warto dodać 'assignedPoolId' do characteristics. Zróbmy to porządnie.

    // Wyszukanie w puli po wartości (Prosta implementacja, zakładamy, że user pamięta co gdzie dał, albo system sam zarządza)
    // Ulepszenie: przypisujemy ID z puli do cechy
    const poolItemIndex = rollPool.value.findIndex(p => p.value === stat.assignedValue && p.isUsed);

    if (poolItemIndex !== -1) {
        rollPool.value[poolItemIndex].isUsed = false;
    }

    stat.assignedValue = null;
}

// Łaska Shallyi (Przerzut w puli)
const rerollSelectedPoolItem = async () => {
    if (selectedPoolIndex.value === null || mercyUsed.value || isRollingAttributes.value) return;

    isRollingAttributes.value = true;
    mercyUsed.value = true; // Zaznaczamy zużycie przed rzutem

    try {
        diceBoxAttributes.clear();
        await diceBoxAttributes.roll('2d10'); // Wizualny rzut 2k10

        // Aktualizacja puli
        rollPool.value[selectedPoolIndex.value].value = Math.floor(Math.random() * 10) + 1 + Math.floor(Math.random() * 10) + 1;

        isRollingAttributes.value = false;
    } catch (e) {
        console.error(e);
        isRollingAttributes.value = false;
    }
}

// Computed: Czy można przejść dalej
const allAssigned = computed(() => {
    return Object.values(characteristics.value).every(c => c.assignedValue !== null);
})
const initDiceBox = async (selector) => {
    const container = document.querySelector(selector);
    if (!container) return null;
    const {width, height} = container.getBoundingClientRect();

    const db = new DiceBox(selector, {
        assetPath: '/assets/dice-box/', // Dostosuj ścieżkę do swoich assets
        origin: window.location.origin,
        theme: 'default',
        themeColor: '#d4af37',
        scale: 8, // Mniejsze kostki dla atrybutów
        container_width: width,
        container_height: height
    });
    await db.init();

    // Fix CSS canvas
    const canvas = container.querySelector('canvas');
    if (canvas) { canvas.style.width = '100%'; canvas.style.height = '100%'; }

    return db;
}

const roll2d10 = () => Math.floor(Math.random() * 10) + 1 + Math.floor(Math.random() * 10) + 1

// Przerzut pojedynczej cechy (Łaska Shallyi)
const rerollSpecificStat = (key) => {
    if (mercyUsed.value) return

    const newRoll = roll2d10()
    characteristics.value[key].roll = newRoll
    characteristics.value[key].total = characteristics.value[key].base + newRoll
    mercyUsed.value = true
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
    { label: 'WW',  keys: ['WW', 'weapon_skill', 'weaponSkill', 'Walka Wręcz'] },
    { label: 'US',  keys: ['US', 'ballistic_skill', 'ballisticSkill', 'Umiejętności Strzeleckie'] },
    { label: 'K',   keys: ['K', 'strength', 'Siła'] },
    { label: 'Odp', keys: ['Odp', 'toughness', 'Wytrzymałość'] },
    { label: 'Zr',  keys: ['Zr', 'agility', 'Zwinność'] },
    { label: 'Int', keys: ['Int', 'intelligence', 'Inteligencja'] },
    { label: 'SW',  keys: ['SW', 'willpower', 'Siła Woli'] },
    { label: 'Ogd', keys: ['Ogd', 'fellowship', 'Ogłada'] }
]

const secondaryProfileConfig = [
    { label: 'A',   keys: ['A', 'attacks', 'Ataki'] },
    { label: 'Żyw', keys: ['Żyw', 'wounds', 'Żywotność'] },
    { label: 'S',   keys: ['S', 'strength_bonus'] },
    { label: 'Wt',  keys: ['Wt', 'toughness_bonus'] },
    { label: 'Sz',  keys: ['Sz', 'movement', 'Szybkość'] },
    { label: 'Mag', keys: ['Mag', 'magic', 'Magia'] },
    { label: 'PO',  keys: ['PO', 'insanity', 'Punkty Obłędu'] },
    { label: 'PP',  keys: ['PP', 'fate', 'Punkty Przeznaczenia'] }
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
        case 1: return selectedRace.value !== null
        case 2: return selectedProfession.value !== null
        case 3:
            const mainStatsDone = Object.values(characteristics.value).every(c => c.assignedValue !== null)
            const secStatsDone = secondaryStats.value.wounds.val !== null && secondaryStats.value.fate.val !== null
            return mainStatsDone && secStatsDone
        case 4: return heroData.value.firstName.trim().length > 0
        default: return false
    }
})

// Metody


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
    const finalHeroData = {
        firstName: heroData.value.firstName,
        lastName: heroData.value.lastName,
        race: selectedRace.value,
        profession: selectedProfession.value,
        characteristics: Object.fromEntries(
            Object.entries(characteristics.value).map(([key, value]) => [key, value.base + value.assignedValue])
        ),
        // Dodajemy drugorzędne
        secondaryCharacteristics: {
            wounds: secondaryStats.value.wounds.val,
            fate: secondaryStats.value.fate.val
        }
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
.attributes-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    max-width: 900px;
    margin: 0 auto;
}

.attributes-grid {
    width: 100%;
    background: rgba(0, 0, 0, 0.5);
    border: 2px solid #d4af37;
    border-radius: 8px;
    margin-bottom: 2rem;
    overflow: hidden;
}

.attr-header-row {
    display: flex;
    background: #d4af37;
    color: #1a1a1a;
    font-weight: bold;
    text-transform: uppercase;
    padding: 10px;
    font-size: 0.85rem;
}

.attr-row {
    display: flex;
    border-bottom: 1px solid rgba(212, 175, 55, 0.2);
    align-items: center;
    padding: 12px 10px;
    transition: background 0.2s;
}

.attr-row:hover {
    background: rgba(212, 175, 55, 0.05);
}

.attr-row:last-child {
    border-bottom: none;
}

/* Kolumny tabeli */
.col-name { flex: 2; display: flex; align-items: center; gap: 10px; }
.col-base, .col-roll, .col-total, .col-action { flex: 1; text-align: center; }

.attr-abbr {
    font-weight: bold;
    color: #d4af37;
    width: 35px;
    display: inline-block;
}
.attr-full { color: #ccc; font-size: 0.9rem; }

.col-base { color: #888; font-family: monospace; font-size: 1.1rem; }
.roll-val { color: #fff; font-family: monospace; font-size: 1.1rem; }
.total-val {
    color: #d4af37;
    font-weight: bold;
    font-size: 1.3rem;
    text-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
}

/* Przycisk Przerzutu */
.sm-reroll-btn {
    background: transparent;
    border: 1px solid #666;
    color: #ccc;
    cursor: pointer;
    border-radius: 4px;
    padding: 4px 8px;
    transition: 0.2s;
}
.sm-reroll-btn:hover {
    border-color: #d4af37;
    background: rgba(212, 175, 55, 0.1);
    transform: scale(1.1);
}

.attributes-controls {
    text-align: center;
}

.mercy-info { color: #ccc; font-style: italic; font-size: 0.9rem; }
.mercy-used { color: #888; text-decoration: line-through; font-size: 0.9rem; }
.attributes-assignment-container {
    display: flex;
    gap: 2rem;
    height: 100%;
    width: 100%;
    max-width: 1100px;
    margin: 0 auto;
    padding-bottom: 2rem;
}
.stats-column {
    flex: 1;
    background: rgba(0, 0, 0, 0.6);
    border: 1px solid #444;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.stats-header-row {
    display: flex;
    padding: 0 1rem 0.5rem 1rem;
    border-bottom: 2px solid #d4af37;
    margin-bottom: 0.5rem;
    color: #d4af37;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 0.8rem;
}
.col-lbl { flex: 1; text-align: center; }
.col-lbl:first-child { flex: 2; text-align: left; }

.stat-assignment-row {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.03);
    padding: 0.8rem 1rem;
    border-radius: 4px;
    border: 1px solid transparent;
    transition: all 0.2s;
    cursor: default;
}

/* Stan: Gotowy do przyjęcia wartości (gdy wybrano liczbę z puli) */
.stat-assignment-row.ready-to-receive {
    cursor: pointer;
    border-color: rgba(212, 175, 55, 0.5);
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.1);
    animation: pulseBorder 2s infinite;
}
.stat-assignment-row.ready-to-receive:hover {
    background: rgba(212, 175, 55, 0.1);
}

@keyframes pulseBorder {
    0% { border-color: rgba(212, 175, 55, 0.3); }
    50% { border-color: rgba(212, 175, 55, 0.8); }
    100% { border-color: rgba(212, 175, 55, 0.3); }
}

.stat-name-block { flex: 2; display: flex; align-items: center; gap: 10px; }
.stat-abbr { font-weight: bold; color: #d4af37; font-size: 1rem; width: 40px; }
.stat-full { color: #aaa; font-size: 0.85rem; }

.stat-base-val { flex: 1; text-align: center; color: #666; font-family: monospace; font-size: 1.1rem; }
.stat-total-val { flex: 1; text-align: center; color: #d4af37; font-weight: bold; font-size: 1.2rem; }
.dimmed { color: #444; }

/* Slot na przypisaną wartość */
.stat-assigned-slot {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    height: 40px;
    width: 40px;
}

.assigned-val {
    font-family: monospace;
    font-size: 1.3rem;
    color: #fff;
    font-weight: bold;
    text-shadow: 0 0 5px #d4af37;
}

.empty-slot-marker {
    color: #444;
    font-size: 1.2rem;
}

.remove-assign-btn {
    position: absolute;
    right: -10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #ff4444;
    cursor: pointer;
    font-size: 0.8rem;
    opacity: 0;
    transition: opacity 0.2s;
}
.stat-assignment-row:hover .remove-assign-btn { opacity: 1; }


/* --- PRAWA KOLUMNA (PULA) --- */
.pool-column {
    flex: 0 0 350px;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.dice-area-small {
    position: relative;
    height: 200px;
    background: #000;
    border: 2px solid #444;
    border-radius: 8px;
    overflow: hidden;
}

#dice-box-attributes {
    width: 100%;
    height: 100%;
}

.roll-attributes-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10;
    background: #d4af37;
    border: none;
    padding: 10px 20px;
    font-weight: bold;
    cursor: pointer;
    font-family: 'Cinzel', serif;
    box-shadow: 0 0 15px rgba(0,0,0,0.8);
    white-space: nowrap;
}

.results-pool-grid {
    background: rgba(30, 30, 30, 0.8);
    border: 1px solid #d4af37;
    border-radius: 8px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.pool-label {
    color: #ccc;
    text-transform: uppercase;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    letter-spacing: 1px;
}

.pool-items {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 1.5rem;
}

.pool-item {
    width: 50px;
    height: 50px;
    background: #222;
    border: 1px solid #555;
    border-radius: 4px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.2rem;
    font-weight: bold;
    color: #fff;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.pool-item:hover:not(.used) {
    border-color: #d4af37;
    transform: scale(1.1);
}

.pool-item.selected {
    background: #d4af37;
    color: #000;
    border-color: #fff;
    box-shadow: 0 0 15px rgba(212, 175, 55, 0.6);
    transform: scale(1.15);
}

.pool-item.used {
    opacity: 0.3;
    cursor: not-allowed;
    background: #111;
    border-color: #333;
}

.used-overlay {
    position: absolute;
    color: #d4af37;
    font-size: 1.5rem;
}

/* Sekcja akcji (Przerzut) */
.pool-actions {
    text-align: center;
    min-height: 60px;
}

.reroll-one-btn {
    background: transparent;
    border: 1px solid #d4af37;
    color: #d4af37;
    padding: 8px 16px;
    cursor: pointer;
    transition: 0.2s;
    font-size: 0.9rem;
}
.reroll-one-btn:hover { background: rgba(212, 175, 55, 0.1); }

.instruction-text { font-size: 0.8rem; color: #888; font-style: italic; }
.mercy-used-info { color: #666; font-size: 0.8rem; text-decoration: line-through; }
.mercy-desc { margin: 0 0 0.5rem 0; font-size: 0.8rem; color: #aaa; }

/* Mobile */
@media (max-width: 900px) {
    .attributes-assignment-container { flex-direction: column-reverse; }
    .pool-column { flex: none; width: 100%; }
    .dice-area-small { height: 150px; }
}

.section-label {
    color: #d4af37;
    font-size: 0.8rem;
    text-transform: uppercase;
    border-bottom: 1px solid rgba(212, 175, 55, 0.3);
    padding-bottom: 5px;
    margin-bottom: 10px;
    font-weight: bold;
    letter-spacing: 1px;
}
.mt-4 { margin-top: 1.5rem; }

.secondary-stats-section {
    background: rgba(0,0,0,0.3);
    padding: 10px;
    border-radius: 6px;
}

.secondary-row {
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.03);
    padding: 10px;
    margin-bottom: 5px;
    border-radius: 4px;
    border: 1px solid rgba(255,255,255,0.05);
}

.sec-name { flex: 2; display: flex; gap: 10px; align-items: center; }
.sec-val { flex: 1; text-align: center; font-size: 1.2rem; color: #fff; font-weight: bold; }
.sec-action { flex: 1; text-align: right; }

.final-val { color: #d4af37; text-shadow: 0 0 5px rgba(212,175,55,0.5); }

.mini-roll-btn {
    background: #d4af37;
    border: none;
    color: #000;
    padding: 5px 10px;
    border-radius: 3px;
    font-weight: bold;
    cursor: pointer;
    font-size: 0.8rem;
    transition: 0.2s;
}
.mini-roll-btn:hover:not(:disabled) { background: #fff; }
.mini-roll-btn:disabled { opacity: 0.5; cursor: not-allowed; background: #555; }

.roll-info { font-size: 0.75rem; color: #888; font-style: italic; }
</style>
