<script setup lang="ts">
import { EyeIcon, UsersIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import { Tooltip } from "floating-vue";
import { pickBy } from "lodash";

const props = defineProps<Pages.Admin.UsersIndexPage>();

const form = ref({
    search: props.filters.search || "",
    is_banned: props.filters.is_banned || false,
    is_admin: props.filters.is_admin || false,
});

const submitFilter = () => {
    const query = pickBy(form.value, (v) => v !== null && v !== undefined);

    router.get(route("admin.users.index"), query, {
        preserveScroll: true,
        replace: true,
    });
};
</script>

<template>
    <LayoutAdmin>
        <Head title="Manage Users" />

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
            <UsersIcon class="size-12 text-stone-400" />

            <div>
                <h1 class="text-2xl font-semibold">Manage Users</h1>

                <p class="text-stone-300">
                    View and manage users and their listings.
                </p>
            </div>
        </div>

        <hr class="my-5 border-t-2 border-stone-800" />

        <ListingTable
            class="relative overflow-x-auto"
            table-class="border-separate border-spacing-y-2 -mt-2"
        >
            <template #header>
                <div class="align-center flex gap-3">
                    <span class="font-bold text-stone-300">Filters:</span>

                    <input
                        id="search"
                        v-model="form.search"
                        name="search"
                        type="text"
                        class="border-slate-900 bg-stone-700 py-0 pl-1"
                        placeholder="Search..."
                        @keydown.enter="submitFilter"
                    />

                    <label
                        for="is_admin"
                        class="flex items-center space-x-2 text-stone-300"
                    >
                        <input
                            id="is_admin"
                            v-model="form.is_admin"
                            name="is_admin"
                            type="checkbox"
                            class="form-checkbox size-4 rounded border-stone-600 bg-stone-700 text-blue-600"
                            @change="submitFilter"
                        />

                        <span>Admins</span>
                    </label>

                    <label
                        for="is_banned"
                        class="flex items-center space-x-2 text-stone-300"
                    >
                        <input
                            id="is_banned"
                            v-model="form.is_banned"
                            name="is_banned"
                            type="checkbox"
                            class="form-checkbox size-4 rounded border-stone-600 bg-stone-700 text-red-600"
                            @change="submitFilter"
                        />

                        <span>Banned</span>
                    </label>

                    <BaseButton
                        variant="danger"
                        :href="route('admin.users.index', { reset: 1 })"
                        as="link"
                    >
                        <XMarkIcon class="size-4" />
                    </BaseButton>
                </div>
            </template>

            <template #table-header>
                <tr class="bg-stone-950 text-left text-stone-300">
                    <th>
                        <SortHeader
                            field="id"
                            :current-sort="props.filters.sort || '-id'"
                            default-sort="-id"
                            class="py-2 font-semibold"
                            >ID</SortHeader
                        >
                    </th>

                    <th>
                        <SortHeader
                            field="name"
                            :current-sort="props.filters.sort || '-id'"
                            class="py-2 font-semibold"
                            >Disc. Name</SortHeader
                        >
                    </th>

                    <th>Username(s)</th>

                    <th>
                        <SortHeader
                            field="created_at"
                            :current-sort="props.filters.sort || '-id'"
                            class="py-2 font-semibold"
                            >Created At</SortHeader
                        >
                    </th>

                    <th></th>
                </tr>
            </template>

            <tr v-for="user in props.users.data" :key="user.id">
                <td>#{{ user.id }}</td>

                <td class="text-lg text-stone-300">
                    {{ user.name }}
                </td>

                <td>
                    <span class="block w-64 truncate text-stone-400">
                        {{ user.usernames.join(", ") }}
                    </span>
                </td>

                <td>
                    <Tooltip>
                        {{ new Date(user.createdAt).toLocaleDateString() }}

                        <template #popper>
                            {{ new Date(user.createdAt).toLocaleString() }}
                        </template>
                    </Tooltip>
                </td>

                <td>
                    <DropdownMenu>
                        <DropdownItem
                            :icon="EyeIcon"
                            text-color="text-amber-500"
                            :href="route('admin.users.show', { id: user.id })"
                        >
                            View
                        </DropdownItem>
                    </DropdownMenu>
                </td>
            </tr>

            <tr v-if="!props.users.data.length">
                <td class="text-center" colspan="5">No users found.</td>
            </tr>

            <template v-if="props.users.data.length" #footer>
                <Pagination :data="props.users" />
            </template>
        </ListingTable>
    </LayoutAdmin>
</template>
