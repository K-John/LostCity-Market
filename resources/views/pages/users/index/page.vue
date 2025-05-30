<script setup lang="ts">
import {
    XMarkIcon,
    ArrowUturnLeftIcon,
    ArrowLeftIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps<Pages.UsersIndexPage>();
const auth = useAuth();

const back = () => {
    window.history.back();
};
</script>

<template>
    <LayoutMain>
        <Head :title="`${toDisplayName(username)}'s Listings'`" />

        <button
            type="button"
            class="mb-4 flex w-fit items-center gap-1 rounded-sm bg-stone-800 px-3 py-1 text-white hover:bg-stone-700"
            @click="back()"
        >
            <ArrowLeftIcon class="size-5" />
            Back
        </button>

        <ListingTable>
            <EmptyTableRow v-if="!props.listings.data.length" />

            <template #header>
                <div class="flex justify-between">
                    <h2 class="text-lg font-bold">
                        <span class="whitespace-pre text-stone-500">{{
                            toDisplayName(username)
                        }}</span
                        >'s listings
                    </h2>

                    <template v-if="auth?.is_admin && !is_banned">
                        <button
                            type="button"
                            class="flex items-center gap-2 rounded-sm bg-stone-800 px-2 py-1 text-red-500 hover:bg-stone-900"
                            @click="
                                router.post(
                                    route('ban.store', { username: username }),
                                    { preserveScroll: true },
                                )
                            "
                        >
                            <XMarkIcon class="size-5" /> Ban User
                        </button>
                    </template>

                    <template v-if="auth?.is_admin && is_banned">
                        <button
                            type="button"
                            class="flex items-center gap-2 rounded-sm bg-stone-800 px-2 py-1 text-green-500 hover:bg-stone-900"
                            @click="
                                router.delete(
                                    route('ban.destroy', {
                                        username,
                                    }),
                                    { preserveScroll: true },
                                )
                            "
                        >
                            <ArrowUturnLeftIcon class="size-5" /> Unban User
                        </button>
                    </template>
                </div>
            </template>

            <ListingTableRow
                v-for="l in listings.data"
                :key="l.id"
                :listing="l"
            >
                <template #default="{ listing }">
                    <ItemTableData :item="listing.item" />

                    <PriceTableData :listing="listing" />

                    <TimestampTableData :timestamp="listing.updatedAt" />

                    <NoteTableData :listing="listing" />

                    <ActionTableData :listing="listing" />
                </template>
            </ListingTableRow>

            <template v-if="listings.data.length" #footer>
                <Pagination :data="listings" />
            </template>
        </ListingTable>
    </LayoutMain>
</template>
