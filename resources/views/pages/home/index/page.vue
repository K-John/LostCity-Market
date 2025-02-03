<script setup lang="ts">
import VueSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { Tooltip } from "floating-vue";
import "floating-vue/dist/style.css";
import _ from "lodash";

const props = defineProps<Pages.HomeIndexPage>();

const selected = ref<Data.Item.ItemData | null>(null);
const options = ref<Data.Item.ItemData[]>([]);
const loading = ref(false);

const onSearch = (searchTerm: string, loading: (state: boolean) => void) => {
    if (searchTerm.length > 2) {
        loading(true);
        search(searchTerm, loading);
    }
};

const search = _.debounce(
    (search: string, loading: (state: boolean) => void) => {
        fetch(`${route("items.index")}?q=${search}`)
            .then((response) => response.json())
            .then((data) => {
                loading(false);
                options.value = data;
            });
    },
    200,
);

watch(selected, (value) => {
    if (value) {
        loading.value = true;
        router.get(route("items.show", { item: value.slug }));
    }
});
</script>

<template>
    <LayoutMain>
        <div class="mb-4 flex flex-row gap-4 border-2 border-[#382418] bg-black p-3">
            <h2 class="text-lg font-bold">Search for an item:</h2>

            <VueSelect
                v-model="selected"
                :options="options"
                label="name"
                :filterable="false"
                class="grow bg-white text-black"
                @search="onSearch"
            >
            </VueSelect>
        </div>

        <div class="flex flex-col gap-2 border-2 border-[#382418] bg-black">
            <div class="px-3 pt-3">
                <h2 class="text-lg font-bold">Recent Listings:</h2>
            </div>

            <table class="border-separate border-spacing-2">
                <tbody>
                    <tr v-for="listing in listings.data" :key="listing.id">
                        <td>
                            <Tooltip>
                                <Link
                                    v-if="listing.item"
                                    :href="
                                        route('items.show', {
                                            item: listing.item.slug,
                                        })
                                    "
                                >
                                    <img
                                        :src="`/img/items/${listing.item.slug}.png`"
                                        :alt="`${listing.item.name} Icon`"
                                        width="32"
                                        height="32"
                                    />
                                </Link>

                                <template #popper>
                                    {{
                                        listing.item
                                            ? listing.item.name
                                            : "Unknown Item"
                                    }}
                                </template>
                            </Tooltip>
                        </td>

                        <td>
                            <span
                                :class="
                                    listing.type === 'buy'
                                        ? 'text-red-500'
                                        : 'text-green-500'
                                "
                                class="font-bold"
                            >
                                [{{ listing.type.charAt(0).toUpperCase() }}]
                            </span>
                            {{ listing.quantity.toLocaleString() }} for
                            {{ listing.price.toLocaleString() }}GP ea.
                        </td>

                        <td class="text-stone-400">
                            {{
                                listing.username
                                    .split(" ")
                                    .map(
                                        (word) =>
                                            word.charAt(0).toUpperCase() +
                                            word.slice(1).toLowerCase(),
                                    )
                                    .join(" ")
                            }}
                        </td>

                        <td>
                            <Tooltip>
                                <p>{{ fromNow(listing.createdAt) }}</p>

                                <template #popper>
                                    {{ formatTime(listing.createdAt) }}
                                </template>
                            </Tooltip>
                        </td>

                        <td class="max-w-[110px]">
                            <Tooltip>
                                <p class="truncate">{{ listing.notes }}</p>

                                <template #popper>
                                    {{ listing.notes }}
                                </template>
                            </Tooltip>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="px-3">
                <Pagination class="mt-0" :data="listings" />
            </div>
        </div>
    </LayoutMain>
</template>
