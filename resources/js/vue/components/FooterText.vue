
<template>
    <transition name="fade" mode="out-in">
        <span :key="text">{{ text }}</span>
    </transition>
</template>

<script>
export default {
    name: 'FooterText',
    data() {
        return {
            text: ''
        }
    },
    created() {
        this.getText();
        setInterval(() => {
            this.getText();
        },  15 * 1000)
    },
    methods: {
        getText() {
            axios.get('get-footer-text')
                .then((response) => {
                    this.text = response.data;
                })
                .catch((error) => {
                    console.log(error);
                })
        }
    }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
