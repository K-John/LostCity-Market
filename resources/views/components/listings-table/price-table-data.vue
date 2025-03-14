<script setup lang="ts">
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

defineProps<{
    listing: Data.Listing.ListingData;
}>();
</script>

<template>
    <td class="flex flex-col gap-x-1 pb-1 sm:flex-row sm:px-1 sm:pb-0">
        <div class="flex gap-1 leading-none sm:leading-[normal]">
            <span
                :class="
                    listing.type === 'buy' ? 'text-red-500' : 'text-green-500'
                "
                class="font-bold"
            >
                [{{ listing.type.charAt(0).toUpperCase() }}]
            </span>

            <Tooltip>
                <p class="text-sm sm:text-[medium]">{{ formatGold(listing.quantity) }}</p>

                <template #popper>
                    {{ listing.quantity.toLocaleString() }}
                </template>
            </Tooltip>
        </div>

        <span class="hidden sm:inline">for</span>

        <Tooltip>
            <p class="text-sm sm:text-[medium]">{{ formatGold(listing.price) }}GP <span class="hidden sm:inline">ea.</span></p>

            <template #popper>
                {{ listing.price.toLocaleString() }}
            </template>
        </Tooltip>
    </td>
</template>
