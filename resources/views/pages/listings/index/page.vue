<script setup lang="ts">
import { router, Head } from "@inertiajs/vue3";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import { ArrowTrendingUpIcon, ClockIcon } from "@heroicons/vue/24/outline/index.js";
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

        <div class="flex justify-between">
            <h1 class="mb-4 text-2xl font-semibold">Active Listings</h1>

            <Tooltip>
                <button
                    type="button"
                    class="flex items-center gap-2 rounded-sm bg-stone-800 px-2 py-1 text-amber-400"
                    :class="{
                        'hover:bg-stone-900': canBumpListings,
                        'cursor-not-allowed opacity-50': !canBumpListings,
                    }"
                    :disabled="!canBumpListings"
                    @click="
                        router.patch(route('listings.bump'), {
                            preserveScroll: true,
                        })
                    "
                >
                    <ArrowTrendingUpIcon class="size-5" /> Bump All
                </button>

                <template #popper>
                    <template v-if="!canBumpListings">
                        You have no listings eligible for bumping
                    </template>

                    <template v-else>
                        Listings can be bumped every 30 mins
                    </template>
                </template>
            </Tooltip>
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

        <div class="mb-4 flex gap-2">
            <ClockIcon class="size-7 text-amber-400" />

            <div>
                <h2 class="text-xl font-semibold leading-none">Recently Expired</h2>

                <p class="text-stone-300">
                    If you'd like to re-activate a listing, you can bump or edit
                    it's information.
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
