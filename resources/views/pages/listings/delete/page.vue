<script setup lang="ts">
import { XMarkIcon, CheckIcon } from "@heroicons/vue/24/outline";

const props = defineProps<Pages.ListingsDeletePage>();

const typeVerb = computed(() => {
    return props.listing.type === "buy" ? "Bought" : "Sold";
});

const submit = (close: () => void) => {
    router.delete(route('listings.destroy', { listing: props.listing.id }), { preserveScroll: true });
    close();
};
</script>

<template>
    <BaseModal>
        <template #default="{ close }">
            <Head title="Remove Listing" />

            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-2">
                    <XMarkIcon class="size-7 text-red-500" />

                    <h1 class="text-xl font-semibold">Remove Listing</h1>
                </div>

                <p class="text-stone-300">
                    Are you sure you want to remove this listing? This action
                    cannot be undone.
                </p>

                <div v-if="listing.item" class="flex items-center gap-2">
                    <div class="w-fit border border-stone-700 bg-stone-800">
                        <img
                            :src="`/img/items/${listing.item.slug}.webp`"
                            :alt="`${listing.item.name} Icon`"
                            class="min-h-[24px] min-w-[24px]"
                            width="32"
                            height="32"
                        />
                    </div>

                    <span class="text-lg">{{ listing.item.name }}</span>
                </div>

                <div class="flex flex-wrap justify-end gap-3">
                    <BaseButton
                        type="button"
                        variant="danger"
                        class="flex items-center gap-1"
                        @click="submit(close)"
                    >
                        <XMarkIcon class="size-5" />

                        Remove
                    </BaseButton>

                    <BaseButton
                        type="button"
                        variant="success"
                        class="flex items-center gap-1"
                        @click="
                            router.visit(
                                route('listing.sale.store', { listing, redirect: redirect }),
                                { preserveScroll: true },
                            )
                        "
                    >
                        <CheckIcon class="size-5" />

                        Mark {{ typeVerb }}
                    </BaseButton>

                    <BaseButton
                        variant="secondary"
                        type="button"
                        @click="close()"
                    >
                        Cancel
                    </BaseButton>
                </div>
            </div>
        </template>
    </BaseModal>
</template>
