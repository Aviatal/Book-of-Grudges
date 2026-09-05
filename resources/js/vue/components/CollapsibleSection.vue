<template>
    <section class="collapsible-section">
        <div class="collapsible-section__header" :class="{ 'collapsible-section__header--static': !toggleable }" @click="toggle">
            <span class="collapsible-section__marker"></span>
            <h2 class="collapsible-section__title">{{ title }}</h2>
            <span v-if="meta || $slots.meta" class="collapsible-section__meta">
                <slot name="meta">{{ meta }}</slot>
            </span>
            <svg
                v-if="toggleable"
                class="collapsible-section__chevron"
                :class="{ 'collapsible-section__chevron--closed': !isOpen }"
                width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="#8b7b52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            >
                <polyline points="18 15 12 9 6 15"></polyline>
            </svg>
        </div>
        <div v-if="isOpen" class="collapsible-section__content">
            <slot></slot>
        </div>
    </section>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

const props = withDefaults(defineProps<{
    title: string;
    meta?: string;
    defaultOpen?: boolean;
    modelValue?: boolean;
    toggleable?: boolean;
}>(), {
    defaultOpen: false,
    toggleable: true,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const isOpen = ref<boolean>(!props.toggleable || (props.modelValue ?? props.defaultOpen));

watch(() => props.modelValue, (value) => {
    if (value !== undefined) {
        isOpen.value = value;
    }
});

const toggle = () => {
    if (!props.toggleable) {
        return;
    }
    isOpen.value = !isOpen.value;
    emit('update:modelValue', isOpen.value);
};
</script>

<style scoped>
.collapsible-section {
    border: 1px solid var(--border-default);
    border-top: 1px solid var(--border-accent);
    background: var(--bg-panel);
    box-shadow: 0 18px 40px -28px #000;
}

.collapsible-section__header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 22px;
    background: var(--bg-panel-gradient);
    cursor: pointer;
}

.collapsible-section__header--static {
    cursor: default;
}

.collapsible-section__marker {
    width: 9px;
    height: 9px;
    background: var(--gold);
    transform: rotate(45deg);
    flex: none;
}

.collapsible-section__title {
    margin: 0;
    flex: 1;
    font-family: var(--font-heading);
    font-size: 16px;
    font-weight: 600;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--text-body);
}

.collapsible-section__meta {
    font-size: 14px;
    color: var(--text-faint-alt);
}

.collapsible-section__chevron {
    flex: none;
    transition: transform 150ms ease;
}

.collapsible-section__chevron--closed {
    transform: rotate(180deg);
}

.collapsible-section__content {
    padding: 22px;
    border-top: 1px solid var(--border-subtle);
}
</style>
