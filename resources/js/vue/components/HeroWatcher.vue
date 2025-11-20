<template>
    <audio ref="experienceSound" muted="muted">
        <source src="/public/sounds/notification_alert.mp3">
    </audio>

    <audio ref="fortuneSound" muted="muted">
        <source src="/public/sounds/fp-added-notification.mp3">
    </audio>
</template>
<script setup lang="ts">
import { defineProps, ref } from "vue";

const props = defineProps<{
    heroId: number
}>();

const experienceSound = ref<HTMLAudioElement | null>(null);
const fortuneSound = ref<HTMLAudioElement | null>(null);
console.log("Hero ID:", props.heroId)
window.Echo.private(`hero.${props.heroId}`)
    .listen('.hero.experience-points-added', (e) => {
        experienceSound.value.muted = false;
        experienceSound.value.play().catch(err => console.error(err));
        customSwal.fire({
            title: `Otrzymałeś ${e.experiencePoints} punktów doświadczenia!`,
            text: e.message,
            confirmButtonText: "Dlaczego tak mało?!",
            width: '30%'
        })
    });

</script>
<!--<script>-->
<!--import {echo} from "../../echo.ts";-->

<!--export default {-->
<!--    name: 'HeroWatcher',-->
<!--    props: ['heroId'],-->
<!--    emits: ['experience-changed', 'fortune-points-changed'],-->
<!--    data() {-->
<!--        return {-->
<!--            eventSource: null-->
<!--        };-->
<!--    },-->
<!--    mounted() {-->
<!--        echo.channel(`hero.` + this.heroId)-->
<!--            .listen('hero.experience-points-added', (data) => {-->
<!--                console.log("Odebrano event:", data);-->
<!--                customSwal.fire({-->
<!--                    title: `Otrzymałeś ${data.amount} punktów doświadczenia!`,-->
<!--                    text: data.message,-->
<!--                    confirmButtonText: "Dlaczego tak mało?!",-->
<!--                    width: '30%'-->
<!--                })-->
<!--            });-->
<!--    },-->
<!--    beforeUnmount() {-->
<!--        if (this.eventSource) {-->
<!--            this.eventSource.close();-->
<!--        }-->
<!--    },-->
<!--    methods: {-->
<!--        establishConnection() {-->
<!--            this.eventSource = new EventSource(`/sse/hero-watcher`);-->

<!--            this.eventSource.onmessage = (event) => {-->
<!--                console.log('xx')-->
<!--                const data = JSON.parse(event.data);-->
<!--                if (data.type === 'EXPERIENCE') {-->
<!--                    this.$refs.experienceNotificationSound.play();-->
<!--                    this.$emit('experience-changed', data.amount);-->

<!--                } else if (data.type === 'FORTUNE_POINTS') {-->
<!--                    this.$refs.fortunePointsNotificationSound.play();-->
<!--                    this.$emit('fortune-points-changed');-->
<!--                    customSwal.fire({-->
<!--                        title: `Gratulacje!`,-->
<!--                        text: "Otrzymałeś punkt szczęścia!",-->
<!--                        confirmButtonText: "Super!",-->
<!--                        width: '30%'-->
<!--                    })-->
<!--                }-->

<!--            };-->

<!--            this.eventSource.onerror = (error) => {-->
<!--                console.error('SSE error:', error);-->
<!--                this.eventSource.close();-->
<!--            };-->
<!--        }-->
<!--    }-->
<!--}-->
<!--</script>-->
