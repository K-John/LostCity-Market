<script setup lang="ts">
import { router, Head } from "@inertiajs/vue3";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import { ArrowTrendingUpIcon } from "@heroicons/vue/24/outline/index.js";

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
    <LayoutMain>
        <Head title="My Listings" />

        <UsernamesAlert v-if="auth && !usernames?.length" />

        <Alert v-if="auth">
            <div class="flex items-center gap-2">
                <h2 class="font-bold text-stone-200">My Usernames:</h2>

                <div v-if="usernames.length" class="flex flex-wrap">
                    <span
                        v-for="(username, index) in usernames"
                        :key="username"
                        class="whitespace-pre text-stone-300"
                    >
                        {{ toDisplayName(username)
                        }}<span v-if="index < usernames.length - 1">, </span>
                    </span>
                </div>

                <span v-else class="text-stone-400">None</span>
            </div>

            <hr class="border-stone-700" />

            <p class="text-sm text-stone-400">
                Usernames not accurate?
                <button
                    type="button"
                    class="text-[#90c040] hover:underline"
                    @click="
                        router.patch(
                            route('usernames.update', {
                                user: auth.email,
                                preserveScroll: true,
                            }),
                        )
                    "
                >
                    Click here
                </button>
                to get your latest usernames from Lost City.
            </p>
        </Alert>

        <ListingTable class="mb-4">
            <template #header>
                <div class="flex justify-between">
                    <h2 class="text-lg font-bold">My Listings:</h2>

                    <Tooltip>
                        <button
                            type="button"
                            class="flex items-center gap-2 rounded-sm bg-stone-800 px-2 py-1 text-amber-400"
                            :class="{
                                'hover:bg-stone-900': canBumpListings,
                                'cursor-not-allowed opacity-50':
                                    !canBumpListings,
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
            </template>

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

        <ListingTable class="border-yellow-800 bg-stone-950">
            <template #header>
                <h2 class="text-lg font-bold">Recently Expired:</h2>
                
                <p>If you'd like to re-active a listing, you can bump or edit it's information.</p>
            </template>

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
    </LayoutMain>
</template>
