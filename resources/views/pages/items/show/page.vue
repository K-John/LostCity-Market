<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { BookmarkSlashIcon, BookmarkIcon } from "@heroicons/vue/24/solid";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

const props = defineProps<Pages.ItemsShowPage>();

const listings = ref<Data.Listing.ListingData[]>([...props.listings.data]);

watch(
    () => props.listings.data,
    (newListings) => {
        listings.value = [...newListings];
    },
);

const listingTypes = computed((): Enums.ListingType[] => ["buy", "sell"]);

const auth = useAuth();
const form = useForm({
    ...props.listingForm,
});

const highlightedIds = ref<number[]>([]);

// Subscribe to the "listings" channel using Laravel Echo, filtering by the current item's id.
onMounted(() => {
    // const channel = window.Echo.channel("listings");

    // channel.listen("ListingEvent", (listingEvent: Data.Listing.ListingData) => {
    //     if (auth) {
    //         listingEvent.canManage =
    //             props.usernames?.includes(listingEvent.username) || false;
    //     }

    //     // If the listing event is not for the current item or the listing type doesn't match, ignore it.
    //     if (
    //         !listingEvent.item ||
    //         listingEvent.item.id !== props.item.id ||
    //         listingEvent.type !== props.listingType
    //     ) {
    //         return;
    //     }

    //     // If the listingEvent has a sold or deleted timestamp, remove the listing from the list if it exists.
    //     if (listingEvent.soldAt || listingEvent.deletedAt) {
    //         const index = listings.value.findIndex(
    //             (l) => l.id === listingEvent.id,
    //         );

    //         if (index !== -1) {
    //             listings.value.splice(index, 1);
    //         }
    //     } else {
    //         // Check if the listing already exists in the list.
    //         const index = listings.value.findIndex(
    //             (l) => l.id === listingEvent.id,
    //         );

    //         if (index === -1) {
    //             // If it doesn't exist, add it to the front of the list.
    //             listings.value.unshift(listingEvent);
    //             // Add the id to the highlightedIds to highlight the new listing.
    //             highlightedIds.value.push(listingEvent.id);

    //             // If the list is too long, remove the last listing.
    //             if (listings.value.length > 30) {
    //                 listings.value.pop();
    //             }
    //         } else {
    //             // If it exists, update the listing.
    //             const existingListing = listings.value[index];
    //             listings.value[index] = listingEvent;

    //             // If the updatedAt timestamp changed, move it to the front of the list.
    //             if (existingListing.updatedAt !== listingEvent.updatedAt) {
    //                 listings.value.splice(index, 1);
    //                 listings.value.unshift(listingEvent);
    //                 // Add the id to the highlightedIds to highlight the updated listing.
    //                 highlightedIds.value.push(listingEvent.id);
    //             }
    //         }
    //     }
    // });
});

// Cleanup the subscription when the component is unmounted.
onUnmounted(() => {
    //window.Echo.leaveChannel("listings");
});
</script>

<template>
    <LayoutMain>
        <Head :title="item.name" />

        <ItemSearch />

        <div class="flex flex-col gap-6">
            <div class="flex flex-row gap-x-4">
                <div
                    class="size-fit border-2 border-stone-600 bg-stone-800 p-1"
                >
                    <img
                        :src="`/img/items/${item.slug}.webp`"
                        :alt="`${item.name} Icon`"
                        width="32"
                        height="32"
                    />
                </div>

                <div class="flex grow flex-col gap-1 sm:grow-0">
                    <div class="flex items-end justify-between gap-4">
                        <h1 class="text-2xl font-bold">
                            {{ item.name }}
                        </h1>

                        <template v-if="auth">
                            <Tooltip>
                                <button
                                    v-if="!item.isFavorite"
                                    class="inline-flex justify-center rounded-sm bg-emerald-800 p-2 text-emerald-200 hover:bg-emerald-700 sm:p-1"
                                    @click="
                                        router.post(
                                            route('favorites.store', {
                                                item_id: item.id,
                                                preserveScroll: true,
                                            }),
                                        )
                                    "
                                >
                                    <BookmarkIcon
                                        class="size-5"
                                        aria-hidden="true"
                                    />
                                </button>

                                <button
                                    v-if="item.isFavorite"
                                    class="inline-flex justify-center rounded-sm bg-red-800 p-2 text-red-200 hover:bg-red-700 sm:p-1"
                                    @click="
                                        router.delete(
                                            route('favorites.destroy', {
                                                favorite: item.id,
                                                preserveScroll: true,
                                            }),
                                        )
                                    "
                                >
                                    <BookmarkSlashIcon
                                        class="size-5"
                                        aria-hidden="true"
                                    />
                                </button>

                                <template #popper>
                                    <template v-if="!item.isFavorite">
                                        Add to Favorites
                                    </template>

                                    <template v-else>
                                        Remove from Favorites
                                    </template>
                                </template>
                            </Tooltip>
                        </template>
                    </div>

                    <div class="flex flex-col gap-x-4 sm:flex-row">
                        <h2 class="font-bold">Prices:</h2>

                        <div class="flex flex-row gap-2 sm:gap-4">
                            <div>
                                <u>Gen. Store:</u>

                                <p>
                                    ~{{
                                        Math.floor(
                                            item.cost * 0.4,
                                        ).toLocaleString()
                                    }}GP
                                </p>
                            </div>

                            <div>
                                <u>High Alch:</u>

                                <p>
                                    {{
                                        Math.floor(
                                            item.cost * 0.6,
                                        ).toLocaleString()
                                    }}GP
                                </p>
                            </div>

                            <div>
                                <u>Low Alch:</u>

                                <p>
                                    {{
                                        Math.floor(
                                            item.cost * 0.4,
                                        ).toLocaleString()
                                    }}GP
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="relative z-10 mb-[-2px] flex flex-row">
                    <Link
                        :href="
                            route('items.show', {
                                item: item,
                                type: listingTypes[0],
                            })
                        "
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
                        :href="
                            route('items.show', {
                                item: item,
                                type: listingTypes[1],
                            })
                        "
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

                <ListingTable @mouseenter="highlightedIds = []">
                    <EmptyTableRow v-if="!props.listings.data.length" />

                    <ListingTableRow
                        v-for="l in listings.slice(0, 20)"
                        :key="l.id"
                        :listing="l"
                        :highlighted="highlightedIds.includes(l.id)"
                    >
                        <template #default="{ listing }">
                            <PriceTableData :listing="listing" />

                            <UsernameTableData :listing="listing" />

                            <TimestampTableData
                                :timestamp="listing.updatedAt"
                            />

                            <NoteTableData :listing="listing" />

                            <ActionTableData :listing="listing" />
                        </template>
                    </ListingTableRow>

                    <template v-if="props.listings.data.length" #footer>
                        <Pagination :data="props.listings" />
                    </template>
                </ListingTable>
            </div>

            <UsernamesAlert v-if="auth && !listingForm?.usernames?.length" />

            <ListingForm
                v-if="auth"
                :listing-form="form"
                :submit-route="route('listings.store')"
                submit-method="post"
            />

            <ListingTable class="border-zinc-900 bg-zinc-950">
                <template #header>
                    <h2 class="text-lg font-bold">Recently Sold:</h2>
                </template>

                <EmptyTableRow v-if="!soldListings.length" />

                <ListingTableRow
                    v-for="l in soldListings"
                    :key="l.id"
                    :listing="l"
                >
                    <template #default="{ listing }">
                        <PriceTableData :listing="listing" />

                        <UsernameTableData :listing="listing" />

                        <TimestampTableData
                            :timestamp="listing.soldAt || ''"
                            :use-color="false"
                        />
                    </template>
                </ListingTableRow>
            </ListingTable>
        </div>
    </LayoutMain>
</template>
