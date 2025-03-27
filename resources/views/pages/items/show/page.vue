<script setup lang="ts">
import { Head, usePoll } from "@inertiajs/vue3";
import { BookmarkSlashIcon, BookmarkIcon } from "@heroicons/vue/24/solid";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

const props = defineProps<Pages.ItemsShowPage>();

const listingTypes = computed((): Enums.ListingType[] => ["buy", "sell"]);

const auth = useAuth();
const form = useForm({
    ...props.listingForm,
});

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
                    <div
                        class="flex items-end justify-between gap-4"
                    >
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
                        v-for="l in listings.data"
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

                    <template v-if="listings.data.length" #footer>
                        <Pagination :data="listings" />
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
