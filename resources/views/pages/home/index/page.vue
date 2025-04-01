<script setup lang="ts">
import {
    ArrowLeftEndOnRectangleIcon,
    BookmarkSlashIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps<Pages.HomeIndexPage>();
const listings = ref<Data.Listing.ListingData[]>([...props.listings.data]);

watch(
    () => props.listings.data,
    (newListings) => {
        listings.value = [...newListings];
    },
);

const auth = useAuth();

const tabTypes = computed((): Enums.HomeTabType[] => [
    "buy",
    "sell",
    "favorites",
]);
const favoritesListingTypes = computed((): Enums.FavoritesListingType[] => [
    "all",
    "buy",
    "sell",
]);

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
    //         ((props.tab === "buy" || props.tab === "sell") &&
    //             listingEvent.type !== props.tab) ||
    //         (props.tab === "favorites" &&
    //             (!props.favorites
    //                 ?.map((f) => f.id)
    //                 .includes(listingEvent.item.id) ||
    //                 (props.listingType !== "all" &&
    //                     listingEvent.type !== props.listingType)))
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
    //             const usernameCount = listings.value
    //                 .slice(0, 20)
    //                 .filter((l) => l.username === listingEvent.username).length;
    //             if (usernameCount < 3) {
    //                 listings.value.unshift(listingEvent);
    //                 // Add the id to the highlightedIds to highlight the new listing.
    //                 highlightedIds.value.push(listingEvent.id);
    //             }

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
    // window.Echo.leaveChannel("listings");
});
</script>

<template>
    <LayoutMain>
        <ItemSearch />

        <div class="relative z-10 mb-[-2px] flex flex-row">
            <Link
                :href="route('home', { tab: tabTypes[0] })"
                preserve-scroll
                class="px-4 py-3 font-bold"
                :class="{
                    'border-2 border-b-0 border-[#382418] bg-black':
                        props.tab === tabTypes[0],
                }"
            >
                <span class="sm:hidden">Buying</span>

                <span class="hidden sm:inline">Buy Listings</span>
            </Link>

            <Link
                :href="route('home', { tab: tabTypes[1] })"
                preserve-scroll
                class="px-4 py-3 font-bold"
                :class="{
                    'border-2 border-b-0 border-[#382418] bg-black':
                        props.tab === tabTypes[1],
                }"
            >
                <span class="sm:hidden">Selling</span>

                <span class="hidden sm:inline">Sell Listings</span>
            </Link>

            <Link
                :href="route('home', { tab: tabTypes[2] })"
                preserve-scroll
                class="px-4 py-3 font-bold"
                :class="{
                    'border-2 border-b-0 border-[#382418] bg-black':
                        props.tab === tabTypes[2],
                }"
            >
                Favorites
            </Link>
        </div>

        <ListingTable @mouseenter="highlightedIds = []">
            <template
                v-if="
                    auth &&
                    props.tab === 'favorites' &&
                    props.favorites &&
                    props.favorites.length > 0
                "
                #header
            >
                <div class="flex items-center justify-between gap-2">
                    <div class="flex gap-2">
                        <BaseButton
                            as="link"
                            variant="secondary"
                            class="!px-3"
                            :href="
                                route('home', {
                                    type: favoritesListingTypes[0],
                                })
                            "
                            :force-focus="
                                props.listingType === favoritesListingTypes[0]
                            "
                        >
                            All
                        </BaseButton>

                        <BaseButton
                            as="link"
                            variant="secondary"
                            :href="
                                route('home', {
                                    type: favoritesListingTypes[1],
                                })
                            "
                            :force-focus="
                                props.listingType === favoritesListingTypes[1]
                            "
                        >
                            Buy
                        </BaseButton>

                        <BaseButton
                            as="link"
                            variant="secondary"
                            :href="
                                route('home', {
                                    type: favoritesListingTypes[2],
                                })
                            "
                            :force-focus="
                                props.listingType === favoritesListingTypes[2]
                            "
                        >
                            Sell
                        </BaseButton>
                    </div>

                    <Link
                        :href="route('favorites.index')"
                        preserve-scroll
                        class="text-[#90c040] hover:underline"
                        >Manage Favorites
                    </Link>
                </div>
            </template>

            <td
                v-if="!auth && props.tab === 'favorites'"
                class="flex flex-col items-center justify-center gap-2 px-2 py-6 sm:flex-row"
            >
                <ArrowLeftEndOnRectangleIcon class="size-12 text-stone-500" />

                <div class="text-center sm:text-left">
                    <p class="text-lg font-semibold">
                        You must be signed in to use this feature.
                    </p>

                    <Link
                        :href="route('login')"
                        class="text-[#90c040] hover:underline"
                    >
                        Sign in here
                    </Link>
                </div>
            </td>

            <td
                v-else-if="
                    auth &&
                    props.tab === 'favorites' &&
                    (props.favorites === null || !props.favorites.length)
                "
                class="flex flex-col items-center justify-center gap-2 px-2 py-6 sm:flex-row"
            >
                <BookmarkSlashIcon class="size-12 text-stone-500" />

                <div class="text-center sm:text-left">
                    <p class="text-lg font-semibold">
                        You have no favorite items.
                    </p>

                    <p class="text-stone-300">
                        Add items to your favorites by clicking the bookmark
                        icon on a listing.
                    </p>
                </div>
            </td>

            <EmptyTableRow v-else-if="!props.listings.data.length" />

            <ListingTableRow
                v-for="l in listings.slice(0, 20)"
                :key="l.id"
                :listing="l"
                :highlighted="highlightedIds.includes(l.id)"
            >
                <template #default="{ listing }">
                    <ItemTableData :item="listing.item" />

                    <PriceTableData :listing="listing" />

                    <UsernameTableData :listing="listing" />

                    <TimestampTableData
                        :timestamp="listing.updatedAt"
                        :use-color="props.tab === 'favorites'"
                    />

                    <NoteTableData :listing="listing" />

                    <ActionTableData :listing="listing" />
                </template>
            </ListingTableRow>

            <template v-if="props.listings.data.length" #footer>
                <Pagination :data="props.listings" />
            </template>
        </ListingTable>
    </LayoutMain>
</template>
