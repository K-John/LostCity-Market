<script setup lang="ts">
import { InformationCircleIcon, PlusIcon } from "@heroicons/vue/24/outline";
const props = defineProps<Pages.FavoritesIndexPage>();

const selectedItem = ref<Data.Item.ItemData | null>(null);

watch(selectedItem, (item) => {
    if (item) {
        router.post(
            route("favorites.store", {
                item_id: item.id,
                preserveScroll: true,
            }),
        );
        selectedItem.value = null;
    }
});
</script>

<template>
    <LayoutAccount>
        <Head title="Favorite Items" />

        <h1 class="mb-4 text-2xl font-semibold">Favorite Items</h1>

        <Alert type="info">
            <div class="flex items-center gap-2">
                <InformationCircleIcon class="size-6 shrink-0 text-sky-200" />

                <p>
                    View listings of your favorite items by navigating to the
                    <Link
                        href="/?tab=favorites"
                        class="text-[#90c040] hover:underline"
                        >Favorites tab in the Main Menu</Link
                    >.
                </p>
            </div>
        </Alert>

        <ListingTable class="pt-2">
            <tr v-for="item in props.items.data" :key="item.id">
                <ItemTableData :item="item" />

                <td>
                    {{ item.name }}
                </td>

                <td>
                    <BaseButton
                        as="button"
                        variant="danger"
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
                    </BaseButton>
                </td>
            </tr>

            <tr v-if="!items.data.length">
                <td class="text-center" colspan="2">No favorites found.</td>
            </tr>

            <template v-if="items.data.length" #footer>
                <Pagination :data="items" />
            </template>
        </ListingTable>

        <hr class="mb-5 mt-6 border-t-2 border-stone-700" />

        <div class="mb-4 flex gap-2">
            <PlusIcon class="size-7 text-green-400" />

            <div>
                <h2 class="text-xl font-semibold leading-none">
                    Add Favorite Items
                </h2>

                <p class="text-stone-300">
                    You can add favorite items here or directly from an item's page.
                </p>
            </div>
        </div>

        <Alert class="mb-6">
            <div class="flex flex-col gap-4 sm:flex-row">
                <h3 class="flex items-center text-lg font-bold">
                    Search for an item:
                </h3>

                <ItemSelect v-model="selectedItem" />
            </div>
        </Alert>
    </LayoutAccount>
</template>
