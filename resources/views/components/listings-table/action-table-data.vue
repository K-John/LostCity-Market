<script setup lang="ts">
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import {
    PencilSquareIcon,
    ArrowTrendingUpIcon,
    CheckIcon,
    XMarkIcon,
    PauseIcon,
    PlayIcon
} from "@heroicons/vue/24/outline/index.js";

const props = defineProps<{
    listing: Data.Listing.ListingData;
}>();

const typeVerb = computed(() => {
    return props.listing.type === "buy" ? "Bought" : "Sold";
});

const page = usePage();
const currentUrl = computed(() => {
    const url = new URL(page.url, window.location.origin);
    url.searchParams.delete("redirect");
    return url.pathname + url.search + url.hash;
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
                    v-if="!listing.pausedAt"
                    :icon="PauseIcon"
                    text-color="text-rose-500"
                    @click="
                        router.post(
                            route('listing.pause.store', {
                                listing: listing,
                            }),
                            { preserveScroll: true },
                        )
                    "
                >
                    Pause
                </DropdownItem>

                <DropdownItem
                    v-else
                    :icon="PlayIcon"
                    text-color="text-emerald-500"
                    @click="
                        router.delete(
                            route('listing.pause.destroy', {
                                listing: listing,
                            }),
                            { preserveScroll: true },
                        )
                    "
                >
                    Unpause
                </DropdownItem>

                <DropdownItem
                    :icon="CheckIcon"
                    text-color="text-green-500"
                    @click="
                        router.visit(
                            route('listing.sale.store', {
                                listing,
                                redirect: currentUrl,
                            }),
                            { preserveScroll: true },
                        )
                    "
                >
                    Mark {{ typeVerb }}
                </DropdownItem>

                <DropdownItem
                    :icon="PencilSquareIcon"
                    text-color="text-amber-500"
                    @click="
                        router.visit(route('listings.edit', { listing }), {
                            preserveScroll: true,
                        })
                    "
                >
                    Edit
                </DropdownItem>

                <DropdownItem
                    :icon="XMarkIcon"
                    text-color="text-red-500"
                    @click="
                        router.visit(
                            route('listings.delete', {
                                listing,
                                redirect: currentUrl,
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
