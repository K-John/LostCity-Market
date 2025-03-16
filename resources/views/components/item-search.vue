<script setup lang="ts">
const navigateToItem = (item: Data.Item.ItemData) => {
    router.get(route("items.show", { slug: item.slug }));
};
</script>

<template>
    <div
        class="mb-4 flex flex-col gap-4 border-2 border-stone-800 bg-stone-950 p-3 sm:flex-row"
    >
        <h2 class="flex items-center text-lg font-bold">Search for an item:</h2>

        <ItemSelect @item-selected="navigateToItem" />
    </div>
</template>
