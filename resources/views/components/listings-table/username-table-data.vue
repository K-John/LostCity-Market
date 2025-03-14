<script setup lang="ts">
defineProps<{
    listing: Data.Listing.ListingData;
}>();
</script>

<template>
    <td class="text-stone-400 sm:px-1">
        <div class="d-flex">
            <Link :href="route('usernames.show', { user: listing.username })" class="whitespace-pre text-sm sm:text-[medium]">
                {{
                    toDisplayName(listing.username)
                }}
            </Link>

            <Link v-if="!listing.userId" :href="route('tokens.show', { listing: listing })" class="hidden align-super text-[x-small] text-white sm:inline">
                #{{ listing.tokenPrefix }}
            </Link>
        </div>
    </td>
</template>
