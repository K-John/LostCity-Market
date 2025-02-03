<script setup lang="ts">
import VueSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import _ from "lodash";
import {
    PencilSquareIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline/index.js";

const props = defineProps<Pages.HomeIndexPage>();
const listingTypes = computed((): Enums.ListingType[] => ["buy", "sell"]);

const selected = ref<Data.Item.ItemData | null>(null);
const options = ref<Data.Item.ItemData[]>([]);
const loading = ref(false);

const onSearch = (searchTerm: string, loading: (state: boolean) => void) => {
    if (searchTerm.length > 2) {
        loading(true);
        search(searchTerm, loading);
    }
};

const search = _.debounce(
    (search: string, loading: (state: boolean) => void) => {
        fetch(`${route("items.index")}?q=${search}`)
            .then((response) => response.json())
            .then((data) => {
                loading(false);
                options.value = data;
            });
    },
    200,
);

watch(selected, (value) => {
    if (value) {
        loading.value = true;
        router.get(route("items.show", { item: value.slug }));
    }
});

const destroy = (id: number) => {
    router.delete(route("listings.destroy", { listing: id }), {
        preserveScroll: true,
    });
};
</script>

<template>
    <LayoutMain>
        <ItemSearch />

        <div class="relative z-10 mb-[-2px] flex flex-row">
            <Link
                :href="route('home', { type: listingTypes[0] })"
                preserve-scroll
                class="px-4 py-3 font-bold"
                :class="{
                    'border-2 border-b-0 border-[#382418] bg-black':
                        props.listingType === listingTypes[0],
                }"
            >
                Buy Listings
            </Link>

            <Link
                :href="route('home', { type: listingTypes[1] })"
                preserve-scroll
                class="px-4 py-3 font-bold"
                :class="{
                    'border-2 border-b-0 border-[#382418] bg-black':
                        props.listingType === listingTypes[1],
                }"
            >
                Sell Listings
            </Link>
        </div>

        <div class="flex flex-col border-2 border-[#382418] bg-black">
            <table class="border-separate border-spacing-2">
                <tbody>
                    <tr v-if="!listings.data.length">
                        <td class="text-center" colspan="4">
                            No listings found.
                        </td>
                    </tr>

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

                        <td class="text-stone-400">
                            {{
                                listing.username
                                    .split(" ")
                                    .map(
                                        (word) =>
                                            word.charAt(0).toUpperCase() +
                                            word.slice(1).toLowerCase(),
                                    )
                                    .join(" ")
                            }}
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
                    </tr>
                </tbody>
            </table>

            <div v-if="listings.data.length" class="px-3">
                <Pagination :data="listings" />
            </div>
        </div>
    </LayoutMain>
</template>
