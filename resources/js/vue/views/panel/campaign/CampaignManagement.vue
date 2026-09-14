<template>
    <div>
        <div class="page-header">
            <div class="page-header__inner">
                <div class="page-header__eyebrow">PANEL MISTRZA GRY</div>
                <h1 class="page-header__title">Zarządzaj kampanią</h1>
            </div>
        </div>

        <div class="page-content">
            <section class="panel-block">
                <h2 class="panel-block__title">Nazwa kampanii</h2>
                <form class="rename-form" @submit.prevent="rename">
                    <input v-model="name" type="text" maxlength="80" required class="text-input">
                    <button type="submit" class="btn" :disabled="renaming">Zapisz</button>
                </form>
            </section>

            <section class="panel-block">
                <h2 class="panel-block__title">Link zaproszenia</h2>
                <p class="page-hint">Wyślij ten link osobom, które mają dołączyć do kampanii jako gracze.</p>
                <div class="invite-row">
                    <input :value="currentInviteUrl" readonly class="text-input" @focus="$event.target.select()">
                    <button type="button" class="btn" @click="copyInviteUrl">Kopiuj</button>
                    <button type="button" class="btn btn--ghost" :disabled="regenerating" @click="regenerateCode">Nowy kod</button>
                </div>
            </section>

            <section class="panel-block">
                <h2 class="panel-block__title">Członkowie ({{ memberList.length }})</h2>
                <div class="member-list">
                    <div v-for="member in memberList" :key="member.id" class="member-card">
                        <span class="member-card__marker"></span>
                        <span class="member-card__info">
                            <span class="member-card__name">{{ member.user.name }}</span>
                            <span class="member-card__meta">
                                {{ member.user.email }} · {{ member.role === 'gm' ? 'Mistrz Gry' : 'Gracz' }}
                                <template v-if="member.hero_name"> · {{ member.hero_name }}</template>
                            </span>
                        </span>
                        <button
                            v-if="member.role !== 'gm'"
                            type="button"
                            class="btn btn--danger"
                            @click="removeMember(member)"
                        >Usuń</button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>
<script>
export default {
    name: "CampaignManagement",
    props: ['campaign', 'members', 'inviteUrl'],
    data() {
        return {
            name: this.campaign.name,
            currentInviteUrl: this.inviteUrl,
            memberList: [...this.members],
            renaming: false,
            regenerating: false,
        };
    },
    methods: {
        rename() {
            this.renaming = true;
            axios.post('/panel/kampania/zmien-nazwe', { name: this.name })
                .then(() => this.$toast.success('Zmieniono nazwę kampanii'))
                .catch(() => this.$toast.error('Nie udało się zmienić nazwy kampanii'))
                .finally(() => this.renaming = false);
        },
        regenerateCode() {
            this.regenerating = true;
            axios.post('/panel/kampania/nowy-kod-zaproszenia')
                .then(({ data }) => {
                    this.currentInviteUrl = data.inviteUrl;
                    this.$toast.success('Wygenerowano nowy link zaproszenia — stary już nie działa');
                })
                .catch(() => this.$toast.error('Nie udało się wygenerować nowego kodu'))
                .finally(() => this.regenerating = false);
        },
        copyInviteUrl() {
            navigator.clipboard.writeText(this.currentInviteUrl)
                .then(() => this.$toast.success('Skopiowano link do schowka'))
                .catch(() => this.$toast.error('Nie udało się skopiować linku'));
        },
        removeMember(member) {
            if (!window.confirm(`Usunąć ${member.user.name} z kampanii? Jego bohater pozostanie zapisany na wypadek ponownego dołączenia.`)) {
                return;
            }
            axios.delete(`/panel/kampania/czlonkowie/${member.user_id}`)
                .then(() => {
                    this.memberList = this.memberList.filter(m => m.id !== member.id);
                    this.$toast.success('Usunięto członka kampanii');
                })
                .catch(() => this.$toast.error('Nie udało się usunąć członka kampanii'));
        },
    },
}
</script>

<style scoped>
.page-header {
    background: var(--bg-panel-gradient);
    border-bottom: 1px solid var(--border-default);
    padding: 26px 34px 20px;
}

.page-header__inner {
    max-width: 900px;
    margin: 0 auto;
}

.page-header__eyebrow {
    font-family: var(--font-heading), serif;
    font-size: 11px;
    letter-spacing: .24em;
    color: var(--text-faint);
    margin-bottom: 6px;
}

.page-header__title {
    margin: 0;
    font-family: var(--font-heading), serif;
    font-size: 30px;
    font-weight: 700;
    letter-spacing: .06em;
    color: var(--gold);
}

.page-content {
    max-width: 900px;
    margin: 0 auto;
    padding: 26px 34px 60px;
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.page-hint {
    margin: 0 0 14px;
    font-size: 14px;
    color: var(--text-faint);
    font-style: italic;
}

.panel-block__title {
    margin: 0 0 14px;
    font-family: var(--font-heading), serif;
    font-size: 16px;
    letter-spacing: .06em;
    color: var(--text-body);
}

.rename-form,
.invite-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.text-input {
    flex: 1;
    min-width: 220px;
    box-sizing: border-box;
    padding: 10px 12px;
    background: var(--bg-inset);
    border: 1px solid var(--border-default);
    color: var(--text-body);
    font-family: var(--font-body), serif;
}

.btn {
    padding: 10px 18px;
    font-family: var(--font-heading), serif;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .1em;
    border: 1px solid var(--border-accent);
    background: linear-gradient(#3a2b17, #241b10);
    color: var(--gold-bright);
    cursor: pointer;
    white-space: nowrap;
}

.btn:disabled {
    opacity: .6;
    cursor: default;
}

.btn--ghost {
    background: transparent;
}

.btn--danger {
    border-color: var(--danger-text);
    background: transparent;
    color: var(--danger-text);
}

.member-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.member-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    border: 1px solid var(--border-accent);
    background: linear-gradient(#2a2117, #1d1710);
}

.member-card__marker {
    width: 11px;
    height: 11px;
    background: var(--gold);
    transform: rotate(45deg);
    flex: none;
}

.member-card__info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.member-card__name {
    font-size: 16px;
    color: var(--text-body);
}

.member-card__meta {
    font-size: 13px;
    color: var(--text-faint-alt);
    margin-top: 2px;
}

@media (max-width: 640px) {
    .page-header,
    .page-content {
        padding-left: 16px;
        padding-right: 16px;
    }
}
</style>
