<template>
    <div class="bg-[#3b3a36] p-4 rounded-md shadow-lg border border-[#8b5a2b] transition-colors duration-300">
        <div
            @click="toggleOpen"
            class="cursor-pointer flex justify-between items-center px-3 py-2 rounded-md hover:bg-[#8b5a2b] hover:text-[#2b2a27] transition-colors duration-300"
        >
            <h2 class="font-semibold text-xl text-[#e4d8b4]">Zdolności</h2>
            <svg
                :class="isOpen ? 'rotate-180' : ''"
                xmlns="http://www.w3.org/2000/svg"
                width="24" height="24"
                viewBox="0 0 24 24"
                fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-down text-[#e4d8b4] transition-transform duration-300"
            >
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>

        <transition name="fade">
            <div v-show="isOpen" class="mt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div v-for="talent in talents" :key="talent.id"
                         class="bg-gray-800 border border-gray-600 p-4 rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold text-white">{{ talent.name }}</h3>
                        <div>{{ talent.description }}</div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        talentsData: Object,
    },
    data() {
        return {
            isOpen: false,
            talents: this.talentsData,
        };
    },
    methods: {
        toggleOpen() {
            this.isOpen = !this.isOpen;
        },
    }
};
</script>
