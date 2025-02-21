<script setup lang="ts">
defineProps<{
    listing: Data.Listing.ListingData;
}>();
</script>

<template>
    <td class="px-1 text-stone-400">
        <div class="d-flex">
            <Link :href="route('usernames.show', { user: listing.username })" class="whitespace-pre">
                {{
                    toDisplayName(listing.username)
                }}
            </Link>

            <Link v-if="!listing.userId" :href="route('tokens.show', { listing: listing })" class="align-super text-[x-small] text-white">
                #{{ listing.tokenPrefix }}
            </Link>
        </div>
    </td>
</template>
