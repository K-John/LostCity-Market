<script setup lang="ts">
import { UserIcon, Bars3Icon } from "@heroicons/vue/24/outline";

const links = [
    {
        name: "Listings",
        route: route("listings.index"),
        matchingRoutes: ["listings.index"],
    },
    {
        name: "Favorites",
        route: route("favorites.index"),
        matchingRoutes: ["favorites.index"],
    },
];

const isCurrentRoute = (matchingRoutes: any) => {
    return matchingRoutes.some((routeName: any) => route().current(routeName));
};
</script>

<template>
    <LayoutMain>
        <div class="flex flex-row gap-x-4 gap-y-6 sm:flex-col">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <UserIcon class="size-12 text-stone-400" />

                <div>
                    <p class="text-2xl font-semibold">My Account</p>

                    <p class="text-stone-300">
                        Manage listings, favorite items, and view account info.
                    </p>
                </div>
            </div>

            <div
                class="flex grow items-center justify-end gap-3 sm:justify-start"
            >
                <BaseButton
                    v-for="(link, index) in links"
                    :key="index"
                    as="link"
                    :href="link.route"
                    variant="secondary"
                    :force-focus="isCurrentRoute(link.matchingRoutes)"
                    class="hidden !px-4 !py-2 sm:block"
                >
                    {{ link.name }}
                </BaseButton>

                <DropdownMenu variant="secondary" class="block sm:hidden">
                    <template #icon>
                        <Bars3Icon class="size-7" />
                    </template>

                    <DropdownItem
                        v-for="(link, index) in links"
                        :key="index"
                        :href="link.route"
                    >
                        {{ link.name }}
                    </DropdownItem>
                </DropdownMenu>
            </div>
        </div>

        <hr class="my-5 border-t-2 border-stone-800" />

        <slot />
    </LayoutMain>
</template>
