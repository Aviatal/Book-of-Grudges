import { ref, computed } from 'vue';
import axios from 'axios';

export interface SkillOption {
    id: number;
    name: string;
    type: string;
    characteristic: string;
    characteristic_value: number;
    is_purchased: boolean;
    additional_name: string | null;
}

// Backend zwraca tylko cechy podstawowe (drugorzędnych, jak Żywotność czy Szybkość, nie da się
// testować), w kolejności takiej samej jak na karcie postaci (patrz HeroCharacteristicSection.vue).
const CHARACTERISTIC_ORDER = ['WW', 'US', 'K', 'Odp', 'Zr', 'Int', 'SW', 'Ogd'];

export function useHeroSkills() {
    const skills = ref<SkillOption[]>([]);
    const heroCharacteristics = ref<Record<string, number>>({});
    const isLoadingSkills = ref(false);

    const orderedHeroCharacteristics = computed(() => {
        return Object.entries(heroCharacteristics.value)
            .map(([key, val]) => ({ key, val }))
            .sort((a, b) => {
                const indexA = CHARACTERISTIC_ORDER.indexOf(a.key);
                const indexB = CHARACTERISTIC_ORDER.indexOf(b.key);

                return (indexA === -1 ? CHARACTERISTIC_ORDER.length : indexA) - (indexB === -1 ? CHARACTERISTIC_ORDER.length : indexB);
            });
    });

    const ensureLoaded = async () => {
        if (skills.value.length > 0) return;

        isLoadingSkills.value = true;
        try {
            const { data } = await axios.get<{ characteristics: Record<string, number>; skills: SkillOption[] }>('/session/chat/skills');
            heroCharacteristics.value = data.characteristics ?? {};
            skills.value = data.skills ?? [];
        } catch (e) {
            console.error('Błąd pobierania umiejętności', e);
        } finally {
            isLoadingSkills.value = false;
        }
    };

    return {
        skills,
        heroCharacteristics,
        isLoadingSkills,
        orderedHeroCharacteristics,
        ensureLoaded,
    };
}
