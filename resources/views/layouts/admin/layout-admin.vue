<script setup lang="ts">
import { Bars3Icon } from "@heroicons/vue/24/outline";

const links = [
    {
        name: "Banners",
        route: route("admin.banners.index"),
        matchingRoutes: [
            "admin.banners.index",
            "admin.banners.create",
            "admin.banners.edit",
        ],
    },
    {
        name: "Items",
        route: route("admin.items.index"),
        matchingRoutes: [
            "admin.items.index",
            "admin.items.create",
            "admin.items.edit",
        ],
    },
];

const isCurrentRoute = (matchingRoutes: any) => {
    return matchingRoutes.some((routeName: any) => route().current(routeName));
};
</script>

<template>
    <LayoutMain>
        <div
            class="mb-6 flex items-center gap-4 border-2 border-[#382418] bg-black px-4 py-3"
        >
            <span class="cursor-default text-lg font-semibold text-stone-300">
                Admin Panel
            </span>

            <div class="h-6 w-[2px] bg-stone-600"></div>

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
                    class="hidden sm:block"
                >
                    {{ link.name }}
                </BaseButton>

                <DropdownMenu variant="secondary" class="block sm:hidden">
                    <template #icon>
                        <Bars3Icon class="size-6" />
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

        <slot />
    </LayoutMain>
</template>
