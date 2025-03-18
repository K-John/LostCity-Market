<script setup lang="ts">
const props = defineProps<Pages.UsersIndexPage>();
</script>

<template>
    <LayoutMain>
        <Head :title="`${toDisplayName(username)}'s Listings'`" />

        <ListingTable>
            <EmptyTableRow v-if="!props.listings.data.length" />

            <template #header>
                <h2 class="text-lg font-bold">
                    <span class="whitespace-pre text-stone-500">{{
                        toDisplayName(username)
                    }}</span
                    >'s listings
                </h2>
            </template>

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
    </LayoutMain>
</template>
