<script>
export default {
    name: 'ExperienceWatcher',
    mounted() {
        this.establishConnection();
    },
    methods: {
        establishConnection() {
            let eventSource = null;
            eventSource = new EventSource(`/api/experience-watch`, {
                withCredentials: true
            });

            eventSource.onmessage = (event) => {
                const data = JSON.parse(event.data);
                this.$emit('experience-changed', data.amount);
                customSwal.fire({
                    title: `Otrzymałeś ${data.amount} punktów doświadczenia!`,
                    text: data.message,
                    confirmButtonText: "Dlaczego tak mało?!",
                    width: '30%'
                })
            };

            eventSource.onerror = (error) => {
                console.error('SSE error:', error);
                eventSource.close();
            };
        }
    }
}
</script>
