<script setup lang="ts">
import { EyeIcon } from "@heroicons/vue/24/outline";
import { Tooltip } from "floating-vue";
import { pickBy } from "lodash";
import LayoutUser from "@/views/layouts/admin/layout-user.vue";

const props = defineProps<Pages.Admin.UserLogsIndexPage>();

const form = ref({
    search: props.filters.search || "",
});

const submitFilter = () => {
    router.get(
        route("admin.users.logs.index", { user: props.selected_user }),
        pickBy(form.value),
        {
            preserveScroll: true,
            replace: true,
        },
    );
};
</script>

<template>
    <LayoutUser :selected-user="props.selected_user">
        <Head :title="`${props.selected_user.name} Logs`" />

        <ListingTable
            class="relative overflow-x-auto"
            table-class="border-separate border-spacing-y-2 -mt-2"
        >
            <template #header>
                <div class="align-center flex gap-3">
                    <span class="font-bold text-stone-300">Filters:</span>

                    <!--<input
                        id="search"
                        v-model="form.search"
                        name="search"
                        type="text"
                        class="border-slate-900 bg-stone-700 py-0 pl-1"
                        placeholder="Search..."
                        @keydown.enter="submitFilter"
                    />-->
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
                            field="event"
                            :current-sort="props.filters.sort || '-id'"
                            class="py-2 font-semibold"
                        >
                            Event
                        </SortHeader>
                    </th>

                    <th>
                        <SortHeader
                            field="subject_type"
                            :current-sort="props.filters.sort || '-id'"
                            class="py-2 font-semibold"
                        >
                            Subject
                        </SortHeader>
                    </th>

                    <th>
                        <SortHeader
                            field="subject_id"
                            :current-sort="props.filters.sort || '-id'"
                            class="py-2 font-semibold"
                        >
                            Subject ID
                        </SortHeader>
                    </th>

                    <th>
                        <SortHeader
                            field="created_at"
                            :current-sort="props.filters.sort || '-id'"
                            class="py-2 font-semibold"
                        >
                            Timestamp
                        </SortHeader>
                    </th>

                    <th></th>
                </tr>
            </template>

            <tr v-for="log in props.logs.data" :key="log.id">
                <td>#{{ log.id }}</td>

                <td>{{ log.event }}</td>

                <td>{{ log.subjectType }}</td>

                <td>{{ log.subjectId }}</td>

                <td>
                    <Tooltip>
                        <span>{{
                            new Date(log.createdAt).toLocaleDateString()
                        }}</span>

                        <template #popper>
                            {{ new Date(log.createdAt).toLocaleString() }}
                        </template>
                    </Tooltip>
                </td>

                <td>
                    <!--<DropdownMenu>
                        <DropdownItem
                            :icon="EyeIcon"
                            text-color="text-amber-500"
                            :href="route('admin.users.show', { id: log.id })"
                        >
                            View
                        </DropdownItem>
                    </DropdownMenu>-->
                </td>
            </tr>

            <tr v-if="!props.logs.data.length">
                <td class="text-center" colspan="5">No logs found.</td>
            </tr>

            <template v-if="props.logs.data.length" #footer>
                <Pagination :data="props.logs" />
            </template>
        </ListingTable>
    </LayoutUser>
</template>
