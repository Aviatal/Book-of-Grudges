<template>
    <audio ref="experienceSound" muted>
        <source src="/public/sounds/notification_alert.mp3">
    </audio>

    <audio ref="fortuneSound" muted>
        <source src="/public/sounds/fp-added-notification.mp3">
    </audio>
</template>
<script setup lang="ts">
import {defineProps, defineEmits, ref} from "vue";
import axios from "axios";
import {useToast} from "vue-toast-notification";
import {MarketplaceItem} from "../../types/MarketplaceItem";
import {Weapon} from "../../types/Weapon";
import {Armor} from "../../types/Armor";
interface ExperienceEvent {
    experiencePoints: number;
    additionalMessage: string;
}
interface PurchaseEvent {
    heroId: number;
    item: MarketplaceItem,
    customName: string,
    price: number
}
interface wealthUpdateObject {
    goldCrowns: number,
    silverShillings: number,
    brassPennies: number
}

const props = defineProps<{
    heroId: number
}>();
const emits = defineEmits<{
    experienceChanged: [amount: number],
    fortunePointsChanged: [],
    addNewItem: [
        newItem: MarketplaceItem|Weapon|Armor,
        type: string,
        wealth: wealthUpdateObject,
        equip: boolean
    ]
}>();
const toast = useToast();

const experienceSound = ref<HTMLAudioElement | null>(null);
const fortuneSound = ref<HTMLAudioElement | null>(null);
window.Echo.private(`hero.${props.heroId}`)
    .listen('.hero.experience-points-added', (e: ExperienceEvent) => {
        experienceSound.value.muted = false;
        experienceSound.value.play().catch(err => console.error(err));
        customSwal.fire({
            title: `Otrzymałeś ${e.experiencePoints} punktów doświadczenia!`,
            text: e.additionalMessage,
            confirmButtonText: "Dlaczego tak mało?!",
            width: '30%'
        });
        emits('experienceChanged', e.experiencePoints);
    })
    .listen('.hero.fortune-points-added', () => {
        fortuneSound.value.muted = false;
        fortuneSound.value.play().catch(err => console.error(err));
        customSwal.fire({
            title: `Gratulacje!`,
            text: "Otrzymałeś punkt szczęścia!",
            confirmButtonText: "Dziękuję, wspaniały Mistrzu Gry!",
            width: '30%'
        })
        emits('fortunePointsChanged');
    })
    .listen('.hero.purchase', (e: PurchaseEvent) => {
        let totalCopper = e.price;
        const g = Math.floor(totalCopper / 240);
        totalCopper %= 240;
        const s = Math.floor(totalCopper / 12);
        totalCopper %= 12;
        const c = totalCopper;

        let message = (g > 0 ? `${g} złotych koron, `: '') + (s > 0 ? `${s} srebnych szylingów, `: '') + (c > 0 ? `${c} miedzianych pensów `: '');
        customSwal
            .fire({
                title: `Kupujesz ${e.customName}!`,
                text: "Musisz na to wydać " + message,
                showDenyButton: e.item.tradeable_type === 'App\\Models\\CommonItems',
                confirmButtonText: "Schowaj do ekwipunku",
                denyButtonText: "Zużyj natychmiast",
                width: '30%'
            })
            .then((result) => {
                axios
                    .post('karta-postaci/' + props.heroId + '/equip-marketplace-item', {
                        item: e.item.id,
                        customName: e.customName,
                        price: e.price,
                        equip: result.isConfirmed
                    })
                    .then((response) => {
                        if(response.data.item.message) {
                            toast.warning(response.data.item.message)
                        }
                        emits('addNewItem', response.data.item.item, response.data.item.type, response.data.wealth, result.isConfirmed)
                    })
                    .catch(error => {
                        toast.error(error.response.data.message)
                    })
            })
    });

</script>
