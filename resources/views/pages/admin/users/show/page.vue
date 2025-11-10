<script setup lang="ts">
import LayoutUser from "@/views/layouts/admin/layout-user.vue";

const props = defineProps<Pages.Admin.UsersShowPage>();
</script>

<template>
    <LayoutUser :selected-user="props.selected_user">
        <Head :title="`${props.selected_user.name} Info`" />

        <h1 class="mb-4 text-2xl font-semibold">User Info</h1>

        <div
            class="grid grid-cols-1 gap-3 border-2 border-[#382418] bg-black p-3 sm:grid-cols-[auto_1fr]"
        >
            <div class="col-span-full grid grid-cols-subgrid gap-x-4 gap-y-2">
                <h2
                    class="size-fit border-b-2 border-stone-700 pb-1 text-lg font-semibold text-stone-300"
                >
                    Discord Name
                </h2>

                <div>
                    <p>
                        {{ props.selected_user.name }} (id:
                        <span class="text-stone-400">{{
                            props.selected_user.discordId
                        }}</span
                        >)
                    </p>
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
                </div>
            </div>

            <hr class="col-span-full border-stone-900" />

            <div class="col-span-full grid grid-cols-subgrid gap-x-4 gap-y-2">
                <div class="flex flex-col gap-1">
                    <h2
                        class="size-fit border-b-2 border-stone-700 pb-1 text-lg font-semibold text-stone-300"
                    >
                        Date Registered
                    </h2>

                    <p class="text-sm text-stone-400">
                        (to the markets, not the game)
                    </p>
                </div>

                <div>
                    <p>{{ formatTime(props.selected_user.createdAt) }}</p>
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
    </LayoutUser>
</template>
