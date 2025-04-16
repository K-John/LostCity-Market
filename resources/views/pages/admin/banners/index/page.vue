<script setup lang="ts">
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import {
    PlusIcon,
    CheckCircleIcon,
    XCircleIcon,
    MegaphoneIcon,
    PencilSquareIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps<Pages.Admin.BannersIndexPage>();
</script>

<template>
    <LayoutAdmin>
        <Head title="Manage Banners" />

        <div
            class="flex flex-col justify-between gap-3 md:flex-row md:items-center"
        >
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <MegaphoneIcon class="size-12 text-stone-400" />

                <div>
                    <h1 class="text-2xl font-semibold">Manage Banners</h1>

                    <p class="text-stone-300">
                        Banners can be used to display important messages or
                        alerts.
                    </p>
                </div>
            </div>

            <BaseButton
                variant="success"
                as="link"
                :href="route('admin.banners.create')"
                class="flex items-center gap-1"
            >
                <PlusIcon class="size-5" />
                Create Banner
            </BaseButton>
        </div>

        <hr class="my-5 border-t-2 border-stone-800" />

        <ListingTable
            class="relative overflow-x-auto"
            table-class="border-separate border-spacing-y-2 -mt-2"
        >
            <template #table-header>
                <tr class="bg-stone-950 text-left text-stone-300">
                    <th>
                        <SortHeader field="id" default-sort="-id" class="py-2 font-semibold">ID</SortHeader>
                    </th>

                    <th></th>

                    <th>
                        <SortHeader field="message" class="py-2 font-semibold">Message</SortHeader>
                    </th>

                    <th class="py-2 font-semibold">Type</th>

                    <th>
                        <SortHeader field="type" class="py-2 font-semibold">Style</SortHeader>
                    </th>

                    <th>
                        <SortHeader field="start_at" class="py-2 font-semibold">Start At</SortHeader>
                    </th>

                    <th>
                        <SortHeader field="end_at" class="py-2 font-semibold">End At</SortHeader>
                    </th>

                    <th></th>
                </tr>
            </template>

            <tr v-for="banner in props.banners.data" :key="banner.id">
                <td>#{{ banner.id }}</td>

                <td>
                    <div v-if="banner.isActive">
                        <CheckCircleIcon class="size-5 text-green-500" />
                    </div>

                    <div v-else>
                        <XCircleIcon class="size-5 text-red-500" />
                    </div>
                </td>

                <td>
                    <span class="block w-64 truncate">
                        {{ banner.message }}
                    </span>
                </td>

                <td>
                    {{ banner.displayScope }}
                </td>

                <td class="italic">
                    {{ banner.type }}
                </td>

                <td>
                    <Tooltip v-if="banner.startAt">
                        {{ new Date(banner.startAt).toLocaleDateString() }}

                        <template #popper>
                            {{ new Date(banner.startAt).toLocaleString() }}
                        </template>
                    </Tooltip>

                    <span v-else class="text-stone-500">N/A</span>
                </td>

                <td>
                    <Tooltip v-if="banner.endAt">
                        {{ new Date(banner.endAt).toLocaleDateString() }}

                        <template #popper>
                            {{ new Date(banner.endAt).toLocaleString() }}
                        </template>
                    </Tooltip>

                    <span v-else class="text-stone-500">N/A</span>
                </td>

                <td>
                    <DropdownMenu>
                        <DropdownItem
                            :icon="PencilSquareIcon"
                            text-color="text-amber-500"
                            :href="route('admin.banners.edit', { id: banner.id })"
                        >
                            Edit
                        </DropdownItem>

                        <DropdownItem
                            :icon="XMarkIcon"
                            text-color="text-red-500"
                            @click="
                                router.delete(
                                    route('admin.banners.destroy', {
                                        banner: banner.id,
                                        force: true,
                                    }),
                                    { preserveScroll: true },
                                )
                            "
                        >
                            Delete
                        </DropdownItem>
                    </DropdownMenu>
                </td>
            </tr>

            <tr v-if="!props.banners.data.length">
                <td class="text-center" colspan="8">No banners found.</td>
            </tr>

            <template v-if="props.banners.data.length" #footer>
                <Pagination :data="props.banners" />
            </template>
        </ListingTable>
    </LayoutAdmin>
</template>
