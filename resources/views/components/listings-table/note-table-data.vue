<script setup lang="ts">
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import {
    PencilSquareIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline/index.js";

defineProps<{
    listing: Data.Listing.ListingData;
}>();

const destroy = (id: number) => {
    router.delete(route("listings.destroy", { listing: id }), {
        preserveScroll: true,
    });
};
</script>

<template>
    <td class="max-w-[110px] px-1">
        <div
            v-if="listing.canManage"
            class="flex flex-nowrap justify-end gap-1"
        >
            <Link
                :href="route('listings.edit', { listing })"
                class="w-fit rounded-md bg-amber-300 px-2 py-1 text-amber-900 hover:bg-amber-400"
            >
                <PencilSquareIcon class="size-5" />
            </Link>

            <button
                class="w-fit rounded-md bg-red-300 px-2 py-1 text-red-900 hover:bg-red-400"
                @click="destroy(listing.id)"
            >
                <TrashIcon class="size-5" />
            </button>
        </div>

        <template v-else>
            <Tooltip>
                <p class="truncate">{{ listing.notes }}</p>

                <template #popper>
                    {{ listing.notes }}
                </template>
            </Tooltip>
        </template>
    </td>
</template>
