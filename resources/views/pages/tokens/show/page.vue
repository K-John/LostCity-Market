<script setup lang="ts">
const props = defineProps<Pages.TokensShowPage>();
</script>

<template>
    <LayoutMain>
        <Head :title="`${token.substring(0, 4)}'s Listings'`" />

        <ListingTable>
            <EmptyTableRow v-if="!props.listings.data.length" />

            <template #header>
                <h2 class="text-lg font-bold">
                    <span class="text-stone-500">{{ token }}</span
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
                </template>
            </ListingTableRow>

            <template v-if="listings.data.length" #footer>
                <Pagination :data="listings" />
            </template>
        </ListingTable>
    </LayoutMain>
</template>
