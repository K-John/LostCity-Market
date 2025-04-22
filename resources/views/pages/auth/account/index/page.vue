<script setup lang="ts">
import { register } from 'module';

const props = defineProps<Pages.Auth.AccountIndexPage>();

const auth = useAuth();
</script>

<template>
    <LayoutAccount>
        <Head title="Account Info" />

        <h1 class="mb-4 text-2xl font-semibold">Account Info</h1>

        <UsernamesAlert v-if="auth && !usernames?.length" />

        <div
            class="grid grid-cols-1 gap-4 border-2 border-[#382418] bg-black p-4 sm:grid-cols-[auto_1fr]"
        >
            <div class="col-span-full grid grid-cols-subgrid gap-x-4 gap-y-2">
                <h2
                    class="size-fit border-b-2 border-stone-700 pb-1 text-lg font-semibold text-stone-300"
                >
                    Discord Name
                </h2>

                <div>
                    <p>{{ name }}</p>
                </div>
            </div>

            <hr class="col-span-full border-stone-900" />

            <div class="col-span-full grid grid-cols-subgrid gap-x-4 gap-y-2">
                <h2
                    class="size-fit border-b-2 border-stone-700 pb-1 text-lg font-semibold text-stone-300"
                >
                    Username(s)
                </h2>

                <div class="flex flex-col gap-3">
                    <ul v-if="usernames.length" class="">
                        <li
                            v-for="username in usernames"
                            :key="username"
                            class="whitespace-pre text-stone-300"
                        >
                            {{ toDisplayName(username) }}
                        </li>
                    </ul>

                    <span v-else class="text-stone-400">None</span>

                    <hr class="border-stone-700" />

                    <Alert>
                        <p class="text-sm">
                            Usernames not accurate?
                            <button
                                type="button"
                                class="text-[#90c040] hover:underline"
                                @click="
                                    router.patch(
                                        route('usernames.update', {
                                            preserveScroll: true,
                                        }),
                                    )
                                "
                            >
                                Click here
                            </button>
                            to get your latest usernames from Lost City.
                        </p>
                    </Alert>
                </div>
            </div>

            <hr class="col-span-full border-stone-900" />

            <div class="col-span-full grid grid-cols-subgrid gap-x-4 gap-y-2">
                <div class="flex flex-col gap-1">
                    <h2 class="size-fit border-b-2 border-stone-700 pb-1 text-lg font-semibold text-stone-300">
                        Date Registered
                    </h2>

                    <p class="text-sm text-stone-400">
                        (to the markets, not the game)
                    </p>
                </div>

                <div>
                    <p>{{ formatTime(register_date) }}</p>
                </div>
            </div>

            <hr class="col-span-full border-stone-900" />

            <div class="col-span-full grid grid-cols-subgrid gap-x-4 gap-y-2">
                <h2
                    class="size-fit border-b-2 border-stone-700 pb-1 text-lg font-semibold text-stone-300"
                >
                    Listings Created
                </h2>

                <div>
                    <p>{{ listing_count }}</p>
                </div>
            </div>
        </div>
    </LayoutAccount>
</template>
