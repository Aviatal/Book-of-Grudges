<template>
    <div class="chat-messages" ref="containerEl">
        <template v-for="msg in messages" :key="msg.id">
            <div v-if="msg.type === 'roll'" class="message-roll-card">
                <div class="roll-card-header">
                    <span class="roll-card-icon">🎲</span>
                    <span class="roll-card-type">INICJATYWA</span>
                    <span class="roll-card-time">{{ formatDate(msg.created_at) }}</span>
                </div>
                <div class="roll-card-author">{{ msg.author_name }}</div>
                <div class="roll-card-breakdown">
                    <div class="roll-die">
                        <span class="roll-die-value">{{ parseRoll(msg.text).zr }}</span>
                        <span class="roll-die-label">Zręczność</span>
                    </div>
                    <span class="roll-op">+</span>
                    <div class="roll-die roll-die-d10">
                        <span class="roll-die-value">{{ parseRoll(msg.text).dice }}</span>
                        <span class="roll-die-label">k10</span>
                    </div>
                    <span class="roll-op">=</span>
                    <div class="roll-die roll-die-total">
                        <span class="roll-die-value">{{ parseRoll(msg.text).total }}</span>
                        <span class="roll-die-label">Wynik</span>
                    </div>
                </div>
            </div>
            <div
                v-else-if="msg.type === 'skill_test'"
                class="message-skill-card"
                :class="[parseSkillTest(msg.text)?.passed ? 'skill-passed' : 'skill-failed', { 'skill-fumble': parseSkillTest(msg.text)?.fumble }]"
            >
                <div class="skill-card-header">
                    <span class="skill-card-icon">🎯</span>
                    <span class="skill-card-type">TEST UMIEJĘTNOŚCI</span>
                    <span v-if="parseSkillTest(msg.text)?.fortune_reroll" class="skill-card-luck-badge">🍀 PUNKT SZCZĘŚCIA</span>
                    <span v-if="parseSkillTest(msg.text)?.fumble" class="skill-card-fumble">💀 PECH</span>
                    <span class="skill-card-time">{{ formatDate(msg.created_at) }}</span>
                </div>
                <div class="skill-card-author">{{ msg.author_name }}</div>
                <div class="skill-card-name">
                    {{ parseSkillTest(msg.text)?.skill }}
                    <span class="skill-card-char">({{ parseSkillTest(msg.text)?.characteristic }})</span>
                </div>
                <div class="skill-card-effective" v-if="parseSkillTest(msg.text)?.half || parseSkillTest(msg.text)?.modifier !== 0">
                    <span class="eff-base">{{ parseSkillTest(msg.text)?.characteristic_value }}</span>
                    <span class="eff-op" v-if="parseSkillTest(msg.text)?.half">÷2</span>
                    <span class="eff-op" v-if="parseSkillTest(msg.text)?.modifier !== 0">
                        {{ parseSkillTest(msg.text)?.modifier > 0 ? '+' + parseSkillTest(msg.text)?.modifier : parseSkillTest(msg.text)?.modifier }}
                    </span>
                    <span class="eff-sep">=</span>
                    <span class="eff-result">{{ parseSkillTest(msg.text)?.effective_value }}</span>
                </div>
                <div class="skill-card-breakdown">
                    <div class="skill-stat">
                        <span class="skill-stat-value">{{ parseSkillTest(msg.text)?.effective_value ?? parseSkillTest(msg.text)?.characteristic_value }}</span>
                        <span class="skill-stat-label">Próg</span>
                    </div>
                    <span class="skill-vs">vs</span>
                    <div class="skill-stat">
                        <span class="skill-stat-value">{{ parseSkillTest(msg.text)?.roll }}</span>
                        <span class="skill-stat-label">k100</span>
                    </div>
                    <div class="skill-verdict" :class="parseSkillTest(msg.text)?.passed ? 'skill-verdict-pass' : 'skill-verdict-fail'">
                        {{ parseSkillTest(msg.text)?.passed ? '✓ ZDANY' : '✗ NIEZDANY' }}
                        <span v-if="(parseSkillTest(msg.text)?.levels ?? 0) >= 1" class="skill-verdict-levels">
                            ({{ parseSkillTest(msg.text)?.passed ? '+' : '-' }}{{ parseSkillTest(msg.text)?.levels }} {{ pluralizeLevels(parseSkillTest(msg.text)?.levels ?? 0) }})
                        </span>
                    </div>
                </div>
                <div v-if="canSpendLuckOn(msg)" class="skill-card-luck">
                    <button
                        class="skill-card-luck-btn"
                        :disabled="(fortunePoints ?? 0) <= 0 || luckBusy"
                        :title="(fortunePoints ?? 0) <= 0 ? 'Nie masz już punktów szczęścia' : 'Wydaj punkt szczęścia i powtórz ten rzut'"
                        @click="emit('spend-luck', msg.id)"
                    >🍀 Wydaj punkt szczęścia i rzuć ponownie</button>
                </div>
            </div>
            <div v-else-if="msg.type === 'dice_roll'" class="message-dice-card">
                <div class="dice-card-header">
                    <span class="dice-card-icon">🎲</span>
                    <span class="dice-card-notation">{{ parseDiceRoll(msg.text)?.notation }}</span>
                    <span class="dice-card-time">{{ formatDate(msg.created_at) }}</span>
                </div>
                <div class="dice-card-author">{{ msg.author_name }}</div>
                <div class="dice-card-results">
                    <span
                        v-for="(r, i) in parseDiceRoll(msg.text)?.results ?? []"
                        :key="i"
                        class="dice-card-die"
                    >{{ r }}</span>
                </div>
                <div v-if="(parseDiceRoll(msg.text)?.count ?? 0) > 1" class="dice-card-total">
                    = {{ parseDiceRoll(msg.text)?.total }}
                </div>
            </div>
            <div v-else class="message">
                <span class="msg-author">[{{ msg.author_name }}]</span>
                <span class="msg-content">{{ msg.text }}</span>
                <span class="msg-time">{{ formatDate(msg.created_at) }}</span>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, nextTick } from 'vue';
import type { Message } from '../../../types/Message';
import { parseRoll, parseSkillTest, parseDiceRoll, formatDate, pluralizeLevels } from '../../../utils/chatMessageParsers';

const props = defineProps<{
    messages: Message[];
    // Podane tylko tam, gdzie gracz może wydać punkt szczęścia po własnym nieudanym rzucie
    // (czat sesji); bez nich karty rzutów nie pokazują przycisku.
    userId?: number;
    heroId?: number;
    fortunePoints?: number;
    // Trwa już wydawanie punktu — blokuje podwójne kliknięcie
    luckBusy?: boolean;
}>();

const emit = defineEmits<{
    'spend-luck': [messageId: number];
}>();

// Punkt szczęścia ma sens tylko po ostatnim rzucie bohatera — starsze rzuty już „się wydarzyły".
const lastOwnSkillTestId = computed(() => {
    if (!props.heroId) return null;
    const own = props.messages.filter(m => m.type === 'skill_test' && m.user_id === props.userId);
    return own.length ? own[own.length - 1].id : null;
});

const canSpendLuckOn = (msg: Message): boolean => {
    if (msg.id !== lastOwnSkillTestId.value) return false;
    const result = parseSkillTest(msg.text);
    // Klucz skill_id ma tylko rzut, który serwer umie powtórzyć (patrz SkillTestResult)
    // Powtórka za punkt szczęścia jest ostateczna — nie da się jej przerzucić kolejnym punktem
    return !!result && 'skill_id' in result && !result.fortune_reroll && (!result.passed || result.fumble);
};

const containerEl = ref<HTMLElement | null>(null);

// Przewiń na dół przy każdym doklejeniu wiadomości — zarówno po wysłaniu własnej, jak i po
// odebraniu cudzej przez WebSocket (rodzic tylko dopisuje do tablicy `messages`, nie musi
// osobno wołać scrolla).
watch(() => props.messages.length, async () => {
    await nextTick();
    if (containerEl.value) {
        containerEl.value.scrollTop = containerEl.value.scrollHeight;
    }
});
</script>

<style scoped>
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.message { font-size: 0.95rem; line-height: 1.2; border-bottom: 1px solid #333; padding-bottom: 2px; }
.msg-author { font-weight: bold; margin-right: 5px; }
.msg-content { color: #ccc; word-break: break-word; display: block; }
.msg-time { font-size: 0.7rem; color: #666; float: right; }

/* Roll card */
.message-roll-card {
    border: 1px solid #d4af37;
    border-radius: 6px;
    background: linear-gradient(135deg, #1a1500 0%, #0f0f0f 100%);
    padding: 8px 10px;
    margin: 4px 0;
    box-shadow: 0 0 12px rgba(212, 175, 55, 0.15), inset 0 0 20px rgba(0,0,0,0.4);
}

.roll-card-header {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
}

.roll-card-icon { font-size: 1rem; }

.roll-card-type {
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 2px;
    color: #d4af37;
    text-transform: uppercase;
    flex: 1;
}

.roll-card-time {
    font-size: 0.65rem;
    color: #555;
}

.roll-card-author {
    font-size: 0.8rem;
    color: #aaa;
    margin-bottom: 8px;
    font-style: italic;
}

.roll-card-breakdown {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.roll-op {
    color: #888;
    font-size: 1.1rem;
    font-weight: bold;
}

.roll-die {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #1e1e1e;
    border: 1px solid #444;
    border-radius: 5px;
    padding: 4px 10px;
    min-width: 48px;
}

.roll-die-d10 {
    border-color: #d4af37;
    background: #1a1500;
}

.roll-die-total {
    border: 2px solid #d4af37;
    background: #d4af37;
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
    min-width: 54px;
}

.roll-die-value {
    font-size: 1.3rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}

.roll-die-total .roll-die-value {
    color: #1a1a1a;
    font-size: 1.5rem;
}

.roll-die-label {
    font-size: 0.55rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 2px;
}

.roll-die-total .roll-die-label { color: #5a4a00; }

/* Skill test card */
.message-skill-card {
    border-radius: 6px;
    padding: 8px 10px;
    margin: 4px 0;
    border: 1px solid #333;
    background: #0f0f0f;
}

.skill-passed { border-color: #2e7d32; background: linear-gradient(135deg, #071209 0%, #0f0f0f 100%); }
.skill-failed  { border-color: #7f1d1d; background: linear-gradient(135deg, #120707 0%, #0f0f0f 100%); }

/* Pech (rzut 97-100) — wyraźnie widoczne niezależnie od tego, czy test formalnie wyszedł */
.skill-fumble {
    border-color: #8b3fd1;
    box-shadow: 0 0 10px rgba(139, 63, 209, 0.35);
}

.skill-card-fumble {
    font-size: 0.62rem;
    font-weight: 800;
    letter-spacing: 1px;
    color: #c9a6f5;
    background: rgba(139, 63, 209, 0.18);
    border: 1px solid #8b3fd1;
    border-radius: 4px;
    padding: 1px 6px;
    text-shadow: 0 0 6px rgba(139, 63, 209, 0.6);
}

.skill-card-header {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 3px;
}

.skill-card-icon { font-size: 0.9rem; }

.skill-card-type {
    font-size: 0.6rem;
    font-weight: 800;
    letter-spacing: 2px;
    color: #888;
    text-transform: uppercase;
    flex: 1;
}

.skill-card-time { font-size: 0.65rem; color: #555; }

.skill-card-author { font-size: 0.78rem; color: #888; font-style: italic; margin-bottom: 5px; }

.skill-card-name {
    font-size: 0.9rem;
    font-weight: 700;
    color: #ddd;
    margin-bottom: 7px;
}

.skill-card-char { font-size: 0.75rem; color: #666; font-weight: normal; }

.skill-card-effective {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.78rem;
    margin-bottom: 6px;
    color: #888;
}

.eff-base { color: #aaa; font-weight: 700; }
.eff-op { color: #9d91f0; font-weight: 700; }
.eff-sep { color: #555; }
.eff-result { color: #d4af37; font-weight: 800; font-size: 0.88rem; }

.skill-card-breakdown {
    display: flex;
    align-items: center;
    gap: 8px;
}

.skill-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 5px;
    padding: 4px 10px;
    min-width: 44px;
}

.skill-stat-value {
    font-size: 1.2rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}

.skill-stat-label {
    font-size: 0.55rem;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 1px;
}

.skill-vs { color: #555; font-size: 0.8rem; font-weight: bold; }

.skill-verdict {
    flex: 1;
    text-align: right;
    font-size: 0.9rem;
    font-weight: 800;
    letter-spacing: 1px;
}

.skill-verdict-pass { color: #4caf50; text-shadow: 0 0 8px rgba(76, 175, 80, 0.4); }
.skill-verdict-fail { color: #f44336; text-shadow: 0 0 8px rgba(244, 67, 54, 0.4); }

.skill-card-luck {
    display: flex;
    justify-content: flex-end;
    margin-top: 6px;
}

.skill-card-luck-btn {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    color: #d4af37;
    background: rgba(30, 30, 30, 0.8);
    border: 1px solid #d4af37;
    border-radius: 4px;
    padding: 4px 8px;
    cursor: pointer;
}

.skill-card-luck-btn:hover:not(:disabled) {
    background: #d4af37;
    color: #1a1a1a;
}

.skill-card-luck-btn:disabled {
    border-color: #555;
    color: #777;
    background: #222;
    cursor: not-allowed;
}

.skill-card-luck-badge {
    font-size: 0.62rem;
    font-weight: 800;
    letter-spacing: 1px;
    color: #d4af37;
    background: rgba(212, 175, 55, 0.12);
    border: 1px solid #d4af37;
    border-radius: 4px;
    padding: 1px 6px;
}

.skill-verdict-levels {
    display: block;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.4px;
    opacity: 0.85;
    text-shadow: none;
}

/* ── Karta wiadomości dice_roll ── */
.message-dice-card {
    background: linear-gradient(135deg, #0f0f1a 0%, #141428 100%);
    border: 1px solid #2a2a5a;
    border-radius: 6px;
    margin: 6px 8px;
    padding: 8px 10px;
}

.dice-card-header {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 3px;
}

.dice-card-icon { font-size: 0.9rem; }

.dice-card-notation {
    font-size: 0.7rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #7b68ee;
    flex: 1;
}

.dice-card-time {
    font-size: 0.65rem;
    color: #555;
}

.dice-card-author {
    font-size: 0.72rem;
    color: #888;
    margin-bottom: 6px;
}

.dice-card-results {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.dice-card-die {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    background: #1a1a3a;
    border: 1px solid #4a4a8a;
    border-radius: 5px;
    font-size: 1rem;
    font-weight: 700;
    color: #a0a0ff;
    font-variant-numeric: tabular-nums;
}

.dice-card-total {
    margin-top: 5px;
    font-size: 0.9rem;
    font-weight: 700;
    color: #d4af37;
    text-align: right;
}
</style>
