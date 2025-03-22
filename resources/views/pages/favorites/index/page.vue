<script setup lang="ts">
import { XMarkIcon } from "@heroicons/vue/24/outline";
const props = defineProps<Pages.FavoritesIndexPage>();
</script>

<template>
    <LayoutMain>
        <Head title="My Favorites" />

        <ListingTable>
            <template #header>
                <h1 class="text-lg font-bold">My Favorites:</h1>
            </template>

            <tr v-for="item in props.items.data" :key="item.id">
                <ItemTableData :item="item" />

                <td>
                    <button
                        class="text-red-500 hover:text-red-700"
                        @click="
                            router.delete(
                                route('favorites.destroy', {
                                    favorite: item.id,
                                    preserveScroll: true,
                                }),
                            )
                        "
                    >
                        Remove
                    </button>
                </td>
            </tr>

            <tr v-if="!items.data.length">
                <td class="text-center" colspan="2">No favorites found.</td>
            </tr>

            <template v-if="items.data.length" #footer>
                <Pagination :data="items" />
            </template>
        </ListingTable>
    </LayoutMain>
</template>
