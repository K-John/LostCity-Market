<script setup lang="ts">
import { ref, onMounted } from "vue";
import { XMarkIcon } from "@heroicons/vue/24/solid";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

const props = defineProps<{
    offerItem: Data.Listing.ListingOfferItemData;
    autoFocus?: boolean;
}>();

const emit = defineEmits<{
    (e: "update:quantity", value: number): void;
    (e: "remove"): void;
}>();

const qtyRef = ref<HTMLInputElement | null>(null);

onMounted(() => {
    if (props.autoFocus) {
        qtyRef.value?.focus();
        qtyRef.value?.select?.();
    }
});

const onInput = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.value === "") return;

    const trimmed = target.value.trim().toLowerCase();

    const match = trimmed.match(/^([0-9]*\.?[0-9]+)\s*([kmb])?$/);

    if (!match) return null;

    const value = parseFloat(match[1]);
    const suffix = match[2];

    let multiplier = 1;

    switch (suffix) {
        case "k":
            multiplier = 1_000;
            break;
        case "m":
            multiplier = 1_000_000;
            break;
        case "b":
            multiplier = 1_000_000_000;
            break;
    }

    emit("update:quantity", value * multiplier);
};
</script>

<template>
    <div
        class="flex items-center gap-2 border border-stone-700 bg-stone-800 px-2 py-1"
    >
        <ItemImage
            :item="offerItem.item"
            :quantity="offerItem.quantity"
        />

        <input
            ref="qtyRef"
            :value="offerItem.quantity"
            type="text"
            inputmode="decimal"
            class="w-20 border-slate-900 bg-stone-700 py-0 pl-1 text-sm"
            placeholder="Qty"
            @input="onInput"
        />

        <Tooltip>
            <button
                type="button"
                class="text-red-400 hover:text-red-300"
                @click="emit('remove')"
            >
                <XMarkIcon class="size-4" />
            </button>

            <template #popper> Remove Item </template>
        </Tooltip>
    </div>
</template>
