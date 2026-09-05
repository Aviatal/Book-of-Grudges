<template>
    <div v-if="loading" class="text-center py-20" style="color: var(--text-faint)">
        <i class="mdi mdi-loading mdi-spin text-5xl"></i>
        <p class="mt-4 uppercase tracking-widest">Otwieranie Kroniki...</p>
    </div>

    <div v-else class="token-edit-container">
        <div class="flex items-center justify-between mb-8 pb-4" style="border-bottom: 2px solid var(--border-accent-hover)">
            <h2 class="text-3xl font-bold tracking-widest uppercase font-heading" style="color: var(--gold)">
                <i class="mdi mdi-feather mr-2"></i> Aktualizacja tokenu
            </h2>
            <div class="flex gap-2">
                <button @click="cancel" class="warhammer-btn-secondary">
                    <i class="mdi mdi-close"></i> Anuluj
                </button>
                <button @click="saveToken" class="warhammer-btn">
                    <i class="mdi mdi-content-save"></i> Zapisz w Księdze
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-6 p-6 rounded-sm shadow-inner" style="background: var(--bg-panel); border: 1px solid var(--border-default)">
                <h3 class="text-xl font-bold uppercase tracking-wide pb-2 font-heading" style="color: var(--text-muted-alt); border-bottom: 1px solid var(--border-frame)">Dane Podstawowe</h3>
                <div>
                    <label class="warhammer-label">
                        Nazwa Tokenu
                        <span class="normal-case font-normal text-xs ml-1" style="color: var(--text-faint-alt)">(przy zmianie pliku uzupełniana automatycznie)</span>
                    </label>
                    <div class="relative">
                        <i class="mdi mdi-skull absolute left-3 top-1/2 -translate-y-1/2 z-10" style="color: var(--border-accent-hover)"></i>
                        <input type="text" v-model="token.name" class="warhammer-input pl-11" placeholder="np. Oko Tzeentcha">
                    </div>
                </div>

                <div>
                    <label class="warhammer-label">Bohater</label>
                    <v-select
                        v-model="token.hero_id"
                        :options="props.heroes"
                        :reduce="(hero: Hero) => hero.id"
                        placeholder="Bohater"
                        label="name"
                        class="custom-select w-full"
                    ></v-select>
                </div>

                <div v-if="!token.hero_id" class="copy-from-row">
                    <label class="warhammer-label flex items-center gap-2">
                        <i class="mdi mdi-content-copy"></i> Skopiuj z innego tokenu
                        <span class="normal-case font-normal text-xs ml-1" style="color: var(--text-faint-alt)">(opcjonalnie — nadpisze kartę poniżej)</span>
                    </label>
                    <v-select
                        v-model="copyFromTokenId"
                        :options="npcTokens"
                        :reduce="(t: NpcTokenOption) => t.id"
                        label="name"
                        placeholder="Wybierz token do skopiowania danych..."
                        class="custom-select w-full"
                        @option:selected="applyCopyFrom"
                    ></v-select>
                </div>

                <NpcSheetEditor
                    v-if="!token.hero_id"
                    v-model="npcSheet"
                    :key="npcSheetKey"
                />
            </div>

            <div class="space-y-6">
                <div class="p-6 rounded-sm shadow-inner" style="background: var(--bg-panel); border: 2px dashed var(--border-accent-hover)">
                    <template v-if="token.image">
                        <h3 class="text-xl font-bold uppercase tracking-wide pb-2 mb-4 text-center font-heading" style="color: var(--text-muted-alt); border-bottom: 1px solid var(--border-frame)">Wygląd na Mapie</h3>

                        <div class="flex flex-col items-center justify-center mb-6 p-4 rounded" style="background: var(--bg-inset); border: 1px solid #000">
                            <div class="text-xs uppercase mb-2 tracking-widest" style="color: var(--border-accent-hover)">{{ token.name }}</div>
                            <div
                                class="token-preview-circ"
                                :style="previewStyle"
                            >
                                <i v-if="!token.image" class="mdi mdi-help text-3xl" style="color: var(--border-frame)"></i>
                            </div>
                        </div>
                    </template>

                    <div class="space-y-4">
                        <div>
                            <label class="warhammer-label flex items-center gap-2">
                                <i class="mdi mdi-image-plus"></i> Obrazek / Ikona
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label class="upload-dropzone">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="mdi mdi-cloud-upload text-2xl" style="color: var(--border-accent-hover)"></i>
                                        <p class="text-xs" style="color: var(--text-body)"><span class="font-bold">Kliknij</span> lub przeciągnij</p>
                                        <p class="text-[10px]" style="color: var(--text-faint-alt)">PNG, JPG (max. 128x128px)</p>
                                    </div>
                                    <input type="file" @change="onFileChange" class="hidden" accept="image/*" />
                                </label>
                            </div>
                            <button v-if="imagePreviewUrl" @click="removeImage" class="text-xs underline mt-1" style="color: var(--danger-text)">
                                <i class="mdi mdi-trash-can"></i> Usuń obrazek
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, StyleValue, watch } from 'vue';
import axios from 'axios';
import { Token, NpcSheet } from '@/types/Token';
import {useToast} from "vue-toast-notification";
import {Hero} from "@/types/Hero";
import NpcSheetEditor from '@/components/panel/NpcSheetEditor.vue';

interface NpcTokenOption { id: number; name: string; }

const props = defineProps<{
    tokenId: number;
    heroes: Hero[];
}>();
const toast = useToast();

const loading = ref<boolean>(false);
const token = ref<Partial<Token>>({
    name: '',
    image: null,
    hero_id: null
});
const npcSheet = ref<NpcSheet | null>(null);
const npcTokens = ref<NpcTokenOption[]>([]);
const copyFromTokenId = ref<number | null>(null);
const npcSheetKey = ref(0);

const loadNpcTokens = async () => {
    try {
        const res = await axios.get('/panel/tokens/get-tokens');
        npcTokens.value = (res.data as Token[])
            .filter(t => !t.hero_id && t.sheet !== null && t.id !== props.tokenId)
            .map(t => ({ id: t.id, name: t.name }));
    } catch {}
};

const applyCopyFrom = async (option: NpcTokenOption) => {
    try {
        const res = await axios.get(`/panel/tokens/get-token/${option.id}`);
        const sourceToken = res.data as Token;
        if (sourceToken.sheet) {
            npcSheet.value = JSON.parse(JSON.stringify(sourceToken.sheet));
            npcSheetKey.value++;
            toast.success(`Skopiowano dane z tokenu „${sourceToken.name}"`);
        }
    } catch {
        toast.error('Nie udało się pobrać danych tokenu');
    } finally {
        copyFromTokenId.value = null;
    }
};

const imagePreviewUrl = ref<string | null>(null);

const fetchTokenData = async () => {
    loading.value = true;
    try {
        const response = await axios.get(`/panel/tokens/get-token/${props.tokenId}`);
        token.value = response.data;
        npcSheet.value = response.data.sheet ?? null;
    } catch (error) {
        console.error('Błąd podczas pobierania danych tokenu:', error);
    } finally {
        loading.value = false;
    }
};

// Kluczowa logika: Obrazek ma pierwszeństwo
const previewStyle = computed<StyleValue>(() => {
    // 1. Sprawdź czy jest uploadowany obrazek (tymczasowy podgląd)
    if (imagePreviewUrl.value) {
        return {
            backgroundImage: `url(${imagePreviewUrl.value})`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
        };
    }
    // 2. Sprawdź czy token ma już przypisany obrazek w bazie
    if (token.value.image_url) {
        return {
            backgroundImage: `url(${token.value.image_url})`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
        };
    }
    return null
});

// Obsługa zmiany pliku
const onFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const files = target.files;
    if (files && files[0]) {
        const file = files[0];

        if (imagePreviewUrl.value) {
            URL.revokeObjectURL(imagePreviewUrl.value);
        }
        imagePreviewUrl.value = URL.createObjectURL(file);
        token.value.image = file;
        // Jeśli token nie jest przypisany do bohatera gracza — aktualizuj nazwę z pliku
        if (!token.value.hero_id) {
            token.value.name = file.name.replace(/\.[^/.]+$/, '');
        }
    }
};

// Usuwanie obrazka
const removeImage = () => {
    token.value.image = null;
    if (imagePreviewUrl.value) {
        URL.revokeObjectURL(imagePreviewUrl.value);
        imagePreviewUrl.value = null;
    }
    const fileInput = document.querySelector('input[type="file"]') as HTMLInputElement;
    if (fileInput) fileInput.value = '';
}

const saveToken = async () => {
    loading.value = true;
    const formData = new FormData();
    formData.append('name', token.value.name ?? '');
    if (imagePreviewUrl.value) {
        formData.append('file',token.value.image ?? '');
    }
    if (token.value.hero_id !== null && token.value.hero_id !== undefined) {
        formData.append('hero_id', token.value.hero_id.toString());
    }
    if (!token.value.hero_id) {
        formData.append('sheet', npcSheet.value ? JSON.stringify(npcSheet.value) : '');
    }
    await axios.put(`/panel/tokens/${props.tokenId}/update`, formData)
        .then((response) => {
            toast.success('Udało się edytować token!')
            token.value = response.data;
            if (imagePreviewUrl.value) {
                URL.revokeObjectURL(imagePreviewUrl.value);
                imagePreviewUrl.value = null;
            }
        })
        .catch((error) => {
            toast.error(error.response.data.error)
        })
        .finally(() => {
            loading.value = false;
        })
};

const cancel = () => {
    window.history.back();
};

onMounted(() => {
    fetchTokenData();
    loadNpcTokens();
});
</script>

<style scoped>

.token-edit-container {
    font-family: var(--font-body), serif;
    color: var(--text-body);
}

/* Customowe Inputy */
.warhammer-label {
    display: block;
    font-family: var(--font-heading), serif;
    font-size: 0.85rem;
    font-weight: bold;
    color: var(--text-muted-alt);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.35rem;
}

.warhammer-input {
    width: 100%;
    background-color: var(--bg-inset);
    color: var(--text-body);
    border: 1px solid var(--border-default);
    padding: 0.6rem 0.75rem;
    border-radius: 2px;
    font-family: var(--font-body), serif;
    transition: border-color 0.2s ease;
}
.warhammer-input.pl-11 {
    padding-left: 2.75rem !important;
}

.warhammer-input:focus {
    outline: none;
    border-color: var(--gold);
    box-shadow: 0 0 5px rgba(212, 175, 55, 0.4);
}

.upload-dropzone {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 96px;
    border: 2px dashed var(--border-accent-hover);
    border-radius: 8px;
    cursor: pointer;
    background: var(--bg-panel-gradient);
    transition: border-color 0.2s ease, background 0.2s ease;
}

.upload-dropzone:hover {
    border-color: var(--gold);
    background: #332714;
}

/* Przyciski */
.warhammer-btn {
    background: linear-gradient(#2a2117, #1d1710);
    color: var(--gold);
    padding: 0.5rem 1.25rem;
    border: 1px solid var(--border-accent);
    font-family: var(--font-heading), serif;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 0.8rem;
    letter-spacing: 0.1em;
    cursor: pointer;
    transition: border-color 0.2s ease, color 0.2s ease;
}

.warhammer-btn:hover {
    border-color: var(--gold);
    color: var(--gold-bright);
}

.warhammer-btn-secondary {
    background: none;
    color: var(--text-faint);
    padding: 0.5rem 1.25rem;
    border: 1px solid transparent;
    font-family: var(--font-heading), serif;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.2s;
}

.warhammer-btn-secondary:hover {
    color: var(--gold);
    border-color: var(--border-accent-hover);
    background-color: var(--bg-inset);
}

.copy-from-row {
    border-top: 1px solid var(--border-subtle);
    padding-top: 1rem;
    margin-top: 0.5rem;
}

.custom-select :deep(.vs__dropdown-toggle) {
    background: var(--bg-inset);
    border: 1px solid var(--border-default);
    border-radius: 2px;
    padding: 0 6px;
    min-height: 36px;
}
.custom-select :deep(.vs__search),
.custom-select :deep(.vs__selected) { color: var(--text-body); font-size: 0.85rem; margin: 0; padding: 2px 4px; }
.custom-select :deep(.vs__search::placeholder) { color: var(--text-faint-alt); }
.custom-select :deep(.vs__open-indicator) { fill: var(--border-accent-hover); }
.custom-select :deep(.vs__clear) { fill: var(--border-accent-hover); }
.custom-select :deep(.vs__dropdown-menu) {
    background: var(--bg-panel);
    border: 1px solid var(--border-frame);
    border-top: none;
    color: var(--text-body);
    font-size: 0.85rem;
}
.custom-select :deep(.vs__dropdown-option) { padding: 6px 10px; color: var(--text-muted-alt); }
.custom-select :deep(.vs__dropdown-option--highlight) { background: var(--border-frame); color: var(--text-body); }

/* Okrągły podgląd Tokena */
.token-preview-circ {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.6), inset 0 0 10px rgba(0,0,0,0.5);
    border: 1px solid var(--border-frame);
}
</style>
