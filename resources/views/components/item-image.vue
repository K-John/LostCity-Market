<script setup lang="ts">
const props = withDefaults(
    defineProps<{
        item: Data.Item.ItemData | Data.Item.ItemFormData | null;
        quantity?: number; // optional quantity used for coin image selection
    }>(),
    {
        quantity: 10000,
    },
);

const denominations = [1, 2, 3, 4, 5, 25, 100, 250, 1000, 10000];

function nearestDenomination(value: number, denominations: number[]): number {
    const sorted = [...denominations].sort((a, b) => a - b);

    let result = sorted[0];

    for (const d of sorted) {
        if (d > value) break;
        result = d;
    }

    return result;
}

const getCoinsImage = (q: number): string => {
    return `gold_pieces_${nearestDenomination(q, denominations)}`;
};

const src = computed(() => {
    if (!props.item) return "";

    if (props.item.game_id === 995) {
        return `/img/items/${getCoinsImage(props.quantity ?? 1)}.webp`;
    }

    return `/img/items/${props.item.slug}.webp`;
});

const alt = computed(() => (props.item ? props.item.name : ""));
</script>

<template>
    <img v-if="item" class="min-h-[24px] min-w-[24px]" :src="src" :alt="alt" />
</template>
