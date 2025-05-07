<script setup lang="ts">
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import {
    PencilSquareIcon,
    ArrowTrendingUpIcon,
    CheckIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline/index.js";

const props = defineProps<{
    listing: Data.Listing.ListingData;
}>();

const typeVerb = computed(() => {
    return props.listing.type === "buy" ? "Bought" : "Sold";
});
</script>

<template>
    <td v-if="listing.canManage && !listing.soldAt" class="sm:px-1">
        <div class="flex flex-nowrap justify-end gap-1">
            <DropdownMenu>
                <DropdownItem
                    :icon="ArrowTrendingUpIcon"
                    text-color="text-amber-400"
                    @click="
                        router.patch(
                            route('listing.bump', {
                                listing: listing,
                            }),
                            { preserveScroll: true },
                        )
                    "
                >
                    Bump
                </DropdownItem>

                <DropdownItem
                    :icon="CheckIcon"
                    text-color="text-green-500"
                    @click="router.visit(route('listing.sale.store', { listing }), { preserveScroll: true })"
                >
                    Mark {{ typeVerb }}
                </DropdownItem>

                <DropdownItem
                    :icon="PencilSquareIcon"
                    text-color="text-amber-500"
                    :href="route('listings.edit', { listing })"
                >
                    Edit
                </DropdownItem>

                <DropdownItem
                    :icon="XMarkIcon"
                    text-color="text-red-500"
                    @click="
                        router.delete(
                            route('listings.destroy', {
                                listing: listing.id,
                                force: true,
                            }),
                            { preserveScroll: true },
                        )
                    "
                >
                    Remove
                </DropdownItem>
            </DropdownMenu>
        </div>
    </td>
</template>
