<template>
<!--    <audio ref="experienceNotificationSound" muted="muted"><source src="/public/sounds/notification_alert.mp3"></audio>-->
<!--    <audio ref="fortunePointsNotificationSound" muted="muted"><source src="/public/sounds/fp-added-notification.mp3"></audio>-->
</template>
<script>
export default {
    name: 'HeroWatcher',
    emits: ['experience-changed', 'fortune-points-changed'],
    data() {
        return {
            eventSource: null
        };
    },
    mounted() {
        this.establishConnection();
    },
    beforeUnmount() {
        if (this.eventSource) {
            this.eventSource.close();
        }
    },
    methods: {
        establishConnection() {
            this.eventSource = new EventSource(`/sse/hero-watcher`);

            this.eventSource.onmessage = (event) => {
                console.log('xx')
                const data = JSON.parse(event.data);
                if (data.type === 'EXPERIENCE') {
                    this.$refs.experienceNotificationSound.play();
                    this.$emit('experience-changed', data.amount);
                    customSwal.fire({
                        title: `Otrzymałeś ${data.amount} punktów doświadczenia!`,
                        text: data.message,
                        confirmButtonText: "Dlaczego tak mało?!",
                        width: '30%'
                    })
                } else if (data.type === 'FORTUNE_POINTS') {
                    this.$refs.fortunePointsNotificationSound.play();
                    this.$emit('fortune-points-changed');
                    customSwal.fire({
                        title: `Gratulacje!`,
                        text: "Otrzymałeś punkt szczęścia!",
                        confirmButtonText: "Super!",
                        width: '30%'
                    })
                }

            };

            this.eventSource.onerror = (error) => {
                console.error('SSE error:', error);
                this.eventSource.close();
            };
        }
    }
}
</script>
