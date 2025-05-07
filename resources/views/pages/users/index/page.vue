<script setup lang="ts">
import { XMarkIcon, ArrowUturnLeftIcon } from "@heroicons/vue/24/outline";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

const props = defineProps<Pages.UsersIndexPage>();
const auth = useAuth();

const back = () => {
    window.history.back();
};
</script>

<template>
    <BaseModal>
        <template #default="{ close }">
            <Head :title="`${toDisplayName(username)}'s Listings'`" />

            <div class="flex justify-between">
                <div>
                    <h1 class="text-xl font-semibold">
                        <span class="whitespace-pre text-stone-500">{{
                            toDisplayName(username)
                        }}</span
                        >'s Listings
                    </h1>

                    <Tooltip class="w-fit">
                        <p
                            class="whitespace-pre font-mono text-sm tracking-widest text-stone-300"
                        >
                            ({{ username }})
                        </p>

                        <template #popper> Unformatted username </template>
                    </Tooltip>
                </div>

                <template v-if="auth?.is_admin && !is_banned">
                    <button
                        type="button"
                        class="flex h-fit items-center gap-2 rounded-sm bg-stone-800 px-2 py-1 text-red-500 hover:bg-stone-900"
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
                        class="flex h-fit items-center gap-2 rounded-sm bg-stone-800 px-2 py-1 text-green-500 hover:bg-stone-900"
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

            <ListingTable class="border-none bg-transparent p-0">
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

            <div class="flex items-center justify-end gap-4">
                <BaseButton variant="secondary" type="button" @click="close">
                    Cancel
                </BaseButton>
            </div>
        </template>
    </BaseModal>
</template>
