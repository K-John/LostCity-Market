<script setup lang="ts">
import VueSelect from "vue-select";
import _ from "lodash";
import "vue-select/dist/vue-select.css";

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
        <div>
            <div>
                <VueSelect
                    v-model="selected"
                    :options="options"
                    label="name"
                    :filterable="false"
                    class="bg-white text-black"
                    @search="onSearch"
                >
                </VueSelect>
            </div>

            <span v-if="loading">Loading...</span>
        </div>

        <div class="border-2 border-[#382418] bg-black">
            <table class="border-separate border-spacing-2">
                <thead>
                    <tr>
                        <th></th>

                        <th>Listing</th>

                        <th>Username</th>

                        <th>Timestamp</th>

                        <th>Notes</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="listing in listings.data" :key="listing.id">
                        <td>
                            <img
                                v-if="listing.item"
                                :src="`/img/items/${listing.item.slug}.png`"
                                :alt="`${listing.item.name} Icon`"
                                width="32"
                                height="32"
                            />
                        </td>

                        <td>
                            <span :class="listing.type === 'buy' ? 'text-red-500' : 'text-green-500'" class="font-bold">
                                [{{ listing.type.charAt(0).toUpperCase() }}]
                            </span>
                             {{ listing.quantity }} for {{ listing.price }}GP ea.
                        </td>

                        <td class="text-stone-400">
                            {{ listing.username.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ') }}
                        </td>

                        <td>
                            {{ fromNow(listing.createdAt) }}
                        </td>

                        <td>
                            {{ listing.notes }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </LayoutMain>
</template>
