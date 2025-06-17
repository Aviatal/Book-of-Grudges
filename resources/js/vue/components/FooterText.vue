
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
            currentIndex: 0,
            footerTexts: [],
            text: ''
        }
    },
    created() {
        this.getTexts();
        setInterval(() => {
            this.changeText();
        },  10 * 1000)
    },
    methods: {
        getTexts() {
            axios.get('get-footer-text')
                .then((response) => {
                    this.footerTexts = response.data;
                    this.text = this.footerTexts[0];
                })
                .catch((error) => {
                    console.log(error);
                })
        },
        changeText() {
            if (this.currentIndex === (this.footerTexts.length - 1)) {
                this.currentIndex = 0;
            } else {
                this.currentIndex++;
            }
            this.text = this.footerTexts[this.currentIndex];
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
