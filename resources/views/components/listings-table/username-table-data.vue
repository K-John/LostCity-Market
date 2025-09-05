<script setup lang="ts">
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";

defineProps<{
    listing: Data.Listing.ListingData;
}>();

const page = usePage();
const currentUrl = page.url.split("?")[0];
</script>

<template>
    <td class="text-stone-400 sm:px-1">
        <div class="d-flex">
            <Tooltip class="w-fit">
                <Link
                    :href="
                        route('usernames.show', {
                            username: listing.username,
                            back: currentUrl,
                        })
                    "
                    class="whitespace-pre text-sm sm:text-[medium]"
                    preserve-scroll
                >
                    {{ toDisplayName(listing.username) }}
                </Link>

                <template #popper>
                    <span class="whitespace-pre font-mono tracking-widest">{{
                        listing.username
                    }}</span>
                </template>
            </Tooltip>
        </div>
    </td>
</template>
