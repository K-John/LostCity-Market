<script setup lang="ts">
const props = defineProps<{
    modelValue: number;
    max: number;
}>();

const emit = defineEmits<{
    (e: "update:modelValue", value: number): void;
}>();

const value = ref(props.modelValue);
watch(
    () => props.modelValue,
    (v) => (value.value = v),
);
watch(value, (v) => emit("update:modelValue", v));

function generateSnapPoints(quantity: number): number[] {
    if (quantity <= 5) {
        return Array.from({ length: quantity }, (_, i) => i + 1);
    }

    const magnitude = Math.pow(10, Math.floor(Math.log10(quantity)));
    const base = magnitude / 2;

    const rawStep = quantity / 10;
    const step = Math.ceil(rawStep / base) * base;

    const steps: number[] = [];
    for (let i = step; i < quantity; i += step) {
        steps.push(i);
    }

    return steps;
}

const ticks = computed(() => generateSnapPoints(props.max));
</script>

<template>
    <div
        class="w-full rounded-md border border-stone-600 bg-stone-700 px-2 py-1"
    >
        <input
            v-model.number="value"
            type="range"
            :min="1"
            :max="props.max"
            class="w-full"
            list="tickmarks"
        />

        <datalist id="tickmarks">
            <option v-for="tick in ticks" :key="tick" :value="tick" />
        </datalist>

        <div class="relative h-4">
            <div
                class="absolute inset-x-1 top-0 flex justify-between text-xs text-stone-300"
            >
                <div
                    v-for="tick in ticks"
                    :key="tick"
                    :style="{
                        left: `${((tick - 1) / (props.max - 1)) * 100}%`,
                        transform: 'translateX(-50%)',
                    }"
                    class="absolute"
                >
                    {{ formatGold(tick) }}
                </div>
            </div>
        </div>
    </div>
</template>
