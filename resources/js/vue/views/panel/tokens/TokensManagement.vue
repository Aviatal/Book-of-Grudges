<template>
    <div>
        <div class="page-header">
            <div class="page-header__inner">
                <div class="page-header__titles">
                    <div class="page-header__eyebrow">PANEL MISTRZA GRY</div>
                    <h1 class="page-header__title">Rejestr artefaktów i tokenów</h1>
                </div>
                <a href="/panel/tokens/create" class="add-token-btn" title="Dodaj token">+ NOWY TOKEN</a>
            </div>
        </div>

        <div class="page-content">
            <div class="tokens-panel">
                <table class="tokens-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>NAZWA</th>
                        <th class="text-center">WIDOK</th>
                        <th>POZYCJA</th>
                        <th>POSTAĆ</th>
                        <th class="text-right">AKCJE</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="token in tokens" :key="token.id">
                        <td class="tokens-table__id">#{{ token.id }}</td>
                        <td class="tokens-table__name">{{ token.name }}</td>

                        <td class="text-center">
                            <div class="token-preview" :style="{ backgroundImage: token.image_url ? `url(${token.image_url})` : 'none' }">
                                <span v-if="!token.image_url" class="token-preview__placeholder">?</span>
                            </div>
                        </td>

                        <td class="tokens-table__position">
                            <span class="tokens-table__coord-label">X:</span> {{ token.x }}
                            <span class="tokens-table__coord-label" style="margin-left: 12px">Y:</span> {{ token.y }}
                        </td>
                        <td class="tokens-table__hero">
                            {{ token.hero ? token.hero.name : 'NPC' }}
                            <span v-if="token.hero?.user" class="tokens-table__hero-user">({{ token.hero.user.name }})</span>
                        </td>
                        <td class="text-right">
                            <a :href="'/panel/tokens/'+ token.id +'/edit'" class="table-action-btn" title="Edytuj">Edytuj</a>
                            <button @click="deleteToken(token.id)" class="table-action-btn table-action-btn--danger" title="Usuń">Usuń</button>
                        </td>
                    </tr>

                    <tr v-if="tokens.length === 0">
                        <td colspan="6" class="tokens-table__empty">
                            Księgi milczą o takich tokenach...
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";
import axios from "axios";
import { Token } from "../../../../types/Token";
import {useToast} from "vue-toast-notification";

const tokens = ref<Token[]>([]);
const toast = useToast();

const fetchTokens = async () => {
    try {
        const response = await axios.get('/panel/tokens/get-tokens');
        tokens.value = response.data;
    } catch (error) {
        console.error("Błąd podczas pobierania:", error);
    }
}

const deleteToken = async (tokenId: number) => {
    await axios.delete(`/panel/tokens/${tokenId}/delete`)
        .then(() => {
            toast.success('Udało się usunąć token!');
            fetchTokens();
        })
        .catch((error) => {
            toast.error(error.response.data.error)
        })
}

onMounted(() => {
    fetchTokens();
})
</script>

<style scoped>
.page-header {
    background: var(--bg-panel-gradient);
    border-bottom: 1px solid var(--border-default);
    padding: 26px 34px 20px;
}

.page-header__inner {
    max-width: 1240px;
    margin: 0 auto;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
}

.page-header__eyebrow {
    font-family: var(--font-heading);
    font-size: 11px;
    letter-spacing: .24em;
    color: var(--text-faint);
    margin-bottom: 6px;
}

.page-header__title {
    margin: 0;
    font-family: var(--font-heading);
    font-size: 30px;
    font-weight: 700;
    letter-spacing: .06em;
    color: var(--gold);
}

.add-token-btn {
    padding: 11px 18px;
    border: 1px solid var(--border-accent);
    background: linear-gradient(#3a2b17, #241b10);
    color: var(--gold-bright);
    font-family: var(--font-heading);
    font-size: 12px;
    letter-spacing: .14em;
    cursor: pointer;
    text-decoration: none;
    transition: border-color 0.2s ease;
}

.add-token-btn:hover {
    border-color: var(--gold);
}

.page-content {
    max-width: 1240px;
    margin: 0 auto;
    padding: 26px 34px 60px;
}

.tokens-panel {
    border: 1px solid var(--border-default);
    border-top: 1px solid var(--border-accent);
    background: var(--bg-panel);
    overflow-x: auto;
}

.tokens-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 16px;
}

.tokens-table th {
    text-align: left;
    padding: 12px 16px;
    font-family: var(--font-heading);
    font-size: 10px;
    letter-spacing: .16em;
    color: var(--text-faint);
    font-weight: 600;
    border-bottom: 1px solid var(--border-default);
}

.tokens-table td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border-subtle);
    vertical-align: middle;
}

.tokens-table tbody tr:hover {
    background: #1e1a13;
}

.text-center { text-align: center; }
.text-right { text-align: right; }

.tokens-table__id {
    color: var(--text-faint-alt);
    font-family: ui-monospace, monospace;
    font-size: 14px;
}

.tokens-table__name {
    color: var(--gold);
    font-size: 18px;
}

.tokens-table__position {
    font-family: ui-monospace, monospace;
    font-size: 14px;
    color: var(--text-muted);
    white-space: nowrap;
}

.tokens-table__coord-label {
    color: var(--border-accent-hover);
}

.tokens-table__hero {
    color: var(--text-muted-alt);
    font-style: italic;
}

.tokens-table__hero-user {
    font-size: 12px;
    color: var(--text-faint-alt);
    font-style: normal;
    margin-left: 4px;
}

.tokens-table__empty {
    padding: 60px 16px;
    text-align: center;
    color: var(--text-faint);
    text-transform: uppercase;
    letter-spacing: .1em;
    font-style: italic;
}

.token-preview {
    display: inline-grid;
    place-items: center;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 1px solid var(--border-frame);
    background-color: var(--bg-inset);
    background-size: cover;
    background-position: center;
}

.token-preview__placeholder {
    color: var(--border-frame);
    font-size: 18px;
}

.table-action-btn {
    display: inline-block;
    padding: 5px 11px;
    margin-left: 6px;
    border: 1px solid var(--border-default);
    background: var(--bg-panel);
    color: var(--text-muted-alt);
    font-size: 13px;
    cursor: pointer;
    font-family: var(--font-body);
    text-decoration: none;
    transition: border-color 0.2s ease, color 0.2s ease;
}

.table-action-btn:hover {
    border-color: var(--border-accent-hover);
    color: var(--text-body);
}

.table-action-btn--danger {
    border-color: var(--danger-border);
    background: var(--danger-bg);
    color: var(--danger-text);
}

.table-action-btn--danger:hover {
    border-color: var(--danger-border-hover);
    color: var(--danger-text-hover);
}

.tokens-panel::-webkit-scrollbar { height: 10px; }
.tokens-panel::-webkit-scrollbar-track { background: var(--bg-inset-alt); }
.tokens-panel::-webkit-scrollbar-thumb {
    background: var(--border-accent);
    border: 2px solid var(--bg-inset-alt);
}
</style>
