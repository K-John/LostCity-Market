<script setup lang="ts">
defineProps<{
    listing: Data.Listing.ListingData;
}>();
</script>

<template>
    <td class="text-stone-400 sm:px-1">
        <div class="d-flex">
            <Link
                :href="route('usernames.show', { username: listing.username })"
                class="whitespace-pre text-sm sm:text-[medium]"
            >
                {{ toDisplayName(listing.username) }}
            </Link>
        </div>
    </td>
</template>
