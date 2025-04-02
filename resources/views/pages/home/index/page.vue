<script setup lang="ts">
import {
    ArrowLeftEndOnRectangleIcon,
    BookmarkSlashIcon,
} from "@heroicons/vue/24/outline";
import { usePoll } from "@inertiajs/vue3";

const props = defineProps<Pages.HomeIndexPage>();

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

// Disabled until I can upgrade server: usePoll(30_000);

const mostRecentUpdateDate = computed(() => {
    if (!props.listings.data.length) return null;

    return props.listings.data.reduce((latest, listing) => {
        const listingDate = new Date(listing.updatedAt);
        return listing.updatedAt && listingDate > new Date(latest)
            ? listing.updatedAt
            : latest;
    }, props.listings.data[0].updatedAt);
});

watch(mostRecentUpdateDate, (newValue, oldValue) => {
    if (newValue !== oldValue) {
        const newHighlightIds = props.listings.data
            .filter(
                (listing) =>
                    listing.updatedAt &&
                    new Date(listing.updatedAt) > new Date(oldValue as string),
            )
            .map((listing) => listing.id);

        highlightedIds.value.push(...newHighlightIds);
    }
});

const highlightedIds = ref<number[]>([]);
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
                v-for="l in listings.data"
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
