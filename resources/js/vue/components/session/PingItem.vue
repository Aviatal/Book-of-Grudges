<template>
    <v-circle
        v-for="n in 3"
        :key="n"
        :config="{
      x: x,
      y: y,
      radius: 0,
      stroke: color,
      strokeWidth: 3,
      opacity: 1,
      listening: false
    }"
        :ref="(el: any) => setAnim(el, n)"
    />
</template>

<script setup lang="ts">
import { nextTick, onUnmounted } from 'vue';
import Konva from 'konva';

const props = defineProps<{
    x: number;
    y: number;
    color: string;
}>();

const tweens: Konva.Tween[] = [];

const setAnim = (el: any, index: number) => {
    if (!el) return;

    const node = el.getNode();

    // Używamy nextTick, aby poczekać, aż vue-konva doda node do Layer
    nextTick(() => {
        if (!node.getLayer()) return;

        const tween = new Konva.Tween({
            node: node,
            duration: 1.5,
            radius: 80,
            opacity: 0,
            easing: Konva.Easings.EaseOut,
            onFinish: () => {
                if (node.getLayer()) {
                    node.radius(0);
                    node.opacity(1);
                    tween.play();
                }
            }
        });

        tweens.push(tween);

        // Startujemy z opóźnieniem zależnym od indeksu fali
        setTimeout(() => {
            if (node.getLayer()) {
                tween.play();
            }
        }, index * 400);
    });
};

// Bardzo ważne: czyścimy animacje przy usuwaniu komponentu
onUnmounted(() => {
    tweens.forEach(t => t.destroy());
});
</script>
