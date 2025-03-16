<script setup lang="ts">
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

defineProps<{
    listing: Data.Listing.ListingData;
}>();
</script>

<template>
    <td class="w-0 px-1 pb-1 sm:pb-0">
        <div class="flex flex-col gap-x-1 sm:flex-row sm:items-center">
            <div class="flex gap-1 leading-none sm:items-center">
                <span
                    :class="
                        listing.type === 'buy'
                            ? 'text-red-500'
                            : 'text-green-500'
                    "
                    class="font-bold"
                >
                    [{{ listing.type.charAt(0).toUpperCase() }}]
                </span>

                <Tooltip>
                    <p class="text-sm sm:text-[medium]">
                        {{ formatGold(listing.quantity) }}
                    </p>

                    <template #popper>
                        {{ listing.quantity.toLocaleString() }}
                    </template>
                </Tooltip>
            </div>

            <span class="hidden sm:inline">for</span>

            <Tooltip class="flex items-center gap-1">
                <p class="text-sm sm:text-[medium]">
                    {{ formatGold(listing.price) }}GP
                </p>
                
                <p class="text-xs sm:text-[medium]">ea.</p>

                <template #popper>
                    {{ listing.price.toLocaleString() }}
                </template>
            </Tooltip>
        </div>
    </td>
</template>
