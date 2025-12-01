<script setup lang="ts">
import {
    BuildingStorefrontIcon,
    XMarkIcon,
    PencilSquareIcon,
    CheckCircleIcon,
    XCircleIcon,
} from "@heroicons/vue/24/outline";
import { pickBy } from "lodash";

const props = defineProps<Pages.Admin.ItemsIndexPage>();

const form = ref({
    search: props.filters.search || "",
    is_active: props.filters.is_active || false,
    is_listable: props.filters.is_listable || false,
});

const submitFilter = () => {
    const query = pickBy(form.value, (v) => v !== null && v !== undefined);

    router.get(route("admin.items.index"), query, {
        preserveScroll: true,
        replace: true,
    });
};
</script>

<template>
    <LayoutAdmin>
        <Head title="Manage Items" />

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
            <BuildingStorefrontIcon class="size-12 text-stone-400" />

            <div>
                <h1 class="text-2xl font-semibold">Manage Items</h1>

                <p class="text-stone-300">View and manage the items.</p>
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
                        for="is_active"
                        class="flex items-center space-x-2 text-stone-300"
                    >
                        <input
                            id="is_active"
                            v-model="form.is_active"
                            name="is_active"
                            type="checkbox"
                            class="form-checkbox size-4 rounded border-stone-600 bg-stone-700 text-blue-600"
                            @change="submitFilter"
                        />

                        <span>Active</span>
                    </label>

                    <label
                        for="is_listable"
                        class="flex items-center space-x-2 text-stone-300"
                    >
                        <input
                            id="is_listable"
                            v-model="form.is_listable"
                            name="is_listable"
                            type="checkbox"
                            class="form-checkbox size-4 rounded border-stone-600 bg-stone-700 text-blue-600"
                            @change="submitFilter"
                        />

                        <span>Listable</span>
                    </label>

                    <BaseButton
                        variant="danger"
                        :href="route('admin.items.index', { reset: 1 })"
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

                    <th></th>

                    <th colspan="2">
                        <SortHeader
                            field="name"
                            :current-sort="props.filters.sort || '-id'"
                            class="py-2 font-semibold"
                            >Item Name</SortHeader
                        >
                    </th>

                    <th></th>
                </tr>
            </template>

            <tr v-for="item in props.items.data" :key="item.id">
                <td>#{{ item.id }}</td>

                <td class="w-0">
                    <div v-if="item.isActive">
                        <CheckCircleIcon class="size-5 text-green-500" />
                    </div>

                    <div v-else>
                        <XCircleIcon class="size-5 text-red-500" />
                    </div>
                </td>

                <ItemTableData :item="item" />

                <td class="text-lg text-stone-300">
                    {{ item.name }}
                </td>

                <td class="text-end">
                    <DropdownMenu>
                        <DropdownItem
                            :icon="PencilSquareIcon"
                            text-color="text-amber-500"
                            @click="
                                router.visit(
                                    route('admin.items.edit', { item }),
                                    {
                                        preserveScroll: true,
                                    },
                                )
                            "
                        >
                            Edit
                        </DropdownItem>
                    </DropdownMenu>
                </td>
            </tr>

            <tr v-if="!props.items.data.length">
                <td class="text-center" colspan="5">No items found.</td>
            </tr>

            <template v-if="props.items.data.length" #footer>
                <Pagination :data="props.items" />
            </template>
        </ListingTable>
    </LayoutAdmin>
</template>
