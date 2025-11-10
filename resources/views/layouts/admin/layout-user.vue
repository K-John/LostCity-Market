<script setup lang="ts">
import {
    UserIcon,
    Bars3Icon,
    ArrowLeftIcon,
    ArrowUturnLeftIcon,
    XMarkIcon,
    KeyIcon,
    FaceFrownIcon
} from "@heroicons/vue/24/outline";

const props = defineProps<{
    selectedUser: Data.User.AdminUserData;
}>();

const links = [
    {
        name: "Account Info",
        route: route("admin.users.show", { id: props.selectedUser.id }),
        matchingRoutes: ["admin.users.show"],
    },
];

const isCurrentRoute = (matchingRoutes: any) => {
    return matchingRoutes.some((routeName: any) => route().current(routeName));
};
</script>

<template>
    <LayoutAdmin>
        <div class="flex flex-row gap-x-4 gap-y-6 sm:flex-col">
            <div
                class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center"
            >
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <Link
                        type="button"
                        class="flex w-fit items-center gap-1 rounded-sm bg-stone-800 px-3 py-1 text-white hover:bg-stone-700"
                        :href="route('admin.users.index')"
                    >
                        <ArrowLeftIcon class="size-5" />
                        Back
                    </Link>

                    <UserIcon class="size-12 text-stone-400" />

                    <div>
                        <p class="text-2xl font-semibold">
                            {{ selectedUser.name }}
                        </p>

                        <p class="text-stone-300">
                            ({{ selectedUser.discordId }})
                        </p>
                    </div>
                </div>

                <div
                    v-if="selectedUser.usernames.length > 0"
                    class="flex flex-col gap-2 sm:flex-row sm:items-center"
                >
                    <button
                        v-if="!selectedUser.isBanned"
                        type="button"
                        class="flex items-center gap-2 rounded-sm bg-stone-800 px-2 py-1 text-red-500 hover:bg-stone-900"
                        @click="
                            router.post(
                                route('ban.store', {
                                    username: selectedUser.usernames[0],
                                }),
                                { preserveScroll: true },
                            )
                        "
                    >
                        <XMarkIcon class="size-5" />
                        Ban User
                    </button>

                    <button
                        v-if="selectedUser.isBanned"
                        type="button"
                        class="flex items-center gap-2 rounded-sm bg-stone-800 px-2 py-1 text-green-500 hover:bg-stone-900"
                        @click="
                            router.delete(
                                route('ban.destroy', {
                                    username: selectedUser.usernames[0],
                                }),
                                { preserveScroll: true },
                            )
                        "
                    >
                        <ArrowUturnLeftIcon class="size-5" />
                        Unban User
                    </button>

                    <button
                        v-if="!selectedUser.isAdmin"
                        type="button"
                        class="flex items-center gap-2 rounded-sm bg-stone-800 px-2 py-1 text-yellow-500 hover:bg-stone-900"
                        @click="
                            router.post(
                                route('promote.store', {
                                    user: selectedUser
                                }),
                                { preserveScroll: true },
                            )
                        "
                    >
                        <KeyIcon class="size-5" />
                        Promote to Admin
                    </button>

                    <button
                        v-if="selectedUser.isAdmin"
                        type="button"
                        class="flex items-center gap-2 rounded-sm bg-stone-800 px-2 py-1 text-yellow-500 hover:bg-stone-900"
                        @click="
                            router.delete(
                                route('promote.destroy', {
                                    user: selectedUser
                                }),
                                { preserveScroll: true },
                            )
                        "
                    >
                        <FaceFrownIcon class="size-5" />
                        Demote to Noob
                    </button>
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
    </LayoutAdmin>
</template>
