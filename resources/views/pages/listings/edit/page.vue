<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { PencilIcon } from "@heroicons/vue/24/outline";

const props = defineProps<Pages.ListingsEditPage>();

const auth = useAuth();
const form = useForm({
    ...props.listingForm,
});

const back = () => {
    window.history.back();
};
</script>

<template>
    <BaseModal>
        <template #default="{ close }">
            <Head title="Update Listing" />

            <UsernamesAlert v-if="auth && !listingForm?.usernames?.length" />

            <div class="flex items-center gap-2">
                <PencilIcon class="size-7 text-amber-500" />

                <h1 class="text-xl font-semibold">Update Listing</h1>
            </div>

            <p class="text-stone-300">
                Update the listing details below and submit the form.
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

            <ListingForm
                :listing-form="form"
                :submit-route="
                    route('listings.update', { listing: listingForm.id })
                "
                submit-method="patch"
                footer-outside
                @success="close()"
            >
                <template #header></template>

                <template #footer>
                    <div class="flex flex-wrap justify-end gap-3">
                        <BaseButton type="submit" variant="success">
                            Submit
                        </BaseButton>

                        <BaseButton
                            type="button"
                            variant="secondary"
                            @click="close()"
                        >
                            Cancel
                        </BaseButton>
                    </div>
                </template>
            </ListingForm>
        </template>
    </BaseModal>
</template>
