<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
const props = defineProps<Pages.ListingsEditPage>();

const auth = useAuth();
const form = useForm({
    ...props.listingForm,
});
</script>

<template>
    <LayoutMain>
        <Head title="Update Listing" />

        <UsernamesAlert v-if="auth && !listingForm?.usernames?.length" />

        <ListingForm
            :listing-form="form"
            :submit-route="route('listings.update', { listing: listingForm.id })"
            submit-method="patch">
        </ListingForm>
    </LayoutMain>
</template>
