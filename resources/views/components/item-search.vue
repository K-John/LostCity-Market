<script setup lang="ts">
const navigateToItem = (item: Data.Item.ItemData) => {
    router.get(route("items.show", { slug: item.slug }));
};
</script>

<template>
    <div
        class="mb-4 flex flex-row gap-4 border-2 border-[#382418] bg-black p-3"
    >
        <h2 class="flex items-center text-lg font-bold">Search for an item:</h2>

        <ItemSelect @item-selected="navigateToItem" />
    </div>
</template>
