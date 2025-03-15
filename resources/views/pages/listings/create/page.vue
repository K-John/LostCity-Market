<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import _ from "lodash";

const props = defineProps<Pages.ListingsCreatePage>();

const listingTypes = computed((): Enums.ListingType[] => ["buy", "sell"]);

const auth = useAuth();
const form = useForm({
    ...props.listingForm,
});
</script>

<template>
    <LayoutMain>
        <Head title="Create Listing" />

        <UsernamesAlert v-if="auth && !listingForm?.usernames?.length" />

        <ListingForm
            :listing-form="form"
            :submit-route="route('listings.store')"
            submit-method="post"
        />
    </LayoutMain>
</template>
