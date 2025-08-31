<script setup lang="ts">
import { router, Head } from "@inertiajs/vue3";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import {
    ArrowTrendingUpIcon,
    ClockIcon,
    PauseIcon,
    PlayIcon,
} from "@heroicons/vue/24/outline/index.js";
import LayoutAccount from "@/views/layouts/account/layout-account.vue";

const props = defineProps<Pages.ListingsIndexPage>();

const auth = useAuth();

const canBumpListings = computed(() =>
    props.listings.data.some(
        (listing) =>
            new Date(listing.updatedAt) < new Date(Date.now() - 30 * 60 * 1000),
    ),
);
</script>

<template>
    <LayoutAccount>
        <Head title="My Listings" />

        <div
            class="mb-4 flex flex-col justify-between gap-3 md:flex-row md:items-center"
        >
            <h1 class="text-2xl font-semibold">Active Listings</h1>

            <div class="flex gap-2">
                <Tooltip>
                    <BaseButton
                        variant="custom"
                        :class="
                            [
                                'flex h-fit items-center gap-1 bg-stone-800 text-amber-400',
                                !canBumpListings
                                    ? 'cursor-not-allowed opacity-50'
                                    : 'hover:bg-stone-900',
                            ].join(' ')
                        "
                        :disabled="!canBumpListings"
                        @click="
                            router.patch(route('listings.bump'), {
                                preserveScroll: true,
                            })
                        "
                    >
                        <ArrowTrendingUpIcon class="size-5" /> Bump All
                    </BaseButton>

                    <template #popper>
                        <template v-if="!canBumpListings">
                            You have no listings eligible for bumping
                        </template>

                        <template v-else>
                            Listings can be bumped every 30 mins
                        </template>
                    </template>
                </Tooltip>

                <Tooltip>
                    <BaseButton
                        variant="custom"
                        :class="
                            [
                                'flex h-fit items-center gap-1 bg-stone-800 text-rose-500',
                                !listings.data.length
                                    ? 'cursor-not-allowed opacity-50'
                                    : 'hover:bg-stone-900',
                            ].join(' ')
                        "
                        :disabled="!listings.data.length"
                        @click="
                            router.post(route('listings.pause.store'), {
                                preserveScroll: true,
                            })
                        "
                    >
                        <PauseIcon class="size-5" /> Pause All
                    </BaseButton>

                    <template #popper>
                        <template v-if="!listings.data.length">
                            You have no listings to pause
                        </template>

                        <template v-else> Pause all active listings </template>
                    </template>
                </Tooltip>
            </div>
        </div>

        <UsernamesAlert v-if="auth && !usernames?.length" />

        <ListingTable class="mb-4 pt-2">
            <EmptyTableRow v-if="!props.listings.data.length" />

            <ListingTableRow
                v-for="l in listings.data"
                :key="l.id"
                :listing="l"
            >
                <template #default="{ listing }">
                    <ItemTableData :item="listing.item" />

                    <PriceTableData :listing="listing" />

                    <UsernameTableData :listing="listing" />

                    <TimestampTableData :timestamp="listing.updatedAt" />

                    <NoteTableData :listing="listing" />

                    <ActionTableData :listing="listing" />
                </template>
            </ListingTableRow>

            <template v-if="listings.data.length" #footer>
                <Pagination :data="listings" />
            </template>
        </ListingTable>

        <hr class="mb-5 mt-6 border-t-2 border-stone-700" />

        <div
            class="mb-4 flex flex-col justify-between gap-3 md:flex-row md:items-center"
        >
            <div class="flex gap-2">
                <PauseIcon class="size-7 shrink-0 text-rose-500" />

                <div>
                    <h2 class="text-xl font-semibold leading-none">
                        Paused Listings
                    </h2>

                    <p class="text-stone-300">
                        You can pause a listing if you're logging off of the
                        game to hide it from the market.
                    </p>
                </div>
            </div>

            <Tooltip>
                <BaseButton
                    variant="custom"
                    :class="
                        [
                            'flex h-fit items-center gap-1 bg-stone-800 text-emerald-500',
                            !pausedListings.length
                                ? 'cursor-not-allowed opacity-50'
                                : 'hover:bg-stone-900',
                        ].join(' ')
                    "
                    @click="
                        router.post(route('listings.pause.destroy'), {
                            preserveScroll: true,
                        })
                    "
                >
                    <PlayIcon class="size-5" /> Unpause All
                </BaseButton>

                <template #popper>
                    <template v-if="!pausedListings.length">
                        You have no listings to unpause
                    </template>

                    <template v-else>
                        Reactivate all listings that are paused
                    </template>
                </template>
            </Tooltip>
        </div>

        <ListingTable class="border-rose-950 bg-stone-950">
            <EmptyTableRow v-if="!pausedListings.length" />

            <ListingTableRow
                v-for="l in pausedListings"
                :key="l.id"
                :listing="l"
            >
                <template #default="{ listing }">
                    <ItemTableData :item="listing.item" />

                    <PriceTableData :listing="listing" />

                    <UsernameTableData :listing="listing" />

                    <TimestampTableData :timestamp="listing.updatedAt" />

                    <NoteTableData :listing="listing" />

                    <ActionTableData :listing="listing" />
                </template>
            </ListingTableRow>
        </ListingTable>

        <hr class="mb-5 mt-6 border-t-2 border-stone-700" />

        <div class="mb-4 flex gap-2">
            <ClockIcon class="size-7 text-amber-400" />

            <div>
                <h2 class="text-xl font-semibold leading-none">
                    Recently Expired
                </h2>

                <p class="text-stone-300">
                    If you'd like to re-activate a listing, you can bump or edit
                    it's information. Expired listings show up here for up to a week.
                </p>
            </div>
        </div>

        <ListingTable class="border-yellow-900 bg-stone-950">
            <EmptyTableRow v-if="!expiredListings.length" />

            <ListingTableRow
                v-for="l in expiredListings"
                :key="l.id"
                :listing="l"
            >
                <template #default="{ listing }">
                    <ItemTableData :item="listing.item" />

                    <PriceTableData :listing="listing" />

                    <UsernameTableData :listing="listing" />

                    <TimestampTableData :timestamp="listing.updatedAt" />

                    <NoteTableData :listing="listing" />

                    <ActionTableData :listing="listing" />
                </template>
            </ListingTableRow>
        </ListingTable>
    </LayoutAccount>
</template>
