<script setup lang="ts">
defineProps<{
    listing: Data.Listing.ListingData;
    highlighted?: boolean;
}>();
</script>

<template>
    <tr
        :class="{
            'animate-pulse bg-green-950': highlighted,
            'text-stone-500 line-through': listing.soldAt,
        }"
    >
        <slot :listing="listing"></slot>
    </tr>
</template>
