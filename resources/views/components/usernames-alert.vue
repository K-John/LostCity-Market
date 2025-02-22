<script setup lang="ts">
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";

const auth = useAuth();
</script>

<template>
    <Alert type="error" class="gap-3">
        <h2 class="flex gap-2 font-bold">
            <ExclamationTriangleIcon class="size-6 text-red-300" />

            You Have No Usernames Linked
        </h2>

        <p>
            You are successfully logged in with Discord, but you have no Lost
            City accounts imported. You can still post listings, but your
            usernames will not be reserved. This is likely one of two reasons:
        </p>

        <hr class="border-red-700" />

        <ul class="flex list-inside list-decimal flex-col gap-3">
            <li>
                You have not linked your Lost City account(s) with Discord. You
                can find instructions for adopting accounts to Discord by
                <Link
                    :href="route('docs.adopt')"
                    class="text-[#90c040] hover:underline"
                    >clicking here</Link
                >.
            </li>

            <li>
                There was an error retrieving your Lost City accounts. You can
                try to retrieve them again by
                <button
                    type="button"
                    class="text-[#90c040] hover:underline"
                    @click="
                        router.patch(
                            route('usernames.update', {
                                user: auth.email,
                                preserveScroll: true,
                            }),
                        )
                    "
                >
                    clicking here</button
                >.
            </li>
        </ul>
    </Alert>
</template>
