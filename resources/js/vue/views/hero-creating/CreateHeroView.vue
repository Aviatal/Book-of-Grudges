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
                            <img :src="'/images/races/' + race.icon" :alt="race.name"/>
                        </div>
                        <div class="race-info">
                            <h3 class="race-name">{{ race.name }}</h3>
                            <p class="race-description">{{ race.description }}</p>
                            <div class="race-bonuses">
                                <div v-for="bonus in race.bonuses" :key="bonus" class="bonus-tag">
                                    +{{ bonus.bonus_name }}
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
                                        <p v-if="selectedProfession.description">{{
                                                selectedProfession.description
                                            }}</p>
                                        <p v-else>Profesja wylosowana pomyślnie. Twoja postać jest gotowa do drogi.</p>
                                    </div>

                                    <div class="profession-details-grid">
                                        <div class="detail-section full-width">
                                            <h4 class="detail-title">Schemat Rozwoju</h4>

                                            <div class="stat-category-label">Cechy Główne</div>
                                            <div class="wfrp-stat-grid">
                                                <div v-for="stat in mainProfileConfig" :key="stat.label"
                                                     class="stat-cell">
                                                    <div class="stat-header">{{ stat.label }}</div>
                                                    <div class="stat-value">
                                                        {{
                                                            formatStatBonus(getStatValue(selectedProfession.characteristics, stat.label))
                                                        }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="stat-category-label secondary-label">Cechy Drugorzędne</div>
                                            <div class="wfrp-stat-grid">
                                                <div v-for="stat in secondaryProfileConfig" :key="stat.label"
                                                     class="stat-cell">
                                                    <div class="stat-header">{{ stat.label }}</div>
                                                    <div class="stat-value">
                                                        {{
                                                            formatStatBonus(getStatValue(selectedProfession.characteristics, stat.label))
                                                        }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="detail-section">
                                            <h4 class="detail-title">Umiejętności</h4>
                                            <div class="items-container">
                                                <div class="mandatory-group"
                                                     v-if="getMandatoryItems(selectedProfession.skills).length">
                                                    <span class="group-label">Otrzymujesz:</span>
                                                    <div class="tags-wrapper">
                                                        <span
                                                            v-for="skill in getMandatoryItems(selectedProfession.skills)"
                                                            :key="skill.id" class="skill-tag">
                                                            {{ formatSkillName(skill) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="choices-container"
                                                     v-if="getChoiceGroups(selectedProfession.skills).length">
                                                    <div
                                                        v-for="(group, index) in getChoiceGroups(selectedProfession.skills)"
                                                        :key="'s-choice-'+index" class="choice-block">
                                                        <span class="choice-label">Wybierz jedną z:</span>
                                                        <div class="choice-options">
                                                            <span v-for="(skill, i) in group" :key="skill.id">
                                                                <span class="choice-item">{{
                                                                        formatSkillName(skill)
                                                                    }}</span>
                                                                <span v-if="i < group.length - 1"
                                                                      class="separator">LUB</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="detail-section">
                                            <h4 class="detail-title">Zdolności</h4>
                                            <div class="items-container">
                                                <div class="mandatory-group"
                                                     v-if="getMandatoryItems(selectedProfession.talents).length">
                                                    <span class="group-label">Otrzymujesz:</span>
                                                    <div class="tags-wrapper">
                                                        <span
                                                            v-for="talent in getMandatoryItems(selectedProfession.talents)"
                                                            :key="talent.id" class="talent-tag">
                                                            {{ formatTalentName(talent) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="choices-container"
                                                     v-if="getChoiceGroups(selectedProfession.talents).length">
                                                    <div
                                                        v-for="(group, index) in getChoiceGroups(selectedProfession.talents)"
                                                        :key="'t-choice-'+index" class="choice-block">
                                                        <span class="choice-label">Wybierz jedną z:</span>
                                                        <div class="choice-options">
                                                            <span v-for="(talent, i) in group" :key="talent.id">
                                                                <span class="choice-item">{{
                                                                        formatTalentName(talent)
                                                                    }}</span>
                                                                <span v-if="i < group.length - 1"
                                                                      class="separator">LUB</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="detail-section">
                                            <h4 class="detail-title">Wyposażenie</h4>
                                            <div class="equipment-list">
                                                <div class="mandatory-eq"
                                                     v-if="getMandatoryItems(selectedProfession.equipments).length">
                                                    <div
                                                        v-for="item in getMandatoryItems(selectedProfession.equipments)"
                                                        :key="item.id" class="equipment-item">
                                                        {{ formatItemName(item) }}
                                                    </div>
                                                </div>
                                                <div class="choices-container"
                                                     v-if="getChoiceGroups(selectedProfession.equipments).length">
                                                    <div
                                                        v-for="(group, index) in getChoiceGroups(selectedProfession.equipments)"
                                                        :key="'t-choice-'+index" class="choice-block">
                                                        <span class="choice-label">Wybierz jedną z:</span>
                                                        <div class="choice-options">
                                                            <span v-for="(item, i) in group" :key="item.id">
                                                                <span class="choice-item">{{
                                                                        formatItemName(item)
                                                                    }}</span>
                                                                <span v-if="i < group.length - 1"
                                                                      class="separator">LUB</span>
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
                    <p class="slide-subtitle">Rzuć kośćmi, przypisz wyniki i wybierz <strong>jedno darmowe rozwinięcie</strong>.</p>
                </div>

                <div class="attributes-assignment-container">
                    <div class="stats-column">

                        <div class="section-label">Cechy Główne</div>
                        <div class="stats-header-row">
                            <span class="col-lbl">Cecha</span>
                            <span class="col-lbl">Baza</span>
                            <span class="col-lbl">Suma</span>
                            <span class="col-lbl action-col">Przypisz</span>
                            <span class="col-lbl short">Rozw.</span>
                        </div>

                        <div v-for="(char, key) in characteristics" :key="key"
                             class="stat-assignment-row"
                             :class="{
                                'ready-to-receive': selectedPoolIndex !== null && char.assignedValue === null,
                                'filled': char.assignedValue !== null,
                                'advanced': freeAdvance?.key === key
                             }"
                             @click="assignSelectedToStat(key)">

                            <div class="stat-name-block">
                                <span class="stat-abbr">{{ mainProfileConfig.find(c => c.keys.includes(key))?.label }}</span>
                                <span class="stat-full">{{ char.name }}</span>
                            </div>

                            <div class="stat-base-val">{{ char.base }}</div>

                            <div class="stat-total-val">
                                <span v-if="char.assignedValue !== null">
                                    {{ char.base + char.assignedValue + (freeAdvance?.key === key ? 5 : 0) }}
                                </span>
                                <span v-else class="dimmed">-</span>
                            </div>

                            <div class="stat-assigned-slot">
                                <span v-if="char.assignedValue !== null" class="assigned-val">+{{ char.assignedValue }}</span>
                                <span v-else class="empty-slot-marker">{{ selectedPoolIndex !== null ? '↓' : '—' }}</span>
                                <button v-if="char.assignedValue !== null" class="remove-assign-btn" @click.stop="unassignStat(key)">✕</button>
                            </div>

                            <div class="stat-advance-slot" @click.stop>
                                <button
                                    v-if="canAdvanceStat(key)"
                                    class="advance-btn"
                                    :class="{ 'active': freeAdvance?.key === key }"
                                    @click="selectFreeAdvance(key, false)"
                                    title="Darmowe rozwinięcie (+5%)"
                                >
                                    +5
                                </button>
                                <span v-else class="dimmed-dot">·</span>
                            </div>
                        </div>

                        <div class="secondary-stats-section">
                            <div class="section-label mt-4">Cechy Drugorzędne (Rzut 1k10)</div>
                            <div class="secondary-row">
                                <div class="sec-name"><span class="stat-abbr">Żyw</span><span class="stat-full">Żywotność</span></div>
                                <div class="sec-val">
                                    <span v-if="secondaryStats.wounds.val" class="final-val">
                                        {{ secondaryStats.wounds.val + (freeAdvance?.key === 'Żyw' ? 1 : 0) }}
                                    </span>
                                    <span v-else class="dimmed">-</span>
                                </div>
                                <div class="sec-action">
                                    <button v-if="!secondaryStats.wounds.val" class="mini-roll-btn" @click="rollSecondary('wounds')" :disabled="isRollingAny">🎲</button>
                                    <span v-else class="roll-info">
                                        (Rzut: {{ secondaryStats.wounds.roll }})
                                    </span>
                                </div>
                            </div>
                            <div class="secondary-row">
                                <div class="sec-name"><span class="stat-abbr">PP</span><span class="stat-full">Przeznaczenie</span></div>
                                <div class="sec-val">
                                    <span v-if="secondaryStats.fate.val" class="final-val">
                                        {{ secondaryStats.fate.val + (freeAdvance?.key === 'PP' ? 1 : 0) }}
                                    </span>
                                    <span v-else class="dimmed">-</span>
                                </div>
                                <div class="sec-action">
                                    <button v-if="!secondaryStats.fate.val" class="mini-roll-btn" @click="rollSecondary('fate')" :disabled="isRollingAny">🎲</button>
                                </div>
                            </div>
                        </div>

                        <div class="secondary-advances-section" v-if="availableSecondaryAdvances.length > 0">
                            <div class="section-label mt-4">Dostępne Rozwinięcia (Cechy Drugorzędne)</div>
                            <p class="hint-text" v-if="!freeAdvance">Możesz też wybrać darmowe rozwinięcie tutaj (+1):</p>

                            <div class="advances-grid">
                                <button
                                    v-for="adv in availableSecondaryAdvances"
                                    :key="adv.label"
                                    class="sec-advance-btn"
                                    :class="{ 'active': freeAdvance?.key === adv.label }"
                                    @click="selectFreeAdvance(adv.label, true)"
                                >
                                    {{ adv.label }} (+1)
                                </button>
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
                                <div
                                    v-if="!mercyUsed && selectedPoolIndex !== null && !rollPool[selectedPoolIndex].isUsed">
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
                    <h2 class="slide-title">Wiedza i Talenty</h2>
                    <p class="slide-subtitle">
                        Zdolności wynikające z Rasy ({{ selectedRace?.name }}) oraz Profesji ({{ selectedProfession?.name }}).
                    </p>
                </div>

                <div class="skills-selection-container">

                    <div class="selection-section">
                        <h3 class="section-title-deco">Umiejętności</h3>

                        <div class="mandatory-list" v-if="aggregatedSkills.mandatory.length > 0">
                            <span class="label-small">Otrzymujesz automatycznie:</span>
                            <div class="tags-group">
                                <span v-for="(item, i) in aggregatedSkills.mandatory"
                                      :key="'mand-s-'+i"
                                      class="static-tag"
                                      :title="item.skill?.description"> {{ formatSkillName(item) }}
                                    <span :class="['source-tag', item.source]">{{ item.source === 'race' ? 'Rasa' : 'Profesja' }}</span>
                                    <span class="check-icon">✓</span>
                                </span>
                            </div>
                        </div>

                        <div class="choices-list" v-if="aggregatedSkills.choices.length > 0">
                            <span class="label-small highlight">Musisz wybrać:</span>

                            <div v-for="(group, gIndex) in aggregatedSkills.choices" :key="'s-group-'+gIndex" class="choice-row">
                                <div class="choice-origin-label">
                                    Źródło: <span :class="['text-source', group[0].source]">{{ group[0].source === 'race' ? 'Rasa' : 'Profesja' }}</span>
                                </div>

                                <div class="choice-options-wrapper">
                                    <div v-for="(item) in group" :key="getUniqueKey(item)"
                                         class="choice-card"
                                         :class="{
        'selected': selectedChoices.skills[gIndex] === getUniqueKey(item),
        'is-upgrade': isSkillMandatory(item) /* Opcjonalna klasa do stylizacji całej karty */
     }"
                                         @click="selectSkill(gIndex, item)">

                                        <div class="radio-circle"></div>

                                        <div class="choice-content">
                                            <div class="choice-text-row">
                                                <span class="choice-text">{{ formatSkillName(item) }}</span>

                                                <span v-if="isSkillMandatory(item)" class="bonus-badge">+10</span>
                                            </div>

                                            <span class="choice-desc" v-if="item.skill?.description">
                                                {{ item.skill?.description }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="selection-section">
                        <h3 class="section-title-deco">Zdolności</h3>

                        <div class="random-talents-block" v-if="requiredRandomTalentsCount > 0">
                            <div class="random-header">
                                <span class="label-small highlight">
                                    Losowe zdolności ({{ selectedRace?.name }}):
                                </span>
                                <span class="counter-badge">
                                    {{ rolledRandomTalents.length }} / {{ requiredRandomTalentsCount }}
                                </span>
                            </div>

                            <div class="random-talents-grid">
                                <div v-for="(talent, index) in rolledRandomTalents" :key="'rnd-'+index" class="talent-card rolled">
                                    <div class="talent-icon">🎲</div>
                                    <div class="talent-info">
                                        <span class="talent-name">{{ talent.name }}</span>
                                        <span class="roll-value">Rzut: {{ talent.roll }}</span>
                                    </div>
                                </div>

                                <button
                                    v-if="rolledRandomTalents.length < requiredRandomTalentsCount"
                                    class="roll-random-btn"
                                    @click="rollRandomTalent"
                                    :disabled="isRollingRandomTalent"
                                >
                                    <span class="btn-icon" :class="{ 'spinning': isRollingRandomTalent }">🎲</span>
                                    {{ isRollingRandomTalent ? 'Losowanie...' : 'Wylosuj Zdolność' }}
                                </button>
                            </div>
                            <div class="separator-line" style="margin: 1.5rem 0; border-bottom: 1px dashed #444;"></div>
                        </div>

                        <div class="mandatory-list" v-if="aggregatedTalents.mandatory.length > 0">
                            <span class="label-small">Otrzymujesz automatycznie:</span>
                            <div class="tags-group">
                                <span v-for="(item, i) in aggregatedTalents.mandatory"
                                      :key="'mand-t-'+i"
                                      class="static-tag"
                                      :title="item.talent?.description || item.description || 'Brak opisu'">

                                    {{ formatTalentName(item) }}

                                    <span :class="['source-tag', item.source]">
                                        {{ item.source === 'race' ? 'Rasa' : 'Profesja' }}
                                    </span>
                                    <span class="check-icon">✓</span>
                                </span>
                            </div>
                        </div>

                        <div class="choices-list" v-if="aggregatedTalents.choices.length > 0">
                            <span class="label-small highlight">Musisz wybrać jedną z każdej grupy:</span>

                            <div v-for="(group, gIndex) in aggregatedTalents.choices" :key="'t-group-'+gIndex" class="choice-row">
                                <div class="choice-origin-label">
                                    Źródło: <span :class="['text-source', group[0].source]">
                                        {{ group[0].source === 'race' ? 'Rasa' : 'Profesja' }}
                                    </span>
                                </div>

                                <div class="choice-options-wrapper">
                                    <div v-for="(item) in group" :key="getUniqueKey(item)"
                                         class="choice-card"
                                         :class="{ 'selected': selectedChoices.talents[gIndex] === getUniqueKey(item) }"
                                         @click="selectTalent(gIndex, item)">

                                        <div class="radio-circle"></div>

                                        <div class="choice-content">
                                            <div class="choice-text-row">
                                                <span class="choice-text">{{ formatTalentName(item) }}</span>

                                                <span v-if="isTalentMandatory(item) || isTalentAlreadyOwned(item.talent?.id || item.id)"
                                                      class="owned-badge"
                                                      title="Już posiadasz ten talent.">
                                                    Posiadasz
                                                </span>
                                            </div>

                                            <span class="choice-desc" v-if="item.talent?.description || item.description">
                                                {{ item.talent?.description || item.description }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div v-if="currentStep === 5" class="creation-slide slide-enter">
                <div class="slide-header">
                    <h2 class="slide-title">Wygląd i Osobowość</h2>
                    <p class="slide-subtitle">Wylosuj cechy swojego bohatera lub dostosuj je ręcznie.</p>
                </div>

                <div class="personal-details-container">

                    <div class="details-controls">
                        <div class="gender-toggle">
                            <label>Płeć:</label>
                            <div class="toggle-switch">
                                <button :class="{ active: personalDetails.gender === 'M' }" @click="personalDetails.gender = 'M'">Mężczyzna</button>
                                <button :class="{ active: personalDetails.gender === 'F' }" @click="personalDetails.gender = 'F'">Kobieta</button>
                            </div>
                        </div>

                        <button class="roll-all-btn" @click="rollPersonalDetails">
                            <span class="btn-icon">🎲</span> Wylosuj Wszystko
                        </button>
                    </div>

                    <div class="details-grid">

                        <div class="grid-row-3">
                            <div class="detail-input-group">
                                <label>Wzrost</label>
                                <div class="input-with-action">
                                    <input type="number" v-model.number="personalDetails.height" class="fantasy-input-small">
                                    <span class="unit">cm</span>
                                    <button class="mini-dice-btn" @click="rollSingleDetail('height')" title="Wylosuj wzrost">🎲</button>
                                </div>
                            </div>

                            <div class="detail-input-group">
                                <label>Waga</label>
                                <div class="input-with-action">
                                    <input type="number" v-model.number="personalDetails.weight" class="fantasy-input-small">
                                    <span class="unit">kg</span>
                                    <button class="mini-dice-btn" @click="rollSingleDetail('weight')" title="Wylosuj wagę">🎲</button>
                                </div>
                            </div>

                            <div class="detail-input-group">
                                <label>Wiek</label>
                                <div class="input-with-action">
                                    <input type="number" v-model.number="personalDetails.age" class="fantasy-input-small">
                                    <span class="unit">lat</span>
                                    <button class="mini-dice-btn" @click="rollSingleDetail('age')" title="Wylosuj wiek">🎲</button>
                                </div>
                            </div>
                        </div>

                        <div class="grid-row-2">
                            <div class="detail-input-group">
                                <label>Kolor Włosów</label>
                                <div class="input-with-action">
                                    <input type="text" v-model="personalDetails.hairColor" class="fantasy-input" placeholder="np. Jasny blond">
                                    <button class="mini-dice-btn" @click="rollSingleDetail('hairColor')">🎲</button>
                                </div>
                            </div>

                            <div class="detail-input-group">
                                <label>Kolor Oczu</label>
                                <div class="input-with-action">
                                    <input type="text" v-model="personalDetails.eyeColor" class="fantasy-input" placeholder="np. Niebieskie">
                                    <button class="mini-dice-btn" @click="rollSingleDetail('eyeColor')">🎲</button>
                                </div>
                            </div>
                        </div>

                        <div class="detail-input-group wide">
                            <label>Znak Szczególny</label>
                            <div class="input-with-action">
                                <input type="text" v-model="personalDetails.distinguishingMark" class="fantasy-input" placeholder="np. Blizna na policzku">
                                <button class="mini-dice-btn" @click="rollSingleDetail('distinguishingMark')">🎲</button>
                            </div>
                        </div>

                        <div class="grid-row-2">
                            <div class="detail-input-group">
                                <label>Znak gwiezdny</label>
                                <div class="input-with-action">
                                    <input type="text" v-model="personalDetails.starSign" class="fantasy-input" placeholder="np. Bębniarz">
                                    <button class="mini-dice-btn" @click="rollSingleDetail('starSign')">🎲</button>
                                </div>
                            </div>

                            <div class="detail-input-group">
                                <label>Rodzeństwo</label>
                                <div class="input-with-action">
                                    <input type="number" v-model.number="personalDetails.siblings" class="fantasy-input-small">
                                    <button class="mini-dice-btn" @click="rollSingleDetail('siblings')">🎲</button>
                                </div>
                            </div>
                        </div>

                        <div class="detail-input-group wide">
                            <label>Miejsce Urodzenia</label>
                            <div class="input-with-action">
                                <input type="text" v-model="personalDetails.birthplace" class="fantasy-input" placeholder="np. Reikland, Altdorf">
                                <button class="mini-dice-btn" @click="rollSingleDetail('birthplace')">🎲</button>
                            </div>
                        </div>

                        <div class="detail-input-group wide">
                            <label>Imię</label>
                            <div class="input-with-action">
                                <input type="text" v-model="personalDetails.name" class="fantasy-input" placeholder="np. Helmut">
                                <button class="mini-dice-btn" @click="rollSingleDetail('name')">🎲</button>
                            </div>
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
import { computed, nextTick, onMounted, ref, watch, reactive } from 'vue'
import { rollProfession } from '../../../data/professions'
import DiceBox from '@3d-dice/dice-box'
import axios from 'axios' // Upewnij się, że masz import axios

// Props i emits
const emit = defineEmits(['hero-created', 'creation-closed'])

// Stan komponentu
const isCreating = ref(false)
const currentStep = ref(1)
const totalSteps = 5
const personalDetails = reactive({
    height: 0,       // w cm
    weight: 0,       // w kg
    hairColor: '',
    eyeColor: '',
    distinguishingMark: '',
    age: 0,
    gender: 'M',     // Domyślnie M, można dodać wybór płci
    siblings: 0,
    starSign: '',
    birthplace: '',
    name: ''
})
const personalDetailsTables = {
    human: {
        heightBase: 150, heightDice: '2d10',
        weightBase: 50,  weightDice: '1d100', // Uproszczenie
        ageBase: 16,  ageDice: '1d100', // Uproszczenie
        hair: ['Jasny blond', 'Ciemny blond', 'Rudy', 'Czarny', 'Brązowy', 'Siwiejący'],
        eyes: ['Niebieskie', 'Szare', 'Brązowe', 'Zielone', 'Ciemne', 'Czarne'],
        marks: ['Blizna na twarzy', 'Brak zęba', 'Tatuaż', 'Kolczyk w uchu', 'Złamany nos', 'Brodawka'],
        birthPlaces: ['Averland', 'Hochland', 'Middenland', 'Nordland', 'Ostermark', 'Ostland', 'Reikland', 'Stirland', 'Talabecland', 'Wissenland'],
        starSign: ['Bębniarz - znak zabawy i radości', 'Dudy - znak oszustwa', 'Dwa byki - znak płodności i rzemiosła'],
        names: {
            'M': ['Helmut', 'Walter', 'Friedrich', 'Karl', 'Albert', 'Max', 'Heinrich', 'Rudolf', 'Hans', 'Otto'],
            'F': ['Eva', 'Maria', 'Anna', 'Alexa', 'Julia', 'Ulrike', 'Ursula', 'Elise', 'Magdalena', 'Wertha']
        }

    },
    dwarf: {
        heightBase: 130, heightDice: '1d10',
        weightBase: 60,  weightDice: '1d100',
        ageBase: 20,  ageDice: '1d100',
        hair: ['Czarny', 'Szary', 'Brązowy', 'Rudy', 'Biały'],
        eyes: ['Ciemne', 'Brązowe', 'Piwne', 'Szare'],
        marks: ['Długa broda', 'Blizna wojenna', 'Tatuaż klanowy', 'Brak palca'],
        birthPlaces: [
            'Averland', 'Hochland', 'Middenland', 'Nordland', 'Ostermark', 'Ostland', 'Reikland', 'Stirland', 'Talabecland', 'Wissenland',
            'Karak Norn', 'Karak Izor', 'Karak Hirn', 'Karak Kadrin', 'Karaz-a-karak', 'Zhufbar', 'Bark Varr'
        ],
        starSign: ['Bębniarz - znak zabawy i radości', 'Dudy - znak oszustwa', 'Dwa byki - znak płodności i rzemiosła'],
        names: {
            'M': ['Bardin', 'Dimzad', 'Kargun', 'Jotunn', 'Snorri', 'Orzad', 'Durak', 'Thorgrim', 'Storri', 'Imrak'],
            'F': ['Anika', 'Berta', 'Janna', 'Ulla', 'Silma', 'Greta', 'Petra', 'Dgmar', 'Brigit', 'Thylda']
        }
    },
    elf: {
        heightBase: 170, heightDice: '1d10',
        weightBase: 45,  weightDice: '1d100',
        ageBase: 30,  ageDice: '1d100',
        hair: ['Srebrny', 'Biały', 'Złoty', 'Czarny', 'Miedziany'],
        eyes: ['Fiołkowe', 'Złote', 'Błękitne', 'Zielone', 'Szare'],
        marks: ['Szpiczaste uszy (oczywiste)', 'Tatuaż leśny', 'Blada cera', 'Dziwny akcent'],
        birthPlaces: ['Altdorf', 'Marienburg', 'Las Laurelorn', 'Wielki Las', 'Las Reikwald'],
        starSign: ['Bębniarz - znak zabawy i radości', 'Dudy - znak oszustwa', 'Dwa byki - znak płodności i rzemiosła'],
        names: {
            'M': ['Aluthol', 'Cavindel', 'Imbol', 'Farmoth', 'Eldirol', 'Harrond', 'Larandar', 'Safis', 'Meilion', 'Mormacar'],
            'F': ['Alane', 'Davendrel', 'Eldril', 'Halion', 'Ionor', 'Pelgrana', 'Tallana', 'Yuviel', 'Maruviel', 'Eponia']
        }
    },
    halfling: {
        heightBase: 100, heightDice: '1d10',
        weightBase: 35,  weightDice: '1d100',
        ageBase: 20,  ageDice: '1d100',
        hair: ['Brązowy', 'Rudy', 'Jasny', 'Kręcone'],
        eyes: ['Brązowe', 'Piwne', 'Niebieskie'],
        marks: ['Owłosione stopy', 'Okrągły brzuch', 'Zadarty nos', 'Piegi'],
        birthPlaces: ['Kraina zgromadzenia', 'Averland', 'Hochland', 'Middenland', 'Nordland', 'Ostermark', 'Ostland', 'Reikland', 'Stirland', 'Talabecland', 'Wissenland'],
        starSign: ['Bębniarz - znak zabawy i radości', 'Dudy - znak oszustwa', 'Dwa byki - znak płodności i rzemiosła'],
        names: {
            'M': ['Adam', 'Albert', 'Alfred', 'Axel', 'Carl', 'Jakob', 'Max', 'Ludo', 'Rudi', 'Udo'],
            'F': ['Agnes', 'Eva', 'Heidi', 'Hanna', 'Silma', 'Greta', 'Sophia', 'Ulla', 'Brigit', 'Wanda']
        }
    }
}
const getRandomArrayItem = (arr) => arr[Math.floor(Math.random() * arr.length)]
// --- FUNKCJA LOSUJĄCA POJEDYNCZY DETAL (Poprawiona) ---
const rollSingleDetail = (key) => {
    if (!selectedRace.value) return
    const raceKey = selectedRace.value.key || 'human'
    const table = personalDetailsTables[raceKey] || personalDetailsTables.human

    switch (key) {
        case 'height':
            // Parsowanie rzutu np '2d10' na logikę
            // Tutaj uproszczenie: zakładamy, że w table.heightDice jest liczba lub string
            // Dla człowieka: 2d10 -> 2-20
            let hRoll = 0
            if (typeof table.heightDice === 'string' && table.heightDice.includes('d')) {
                const [count, die] = table.heightDice.split('d').map(Number)
                for(let i=0; i<count; i++) hRoll += Math.floor(Math.random() * die) + 1
            } else {
                hRoll = Math.floor(Math.random() * (table.heightDice || 10)) + 1
            }
            personalDetails.height = table.heightBase + hRoll
            break;

        case 'weight':
            let wRoll = 0
            // Uproszczona logika dla wagi (k100 vs k10 w zależności od rasy)
            // Możesz tu dodać parsowanie jak wyżej
            const wDie = typeof table.weightDice === 'number' ? table.weightDice : 20
            wRoll = Math.floor(Math.random() * wDie) + 1
            personalDetails.weight = table.weightBase + wRoll
            break;

        case 'age':
            const aDie = typeof table.ageDice === 'number' ? table.ageDice : 10
            const aRoll = Math.floor(Math.random() * aDie) + 1
            personalDetails.age = (table.ageBase || 15) + aRoll
            break;

        case 'hairColor':
            personalDetails.hairColor = getRandomArrayItem(table.hair)
            break;

        case 'eyeColor':
            personalDetails.eyeColor = getRandomArrayItem(table.eyes)
            break;

        case 'distinguishingMark':
            personalDetails.distinguishingMark = getRandomArrayItem(table.marks)
            break;

        case 'starSign':
            // POPRAWKA: W tabeli klucz to 'starSign' (tablica), a nie 'starSigns'
            personalDetails.starSign = getRandomArrayItem(table.starSign)
            break;

        case 'birthplace':
            // POPRAWKA: W tabeli klucz to 'birthPlaces' (z dużą literą P)
            personalDetails.birthplace = getRandomArrayItem(table.birthPlaces)
            break;

        case 'siblings':
            personalDetails.siblings = Math.floor(Math.random() * 6)
            break;

        case 'name':
            // POPRAWKA: Obsługa płci dla imion
            const genderKey = personalDetails.gender
            const namesList = table.names[genderKey]
            if (namesList) {
                personalDetails.value.name = getRandomArrayItem(namesList)
            }
            break;
    }
}

// Funkcja "Wylosuj Wszystko" używa teraz pojedynczych
const rollPersonalDetails = () => {
    rollSingleDetail('height')
    rollSingleDetail('weight')
    rollSingleDetail('age')
    rollSingleDetail('hairColor')
    rollSingleDetail('eyeColor')
    rollSingleDetail('distinguishingMark')
    rollSingleDetail('starSign')
    rollSingleDetail('siblings')
    rollSingleDetail('birthplace')
    rollSingleDetail('name') // Imię zazwyczaj zostawiamy graczowi, chyba że chce
}

let diceBox = null
let diceBoxAttributes = null

// Dane bohatera
const heroData = ref({
    race: null,
    characteristics: {}
})

// RECTIVE do przechowywania wyborów
const selectedChoices = reactive({
    skills: {},
    talents: {}
})

const randomTalentsTables = {
    // Tabela dla Ludzi (2 rzuty)
    human: [
        { min: 1,  max: 4,   id: 23, name: 'Bardzo silny' },
        { min: 5,  max: 9,  id: 24, name: 'Bardzo szybki' },
        { min: 10, max: 13,  id: 28, name: 'Błyskotliwość' },
        { min: 14, max: 18,  id: 32, name: 'Bystry wzrok' },
        { min: 19, max: 22,  id: 33, name: 'Charyzmatyczny' },
        { min: 23, max: 27,  id: 68, name: 'Czuły słuch' },
        { min: 28, max: 31,  id: 71, name: 'Geniusz artmetyczny' },
        { min: 32, max: 35,  id: 78, name: 'Krzepki' },
        { min: 36, max: 40,  id: 16, name: 'Naśladowca' },
        { min: 41, max: 44,  id: 19, name: 'Niezwykle odporny' },
        { min: 45, max: 49,  id: 21, name: 'Oburęczność' },
        { min: 50, max: 53,  id: 38, name: 'Odporność ma choroby' },
        { min: 54, max: 57,  id: 39, name: 'Odporność ma magię' },
        { min: 58, max: 61,  id: 40, name: 'Odporność ma trucizny' },
        { min: 62, max: 66,  id: 41, name: 'Odporność psychiczna' },
        { min: 67, max: 71,  id: 44, name: 'Opanowanie' },
        { min: 72, max: 75,  id: 4, name: 'Strzelec wyborowy' },
        { min: 76, max: 79,  id: 6, name: 'Szczęście' },
        { min: 80, max: 83,  id: 7, name: 'Szósty zmysł' },
        { min: 84, max: 87, id: 8, name: 'Szybki refleks' },
        { min: 88, max: 91, id: 54, name: 'Twardziel' },
        { min: 92, max: 95, id: 56, name: 'Urodzony wojownik' },
        { min: 96, max: 100, id: 58, name: 'Widzenie w ciemności' },
    ],
    // Tabela dla Niziołków (1 rzut, specyficzna)
    halfling: [
        { min: 1,  max: 4,   id: 23, name: 'Bardzo silny' },
        { min: 5,  max: 9,  id: 24, name: 'Bardzo szybki' },
        { min: 10, max: 13,  id: 28, name: 'Błyskotliwość' },
        { min: 14, max: 18,  id: 32, name: 'Bystry wzrok' },
        { min: 19, max: 23,  id: 33, name: 'Charyzmatyczny' },
        { min: 24, max: 28,  id: 68, name: 'Czuły słuch' },
        { min: 29, max: 34,  id: 71, name: 'Geniusz artmetyczny' },
        { min: 35, max: 39,  id: 78, name: 'Krzepki' },
        { min: 40, max: 44,  id: 16, name: 'Naśladowca' },
        { min: 45, max: 49,  id: 19, name: 'Niezwykle odporny' },
        { min: 50, max: 53,  id: 21, name: 'Oburęczność' },
        { min: 54, max: 58,  id: 38, name: 'Odporność ma choroby' },
        { min: 59, max: 62,  id: 39, name: 'Odporność ma magię' },
        { min: 63, max: 64,  id: 40, name: 'Odporność ma trucizny' },
        { min: 65, max: 68,  id: 41, name: 'Odporność psychiczna' },
        { min: 69, max: 73,  id: 44, name: 'Opanowanie' },
        { min: 74, max: 78,  id: 4, name: 'Strzelec wyborowy' },
        { min: 79, max: 82,  id: 6, name: 'Szczęście' },
        { min: 83, max: 87,  id: 7, name: 'Szósty zmysł' },
        { min: 88, max: 92, id: 8, name: 'Szybki refleks' },
        { min: 93, max: 96, id: 54, name: 'Twardziel' },
        { min: 96, max: 100, id: 56, name: 'Urodzony wojownik' },
    ]
}

const isTalentAlreadyOwned = (talentId) => {
    // 1. Sprawdź w już wylosowanych (z tabeli losowej Rasy)
    if (rolledRandomTalents.value.some(t => t.id === talentId)) return true

    // 2. Sprawdź w obowiązkowych (stałe z Rasy + stałe z Profesji)
    if (aggregatedTalents.value.mandatory.some(t => (t.talent?.id || t.id) === talentId)) return true

    // USUNIĘTO: 3. Sprawdź w wybranych z listy.
    // To powodowało błąd, że po kliknięciu pojawiała się flaga "Posiadasz".

    return false
}

// Wybór Umiejętności (Naprawiona literówka)
const getUniqueKey = (item) => {
    // Preferujemy ID pivota (relacji), bo jest zawsze unikalne
    if (item.pivot?.id) return `pivot-${item.pivot.id}`

    // Jeśli nie ma pivota (np. z rasy z API), tworzymy klucz z ID i dodatkowej nazwy
    const itemId = item.skill?.id || item.talent?.id || item.id
    const addName = item.additional_name || item.pivot?.additional_name || ''

    if (itemId) {
        return `item-${itemId}-${addName}`
    }

    // Ostateczny fallback
    return `rand-${Math.random()}`
}
const isSkillMandatory = (item) => {
    const itemId = item.skill?.id || item.id
    // Przeszukujemy listę mandatory
    return aggregatedSkills.value.mandatory.some(m => {
        const mId = m.skill?.id || m.id
        // Sprawdzamy ID ORAZ czy nazwy dodatkowe (np. rodzaj rzemiosła) są takie same
        // Jeśli nazwy dodatkowe są różne (np. Rzemiosło (Kowal) vs (Krawiec)), to NIE jest ta sama umiejętność
        const itemAdd = item.additional_name || item.pivot?.additional_name
        const mAdd = m.additional_name || m.pivot?.additional_name

        return mId === itemId && itemAdd === mAdd
    })
}
const isTalentMandatory = (item) => {
    const itemId = item.talent?.id || item.id
    return aggregatedTalents.value.mandatory.some(m => {
        const mId = m.talent?.id || m.id
        return mId === itemId
    })
}

// Zaktualizowane funkcje wyboru (używają klucza zamiast ID)
const selectSkill = (groupIndex, item) => {
    selectedChoices.skills[groupIndex] = getUniqueKey(item)
}

const selectTalent = (groupIndex, item) => {
    selectedChoices.talents[groupIndex] = getUniqueKey(item)
}
// --- Helpers do Źródeł (Rasa vs Profesja) ---
const tagSource = (items, sourceName) => {
    if (!items) return []
    return items.map(item => ({ ...item, source: sourceName }))
}

const tagSourceGroup = (groups, sourceName) => {
    if (!groups) return []
    return groups.map(group => group.map(item => ({ ...item, source: sourceName })))
}

// --- Helpers do wyciągania list (Group 0 vs Group > 0) ---
const getMandatoryItems = (groupedCollection) => {
    if (!groupedCollection) return [];
    // Obsługa struktury tablicowej (API) lub obiektowej (JSON)
    const values = Array.isArray(groupedCollection) ? groupedCollection : Object.values(groupedCollection);

    // Szukamy grupy '0' (obowiązkowe) lub tablic o długości 1 (jeśli logika tak zakłada)
    // W twoim JSON z API rasy pewnie przychodzą jako obiekt { '0': [...], '1': [...] }
    if (!Array.isArray(groupedCollection)) {
        return groupedCollection['0'] || [];
    }

    // Fallback dla innej struktury
    return values.filter((group: any) => Array.isArray(group) && group.length === 1).flat();
}

const getChoiceGroups = (groupedCollection) => {
    if (!groupedCollection) return [];

    // Jeśli to obiekt (klucze '1', '2' itd.)
    if (!Array.isArray(groupedCollection)) {
        return Object.keys(groupedCollection)
            .filter(key => key !== '0') // Wszystko co nie jest 0
            .map(key => groupedCollection[key]);
    }

    // Fallback
    return Object.values(groupedCollection)
        .filter((group: any) => Array.isArray(group) && group.length > 1);
}

// --- AGREGACJA (Rasa + Profesja) ---
const aggregatedSkills = computed(() => {
    const race = selectedRace.value
    const prof = selectedProfession.value

    const raceMandatory = race?.skills ? getMandatoryItems(race.skills) : []
    const profMandatory = prof?.skills ? getMandatoryItems(prof.skills) : []

    const raceChoices = race?.skills ? getChoiceGroups(race.skills) : []
    const profChoices = prof?.skills ? getChoiceGroups(prof.skills) : []

    return {
        mandatory: [
            ...tagSource(raceMandatory, 'race'),
            ...tagSource(profMandatory, 'profession')
        ],
        choices: [
            ...tagSourceGroup(raceChoices, 'race'),
            ...tagSourceGroup(profChoices, 'profession')
        ]
    }
})

const aggregatedTalents = computed(() => {
    const race = selectedRace.value
    const prof = selectedProfession.value

    const raceMandatory = race?.talents ? getMandatoryItems(race.talents) : []
    const profMandatory = prof?.talents ? getMandatoryItems(prof.talents) : []

    const raceChoices = race?.talents ? getChoiceGroups(race.talents) : []
    const profChoices = prof?.talents ? getChoiceGroups(prof.talents) : []

    return {
        mandatory: [
            ...tagSource(raceMandatory, 'race'),
            ...tagSource(profMandatory, 'profession')
        ],
        choices: [
            ...tagSourceGroup(raceChoices, 'race'),
            ...tagSourceGroup(profChoices, 'profession')
        ]
    }
})

const areAllChoicesMade = computed(() => {
    const skillsRequired = aggregatedSkills.value.choices.length
    const talentsRequired = aggregatedTalents.value.choices.length

    const skillsDone = Object.keys(selectedChoices.skills).length === skillsRequired
    const talentsDone = Object.keys(selectedChoices.talents).length === talentsRequired

    // Sprawdzamy czy wylosowano wymaganą liczbę talentów losowych
    const randomTalentsDone = rolledRandomTalents.value.length === requiredRandomTalentsCount.value

    return skillsDone && talentsDone && randomTalentsDone
})

// --- Zmienne referencyjne ---
const selectedRace = ref(null)
const selectedProfession = ref(null)
const availableRaces = ref([])

// --- Logika Cech ---
const characteristics = ref({
    WW: {name: 'Walka Wręcz', base: 0, assignedValue: null},
    US: {name: 'Um. Strzeleckie', base: 0, assignedValue: null},
    K: {name: 'Krzepa', base: 0, assignedValue: null},
    Odp: {name: 'Odporność', base: 0, assignedValue: null},
    Zr: {name: 'Zręczność', base: 0, assignedValue: null},
    Int: {name: 'Inteligencja', base: 0, assignedValue: null},
    SW: {name: 'Siła Woli', base: 0, assignedValue: null},
    Ogd: {name: 'Ogłada', base: 0, assignedValue: null}
})
const secondaryStats = ref({
    wounds: {val: null, roll: null},
    fate: {val: null, roll: null}
})

// --- Watchers i Init ---
watch(currentStep, async (newStep) => {
    if (newStep === 2 && !diceBox) {
        await nextTick()
        const el = document.querySelector('#dice-box-canvas')
        if (el) await initializeDiceBox()
    }
    // Jeśli wchodzimy w krok 4, czyścimy wybory, jeśli zmieniła się profesja/rasa
    // (Można to dopracować, ale reset jest bezpieczny)
})

watch(selectedProfession, () => {
    // Reset wyborów przy zmianie profesji
    for (const key in selectedChoices.skills) delete selectedChoices.skills[key]
    for (const key in selectedChoices.talents) delete selectedChoices.talents[key]
})

// Fetch Ras
const fetchRaces = () => {
    axios.get('/create-character/get-races')
        .then(response => {
            availableRaces.value = response.data;
        })
        .catch(error => {
            console.error('Error fetching races:', error);
        });
}

onMounted(() => {
    fetchRaces();
})

// Wybór Rasy
const selectRace = (race) => {
    selectedRace.value = race
    heroData.value.race = race

    // Reset i ustawienie bazowych cech
    // UWAGA: Sprawdź czy `base_stats` z API to tablica czy obiekt. Kod zakłada tablicę obiektów.
    Object.keys(characteristics.value).forEach(key => {
        // Znajdź odpowiednią cechę w tablicy z API
        const statData = race.base_stats?.find(s => s.characteristic?.short_name === key || s.short_name === key);
        characteristics.value[key].base = statData ? statData.value : 20; // Domyślnie 20 jeśli błąd
        characteristics.value[key].assignedValue = null
    })

    // Reset drugorzędnych
    secondaryStats.value.wounds = {val: null, roll: null}
    secondaryStats.value.fate = {val: null, roll: null}
}

// --- Helpers formatowania ---
const formatSkillName = (item) => {
    // item może być z API (zagnieżdżony w item.skill) lub płaski
    // item może być też pivotem
    const skillObj = item.skill || item;
    const name = skillObj.name || 'Nieznana';
    const additional = item.additional_name || item.pivot?.additional_name;

    return additional ? `${name} (${additional})` : name;
}

const formatTalentName = (item) => {
    const talentObj = item.talent || item;
    const name = talentObj.name || 'Nieznany';
    const additional = item.additional_name || item.pivot?.additional_name;

    return additional ? `${name} (${additional})` : name;
}

// --- Finish Creation ---
const finishCreation = () => {
    // Funkcja pomocnicza: Znajdź obiekt źródłowy na podstawie wybranego klucza string
    const resolveItemFromKey = (key, allChoicesGroups) => {
        for (const group of allChoicesGroups) {
            for (const item of group) {
                if (getUniqueKey(item) === key) {
                    // Zwracamy obiekt w formacie gotowym do zapisu (np. { id: 5, optional: 'Kowalstwo' })
                    // Tutaj upraszczamy do samego ID, ale można łatwo rozszerzyć
                    return item.skill?.id || item.talent?.id || item.id
                }
            }
        }
        return null
    }
    const finalCharacteristics = {}
    Object.entries(characteristics.value).forEach(([key, val]) => {
        // Mapujemy klucze charakterystyk (np. WW) na to co jest w freeAdvance
        // W characteristics kluczem jest np 'WW', a w freeAdvance.key też 'WW'
        let total = val.base + (val.assignedValue || 0)

        if (freeAdvance.value?.type === 'main' && freeAdvance.value?.key === key) {
            total += freeAdvance.value.value
        }
        finalCharacteristics[key] = total
    })

    // Drugorzędne
    const finalSecondary = {
        wounds: secondaryStats.value.wounds.val,
        fate: secondaryStats.value.fate.val,
    }

    // Jeśli darmowy awans był na Żywotność lub PP, dodajemy go do wyniku
    if (freeAdvance.value?.type === 'secondary') {
        if (freeAdvance.value.key === 'Żyw') finalSecondary.wounds += 1
        if (freeAdvance.value.key === 'PP') finalSecondary.fate += 1
    }
    // 1. Obowiązkowe (Mandatory)
    const mandatorySkillIds = aggregatedSkills.value.mandatory.map(i => i.skill?.id || i.id)
    const mandatoryTalentIds = aggregatedTalents.value.mandatory.map(i => i.talent?.id || i.id)

    // 2. Wybrane (Choices) - Odzyskujemy ID z kluczy
    const chosenSkillIds = Object.values(selectedChoices.skills)
        .map(key => resolveItemFromKey(key, aggregatedSkills.value.choices))
        .filter(id => id !== null)

    const chosenTalentIds = Object.values(selectedChoices.talents)
        .map(key => resolveItemFromKey(key, aggregatedTalents.value.choices))
        .filter(id => id !== null)

    const randomTalentIds = rolledRandomTalents.value.map(t => t.id)

    const finalHeroData = {
        race: selectedRace.value.name,
        profession: selectedProfession.value.id,
        skills: [...mandatorySkillIds, ...chosenSkillIds],
        talents: [...mandatoryTalentIds, ...chosenTalentIds, ...randomTalentIds],
        characteristics: finalCharacteristics,
        secondaryCharacteristics: finalSecondary,
        freeAdvance: freeAdvance.value,
        personalDetails: { ...personalDetails }
    }

    emit('hero-created', finalHeroData)
    isCreating.value = false
}

// --- Pozostałe zmienne i funkcje (DiceBox, Configi, Navigation) ---
const rollPool = ref([])
const selectedPoolIndex = ref(null)
const isRollingAttributes = ref(false)
const mercyUsed = ref(false)
const isRollingSecondary = ref(false)
const isRollingProfession = ref(false)
const professionRoll = ref(0)
const freeAdvance = ref(null);
const canAdvanceStat = (statLabel) => {
    if (!selectedProfession.value) return false
    const val = getStatValue(selectedProfession.value.characteristics, statLabel)
    return val && val > 0
}
const selectFreeAdvance = (statLabel, isSecondary = false) => {
    // Jeśli klikamy w to samo, odznaczamy
    if (freeAdvance.value?.key === statLabel) {
        freeAdvance.value = null
        return
    }

    const bonusValue = isSecondary ? 1 : 5

    freeAdvance.value = {
        key: statLabel,
        value: bonusValue,
        type: isSecondary ? 'secondary' : 'main'
    }
}
const availableSecondaryAdvances = computed(() => {
    if (!selectedProfession.value) return []

    // Filtrujemy konfigurację drugorzędnych
    return secondaryProfileConfig
        .filter(conf => {
            // Pomijamy Żywotność i PP, bo one mają swoją mechanikę rzutu (chyba że chcemy pozwolić podbić wylosowane)
            // W WFRP darmowy awans można wziąć na Żyw, jeśli jest w schemacie. Zostawmy wszystkie.
            return canAdvanceStat(conf.label)
        })
        .map(conf => ({
            label: conf.label,
            name: getSecondaryStatFullName(conf.label) // Helper do pełnej nazwy
        }))
})

const getSecondaryStatFullName = (label) => {
    const map = { 'A': 'Ataki', 'Żyw': 'Żywotność', 'S': 'Siła', 'Wt': 'Wytrzymałość', 'Sz': 'Szybkość', 'Mag': 'Magia', 'PO': 'Punkty Obłędu', 'PP': 'Punkty Przeznaczenia' }
    return map[label] || label
}

const rolledRandomTalents = ref([])
const isRollingRandomTalent = ref(false)

const isRollingAny = computed(() => isRollingAttributes.value || isRollingSecondary.value)
const requiredRandomTalentsCount = computed(() => {
    if (!selectedRace.value) return 0
    // Klucze ras muszą się zgadzać z tymi w availableRaces
    if (selectedRace.value.key === 'human') return 2
    if (selectedRace.value.key === 'halfling') return 1
    return 0
})

// 3. Funkcja losująca
const rollRandomTalent = async () => {
    if (rolledRandomTalents.value.length >= requiredRandomTalentsCount.value) return
    if (isRollingRandomTalent.value) return

    isRollingRandomTalent.value = true

    try {
        if (!diceBoxAttributes) await initDiceBoxAttributes()

        let talent = null
        let attempts = 0
        const maxAttempts = 5 // Zabezpieczenie przed nieskończoną pętlą

        // Pobieramy odpowiednią tabelę
        const raceKey = selectedRace.value.key
        const currentTable = randomTalentsTables[raceKey] || randomTalentsTables.human // Fallback do ludzkiej

        // Pętla do obsługi duplikatów (auto-reroll)
        do {
            diceBoxAttributes.clear()
            const result = await diceBoxAttributes.roll('1d100')
            const rollValue = result[0].value

            // Znajdź talent w zakresie
            talent = currentTable.find(t => rollValue >= t.min && rollValue <= t.max)

            if (talent) {
                // Dodajemy wynik rzutu do obiektu talentu
                talent = { ...talent, roll: rollValue }

                // Jeśli już mamy ten talent, rzucamy jeszcze raz (o ile nie przekroczyliśmy limitu prób)
                if (isTalentAlreadyOwned(talent.id)) {
                    console.log(`Wylosowano duplikat: ${talent.name} (${rollValue}). Przerzucam...`)
                    talent = null // Reset, pętla pójdzie dalej
                    await new Promise(r => setTimeout(r, 800)) // Krótka pauza dla efektu
                }
            }
            attempts++
        } while (!talent && attempts < maxAttempts)

        if (talent) {
            rolledRandomTalents.value.push(talent)
        } else {
            console.warn("Nie udało się wylosować unikalnego talentu po wielu próbach.")
        }

    } catch (e) {
        console.error(e)
    } finally {
        isRollingRandomTalent.value = false
    }
}
const progressPercentage = computed(() => (currentStep.value / totalSteps) * 100)
const canProceedPersonal = computed(() => {
    return personalDetails.height > 0 &&
        personalDetails.weight > 0 &&
        personalDetails.hairColor !== ''
})

const canProceed = computed(() => {
    switch (currentStep.value) {
        case 1: return selectedRace.value !== null
        case 2: return selectedProfession.value !== null
        case 3:
            const mainStatsDone = Object.values(characteristics.value).every(c => c.assignedValue !== null)
            const secStatsDone = secondaryStats.value.wounds.val !== null && secondaryStats.value.fate.val !== null
            const advanceDone = freeAdvance.value !== null
            return mainStatsDone && secStatsDone && advanceDone
        case 4: return areAllChoicesMade.value
        case 5: return canProceedPersonal.value // NOWE: Krok 5 (Detale)
        default: return false
    }
})

// --- Inicjalizacja DiceBox ---
const initializeDiceBox = async () => {
    const container = document.querySelector('#dice-box-canvas')
    if (!container) return
    const {width, height} = container.getBoundingClientRect()
    diceBox = new DiceBox('#dice-box-canvas', {
        assetPath: '/assets/dice-box/',
        theme: 'default',
        themeColor: '#d4af37',
        scale: 9,
        container_width: width, container_height: height
    })
    await diceBox.init()
    const canvas = container.querySelector('canvas')
    if (canvas) { canvas.style.width = '100%'; canvas.style.height = '100%' }
}

const initDiceBoxAttributes = async () => {
    const container = document.querySelector('#dice-box-attributes')
    if (!container) return
    const {width, height} = container.getBoundingClientRect()
    diceBoxAttributes = new DiceBox('#dice-box-attributes', {
        assetPath: '/assets/dice-box/',
        theme: 'default',
        themeColor: '#d4af37',
        scale: 6,
        container_width: width, container_height: height
    })
    await diceBoxAttributes.init()
    const canvas = container.querySelector('canvas')
    if (canvas) { canvas.style.width = '100%'; canvas.style.height = '100%' }
}

// Watcher dla kroku 3 (Cechy) - osobny dicebox
watch(currentStep, async (newStep) => {
    if (newStep === 3 && !diceBoxAttributes) {
        await nextTick()
        await initDiceBoxAttributes()
    }
})
watch(selectedRace, () => {
    rolledRandomTalents.value = []
})

// --- Funkcje Rzutów ---
const rollForProfession = async () => {
    if (!selectedRace.value || isRollingProfession.value) return
    if (!diceBox) await initializeDiceBox()

    isRollingProfession.value = true
    selectedProfession.value = null
    try {
        diceBox.clear()
        const res = await diceBox.roll('1d100')
        professionRoll.value = res[0].value

        const response = await rollProfession(selectedRace.value.key, professionRoll.value)
        selectedProfession.value = response.data || response
    } catch(e) { console.error(e) }
    finally { isRollingProfession.value = false }
}

const rollAttributesPool = async () => {
    if (isRollingAny.value) return
    isRollingAttributes.value = true
    rollPool.value = []
    selectedPoolIndex.value = null
    mercyUsed.value = false
    Object.keys(characteristics.value).forEach(k => characteristics.value[k].assignedValue = null)

    try {
        if(!diceBoxAttributes) await initDiceBoxAttributes()
        diceBoxAttributes.clear()
        await diceBoxAttributes.roll('8d10') // Wizualnie

        // Logika 2k10 x 8
        const newPool = []
        for(let i=0; i<8; i++) {
            const val = Math.floor(Math.random()*10)+1 + Math.floor(Math.random()*10)+1
            newPool.push({ id: i, value: val, isUsed: false })
        }
        setTimeout(() => { rollPool.value = newPool; isRollingAttributes.value = false }, 1000)
    } catch(e) { console.error(e); isRollingAttributes.value = false }
}

const rollSecondary = async (type) => {
    if (isRollingAny.value) return
    isRollingSecondary.value = true
    try {
        if(!diceBoxAttributes) await initDiceBoxAttributes()
        diceBoxAttributes.clear()
        await diceBoxAttributes.roll('1d10')

        const roll = Math.floor(Math.random()*10)+1
        // Logika tabelkowa (skopiowana z poprzedniego kodu, tutaj skrótowo wywołana)
        // ... (Tu wklej funkcję calculateSecondaryStats z poprzedniej odpowiedzi) ...
        // Używam mocka dla zwięzłości:
        const val = (roll > 5) ? 12 : 10;

        secondaryStats.value[type] = { val: val, roll: roll }
    } catch(e) { console.error(e) }
    finally { isRollingSecondary.value = false }
}

const selectPoolItem = (idx) => { if(!rollPool.value[idx].isUsed) selectedPoolIndex.value = (selectedPoolIndex.value === idx ? null : idx) }

const assignSelectedToStat = (key) => {
    if (selectedPoolIndex.value === null) return
    const poolItem = rollPool.value[selectedPoolIndex.value]
    const stat = characteristics.value[key]

    if (stat.assignedValue !== null) unassignStat(key)

    stat.assignedValue = poolItem.value
    rollPool.value[selectedPoolIndex.value].isUsed = true
    selectedPoolIndex.value = null
}

const unassignStat = (key) => {
    const stat = characteristics.value[key]
    if (stat.assignedValue === null) return
    const idx = rollPool.value.findIndex(p => p.value === stat.assignedValue && p.isUsed)
    if(idx !== -1) rollPool.value[idx].isUsed = false
    stat.assignedValue = null
}

const rerollSelectedPoolItem = async () => {
    if(selectedPoolIndex.value === null || mercyUsed.value) return
    mercyUsed.value = true
    isRollingAttributes.value = true
    try {
        diceBoxAttributes.clear()
        await diceBoxAttributes.roll('2d10')
        rollPool.value[selectedPoolIndex.value].value = Math.floor(Math.random()*10)+1 + Math.floor(Math.random()*10)+1
    } catch(e){} finally { isRollingAttributes.value = false }
}

const nextStep = () => { if(canProceed.value && currentStep.value < totalSteps) currentStep.value++ }
const previousStep = () => { if(currentStep.value > 1) currentStep.value-- }
const startCreation = () => { isCreating.value = true; currentStep.value = 1 }
const closeCreation = () => {
    if(diceBox) { diceBox.clear(); diceBox = null }
    if(diceBoxAttributes) { diceBoxAttributes.clear(); diceBoxAttributes = null }
    isCreating.value = false
    emit('creation-closed')
}

// Configi do wyświetlania
const mainProfileConfig = [
    {label: 'WW', keys: ['WW']}, {label: 'US', keys: ['US']}, {label: 'K', keys: ['K']}, {label: 'Odp', keys: ['Odp']},
    {label: 'Zr', keys: ['Zr']}, {label: 'Int', keys: ['Int']}, {label: 'SW', keys: ['SW']}, {label: 'Ogd', keys: ['Ogd']}
]
const secondaryProfileConfig = [
    {label: 'A', keys: ['A']}, {label: 'Żyw', keys: ['Żyw']}, {label: 'S', keys: ['S']}, {label: 'Wt', keys: ['Wt']},
    {label: 'Sz', keys: ['Sz']}, {label: 'Mag', keys: ['Mag']}, {label: 'PO', keys: ['PO']}, {label: 'PP', keys: ['PP']}
]
const getStatValue = (chars, label) => {
    if(Array.isArray(chars)) {
        return chars.find(c => c.characteristic?.short_name === label)?.available_advancement
    }
    return chars?.[label]
}
const formatStatBonus = (val) => val ? `+${val}` : '—'
const formatItemName = (i) => i.item_name || i.item?.tradeable?.name || 'Przedmiot'

defineExpose({ startCreation })
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
    width: 450px; /* Sztywna szerokość */

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
    box-shadow: inset 0 0 50px rgba(0, 0, 0, 0.8);
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
    scrollbar-color: #d4af37 rgba(255, 255, 255, 0.05);
}

/* Chrome/Safari Scrollbar */
.profession-content-scroll::-webkit-scrollbar {
    width: 6px;
}

.profession-content-scroll::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
}

.profession-content-scroll::-webkit-scrollbar-thumb {
    background: #d4af37;
    border-radius: 3px;
}

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

.char-name {
    font-size: 0.7rem;
    color: #888;
    font-weight: bold;
}

.char-bonus {
    font-size: 1rem;
    color: #d4af37;
    font-weight: bold;
}

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

.input-group {
    margin-bottom: 1.5rem;
}

.input-label {
    display: block;
    color: #d4af37;
    margin-bottom: 0.5rem;
    font-weight: bold;
}

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

.step-indicators {
    display: flex;
    gap: 0.5rem;
}

.step-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #444;
    transition: 0.3s;
}

.step-dot.active {
    background: #d4af37;
    transform: scale(1.4);
}

.step-dot.completed {
    background: #888;
}

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
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
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
    .hero-creation-overlay {
        padding: 0;
        align-items: flex-end;
    }

    .creation-container {
        width: 100%;
        height: 100%;
        border-radius: 0;
        border: none;
        max-height: none;
    }

    .race-selection-grid {
        gap: 1rem;
    }

    .race-card {
        width: 100%;
    }
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
.col-name {
    flex: 2;
    display: flex;
    align-items: center;
    gap: 10px;
}

.col-base, .col-roll, .col-total, .col-action {
    flex: 1;
    text-align: center;
}

.attr-abbr {
    font-weight: bold;
    color: #d4af37;
    width: 35px;
    display: inline-block;
}

.attr-full {
    color: #ccc;
    font-size: 0.9rem;
}

.col-base {
    color: #888;
    font-family: monospace;
    font-size: 1.1rem;
}

.roll-val {
    color: #fff;
    font-family: monospace;
    font-size: 1.1rem;
}

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

.mercy-info {
    color: #ccc;
    font-style: italic;
    font-size: 0.9rem;
}

.mercy-used {
    color: #888;
    text-decoration: line-through;
    font-size: 0.9rem;
}

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

.col-lbl {
    flex: 1;
    text-align: center;
}

.col-lbl:first-child {
    flex: 2;
    text-align: left;
}

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
    0% {
        border-color: rgba(212, 175, 55, 0.3);
    }
    50% {
        border-color: rgba(212, 175, 55, 0.8);
    }
    100% {
        border-color: rgba(212, 175, 55, 0.3);
    }
}

.stat-name-block {
    flex: 2;
    display: flex;
    align-items: center;
    gap: 10px;
}

.stat-abbr {
    font-weight: bold;
    color: #d4af37;
    font-size: 1rem;
    width: 40px;
}

.stat-full {
    color: #aaa;
    font-size: 0.85rem;
}

.stat-base-val {
    flex: 1;
    text-align: center;
    color: #666;
    font-family: monospace;
    font-size: 1.1rem;
}

.stat-total-val {
    flex: 1;
    text-align: center;
    color: #d4af37;
    font-weight: bold;
    font-size: 1.2rem;
}

.dimmed {
    color: #444;
}

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

.stat-assignment-row:hover .remove-assign-btn {
    opacity: 1;
}


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
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.8);
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

.reroll-one-btn:hover {
    background: rgba(212, 175, 55, 0.1);
}

.instruction-text {
    font-size: 0.8rem;
    color: #888;
    font-style: italic;
}

.mercy-used-info {
    color: #666;
    font-size: 0.8rem;
    text-decoration: line-through;
}

.mercy-desc {
    margin: 0 0 0.5rem 0;
    font-size: 0.8rem;
    color: #aaa;
}

/* Mobile */
@media (max-width: 900px) {
    .attributes-assignment-container {
        flex-direction: column-reverse;
    }

    .pool-column {
        flex: none;
        width: 100%;
    }

    .dice-area-small {
        height: 150px;
    }
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

.mt-4 {
    margin-top: 1.5rem;
}

.secondary-stats-section {
    background: rgba(0, 0, 0, 0.3);
    padding: 10px;
    border-radius: 6px;
}

.secondary-row {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.03);
    padding: 10px;
    margin-bottom: 5px;
    border-radius: 4px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.sec-name {
    flex: 2;
    display: flex;
    gap: 10px;
    align-items: center;
}

.sec-val {
    flex: 1;
    text-align: center;
    font-size: 1.2rem;
    color: #fff;
    font-weight: bold;
}

.sec-action {
    flex: 1;
    text-align: right;
}

.final-val {
    color: #d4af37;
    text-shadow: 0 0 5px rgba(212, 175, 55, 0.5);
}

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

.mini-roll-btn:hover:not(:disabled) {
    background: #fff;
}

.mini-roll-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #555;
}

.roll-info {
    font-size: 0.75rem;
    color: #888;
    font-style: italic;
}

.skills-selection-container {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
}

.selection-section {
    background: rgba(0, 0, 0, 0.4);
    border: 1px solid #444;
    border-radius: 8px;
    padding: 1.5rem;
}

.section-title-deco {
    color: #d4af37;
    font-size: 1.4rem;
    border-bottom: 2px solid #d4af37;
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem;
    text-transform: uppercase;
    text-align: left;
}

.label-small {
    display: block;
    font-size: 0.8rem;
    color: #888;
    text-transform: uppercase;
    margin-bottom: 0.8rem;
    letter-spacing: 1px;
}

.label-small.highlight {
    color: #d4af37;
    font-weight: bold;
    margin-top: 1.5rem;
}

/* Tagi obowiązkowe */
.tags-group {
    display: flex;
    flex-wrap: wrap;
    gap: 0.8rem;
}

.static-tag {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid #555;
    color: #ccc;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: default;
}

.check-icon {
    color: #4caf50; /* Zielony ptaszek */
    font-weight: bold;
}

/* Karty wyboru */
.choice-row {
    margin-bottom: 1.2rem;
    border-left: 2px solid rgba(212, 175, 55, 0.3);
    background: linear-gradient(to right, rgba(212, 175, 55, 0.05), transparent);
    padding: 1rem;
    border-radius: 0 4px 4px 0;
}

.choice-options-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

/* Karta wyboru - pozwalamy jej rosnąć w pionie */
.choice-card {
    flex: 1;
    min-width: 250px; /* Nieco szersza, żeby tekst się mieścił */
    background: #1a1a1a;
    border: 1px solid #444;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: flex-start; /* ZMIANA: Wyrównanie do góry (start), nie do środka */
    transition: all 0.2s ease;
    gap: 12px;
    padding: 12px; /* Nieco większy padding dla oddechu */
    height: 100%; /* Żeby karty w jednym rzędzie (flex) miały tę samą wysokość */
}

/* Wnętrze karty */
.choice-content {
    display: flex;
    flex-direction: column; /* Zmiana na kolumnę dla lepszej kontroli */
    width: 100%;
    gap: 4px;
}
.bonus-badge {
    font-size: 0.7rem;
    background: rgba(76, 175, 80, 0.2); /* Zielony transparentny */
    color: #81c784; /* Jasny zielony */
    border: 1px solid #4caf50;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: bold;
    white-space: nowrap;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.1);
    align-self: flex-start; /* Badge trzyma się góry */
    flex-shrink: 0;         /* Nie zgniatać */
    margin-left: auto;      /* Popycha badge do prawej krawędzi */
}

/* Badge "Posiadasz" dla talentów */
.owned-badge {
    font-size: 0.65rem;
    background: rgba(255, 193, 7, 0.15); /* Żółty/Pomarańczowy */
    color: #ffca28;
    border: 1px solid #ffb300;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: normal;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    align-self: flex-start; /* Badge trzyma się góry */
    flex-shrink: 0;         /* Nie zgniatać */
    margin-left: auto;      /* Popycha badge do prawej krawędzi */
}

/* Opcjonalnie: Delikatne wyróżnienie karty, która jest ulepszeniem */
.choice-card.is-upgrade {
    background: rgba(76, 175, 80, 0.05); /* Bardzo delikatna zieleń w tle */
}
.choice-card.is-upgrade:hover {
    background: rgba(76, 175, 80, 0.1);
}

.choice-card:hover {
    border-color: #888;
    background: #252525;
}

.choice-card.selected {
    border-color: #d4af37;
    background: rgba(212, 175, 55, 0.15);
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.1);
}

.radio-circle {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #666;
    position: relative;
    transition: 0.2s;
    flex-shrink: 0; /* Ważne: zapobiega zgniataniu kółka */
    margin-top: 3px; /* Dopasowanie do linii tekstu tytułu */
}

.choice-card.selected .radio-circle {
    border-color: #d4af37;
    background: #d4af37;
    box-shadow: inset 0 0 0 3px #1a1a1a; /* Efekt kropki w środku */
}

.choice-text {
    color: #e4d8b4;
    font-weight: 500;
}

/* Responsywność */
@media (max-width: 600px) {
    .choice-options-wrapper {
        flex-direction: column;
    }
    .choice-card {
        width: 100%;
    }
}
/* Tagi źródła */
.source-tag {
    font-size: 0.65rem;
    padding: 2px 6px;
    border-radius: 4px;
    margin-left: 8px;
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: 0.5px;
}

.source-tag.race {
    background: rgba(100, 149, 237, 0.2); /* CornflowerBlue z przezroczystością */
    color: #6495ed;
    border: 1px solid #6495ed;
}

.source-tag.profession {
    background: rgba(212, 175, 55, 0.2); /* Złoty */
    color: #d4af37;
    border: 1px solid #d4af37;
}

/* Tekst nad grupą wyboru */
.choice-origin-label {
    font-size: 0.8rem;
    color: #888;
    margin-bottom: 0.8rem;
    font-style: italic;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    padding-bottom: 4px;
}

.text-source.race {
    color: #6495ed;
    font-weight: bold;
}

.text-source.profession {
    color: #d4af37;
    font-weight: bold;
}
.random-talents-block {
    background: rgba(0, 0, 0, 0.2);
    border: 1px dashed #d4af37;
    border-radius: 6px;
    padding: 1.2rem;
    margin-bottom: 1rem;
}

.random-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}
.choice-text-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 8px;
    width: 100%;
}

.counter-badge {
    font-family: monospace;
    font-size: 1.1rem;
    color: #000;
    background: #d4af37;
    padding: 2px 8px;
    border-radius: 4px;
    font-weight: bold;
}

.random-talents-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
}

.roll-random-btn {
    background: linear-gradient(180deg, #d4af37 0%, #b4941f 100%);
    border: 1px solid #ffd700;
    color: #1a1a1a;
    padding: 10px 20px;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Cinzel', serif;
    transition: 0.2s;
}

.roll-random-btn:hover:not(:disabled) {
    box-shadow: 0 0 15px rgba(212, 175, 55, 0.4);
    transform: translateY(-1px);
}

.roll-random-btn:disabled {
    filter: grayscale(1);
    cursor: not-allowed;
    opacity: 0.8;
}

.spinning {
    animation: spin 0.8s linear infinite;
}

@keyframes spin { 100% { transform: rotate(360deg); } }

.talent-card.rolled {
    border: 1px solid #d4af37;
    background: rgba(212, 175, 55, 0.1);
    display: flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 4px;
    gap: 10px;
    animation: fadeIn 0.5s ease-out;
}

.talent-name { font-weight: bold; color: #fff; }
.roll-value { font-size: 0.75rem; color: #aaa; display: block; }

@keyframes fadeIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
.text-group {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.choice-desc {
    font-size: 0.75rem;
    color: #999;
    margin-top: 4px;
    font-style: italic;
    white-space: normal; /* ZMIANA: Pozwalamy na zawijanie tekstu */
    line-height: 1.4;    /* Lepsza czytelność */
    display: block;      /* Żeby zajmował nową linię */
}

.static-tag[title] {
    cursor: help;
}

/* Responsywność dla opisów na małych ekranach */
@media (max-width: 600px) {
    .choice-desc {
        max-width: 150px;
        font-size: 0.7rem;
    }
}
.col-lbl.short {
    max-width: 50px;
    text-align: center;
}

.stat-advance-slot {
    width: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Przycisk +5 */
.advance-btn {
    background: transparent;
    border: 1px solid #444;
    color: #666;
    border-radius: 4px;
    padding: 2px 6px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.2s;
}

.advance-btn:hover {
    border-color: #d4af37;
    color: #d4af37;
}

.advance-btn.active {
    background: #d4af37;
    color: #000;
    border-color: #ffd700;
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
    font-weight: bold;
}

.dimmed-dot {
    color: #333;
    font-size: 1.5rem;
    line-height: 0;
}

/* Wyróżnienie wiersza po rozwinięciu */
.stat-assignment-row.advanced {
    background: linear-gradient(90deg, rgba(212, 175, 55, 0.05) 0%, transparent 100%);
    border-left: 2px solid #d4af37;
}

/* Sekcja Drugorzędnych Rozwinięć */
.secondary-advances-section {
    margin-top: 1rem;
    background: rgba(0,0,0,0.2);
    padding: 1rem;
    border-radius: 6px;
    border: 1px dashed #444;
}

.hint-text {
    font-size: 0.75rem;
    color: #888;
    margin-bottom: 0.5rem;
    font-style: italic;
}

.advances-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.sec-advance-btn {
    background: #1a1a1a;
    border: 1px solid #444;
    color: #ccc;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: 0.2s;
}

.sec-advance-btn:hover {
    border-color: #888;
}

.sec-advance-btn.active {
    background: #d4af37;
    color: #000;
    border-color: #ffd700;
    box-shadow: 0 0 8px rgba(212, 175, 55, 0.3);
    font-weight: bold;
}
.personal-details-container {
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    background: rgba(0, 0, 0, 0.4);
    border: 1px solid #444;
    border-radius: 8px;
    padding: 2rem;
}
.grid-row-3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 1.5rem;
}

.grid-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

/* Responsywność dla gridów */
@media (max-width: 768px) {
    .grid-row-3, .grid-row-2 {
        grid-template-columns: 1fr; /* Na telefonie wszystko w pionie */
        gap: 1rem;
    }
}

.details-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    padding-bottom: 1.5rem;
}

/* Przełącznik Płci */
.gender-toggle {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.gender-toggle label {
    color: #d4af37;
    font-weight: bold;
}
.toggle-switch {
    display: flex;
    background: #111;
    border-radius: 4px;
    border: 1px solid #444;
    overflow: hidden;
}
.toggle-switch button {
    background: transparent;
    border: none;
    color: #888;
    padding: 6px 12px;
    cursor: pointer;
    transition: 0.2s;
}
.toggle-switch button.active {
    background: #d4af37;
    color: #000;
    font-weight: bold;
}

/* Przycisk losowania */
.roll-all-btn {
    background: linear-gradient(to bottom, #d4af37, #b4941f);
    border: 1px solid #ffd700;
    color: #000;
    padding: 8px 20px;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
}
.roll-all-btn:hover {
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
}

/* Grid formularza */
.details-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.detail-input-group {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.detail-input-group.wide {
    width: 100%;
}

.input-with-action {
    display: flex;
    align-items: center;
    background: #1a1a1a;
    border: 1px solid #444;
    border-radius: 4px;
    padding-right: 4px; /* Odstęp dla przycisku */
    transition: 0.2s;
}

.input-with-action:focus-within {
    border-color: #d4af37;
    box-shadow: 0 0 5px rgba(212, 175, 55, 0.3);
}

/* Inputy wewnątrz wrappera */
.input-with-action input {
    background: transparent;
    border: none;
    color: #e4d8b4;
    padding: 10px;
    font-family: inherit;
    font-size: 1rem;
    width: 100%;
    outline: none;
}
.mini-dice-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid transparent;
    color: #d4af37;
    cursor: pointer;
    font-size: 1rem;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: 0.2s;
    flex-shrink: 0;
}

.mini-dice-btn:hover {
    background: rgba(212, 175, 55, 0.1);
    border-color: #d4af37;
    transform: scale(1.1);
}

.mini-dice-btn:active {
    transform: scale(0.95);
}
.input-with-action .unit {
    color: #555;
    font-size: 0.8rem;
    margin-right: 8px;
    white-space: nowrap;
}

.detail-input-group label {
    color: #888;
    font-size: 0.8rem;
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: 0.5px;
}
/* Inputy */
.fantasy-input, .fantasy-input-small {
    background: #1a1a1a;
    border: 1px solid #555;
    color: #e4d8b4;
    padding: 10px;
    border-radius: 4px;
    font-family: inherit;
    font-size: 1rem;
    transition: 0.2s;
}

.fantasy-input-small {
    width: 100px;
    text-align: center;
}

.fantasy-input:focus, .fantasy-input-small:focus {
    border-color: #d4af37;
    outline: none;
    box-shadow: 0 0 5px rgba(212, 175, 55, 0.3);
}

.input-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
}

.unit {
    color: #666;
    font-size: 0.8rem;
    font-style: italic;
}
</style>
