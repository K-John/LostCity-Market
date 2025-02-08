<script setup lang="ts">
defineProps<{
    listing: Data.Listing.ListingData;
}>();
</script>

<template>
    <td class="px-1 text-stone-400">
        <div class="d-flex">
            <Link :href="route('users', { username: listing.username })">
                {{
                    listing.username
                        .split(" ")
                        .map(
                            (word) =>
                                word.charAt(0).toUpperCase() +
                                word.slice(1).toLowerCase(),
                        )
                        .join(" ")
                }}
            </Link>

            <Link :href="route('tokens.show', { listing: listing })" class="align-super text-[x-small] text-white">
                #{{ listing.tokenPrefix }}
            </Link>
        </div>
    </td>
</template>
