<script setup lang="ts">
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

const props = defineProps<Pages.ListingsIndexPage>();

import {
    PencilSquareIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline/index.js";
</script>

<template>
    <LayoutMain>
        <div>
            <div class="flex flex-col gap-2 border-2 border-[#382418] bg-black">
                <div class="px-3 pt-3">
                    <h2 class="text-lg font-bold">Recent Listings:</h2>
                </div>

                <table class="border-separate border-spacing-2">
                    <tbody>
                        <tr v-for="listing in listings.data" :key="listing.id">
                            <td>
                                <Tooltip>
                                    <Link
                                        v-if="listing.item"
                                        :href="
                                            route('items.show', {
                                                item: listing.item.slug,
                                            })
                                        "
                                    >
                                        <img
                                            :src="`/img/items/${listing.item.slug}.png`"
                                            :alt="`${listing.item.name} Icon`"
                                            width="32"
                                            height="32"
                                        />
                                    </Link>

                                    <template #popper>
                                        {{
                                            listing.item
                                                ? listing.item.name
                                                : "Unknown Item"
                                        }}
                                    </template>
                                </Tooltip>
                            </td>

                            <td>
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
                                {{ listing.quantity.toLocaleString() }} for
                                {{ listing.price.toLocaleString() }}GP ea.
                            </td>

                            <td>
                                <Tooltip>
                                    <p>{{ fromNow(listing.updatedAt) }}</p>

                                    <template #popper>
                                        {{ formatTime(listing.updatedAt) }}
                                    </template>
                                </Tooltip>
                            </td>

                            <td class="max-w-[110px]">
                                <Tooltip>
                                    <p class="truncate">{{ listing.notes }}</p>

                                    <template #popper>
                                        {{ listing.notes }}
                                    </template>
                                </Tooltip>
                            </td>

                            <td class="flex flex-nowrap gap-1">
                                <Link
                                    :href="route('listings.edit', { listing })"
                                    class="w-fit rounded-md bg-amber-300 px-2 py-1 text-amber-900 hover:bg-amber-400"
                                >
                                    <PencilSquareIcon class="size-5" />
                                </Link>

                                <Link
                                    :href="route('listings.destroy', { listing })"
                                    class="w-fit rounded-md bg-red-300 px-2 py-1 text-red-900 hover:bg-red-400"
                                >
                                    <TrashIcon class="size-5" />
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="px-3">
                    <Pagination class="mt-0" :data="listings" />
                </div>
            </div>
        </div>
    </LayoutMain>
</template>
