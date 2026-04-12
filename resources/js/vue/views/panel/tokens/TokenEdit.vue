<template>
    <div v-if="loading" class="text-center py-20 text-[#8b5a2b]">
        <i class="mdi mdi-loading mdi-spin text-5xl"></i>
        <p class="mt-4 uppercase tracking-widest">Otwieranie Kroniki...</p>
    </div>

    <div v-else class="token-edit-container">
        <div class="flex items-center justify-between mb-8 border-b-2 border-[#8b5a2b] pb-4">
            <h2 class="text-3xl font-bold text-[#d4af37] tracking-widest uppercase font-serif">
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
            <div class="md:col-span-2 space-y-6 bg-[#2b2a27] p-6 rounded-sm border border-[#8b5a2b] shadow-inner">
                <h3 class="text-xl font-bold text-[#c4a47c] uppercase tracking-wide border-b border-[#5e4128] pb-2">Dane Podstawowe</h3>
                <div>
                    <label class="warhammer-label">Nazwa Tokenu</label>
                    <div class="relative">
                        <i class="mdi mdi-skull absolute left-3 top-1/2 -translate-y-1/2 text-[#8b5a2b] z-10"></i>
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
            </div>

            <div class="space-y-6">
                <div class="bg-[#2b2a27] p-6 rounded-sm border-2 border-dashed border-[#8b5a2b] shadow-inner">
                    <template v-if="token.image">
                        <h3 class="text-xl font-bold text-[#c4a47c] uppercase tracking-wide border-b border-[#5e4128] pb-2 mb-4 text-center">Wygląd na Mapie</h3>

                        <div class="flex flex-col items-center justify-center mb-6 p-4 bg-[#1b1b18] rounded border border-black">
                            <div class="text-xs text-[#8b5a2b] uppercase mb-2 tracking-widest">{{ token.name }}</div>
                            <div
                                class="token-preview-circ"
                                :style="previewStyle"
                            >
                                <i v-if="!token.image" class="mdi mdi-help text-3xl text-[#5e4128]"></i>
                            </div>
                        </div>
                    </template>

                    <div class="space-y-4">
                        <div>
                            <label class="warhammer-label flex items-center gap-2">
                                <i class="mdi mdi-image-plus"></i> Obrazek / Ikona
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-[#8b5a2b] border-dashed rounded-lg cursor-pointer bg-[#3b3a36] hover:bg-[#4b4a46] hover:border-[#d4af37] transition-all">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="mdi mdi-cloud-upload text-2xl text-[#8b5a2b]"></i>
                                        <p class="text-xs text-[#e4d8b4]"><span class="font-bold">Kliknij</span> lub przeciągnij</p>
                                        <p class="text-[10px] text-[#706f6c]">PNG, JPG (max. 128x128px)</p>
                                    </div>
                                    <input type="file" @change="onFileChange" class="hidden" accept="image/*" />
                                </label>
                            </div>
                            <button v-if="imagePreviewUrl" @click="removeImage" class="text-xs text-red-500 underline mt-1 hover:text-red-400">
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
import { ref, onMounted, computed, StyleValue } from 'vue';
import axios from 'axios';
import { Token } from '@/types/Token';
import {useToast} from "vue-toast-notification";
import {Hero} from "@/types/Hero";

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

const imagePreviewUrl = ref<string | null>(null);

const fetchTokenData = async () => {
    loading.value = true;
    try {
        const response = await axios.get(`/panel/tokens/get-token/${props.tokenId}`);
        token.value = response.data;
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

        // Stwórz tymczasowy URL do podglądu
        if (imagePreviewUrl.value) {
            URL.revokeObjectURL(imagePreviewUrl.value);
        }
        imagePreviewUrl.value = URL.createObjectURL(file);
        token.value.image = file
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
    console.log(props.heroes)
});
</script>

<style scoped>

.token-edit-container {
    font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
    color: #e4d8b4;
}

/* Customowe Inputy */
.warhammer-label {
    display: block;
    font-size: 0.85rem;
    font-weight: bold;
    color: #c4a47c;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.35rem;
}

.warhammer-input {
    width: 100%;
    background-color: #1b1b18;
    color: #e4d8b4;
    border: 1px solid #8b5a2b;
    padding: 0.6rem 0.75rem;
    border-radius: 2px;
    transition: all 0.2s;
}
.warhammer-input.pl-11 {
    padding-left: 2.75rem !important;
}

.warhammer-input:focus {
    outline: none;
    border-color: #d4af37;
    box-shadow: 0 0 5px rgba(212, 175, 55, 0.4);
}

.warhammer-input-color {
    position: absolute;
    left: 4px;
    top: 4px;
    width: 36px;
    height: 36px;
    border: none;
    cursor: pointer;
    background: none;
}
/* Ukrycie domyślnego wyglądu input color dla lepszej integracji */
.warhammer-input-color::-webkit-color-swatch-wrapper {
    padding: 0;
}
.warhammer-input-color::-webkit-color-swatch {
    border: 1px solid black;
    border-radius: 2px;
}

/* Przyciski */
.warhammer-btn {
    background-color: #8b5a2b;
    color: #e4d8b4;
    padding: 0.5rem 1.25rem;
    border: 1px solid #5e4128;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 0.8rem;
    letter-spacing: 0.1em;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 2px 2px 0px rgba(0,0,0,1);
}

.warhammer-btn:hover {
    background-color: #d4af37;
    color: #1b1b18;
}

.warhammer-btn-secondary {
    background: none;
    color: #8b5a2b;
    padding: 0.5rem 1.25rem;
    border: 1px solid transparent;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.2s;
}

.warhammer-btn-secondary:hover {
    color: #d4af37;
    border-color: #8b5a2b;
    background-color: #1b1b18;
}

/* Okrągły podgląd Tokena */
.token-preview-circ {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.6), inset 0 0 10px rgba(0,0,0,0.5);
    border: 1px solid #5e4128;
}
</style>
