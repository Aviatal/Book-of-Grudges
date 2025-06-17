<template>
    <h1 class="flex justify-center text-[#d4af37] text-2xl mb-3">Przydziel punkty szczęścia</h1>
    <section class="flex flex-wrap gap-3 justify-center">
        <button v-for="user in activeUsers"
                class="p-3 text-lg border-[#c09f80] border-1 border-solid rounded-sm bg-[#c29d2b] text-[#efefef] m-4"
                @click="addFortunePoint(user.hero)"
        >
            {{ user.hero.name }}
        </button>
    </section>
</template>
<script>
export default {
    name: "FortunePointsManagement",
    props: ['activeUsers'],
    methods: {
        addFortunePoint(hero) {
            axios.post('panel/fortune-points/assign-fortune-point', {heroId: hero.id})
                .then(() => {
                    this.$toast.success('Przydzielono punkt szczęścia!')
                })
                .catch(() => {
                    this.$toast.error('Wystąpił błąd podczas przydzielana punktu szczęścia')
                })
        }
    }
}
</script>
