<script setup lang="ts">
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

defineProps<{
    listing: Data.Listing.ListingData;
}>();
</script>

<template>
    <td class="flex gap-1 px-1">
        <span
            :class="listing.type === 'buy' ? 'text-red-500' : 'text-green-500'"
            class="font-bold"
        >
            [{{ listing.type.charAt(0).toUpperCase() }}]
        </span>

        <Tooltip>
            <p>{{ formatGold(listing.quantity) }}</p>

            <template #popper>
                {{ listing.quantity.toLocaleString() }}
            </template>
        </Tooltip>

        for

        <Tooltip>
            <p>{{ formatGold(listing.price) }}GP ea.</p>

            <template #popper>
                {{ listing.price.toLocaleString() }}
            </template>
        </Tooltip>
    </td>
</template>
