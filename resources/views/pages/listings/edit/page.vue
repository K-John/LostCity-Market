<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { ArrowLeftIcon } from "@heroicons/vue/24/outline";

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
    <LayoutMain>
        <Head title="Update Listing" />

        <UsernamesAlert v-if="auth && !listingForm?.usernames?.length" />

        <button
            type="button"
            class="mb-4 flex w-fit items-center gap-1 rounded-sm bg-stone-800 px-3 py-1 text-white hover:bg-stone-700"
            @click="back()"
        >
            <ArrowLeftIcon class="size-5" />
            Back
        </button>

        <ListingForm
            :listing-form="form"
            :submit-route="
                route('listings.update', { listing: listingForm.id })
            "
            submit-method="patch"
        >
        </ListingForm>
    </LayoutMain>
</template>
