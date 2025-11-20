<template>
    <audio ref="experienceNotificationSound" muted="muted"><source src="/public/sounds/notification_alert.mp3"></audio>
    <audio ref="fortunePointsNotificationSound" muted="muted"><source src="/public/sounds/fp-added-notification.mp3"></audio>
</template>
<script setup lang="ts">
import { useEcho } from "@laravel/echo-vue";
import { defineProps } from "vue";

const props = defineProps<{
    heroId: number
}>();

useEcho(
    `hero.${props.heroId}`,
    "ExperiencePointsAdded",
    (e) => {
        console.log("Odebrano event:");
        console.log(e);
        alert('test')
    },
);
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
<!--                    customSwal.fire({-->
<!--                        title: `Otrzymałeś ${data.amount} punktów doświadczenia!`,-->
<!--                        text: data.message,-->
<!--                        confirmButtonText: "Dlaczego tak mało?!",-->
<!--                        width: '30%'-->
<!--                    })-->
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
