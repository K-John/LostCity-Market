<script setup lang="ts">
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import PopoverMenu from "@/views/components/PopoverMenu.vue";

const props = defineProps<{
    listing: Data.Listing.ListingData;
}>();

const MAX_PREVIEW_ITEMS = 3;

// Flatten all items across all offers
const flattenedItems = computed(() => {
    const out: any[] = [];

    props.listing.offers.forEach((offer: any, offerIndex: number) => {
        (offer.items ?? []).forEach((offerItem: any) => {
            out.push({
                offerIndex,
                ...offerItem,
            });
        });
    });

    return out;
});

// Items we’ll actually show icons for
const previewItems = computed(() =>
    flattenedItems.value.slice(0, MAX_PREVIEW_ITEMS),
);

const totalItemsCount = computed(() => flattenedItems.value.length);

const remainingItemsCount = computed(() =>
    Math.max(0, totalItemsCount.value - previewItems.value.length),
);

const showEach = computed(() => {
    const offers = props.listing.offers;

    if (offers.length !== 1) return false;
    if (props.listing.quantity === 1) return false;

    const offer = offers[0];

    return (
        offer.title?.trim().toLowerCase() === "for each item:" &&
        offer.items?.length <= MAX_PREVIEW_ITEMS
    );
});
</script>

<template>
    <td class="px-1">
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

            <Tooltip
                v-if="listing.price !== null"
                class="flex items-center gap-1"
            >
                <p class="text-sm sm:text-[medium]">
                    {{ formatGold(listing.price) }}GP
                </p>

                <p class="text-xs sm:text-[medium]">ea.</p>

                <template #popper>
                    {{ listing.price.toLocaleString() }}
                </template>
            </Tooltip>

            <PopoverMenu
                v-if="listing.offers && listing.offers.length > 0"
            >
                <template #button>
                    <Tooltip>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1">
                                <div
                                    v-for="(offerItem, index) in previewItems"
                                    :key="index"
                                    class="relative size-8 shrink-0 border border-stone-700 bg-stone-800"
                                >
                                    <img
                                        v-if="offerItem.item"
                                        class="size-full object-cover"
                                        :src="`/img/items/${offerItem.item.slug}.webp`"
                                        :alt="offerItem.item.name"
                                    />

                                    <span
                                        class="absolute -left-1 -top-1 rounded bg-black/80 px-0.5 text-sm leading-none"
                                    >
                                        {{ formatGold(offerItem.quantity) }}
                                    </span>
                                </div>

                                <span
                                    v-if="remainingItemsCount > 0"
                                    class="ml-0.5 truncate text-sm text-stone-300"
                                >
                                    +{{ remainingItemsCount }}
                                </span>

                                <span
                                    v-if="showEach && remainingItemsCount === 0"
                                    class="ml-0.5 truncate text-sm text-stone-300"
                                >
                                    ea.
                                </span>
                            </div>

                            <span
                                v-if="listing.offers.length > 1"
                                class="mb-0.5 block truncate text-[10px] text-stone-400"
                            >
                                {{ listing.offers.length }} offers
                            </span>
                        </div>

                        <template #popper> Click to view full offer </template>
                    </Tooltip>
                </template>

                <div
                    class="flex flex-col gap-2 border-2 border-[#382418] bg-black p-2"
                >
                    <template
                        v-for="(offer, offerIndex) in listing.offers"
                        :key="offerIndex"
                    >
                        <div
                            class="flex flex-col gap-2 border-2 border-stone-700 bg-stone-900 p-2"
                        >
                            <p
                                class="align-top text-sm leading-none text-stone-200"
                            >
                                {{ offer.title }}
                            </p>

                            <div class="flex flex-wrap gap-2">
                                <div
                                    v-for="(
                                        offerItem, itemIndex
                                    ) in offer.items"
                                    :key="itemIndex"
                                    class="flex items-center gap-2"
                                >
                                    <div
                                        class="flex items-center border border-stone-700 bg-stone-800"
                                    >
                                        <Tooltip class="w-fit">
                                            <Link
                                                v-if="offerItem.item"
                                                :href="
                                                    route('items.show', {
                                                        item: offerItem.item
                                                            .slug,
                                                    })
                                                "
                                            >
                                                <img
                                                    v-if="offerItem.item"
                                                    class="size-full object-cover"
                                                    :src="`/img/items/${offerItem.item.slug}.webp`"
                                                    :alt="offerItem.item.name"
                                                />
                                            </Link>

                                            <template #popper>
                                                {{
                                                    offerItem.item
                                                        ? offerItem.item.name
                                                        : "Unknown Item"
                                                }}
                                            </template>
                                        </Tooltip>

                                        <Tooltip class="px-1">
                                            <p class="text-sm sm:text-[medium]">
                                                {{
                                                    formatGold(
                                                        offerItem.quantity,
                                                    )
                                                }}
                                            </p>

                                            <template #popper>
                                                {{
                                                    offerItem.quantity.toLocaleString()
                                                }}
                                            </template>
                                        </Tooltip>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="offerIndex !== listing.offers.length - 1"
                            class="flex items-center gap-2"
                        >
                            <div class="h-px grow bg-stone-700"></div>

                            <div class="text-sm font-medium">OR</div>

                            <div class="h-px grow bg-stone-700"></div>
                        </div>
                    </template>
                </div>
            </PopoverMenu>
        </div>
    </td>
</template>
