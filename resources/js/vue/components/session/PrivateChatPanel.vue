<template>
    <FloatingPanel
        panel-id="private-chat"
        title="✉️ Wiadomości prywatne"
        :default-pos="defaultPos"
        :default-size="{ w: 340, h: 420 }"
        :min-width="260"
        :min-height="200"
        :mobile-fullscreen="isMobile"
        start-minimized
    >
        <template #header-actions>
            <span v-if="totalUnread > 0" class="pm-unread-badge">{{ totalUnread }}</span>
        </template>

        <div class="pm-body">
            <div class="pm-contacts">
                <button
                    v-for="conversation in conversations"
                    :key="conversation.contact.user_id"
                    class="pm-contact"
                    :class="{ 'pm-contact-active': selectedContactId === conversation.contact.user_id }"
                    @click="selectContact(conversation.contact.user_id)"
                >
                    <span class="pm-contact-name">{{ conversation.contact.name }}</span>
                    <span v-if="conversation.unread > 0" class="pm-contact-unread">{{ conversation.unread }}</span>
                </button>
                <div v-if="conversations.length === 0" class="pm-contacts-empty">
                    {{ isGm ? 'Brak graczy w kampanii' : 'Brak Mistrza Gry w kampanii' }}
                </div>
            </div>

            <div class="pm-thread" v-if="selectedConversation">
                <MessageThread :messages="selectedConversation.messages" />

                <div class="chat-input-area">
                    <input
                        v-model="newMessage"
                        @keyup.enter="sendMessage"
                        placeholder="Napisz prywatną wiadomość..."
                        type="text"
                    />
                    <button @click="sendMessage">➤</button>
                </div>

                <RollPicker
                    :skills="skills"
                    :characteristics="orderedHeroCharacteristics"
                    :is-loading-skills="isLoadingSkills"
                    :disabled="isRollingSkill || isRollingDice"
                    @ensure-skills-loaded="ensureSkillsLoaded"
                    @roll-characteristic="rollCharacteristic"
                    @roll-skill="rollSkill"
                    @roll-dice="rollDice"
                />
            </div>
            <div class="pm-thread pm-thread-empty" v-else>
                Wybierz rozmówcę z listy powyżej.
            </div>
        </div>
    </FloatingPanel>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import FloatingPanel from './FloatingPanel.vue';
import MessageThread from './MessageThread.vue';
import RollPicker from './RollPicker.vue';
import { useHeroSkills } from '../../../composables/useHeroSkills';
import { playNotificationSound, playDiceSound } from '../../../utils/sound';
import type { Message } from '../../../types/Message';
import type { PrivateContact } from '../../../types/PrivateContact';

const props = defineProps<{
    campaignId: number;
    userId: number;
    isGm: boolean;
    isMobile?: boolean;
}>();

const W = window.innerWidth;
const H = window.innerHeight;
const defaultPos = { x: W - 720, y: H - 450 };

const contacts = ref<PrivateContact[]>([]);
const messages = ref<Message[]>([]);
const selectedContactId = ref<number | null>(null);
const newMessage = ref('');
const isRollingSkill = ref(false);
const isRollingDice = ref(false);

const { skills, isLoadingSkills, orderedHeroCharacteristics, ensureLoaded: ensureSkillsLoaded } = useHeroSkills();

const readMarkers = ref<Record<number, string>>({});

const readMarkerKey = (otherId: number) => `pm_read_${props.campaignId}_${props.userId}_${otherId}`;

const loadReadMarker = (otherId: number): string => {
    try {
        return localStorage.getItem(readMarkerKey(otherId)) ?? '';
    } catch {
        return '';
    }
};

const markRead = (otherId: number) => {
    const now = new Date().toISOString();
    readMarkers.value[otherId] = now;
    try {
        localStorage.setItem(readMarkerKey(otherId), now);
    } catch { /* localStorage niedostępny (np. tryb prywatny) — po prostu nie zapamiętujemy */ }
};

const conversations = computed(() => {
    return contacts.value.map(contact => {
        const contactMessages = messages.value
            .filter(m =>
                (m.user_id === contact.user_id && m.recipient_id === props.userId) ||
                (m.user_id === props.userId && m.recipient_id === contact.user_id)
            )
            .sort((a, b) => a.created_at.localeCompare(b.created_at));

        const lastRead = readMarkers.value[contact.user_id] ?? '';
        const unread = contactMessages.filter(m => m.user_id === contact.user_id && m.created_at > lastRead).length;

        return { contact, messages: contactMessages, unread };
    });
});

const totalUnread = computed(() => conversations.value.reduce((sum, c) => sum + c.unread, 0));

const selectedConversation = computed(() =>
    conversations.value.find(c => c.contact.user_id === selectedContactId.value) ?? null
);

const selectContact = (contactId: number) => {
    selectedContactId.value = contactId;
    markRead(contactId);
};

const fetchContacts = async () => {
    const { data } = await axios.get<PrivateContact[]>('/session/chat/private/contacts');
    contacts.value = data;
    contacts.value.forEach(c => {
        readMarkers.value[c.user_id] = loadReadMarker(c.user_id);
    });
    if (selectedContactId.value === null && contacts.value.length > 0) {
        selectContact(contacts.value[0].user_id);
    }
};

const fetchMessages = async () => {
    const { data } = await axios.get<Message[]>('/session/chat/private');
    messages.value = data;
};

// Wiadomość dodajemy zarówno od razu po odpowiedzi HTTP (żeby nadawca widział własną wiadomość
// natychmiast, nawet gdy broadcast WS akurat zawiedzie — np. Reverb offline), jak i z echa
// WebSocketa (żeby druga strona zobaczyła ją na żywo) — stąd zabezpieczenie przed duplikatem.
const addMessage = (message: Message) => {
    if (messages.value.some(m => m.id === message.id)) return;
    messages.value.push(message);
};

const subscribeRealtime = (): void => {
    window.Echo.private(`private-chat.${props.campaignId}.${props.userId}`)
        .listen('.private-message-sent', (e: { message: Message }) => {
            addMessage(e.message);

            if (e.message.user_id === props.userId) return; // echo własnej wiadomości/rzutu

            if (e.message.user_id === selectedContactId.value) {
                markRead(e.message.user_id);
            }

            playNotificationSound();
        });
};

onMounted(async () => {
    await fetchContacts();
    await fetchMessages();
    subscribeRealtime();
});

onUnmounted(() => {
    window.Echo.leave(`private-chat.${props.campaignId}.${props.userId}`);
});

const sendMessage = async () => {
    if (newMessage.value.trim() === '' || selectedContactId.value === null) return;

    try {
        const { data } = await axios.post('/session/chat/private/send', {
            text: newMessage.value,
            recipient_id: selectedContactId.value,
        });
        addMessage(data.message);
        newMessage.value = '';
    } catch (error) {
        console.error('Błąd wysyłania prywatnej wiadomości', error);
    }
};

const rollCharacteristic = async (characteristic: string, modifier: number, half: boolean) => {
    if (isRollingSkill.value || selectedContactId.value === null) return;
    isRollingSkill.value = true;
    playDiceSound();
    try {
        const { data } = await axios.post('/session/chat/private/roll-characteristic', {
            characteristic,
            modifier,
            half,
            recipient_id: selectedContactId.value,
        });
        addMessage(data.message);
    } catch (e) {
        console.error('Błąd rzutu na cechę', e);
    } finally {
        isRollingSkill.value = false;
    }
};

const rollSkill = async (skillId: number, modifier: number, half: boolean) => {
    if (isRollingSkill.value || selectedContactId.value === null) return;
    isRollingSkill.value = true;
    playDiceSound();
    try {
        const { data } = await axios.post('/session/chat/private/roll-skill', {
            skill_id: skillId,
            modifier,
            half,
            recipient_id: selectedContactId.value,
        });
        addMessage(data.message);
    } catch (e) {
        console.error('Błąd testu umiejętności', e);
    } finally {
        isRollingSkill.value = false;
    }
};

const rollDice = async (count: number, sides: number) => {
    if (isRollingDice.value || selectedContactId.value === null) return;
    isRollingDice.value = true;
    playDiceSound();
    try {
        const { data } = await axios.post('/session/chat/private/roll-dice', {
            count,
            sides,
            recipient_id: selectedContactId.value,
        });
        addMessage(data.message);
    } catch (e) {
        console.error('Błąd rzutu kośćmi', e);
    } finally {
        isRollingDice.value = false;
    }
};
</script>

<style scoped>
.pm-unread-badge {
    background: #d4af37;
    color: #1a1a1a;
    font-size: 0.7rem;
    font-weight: 800;
    padding: 1px 6px;
    border-radius: 999px;
    line-height: 1.4;
}

.pm-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 0;
}

.pm-contacts {
    display: flex;
    gap: 4px;
    padding: 6px;
    overflow-x: auto;
    border-bottom: 1px solid #5e4128;
    flex-shrink: 0;
}

.pm-contact {
    display: flex;
    align-items: center;
    gap: 5px;
    background: #1c1510;
    border: 1px solid #3b3a36;
    color: #cbbf9a;
    padding: 4px 9px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.78rem;
    white-space: nowrap;
    transition: border-color 0.12s, background 0.12s;
}

.pm-contact:hover { border-color: #d4af37; }

.pm-contact-active {
    border-color: #d4af37;
    background: #2c1e0c;
    color: #d4af37;
}

.pm-contact-unread {
    background: #d4af37;
    color: #1a1a1a;
    font-size: 0.65rem;
    font-weight: 800;
    padding: 0 5px;
    border-radius: 999px;
}

.pm-contacts-empty {
    color: #555;
    font-size: 0.8rem;
    padding: 4px 6px;
}

.pm-thread {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 0;
}

.pm-thread-empty {
    align-items: center;
    justify-content: center;
    color: #555;
    font-size: 0.85rem;
    padding: 20px;
    text-align: center;
}

.chat-input-area {
    padding: 10px;
    display: flex;
    gap: 5px;
    background: #111;
}

.chat-input-area input {
    flex: 1;
    background: #222;
    border: 1px solid #444;
    color: white;
    padding: 5px;
    border-radius: 4px;
}

.chat-input-area button {
    background: #d4af37;
    border: none;
    color: black;
    padding: 0 10px;
    cursor: pointer;
    border-radius: 4px;
}
</style>
