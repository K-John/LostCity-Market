<script setup lang="ts">
import { usePoll } from "@inertiajs/vue3";

const props = defineProps<Pages.HomeIndexPage>();
const listingTypes = computed((): Enums.ListingType[] => ["buy", "sell"]);

usePoll(30_000);

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

        <ListingTable @mouseenter="highlightedIds = []">
            <EmptyTableRow v-if="!props.listings.data.length" />

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

                    <TimestampTableData :timestamp="listing.updatedAt" />
                    
                    <NoteTableData :listing="listing" />
                </template>
            </ListingTableRow>

            <template v-if="listings.data.length" #footer>
                <Pagination :data="listings" />
            </template>
        </ListingTable>
    </LayoutMain>
</template>
