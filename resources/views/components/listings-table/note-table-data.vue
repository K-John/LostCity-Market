<script setup lang="ts">
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import { ChatBubbleBottomCenterIcon } from "@heroicons/vue/24/solid/index.js";

defineProps<{
    listing: Data.Listing.ListingData;
}>();
</script>

<template>
    <td :colspan="listing.canManage ? 1 : 2" class="max-w-[110px] sm:px-1">
        <Tooltip v-if="listing.notes">
            <button class="p-1 sm:hidden">
                <ChatBubbleBottomCenterIcon class="size-5 text-stone-400" />
            </button>

            <p class="hidden truncate sm:block">
                {{ listing.notes }}
            </p>

            <template #popper>
                {{ listing.notes }}
            </template>
        </Tooltip>

        <span v-else>-</span>
    </td>
</template>
