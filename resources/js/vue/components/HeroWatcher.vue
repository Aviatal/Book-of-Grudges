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
interface ExperienceEvent {
    experiencePoints: number;
    additionalMessage: string;
}

const props = defineProps<{
    heroId: number
}>();
const emits = defineEmits<{
    experienceChanged: [amount: number],
    fortunePointsChanged: []
}>();

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
    });

</script>
