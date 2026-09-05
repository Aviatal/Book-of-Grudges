<template>
    <div>
        <div class="stat-group-label">
            <span>CECHY PODSTAWOWE</span>
            <span class="stat-group-line"></span>
        </div>
        <div class="stat-grid">
            <div
                v-for="code in primaryCodes"
                :key="code"
                class="stat-card stat-card--clickable"
                title="Kliknij, aby rozwinąć cechę"
                @click="developCharacteristic(code)"
            >
                <div class="stat-card__code">{{ code }}</div>
                <div class="stat-card__value">{{ totalValue(code) }}</div>
                <div class="stat-card__detail">{{ characteristic[code]?.pivot.start_value }} + {{ characteristic[code]?.pivot.advancement }}</div>
                <div class="stat-card__available">dostępne: {{ characteristic[code]?.available_advancement ?? '-' }}</div>
                <button class="stat-card__develop" @click.stop="developCharacteristic(code)">+</button>
            </div>
        </div>

        <div class="stat-group-label stat-group-label--secondary">
            <span>CECHY DRUGORZĘDNE</span>
            <span class="stat-group-line"></span>
        </div>
        <div class="stat-grid">
            <div
                v-for="stat in secondaryConfig"
                :key="stat.code"
                class="stat-card"
                :class="{ 'stat-card--clickable': !stat.derivedFrom }"
                :title="stat.derivedFrom ? '' : 'Kliknij, aby rozwinąć cechę'"
                @click="stat.derivedFrom ? null : developCharacteristic(stat.code)"
            >
                <div class="stat-card__code">{{ stat.code }}</div>
                <template v-if="stat.derivedFrom">
                    <div class="stat-card__value">{{ getRelatedCharacteristicValue(stat.derivedFrom) }}</div>
                    <div class="stat-card__detail">z {{ stat.derivedFrom }} {{ totalValue(stat.derivedFrom) }}</div>
                </template>
                <template v-else>
                    <div class="stat-card__value">{{ totalValue(stat.code) }}</div>
                    <div class="stat-card__detail">{{ characteristic[stat.code]?.pivot.start_value }} + {{ characteristic[stat.code]?.pivot.advancement }}</div>
                    <div class="stat-card__available">dostępne: {{ characteristic[stat.code]?.available_advancement ?? '-' }}</div>
                    <button class="stat-card__develop" @click.stop="developCharacteristic(stat.code)">+</button>
                </template>
            </div>
        </div>

        <div class="stat-footer-note">Kliknij <span class="stat-footer-note__accent">+</span>, aby rozwinąć cechę za punkty doświadczenia.</div>
    </div>
</template>
<script setup lang="ts">
import {defineProps, defineEmits, computed} from "vue";
import axios from "axios";
import {useToast} from "vue-toast-notification";
import {CharacteristicPivot} from "../../../../types/Hero";

const props = defineProps<{
    characteristicData: Object
    heroId: number
}>();

const emits = defineEmits<{
    addCharacteristic: [characteristicName: string, characteristic: CharacteristicPivot, changeCurrentWounds: number, spentExperience: number];
}>();

const characteristic = computed(() => props.characteristicData ?? {});
const toast = useToast();

const primaryCodes = ['WW', 'US', 'K', 'Odp', 'Zr', 'Int', 'SW', 'Ogd'];
const secondaryConfig: { code: string; derivedFrom?: string }[] = [
    {code: 'A'},
    {code: 'Żyw'},
    {code: 'S', derivedFrom: 'K'},
    {code: 'Wt', derivedFrom: 'Odp'},
    {code: 'Sz'},
    {code: 'Mag'},
    {code: 'PO'},
    {code: 'PP'},
];

const totalValue = (code: string): number => {
    const stat = characteristic.value[code];
    return stat ? stat.pivot.start_value + stat.pivot.advancement : 0;
};

const developCharacteristic = (characteristicName: string): void => {
    let titleText = 'Czy na pewno chcesz rozwinąć umiejętność?';
    let subtitleText = 'Będzie Cię to kosztowało punkty doświadczenia!';
    if (characteristicName === 'PP') {
        titleText = 'Czy na pewno zasłużyłeś na uzyskanie Punktu Przeznaczenia, miernoto?'
        subtitleText = 'Czytaj: "Czy Mistrz gry przyznał Ci Punkt Przeznaczenia?"'
    } else if (characteristicName === 'PO') {
        titleText = 'Ktoś tu powoli szaleje! Co zrobiłeś nie tak, że chcesz dodać sobie Punkt Obłędu'
        subtitleText = 'Uważaj! Ludzie zwykle dziwnie patrzą na kogoś z trzecim okiem na środku czoła'
    }
    customSwal
        .fire({
            title: titleText,
            text: subtitleText,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Tak!",
            cancelButtonText: 'Nie.',
            width: '30%'
        })
        .then((result) => {
            if (result.isConfirmed) {
                axios.post('karta-postaci/' + props.heroId + '/update-characteristic', {
                    characteristic: characteristic.value[characteristicName]
                })
                    .then((response) => {
                        toast.success(response.data.message)
                        characteristic.value[characteristicName].pivot.advancement += response.data.developedValue;
                        emits(
                            'addCharacteristic',
                            characteristicName,
                            characteristic.value[characteristicName].pivot,
                            response.data.changeCurrentWounds,
                            response.data.spentExperience,
                        )
                        customSwal.fire({title: "Rozwinięto!", icon: "success", theme: 'dark'});
                    })
                    .catch((error) => {
                        console.log(error)
                        customSwal.fire({title: error.response.data.message, icon: "error", theme: 'dark'});
                    })
            }
        });
};

const getRelatedCharacteristicValue = (basedOn: string) => {
    return Math.floor((characteristic.value[basedOn]?.pivot.start_value + (characteristic.value[basedOn]?.pivot.advancement ?? 0)) / 10)
}
</script>
<style scoped>
.stat-group-label {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 14px;
}

.stat-group-label--secondary {
    margin: 26px 0 14px;
}

.stat-group-label span:first-child {
    font-family: var(--font-heading);
    font-size: 11px;
    letter-spacing: .2em;
    color: var(--text-faint);
}

.stat-group-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, var(--border-default), transparent);
}

.stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(118px, 1fr));
    gap: 10px;
}

.stat-card {
    position: relative;
    border: 1px solid var(--border-default);
    background: linear-gradient(#221d17, #171310);
    padding: 14px 10px 12px;
    text-align: center;
}

.stat-card--clickable {
    cursor: pointer;
}

.stat-card--clickable:hover {
    border-color: var(--border-accent-hover);
}

.stat-card__code {
    font-family: var(--font-heading);
    font-size: 11px;
    letter-spacing: .16em;
    color: var(--gold-muted);
}

.stat-card__value {
    font-size: 34px;
    line-height: 1.15;
    color: var(--text-body);
}

.stat-card__detail {
    font-size: 13px;
    color: var(--text-faint);
}

.stat-card__available {
    margin-top: 4px;
    font-size: 11px;
    color: var(--text-faint-alt);
}

.stat-card__develop {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 22px;
    height: 22px;
    padding: 0;
    border: 1px solid var(--border-frame);
    background: var(--bg-inset-alt);
    color: var(--gold-muted);
    font-size: 14px;
    line-height: 1;
    cursor: pointer;
    font-family: var(--font-body);
}

.stat-card__develop:hover {
    border-color: var(--gold);
    color: var(--gold-bright);
    background: #241d14;
}

.stat-footer-note {
    margin-top: 16px;
    font-size: 14px;
    color: var(--text-faint);
    font-style: italic;
}

.stat-footer-note__accent {
    color: var(--gold-muted);
    font-style: normal;
}
</style>
